<?php

namespace tests\codeception\unit\models;

use PhpOrient\PhpOrient;
/**
 *  https://github.com/Ostico/PhpOrient/issues/58
 *  [depricated], fixed in "ostico/phporient": "v1.2.5"
 */

class PhpOrientTest extends \Codeception\Test\Unit
{
    public $appConfig = '@tests/unit/_config.php';
    
    public function testCreate()
    {
        $this->assertTrue(true, 'test do nothing');
    }
    
    public function testCreateEmptyRecord()
    {   
        /**
         *  OrientDB does not support DEFAULT VALUES but support the same syntax
         *  INSERT INTO Goods CONTENT {"@class":"Goods","@version":0,"@rid":"#-1:-1"}
         */
        
        /**
         *  Uninitialized string offset: 0
         *  trace:
            #1  /var/www/logistics/vendor/ostico/phporient/src/PhpOrient/Protocols/Binary/Abstracts/Operation.php:514
            #2  /var/www/logistics/vendor/ostico/phporient/src/PhpOrient/Protocols/Binary/Abstracts/Operation.php:596
            #3  /var/www/logistics/vendor/ostico/phporient/src/PhpOrient/Protocols/Binary/Operations/Command.php:191
            #4  /var/www/logistics/vendor/ostico/phporient/src/PhpOrient/Protocols/Binary/Abstracts/Operation.php:287
            #5  /var/www/logistics/vendor/ostico/phporient/src/PhpOrient/Protocols/Binary/SocketTransport.php:163
            #6  /var/www/logistics/vendor/ostico/phporient/src/PhpOrient/PhpOrient.php:274
            #7  /var/www/logistics/vendor/haiflive/orientdb-yii2-connector/tests/unit/models/PhpOrientTest.php:41
         */
        /**
         *  CSV.php line 32
            if(!empty($input)) {
                $chunk              = self::eatKey( $input );
                $key                = $chunk[ 0 ];
                $input              = $chunk[ 1 ];
            }
         */
         
        
        $query = 'INSERT INTO Goods CONTENT {"@class":"Goods","@version":0,"@rid":"#-1:-1"}';
        
        $client = $this->createDBConnection();
        
        $client->command($query);
    }
    
    public function testInsertLastInteger()
    {
        // INSERT INTO `Goods` (GoodsShortDescription, PlacesQuantity, SerialNumber, goodsArticul, Favorite) VALUES ('Test Goods Short Description', '56', '00011111122', '213-000037', '1')
        /**
         *  if las param type is INTEGER it return error
         *      Uninitialized string offset: 0
         *  trace:
            #1  /var/www/logistics/vendor/ostico/phporient/src/PhpOrient/Protocols/Binary/Serialization/CSV.php:59
            #2  /var/www/logistics/vendor/ostico/phporient/src/PhpOrient/Protocols/Binary/Abstracts/Operation.php:514
            #3  /var/www/logistics/vendor/ostico/phporient/src/PhpOrient/Protocols/Binary/Abstracts/Operation.php:596
            #4  /var/www/logistics/vendor/ostico/phporient/src/PhpOrient/Protocols/Binary/Operations/Command.php:191
            #5  /var/www/logistics/vendor/ostico/phporient/src/PhpOrient/Protocols/Binary/Abstracts/Operation.php:287
            #6  /var/www/logistics/vendor/ostico/phporient/src/PhpOrient/Protocols/Binary/SocketTransport.php:163
            #7  /var/www/logistics/vendor/ostico/phporient/src/PhpOrient/PhpOrient.php:274
            #8  /var/www/logistics/vendor/haiflive/orientdb-yii2-connector/tests/unit/models/PhpOrientTest.php:58
        */
        /**
         *  solution
         *  CSV.php line 227:
            if ($length > $i)
                $input = substr( $input, $i );
         */
         
        $query = "INSERT INTO `Goods` " .
                    "(GoodsShortDescription, PlacesQuantity, goodsArticul, Favorite) " .
                    "VALUES " .
                    "('Test Goods Short Description', '56', '213-000037', '1')";
        
        $client = $this->createDBConnection();
        
        $client->command($query);
    }
    
    protected function createDBConnection()
    {
        $client = new PhpOrient();
        $client->configure( [
            'username' => 'root',
            'password' => '369',
            'hostname' => 'localhost',
            'port'     => 2424,
        ] );
        
        $client->connect();
        $client->dbOpen( 'logistics' );
        
        return $client;
    }
}
