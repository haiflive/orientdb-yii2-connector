<?php
namespace OrientDBYii2Connector;

use Yii;
use yii\base\Component;
use PhpOrient\Protocols\Binary\Data\Record;

use OrientDBYii2Connector\OrientDBException;
use OrientDBYii2Connector\DataRreaderOrientDB;

class Command extends Component
{
    const FETCH_SCALAR = 0;
    const FETCH_COLUMN = 10;
    
    public $db;
    public $params = [];
    
    private $_sql;
    private $fetch_plan;
    private $_builder;
    private $relations = [];
    
    public function bindValue($name, $value, $dataType = null)
    {
        //! BUG, need detect data type
        $this->params[$name] = $value;

        return $this;
    }
    
    public function bindValues($values)
    {
        if (empty($values)) {
            return $this;
        }
        
        //! BUG, need check Schema
        foreach ($values as $name => $value) {
            if (is_array($value) && array_values($value) === $value) { // sequential
                $this->params[$name] = $value[0];
            } if(is_array($value)) { // associative array, embedded data
                $this->params[$name] = $value;
            } else {
                $this->params[$name] = $value;
            }
        }

        return $this;
    }
    
    public function getSql()
    {
        return $this->_sql;
    }
    
    public function setSql($sql)
    {
        if ($sql !== $this->_sql) {
            $this->_sql = $sql; //! BUG need quita sql
            $this->params = [];
        }

        return $this;
    }
    
    public function setFetch_plan($fetch_plan)
    {
        if(is_array($fetch_plan)) {
            $this->fetch_plan = implode(' ', $fetch_plan);
        } else if(is_string($fetch_plan)) {
            if($fetch_plan !== $this->fetch_plan)
                $this->fetch_plan = $fetch_plan;
        } else {
            throw new OrientDBException(__CLASS__ . ": unkown `fetch_plan`");
        }

        return $this;
    }
    
    public function getRawSql()
    {
        if (empty($this->params)) {
            return $this->_sql;
        }
        $params = [];
        foreach ($this->params as $name => $value) {
            if (is_string($name) && strncmp(':', $name, 1)) {
                $name = ':' . $name;
            }
            if (is_string($value)) {
                $params[$name] = $this->db->quoteValue($value);
            } elseif (is_bool($value)) {
                $params[$name] = ($value ? 'TRUE' : 'FALSE');
            } elseif ($value === null) {
                $params[$name] = 'NULL';
            } elseif (!is_object($value) && !is_resource($value)) {
                $params[$name] = $this->db->quoteValue($value);
            }
        }
        
        $sql = $this->_sql;
        foreach($params as $name => $value) {
            $sql = str_replace($name, $value, $sql);
        }
        
        // if (!isset($params[1])) {
            // return strtr($this->_sql, $params);
        // }
        
        // foreach (explode('?', $this->_sql) as $i => $part) {
            // $sql .= (isset($params[$i]) ? $params[$i] : '') . $part;
        // }

        return $sql;
    }
    
    public function query()
    {
        return $this->queryInternal('');
    }
    
    public function queryAll($fetchMode = null)
    {
        return $this->queryInternal('fetchAll', $fetchMode);
    }
    
    public function queryOne($fetchMode = null)
    {
        return $this->queryInternal('fetch', $fetchMode);
    }
    
    public function queryScalar()
    {
        $rows = $this->queryInternal('fetchColumn', self::FETCH_SCALAR);
        
        if( !empty($rows['records']) && isset($rows['records'][0]) ) {
            $data = $rows['records'][0]->getOData();
            if(!empty($data))
                return array_shift($data);
        }
        
        return null;
    }
    
    public function queryColumn()
    {
        $rows = $this->queryInternal('fetchAll', self::FETCH_COLUMN);
        
        $result = [];
        foreach($rows['records'] as $record) {
            $data = $record->getOData();
            array_push($result, array_shift($data));
        }
        return $result;
    }
    
    protected function queryInternal($method, $fetchMode = null)
    {   
        $rawSql = $this->getRawSql();
        
        Yii::info($rawSql, 'app\components\orientdb::query');
        
        $token = $rawSql;
        try {
            Yii::beginProfile($token, 'yii\db\Command::query');
            $n = [];
            if (!empty($this->fetch_plan) && $fetchMode !== self::FETCH_COLUMN && $fetchMode !== self::FETCH_SCALAR) { // column or scalar
                $this->relations = []; // clear
                $myFunction = function( Record $record) {
                    array_push($this->relations, $record);
                    //! BUG, need associate relation data in to tree
                };
                $n = $this->db->queryAsync($rawSql, [ 'fetch_plan' => $this->fetch_plan, '_callback' => $myFunction ]);
                $n = [
                    'records'   => $n,
                    'relations' => $this->relations
                ];
            } else {
                $n = [
                    'records'   => $this->db->query($rawSql),
                    'relations' => []
                ];
            }
            
            Yii::endProfile($token, 'yii\db\Command::query');
            
            return $n;
        } catch (\Exception $e) {
            Yii::endProfile($token, 'yii\db\Command::query');
            throw new OrientDBException(__CLASS__ . " databse return error: " . $e->getMessage() . ",  When execute sql: " . $rawSql);
        }
    }
    
    public function insert($table, $columns)
    {
        $params = [];
        $sql = $this->db->getQueryBuilder()->insert($table, $columns, $params);
        
        return $this->setSql($sql)->bindValues($params);
    }
    
    public function update($table, $columns, $condition = '', $params = [])
    {
        $sql = $this->db->getQueryBuilder()->update($table, $columns, $condition, $params);

        return $this->setSql($sql)->bindValues($params);
    }
    
    public function delete($table, $condition = '', $params = [], $itVertex = true)
    {
        $sql = $this->db->getQueryBuilder()->delete($table, $condition, $params, $itVertex = true);

        return $this->setSql($sql)->bindValues($params);
    }
    
    public function execute()
    {
        $sql = $this->getSql();
        
        $rawSql = $this->getRawSql();
        
        Yii::info($rawSql, __METHOD__);

        if ($sql == '') {
            return 0;
        }
        
        $token = $rawSql;
        try {
            Yii::beginProfile($token, __METHOD__);

            $n = $this->db->command($rawSql);
            
            Yii::endProfile($token, __METHOD__);
            
            if(is_a($n, 'PhpOrient\Protocols\Binary\Data\Record')){
                return DataRreaderOrientDB::getRecordData($n); // insert return record
            }
            
            return $n; // update return '0' of '1'
            
        } catch (\Exception $e) {
            Yii::endProfile($token, __METHOD__);
            throw new OrientDBException(__CLASS__ . " databse return error: " . $e->getMessage() . ",  When execute sql: " . $rawSql);
        }
    }
}
