<?php
namespace OrientDBYii2Connector;

use Yii;
use yii\base\InvalidConfigException;
use PhpOrient\PhpOrient;
use OrientDBYii2Connector\Command;
use OrientDBYii2Connector\NotSupportedException;

class Connection extends \yii\base\Component
{
    /**
     * @event Event an event that is triggered after a DB connection is established
     */
    const EVENT_AFTER_OPEN = 'afterOpen';
    
    /**
     * Connection host like `localhost`
     * 
     * @var string
     */
    public $hostname;
    
    /**
     * Connection port like `2424`
     * 
     * @var string
     */
    public $port;
    
    /**
     * the username for establishing HttpBinding connection instance. Defaults to `null` meaning no username to use.
     * 
     * @var string 
     */
    public $username;
    
    /**
     * the password for establishing HttpBinding connection instance. Defaults to `null` meaning no password to use.
     * 
     * @var string
     */
    public $password;
    
    /**
     * the PhpOrient instance associated with this PhpOrient connection instance.
     * This property is mainly managed by [[open()]] and [[close()]] methods.
     * When a client connection instance is active, this property will represent a PhpOrient instance;
     * otherwise, it will be null.
     * 
     * @var string
     */
    public $client;
    
    /**
     *  the dbname, name of database to connect
     *
     * @var string
     */
    public $dbname;
    
    /**
     * @inTransaction - transaction in progress
     * see more (class TxCommit) $this->_transport->inTransaction;
     */
    protected $inTransaction;
    
    /**
     * @var Schema the database schema
     */
    private $_schema;
    public $enableSchemaCache = false;
    
    public function init()
    {
        parent::init();
        
        $this->open();
    }
    
    /**
     * Establishes a client connection instance.
     * It does nothing if a client connection instance has already been established.
     * @throws Exception if connection fails
     */
    public function open()
    {
        if ($this->client !== null)
            return;
        
        if (empty($this->hostname))
            throw new InvalidConfigException('Connection::hostname cannot be empty.');
        
        if (empty($this->port))
            throw new InvalidConfigException('Connection::port cannot be empty.');
        
        if (empty($this->dbname))
            throw new InvalidConfigException('Connection::dbname cannot be empty.');
        
        if (empty($this->username))
            throw new InvalidConfigException('Connection::username cannot be empty.');
        
        $token = 'Creating client connection instance: ' . $this->hostname . ':' . $this->port;
        try {
            Yii::info($token, __METHOD__);
            Yii::beginProfile($token, __METHOD__);
            $this->client = $this->createClientInstance();
            $this->trigger(self::EVENT_AFTER_OPEN);
            Yii::endProfile($token, __METHOD__);
        } catch (\Exception $e) {
            Yii::endProfile($token, __METHOD__);
            throw $e;
        }
    }
    /**
     * Removing the currently active client connection instance.
     * It does nothing if the connection is already closed.
     */
    public function close()
    {
        if ($this->client !== null) {
            Yii::trace('Closing orient DB connection: ' . $this->hostname . ':' . $this->port, __METHOD__);
            $this->client->dbClose(); // returns int
            Yii::trace('Removing client connection instance: ' . $this->hostname . ':' . $this->port, __METHOD__);
            $this->client = null;
            $this->_schema = null;
            $this->_transaction = null;
        }
    }
    
    /**
     * Creates the client connection instance.
     * This method is called by [[open]] to establish a DB connection.
     * The default implementation will create a PhpOrient instance.
     * You may override this method if the default client needs to be adapted for certain DBMS.
     * @return client the pdo instance
     */
    public function createClientInstance()
    {
        $client = new PhpOrient();
        $client->configure( [
            'username' => $this->username,
            'password' => $this->password,
            'hostname' => $this->hostname,
            'port'     => $this->port,
        ] );
        
        $client->connect();
        $client->dbOpen( $this->dbname );
        
        return $client;
    }
    
    /**
     * Returns a value indicating whether the DB connection is established.
     * @return boolean whether the DB connection is established
     */
    public function getIsActive()
    {
        return $this->client !== null;
    }
    
    /**
     * Creates a command for execution.
     * @param string $sql the SQL statement to be executed
     * @param array $params the parameters to be bound to the SQL statement
     * @return Command
     */
    public function createCommand($sql = null, $params = [])
    {
        $command = new Command([
            'db' => $this,
            'sql' => $sql,
        ]);
        return $command->bindValues($params);
    }
    
    /**
     * Returns the currently active transaction.
     * @return Transaction the currently active transaction. Null if no active transaction.
     */
    public function getTransaction()
    {
        return $this->_transaction && $this->_transaction->getIsActive() ? $this->_transaction : null;
    }

    /**
     * Starts a transaction.
     * @param string|null $isolationLevel The isolation level to use for this transaction.
     * See [[Transaction::begin()]] for details.
     * @return Transaction the transaction initiated
     */
    public function beginTransaction($isolationLevel = null)
    {
        if ($this->inTransaction) {
            throw new InvalidCommandException("A Transaction already exists. You can not nest transactions");
        }

        $this->inTransaction = true;
        
        if($isolationLevel !== null) // $this->_transaction->need use attach
            throw new NotSupportedException(__CLASS__ . " does not support isolationLevel");
        
        $this->open();

        if (($transaction = $this->getTransaction()) === null) {
            $transaction = $this->_transaction = $client->getTransactionStatement();
        }
        $transaction->begin();

        return $transaction;
    }

    /**
     * Executes callback provided in a transaction.
     *
     * @param callable $callback a valid PHP callback that performs the job. Accepts connection instance as parameter.
     * @param string|null $isolationLevel The isolation level to use for this transaction.
     * See [[Transaction::begin()]] for details.
     * @throws \Exception
     * @return mixed result of callback function
     */
    public function transaction(callable $callback, $isolationLevel = null)
    {
        if($isolationLevel !== null)
            throw new NotSupportedException(__CLASS__ . " does not support isolationLevel");
        
        $transaction = $this->beginTransaction($isolationLevel);

        try {
            $result = call_user_func($callback, $this);
            if ($this->inTransaction) {
                $this->inTransaction = false;
                $transaction->commit();
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        return $result;
    }
    
    public function getSchema()
    {
        if ($this->_schema !== null) {
            return $this->_schema;
        } else {
            $config = ['class' => 'OrientDBYii2Connector\Schema'];
            $config['db'] = $this;

            return $this->_schema = Yii::createObject($config);
        }
    }
    
    public function getQueryBuilder()
    {
        return $this->getSchema()->getQueryBuilder();
    }
    
    // quota methods
    public function quoteValue($value)
    {
        // return QuotaOrientDB::quoteValue($value);
        return $this->getSchema()->quoteValue($value);
    }
    
    public function quoteTableName($name)
    {
        return QuotaOrientDB::quoteTableName($name);
        // return $this->getSchema()->quoteTableName($name);
    }
    
    public function quoteColumnName($name)
    {
        // return QuotaOrientDB::quoteColumnName($name);
        return $this->getSchema()->quoteColumnName($name);
    }
    
    public function isRid($value)
    {
        return $this->getSchema()->isRid($value);
    }
    
    // proxy comands to orient driver:
    public function command( $query ) {
        return $this->client->command($query);
    }
    
    public function query( $query, $limit = 20, $fetchPlan = '*:0' ) {
        return $this->client->query( $query, $limit, $fetchPlan );
    }
    
    public function queryAsync( $query, Array $params = array() ) {
        return $this->client->queryAsync($query, $params);
    }
}
