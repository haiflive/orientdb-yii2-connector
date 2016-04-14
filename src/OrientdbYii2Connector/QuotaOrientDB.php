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
        if(is_array($value))
            return json_encode($value); //!? BUG need quota recursively 
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
        
        $search  = array("\\",   "'",  '"');  // "\0","\n","\r","\x1a",
        $replace = array("\\\\", "\'", '\"'); // "\\0","\\n","\\r","\Z",
        //search string     search regex        sql replacement regex
        
        
        return str_replace($search, $replace, $str);
    }
}
