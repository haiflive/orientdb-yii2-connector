<?php

namespace tests\codeception\unit\models;

use Yii;
use OrientDBYii2Connector\Query;
use OrientDBYii2Connector\DataRreaderOrientDB;

/**
 *  example run single test
 *  > codecept run unit models/oDealTest:testCreatePrice --debug
 *  debugging:
 *  codecept_debug($post);
 */
 
class oQueryTest extends \Codeception\Test\Unit
{
    public function testCreateRecord()
    {
        $client = Yii::$app->dborient->createCommand();
        
        $good_insert = $client->insert('Goods', [
            'GoodsDescription' => 'test Query GoodsDescription',
            'GoodsQuantity' => '11',
        ])->execute();
        
        // get by RID
        $good_select = (new Query())->from('Goods')
          ->where(['@rid'=>$good_insert['@rid']])
          ->one();
        
        $this->assertTrue($good_insert['@rid'] == $good_select['@rid'], 'compare RID, just insert and select');
        $this->assertTrue($good_insert['GoodsDescription'] == $good_select['GoodsDescription'], 'compare GoodsDescription, just insert and select');
        $this->assertTrue($good_insert['GoodsQuantity'] == $good_select['GoodsQuantity'], 'compare GoodsQuantity, just insert and select');
        
        $test_query_all = (new Query())
          ->from('Goods')
          ->where(['@rid'=>$good_insert['@rid']])
          ->all();
        
        $this->assertTrue(count($test_query_all) > 0, 'test query all');
        $this->assertTrue($test_query_all[0]['@rid'] == $good_insert['@rid'] , 'test query all');
    }
    
    
}

