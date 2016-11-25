<?php
namespace OrientDBYii2Connector;

/**
 *  @QuotaOrientDB static class helper quota db vaues(orient has no pdo)
 *  
 */

class QuotaOrientDB
{
    /**
     * @param $value string to bee filter
     * @return string - filtered string
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

    /**
     * @param $tableName
     * @return string - quoted table name
     * @throws OrientDBException - if table name invalid
     */
    static public function quoteTableName($tableName)
    {
        $name = filter_var($tableName, FILTER_VALIDATE_REGEXP, ["options"=>["regexp"=>"/^[a-zA-Z_][a-zA-Z0-9_]*$/"]]);
        if( !$name || empty($name))
            throw new OrientDBException(__CLASS__ . " unsafe table name: "  . $tableName);
        
        return '`'. $name .'`';
    }

    /**
     * @param $columnName - quota column name
     *  skip: @rid, @version, @class, clusterName.@rid, clusterName.@version, clusterName.@class
     * @return mixed
     * @throws OrientDBException
     */
    static public function quoteColumnName($columnName)
    {
        $name = filter_var($columnName, FILTER_VALIDATE_REGEXP, [
                    "options" => [
                        "regexp"=>"/^[a-zA-Z_@][a-zA-Z0-9_]*(\.@)?[a-zA-Z0-9_]*$/"
                    ]
                ]);
        
        if( $name !== $columnName || empty($name))
            throw new OrientDBException(__CLASS__ . " unsafe column name: "  . $columnName);
        
        return $columnName;
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
        
        return addcslashes($str, "\000\n\r\\\032\047");
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
            throw new OrientDBException(
                __CLASS__ . " embedded relation require `@class` param"
                . "\r\n given: " . json_encode($embeddedData)
            );
        }
        
        // this embedded list:
        $result = [];
        foreach($embeddedData as $key => $val) {
            array_push($result, self::prepairEmbedded($val));
        }
        
        return $result;
    }
}
