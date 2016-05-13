<?php

namespace OrientDBYii2Connector;

use yii\db\Expression;
use yii\db\TableSchema;
use yii\db\ColumnSchema;

class Schema extends \yii\db\Schema
{
    public $typeMap = [
        'tinyint' => self::TYPE_SMALLINT,
        'bit' => self::TYPE_INTEGER,
        'smallint' => self::TYPE_SMALLINT,
        'mediumint' => self::TYPE_INTEGER,
        'int' => self::TYPE_INTEGER,
        'integer' => self::TYPE_INTEGER,
        'bigint' => self::TYPE_BIGINT,
        'float' => self::TYPE_FLOAT,
        'double' => self::TYPE_DOUBLE,
        'real' => self::TYPE_FLOAT,
        'decimal' => self::TYPE_DECIMAL,
        'numeric' => self::TYPE_DECIMAL,
        'tinytext' => self::TYPE_TEXT,
        'mediumtext' => self::TYPE_TEXT,
        'longtext' => self::TYPE_TEXT,
        'longblob' => self::TYPE_BINARY,
        'blob' => self::TYPE_BINARY,
        'text' => self::TYPE_TEXT,
        'varchar' => self::TYPE_STRING,
        'string' => self::TYPE_STRING,
        'char' => self::TYPE_STRING,
        'datetime' => self::TYPE_DATETIME,
        'year' => self::TYPE_DATE,
        'date' => self::TYPE_DATE,
        'time' => self::TYPE_TIME,
        'timestamp' => self::TYPE_TIMESTAMP,
        'enum' => self::TYPE_STRING,
    ];
    
    protected $columnTypes;
    
    // public function quoteSimpleTableName($name)
    // {
        // return strpos($name, '`') !== false ? $name : "`$name`";
    // }
    
    // public function quoteSimpleColumnName($name)
    // {
        // return strpos($name, '`') !== false || $name === '*' ? $name : "`$name`";
    // }
    
    // --
    public function quoteValue($value)
    {
        return QuotaOrientDB::quoteValue($value);
    }
    
    // public function quoteTableName($name)
    // {
        // return QuotaOrientDB::quoteTableName($name);
    // }
    
    public function quoteColumnName($name)
    {
        return QuotaOrientDB::quoteColumnName($name);
    }
    
    public function isRid($value)
    {
        return QuotaOrientDB::isRid($value);
    }
    // --
    
    public function createQueryBuilder()
    {
        return new QueryBuilder($this->db);
    }
    
    protected function loadTableSchema($name)
    {
        $table = new TableSchema;
        $this->resolveTableNames($table, $name);

        if ($this->findColumns($table)) {
            $this->findConstraints($table);

            return $table;
        } else {
            return null;
        }
    }
    
    protected function resolveTableNames($table, $name)
    {
        $parts = explode('.', str_replace('`', '', $name));
        if (isset($parts[1])) {
            $table->schemaName = $parts[0];
            $table->name = $parts[1];
            $table->fullName = $table->schemaName . '.' . $table->name;
        } else {
            $table->fullName = $table->name = $parts[0];
        }
    }
    
    protected function loadColumnSchema($info)
    {
        $column = $this->createColumnSchema();
        
        $column->name = $info['name'];
        $column->allowNull = !$info['notNull'];
        $column->dbType = $this->getColumnType($info['globalId']); // 'longtext'; //$info['type']; //! BUG, need assign with types
        
        $column->type = self::TYPE_STRING;
        if (preg_match('/^(\w+)(?:\(([^\)]+)\))?/', $column->dbType, $matches)) {
            $type = strtolower($matches[1]);
            if (isset($this->typeMap[$type])) {
                $column->type = $this->typeMap[$type];
            }
            if (!empty($matches[2])) {
                if ($type === 'enum') {
                    $values = explode(',', $matches[2]);
                    foreach ($values as $i => $value) {
                        $values[$i] = trim($value, "'");
                    }
                    $column->enumValues = $values;
                } else {
                    $values = explode(',', $matches[2]);
                    $column->size = $column->precision = (int) $values[0];
                    if (isset($values[1])) {
                        $column->scale = (int) $values[1];
                    }
                    if ($column->size === 1 && $type === 'bit') {
                        $column->type = 'boolean';
                    } elseif ($type === 'bit') {
                        if ($column->size > 32) {
                            $column->type = 'bigint';
                        } elseif ($column->size === 32) {
                            $column->type = 'integer';
                        }
                    }
                }
            }
        }

        $column->phpType = $this->getColumnPhpType($column);

        return $column;
    }
    
    protected function getColumnType($columnID){
        if(empty($this->columnTypes)) {
            $sql = 'select globalProperties from metadata:schema LIMIT 100000';
            try {
                $this->columnTypes = [];
                
                $data = $this->db->createCommand($sql)->queryAll();
                $columns = $data['records'][0]->getOData();
                
                foreach($columns['globalProperties'] as $column) {
                    array_push($this->columnTypes, $column->getOData());
                }
                
            } catch (\Exception $e) {
                throw $e;
            }
        }
        
        foreach($this->columnTypes as $columnType) {
            if($columnType['id'] == $columnID)
                return $columnType['type'];
        }
        
        return 'longtext'; // default
    }
    
    protected function findColumns($table)
    {
        $sql = 'select expand(properties) from (
                    select expand(classes) from metadata:schema
                ) where name = ' . $this->quoteTableName($table->fullName) . ' LIMIT 100000'; //? default driver LIMIT 20
        try {
            $columns = $this->db->createCommand($sql)->queryAll();
        } catch (\Exception $e) {
            throw $e;
        }
        
        foreach ($columns['records'] as $info) {
            $column = $this->loadColumnSchema($info->getOData());
            $table->columns[$column->name] = $column;
        }
        
        // add default fields '@rid', '@version', '@class'
        $column = $this->createColumnSchema();
        $column->name = '@rid'; // -
        $column->allowNull = true;
        $column->dbType = 'string';
        $column->type = self::TYPE_STRING;
        $column->phpType = $this->getColumnPhpType($column);
        
        $table->columns[$column->name] = $column;
        // -
        $column = $this->createColumnSchema();
        $column->name = '@version'; // -
        $column->allowNull = true;
        $column->dbType = 'bigint';
        $column->type = self::TYPE_BIGINT;
        $column->phpType = $this->getColumnPhpType($column);
        
        $table->columns[$column->name] = $column;
        
        //-
        $column = $this->createColumnSchema();
        $column->name = '@class'; // -
        $column->allowNull = true;
        $column->dbType = 'string';
        $column->type = self::TYPE_STRING;
        $column->phpType = $this->getColumnPhpType($column);
        
        $table->columns[$column->name] = $column;
        ksort($table->columns); // orientdb return random sort, for gii prefere ASC sort
        // --
        
        $table->primaryKey[] = '@rid';

        return true;
    }
    
    protected function findConstraints($table)
    {
        //? BUG, Constraints
        // orientdb has no `primary` and `foreign` keys
    }
    
    // not tested -> and not supported yet
    public function findUniqueIndexes($table)
    {
        $sql = 'select expand(indexes) from metadata:indexmanager
                    where type = \'UNIQUE\' AND name = ' . $this->quoteTableName($table->fullName) . ' LIMIT 100000'; //? default driver LIMIT 20;
        
        try {
            $columns = $this->db->createCommand($sql)->queryAll();
        } catch (\Exception $e) {
            throw $e;
        }
        $result = [];
        foreach ($columns['records'] as $info) {
            $index = $info->getOData();
            $result[] = $index['name'];
        }
        
        return $result;
    }
    
    // not tested -> and not supported yet
    protected function findTableNames($schema = '')
    {
        $sql = 'select expand(classes) from metadata:schema LIMIT 100000'; //? default driver LIMIT 20;
        
        try {
            $columns = $this->db->createCommand($sql)->queryAll();
        } catch (\Exception $e) {
            throw $e;
        }
        
        $result = [];
        foreach ($columns['records'] as $info) {
            $index = $info->getOData();
            $result[] = $index['name'];
            
            if ($schema === $index['name']) {
                return [$schema];
            }
        }
        
        return $result;
    }
}
