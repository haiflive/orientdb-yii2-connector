<?php
namespace OrientDBYii2Connector;

use OrientDBYii2Connector\OrientDBException;
/**
 *  @QuotaOrientDB static class helper quota db vaues(orient has no pdo)
 *  
 */

class QuotaOrientDB
{
    /**
     *  @value - string to bee filter
     *  @return filtered string
     */
    static public function quoteValue($value)
    {
        if(is_array($value)) {
            $isArrayOfRid = true;
            foreach($value as $key => $val) {
                if ($val instanceof ActiveRecord) { // LINKLIST relation
                    $rid = $val->getAttribute($val->primaryKey()[0]);
                    if(self::isRid($rid)) {
                        $value[$key] = $rid;
                    } else {
                        $value[$key] = null; //? unexpected data, need to return error
                        $isArrayOfRid = false;
                    }
                } else {
                    $isArrayOfRid = false;
                }
            }
            
            if(!$isArrayOfRid) // it embeddedlist
                return json_encode(self::prepairEmbedded($value)); //!? BUG need quota recursively 
            
            return '['. implode(',', $value) .']';
        }
        //! BUG need filter all data
        // return bin2hex($value);
        return '\'' . self::escape($value) . '\'';
    }
    
    static public function quoteTableName($tableName)
    {
        $name = filter_var($tableName, FILTER_VALIDATE_REGEXP, ["options"=>["regexp"=>"/^[a-zA-Z_][a-zA-Z0-9_]*$/"]]);
        if( !$name || empty($name))
            throw new OrientDBException(__CLASS__ . " unsafe table name: "  . $tableName);
        
        return '`'. $name .'`';
    }
    
    /**
     *  skip: @rid, @version, @class, clusterName.@rid, clusterName.@version, clusterName.@class
     */
    static public function quoteColumnName($columName)
    {
        $name = filter_var($columName, FILTER_VALIDATE_REGEXP, [
                    "options" => [
                        "regexp"=>"/^[a-zA-Z_@][a-zA-Z0-9_]*(\.@)?[a-zA-Z0-9_]*$/"
                    ]
                ]);
        
        if( $name !== $columName || empty($name))
            throw new OrientDBException(__CLASS__ . " unsafe column name: "  . $columName);
        
        return $columName;
    }
    
    static public function isRid($value)
    {
        return preg_match("/^#[0-9]*:[0-9]*$/", $value);
    }
    
    // need ask orientdb's developer what symbols need escape
    static private function escape($str)
    {
        // $value = str_replace("\\", "\\\\", $value);
        // $value = str_replace("'", "\'", $value);
        // $value = str_replace('"', '\"', $value);
        
        /*
        $search  = array("\\",   "'",  '"');  // "\0","\n","\r","\x1a",
        $replace = array("\\\\", "\'", '\"'); // "\\0","\\n","\\r","\Z",
        //search string     search regex        sql replacement regex
        
        return str_replace($search, $replace, $str);
        */
        
        return addcslashes(str_replace("'", "''", $str), "\000\n\r\\\032");
    }
    
    static function prepairEmbedded($embeddedData) {
         // this embedded record:
        if(isset($embeddedData['@class'])) {
            $embeddedData['@type'] = 'd'; // required field for embedded record
            // find child record:
            foreach($embeddedData as $key => $val) {
                if(is_array($embeddedData[$key]))
                    $embeddedData[$key] = self::prepairEmbedded($val);
            }
            
            return $embeddedData;
        } else if(!empty($embeddedData) && isset($embeddedData[0]) && !is_array($embeddedData[0])) { // check it may bee error
            throw new OrientDBException(__CLASS__ . " embedded relation require `@class` param");
        }
        
        // this embedded list:
        $result = [];
        foreach($embeddedData as $key => $val) {
            array_push($result, self::prepairEmbedded($val));
        }
        
        return $result;
    }
}
