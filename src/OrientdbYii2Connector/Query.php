<?php
namespace OrientDBYii2Connector;

use Yii;
use yii\base\Component;
use yii\db\Expression;
use yii\db\QueryInterface;
use yii\db\QueryTrait;

use OrientDBYii2Connector\OrientDBException;

class Query extends Component implements QueryInterface
{
    use QueryTrait;
    
    // QueryTrait:
    public $where;
    public $limit;
    public $offset;
    public $orderBy;
    public $groupBy;
    public $indexBy;
    // base:
    public $select;
    public $selectOption;
    public $distinct;
    public $from;
    // not supported by OrientDB(user fetch_plan):
    // public $join;
    // public $having;
    // public $union;
    public $fetch_plan = '';
    public $params = [];
    
    public function select($columns, $option = null)
    {
        if ($columns instanceof Expression) {
            $columns = [$columns];
        } elseif (!is_array($columns)) {
            $columns = preg_split('/\s*,\s*/', trim($columns), -1, PREG_SPLIT_NO_EMPTY);
        }
        $this->select = $columns;
        $this->selectOption = $option;
        return $this;
    }
    
    public function addSelect($columns)
    {
        if ($columns instanceof Expression) {
            $columns = [$columns];
        } elseif (!is_array($columns)) {
            $columns = preg_split('/\s*,\s*/', trim($columns), -1, PREG_SPLIT_NO_EMPTY);
        }
        if ($this->select === null) {
            $this->select = $columns;
        } else {
            $this->select = array_merge($this->select, $columns);
        }
        return $this;
    }

    public function from($tables)
    {
        if (!is_array($tables)) {
            $tables = preg_split('/\s*,\s*/', trim($tables), -1, PREG_SPLIT_NO_EMPTY);
        }
        $this->from = $tables;
        return $this;
    }
    
    public function fetch_plan($plains)
    {
        $this->fetch_plan = $plains;
        return $this;
    }
    
    public function where($condition, $params = [])
    {
        $this->where = $condition;
        $this->addParams($params);
        return $this;
    }
    
    public function andWhere($condition, $params = [])
    {
        if ($this->where === null) {
            $this->where = $condition;
        } else {
            $this->where = ['and', $this->where, $condition];
        }
        $this->addParams($params);
        return $this;
    }
    
    public function orWhere($condition, $params = [])
    {
        if ($this->where === null) {
            $this->where = $condition;
        } else {
            $this->where = ['or', $this->where, $condition];
        }
        $this->addParams($params);
        return $this;
    }
    
    public function groupBy($columns)
    {
        if ($columns instanceof Expression) {
            $columns = [$columns];
        } elseif (!is_array($columns)) {
            $columns = preg_split('/\s*,\s*/', trim($columns), -1, PREG_SPLIT_NO_EMPTY);
        }
        $this->groupBy = $columns;
        return $this;
    }
    
    public function params($params)
    {
        $this->params = $params;
        return $this;
    }
    
    public function addParams($params)
    {
        if (!empty($params)) {
            if (empty($this->params)) {
                $this->params = $params;
            } else {
                foreach ($params as $name => $value) {
                    if (is_int($name)) {
                        $this->params[] = $value;
                    } else {
                        $this->params[$name] = $value;
                    }
                }
            }
        }
        return $this;
    }
    
    // -------
    public function all($db = null)
    {
        $command = $this->createCommand($db);
        if(!empty($this->fetch_plan))
            $command->setFetch_plan($this->fetch_plan);
        $rows = $command->queryAll();
        return $this->populate($rows);
    }
    
    public function populate($rows)
    {
        if ($this->indexBy === null) {
            return $rows;
        }
        $result = [];
        foreach ($rows as $row) {
            if (is_string($this->indexBy)) {
                $key = $row[$this->indexBy];
            } else {
                $key = call_user_func($this->indexBy, $row);
            }
            $result[$key] = $row;
        }
        return $result;
    }
    
    public function one($db = null)
    {
        $this->limit = 1;
        $command = $this->createCommand($db);
        if(!empty($this->fetch_plan)){
            $command->setFetch_plan($this->fetch_plan);
            return $command->queryOne();
        } else {
            $rows = $command->queryOne();
            if(isset($rows['records'][0])) {
                return $rows['records'][0];
            }
        }
        
        return null;
    }
    
    public function scalar($db = null)
    {
        $this->limit = 1;
        return $this->createCommand($db)->queryScalar();
    }
    
    public function column($db = null)
    {
        return $this->createCommand($db)->queryColumn();
    }
    
    // ------
    public function count($q = '*', $db = null)
    {
        return $this->queryScalar("COUNT($q)", $db);
    }
    
    public function sum($q, $db = null)
    {
        return $this->queryScalar("SUM($q)", $db);
    }
    
    public function average($q, $db = null)
    {
        return $this->queryScalar("AVG($q)", $db);
    }
    
    public function min($q, $db = null)
    {
        return $this->queryScalar("MIN($q)", $db);
    }
    
    public function max($q, $db = null)
    {
        return $this->queryScalar("MAX($q)", $db);
    }
    
    public function exists($db = null)
    {
        $this->limit = 1;
        $select = $this->select;
        $this->select = [new Expression('*')]; // [new Expression('1')]; - PhpOrient return error
        $command = $this->createCommand($db);
        $this->select = $select;
        var_dump($command->queryScalar());
        return $command->queryScalar() !== null;
    }
    
    protected function queryScalar($selectExpression, $db)
    {
        $select = $this->select;
        $limit = $this->limit;
        $offset = $this->offset;

        $this->select = [$selectExpression];
        $this->limit = null;
        $this->offset = null;
        $command = $this->createCommand($db);

        $this->select = $select;
        $this->limit = $limit;
        $this->offset = $offset;

        if (empty($this->groupBy) /* && empty($this->having) && empty($this->union) */ && !$this->distinct) {
            return $command->queryScalar();
        } else {
            return (new Query)->select([$selectExpression])
                ->from(['c' => $this])
                ->createCommand($command->db)
                ->queryScalar();
        }
    }
    
    public function createCommand($db = null)
    {
        if ($db === null) {
            $db = Yii::$app->get('dborient');;
        }
        list ($sql, $params) = $db->getQueryBuilder()->build($this);

        return $db->createCommand($sql, $params);
    }
    
    public function prepare($builder)
    {
        return $this;
    }
}
