<?php

namespace tests\codeception\unit\models;

use Yii;
use OrientDBYii2Connector\Query;

/**
 *  example run single test
 *  > codecept run unit models/oQueryTest:testCreateRecord --debug
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
    
    public function testCreateRelatedEmbedded()
    {
        $client = Yii::$app->dborient->createCommand();
        
        $expense_insert = $client->insert('Expense', [
            'Name' => 'test Query Expense related',
            'Price' => '1021',
            // 'executor' => // linlk
            'prices' => [[ // embedded list (has_many)
              // "@type" => "d",
              // "@version" => 0,
              "@class" => "Price", //! required for embedded
              "Price" => 45,
              "Cost" => 156,
              "transport" => [ // embedded (has_one)
                "@class" => "Transport", //! required for embedded
                "Capacity" => 3,
                "ContainerKind" => "test Query Expense embedded -> embedded"
              ]
            ]]
        ])->execute();
        
        // get by RID
        $expense_select = (new Query())->from('Expense')
          ->where(['@rid'=>$expense_insert['@rid']])
          ->one();
        
        $this->assertTrue($expense_insert['@rid'] == $expense_select['@rid'], 'compare RID, just insert and select');
        $this->assertTrue($expense_insert['Name'] == $expense_select['Name'], 'compare Name, just insert and select');
        $this->assertTrue($expense_insert['Price'] == $expense_select['Price'], 'compare Price, just insert and select');
        $this->assertTrue($expense_insert['prices'][0]['Cost'] == $expense_select['prices'][0]['Cost'], 'compare prices(embedded), just insert and select');
        $this->assertTrue($expense_insert['prices'][0]['transport']['ContainerKind'] == $expense_select['prices'][0]['transport']['ContainerKind'], 'compare prices(embedded -> embedded), just insert and select');
        
        $test_query_all = (new Query())
          ->from('Expense')
          ->where(['@rid'=>$expense_insert['@rid']])
          ->all();
        
        $this->assertTrue(count($test_query_all) > 0, 'test query all');
        $this->assertTrue($test_query_all[0]['@rid'] == $expense_insert['@rid'] , 'test query all');
    }
    
    public function testCreateRelatedLink()
    {
        $client = Yii::$app->dborient->createCommand();
        
        // create record `Organization` for relation with `Expense`
        $organization_insert = $client->insert('Organization', [
            'Country' => 56,
            'OrganizationName' => 'rest link realtion organization name',
            'Site' => 'test.te'
        ])->execute();
        
        // create `Expense` with link relation `executor`
        $expense_insert = $client->insert('Expense', [
            'Name' => 'test Query Expense related',
            'Price' => '1021',
            'executor' => $organization_insert['@rid'] // linlk
        ])->execute();
        
        // get by RID
        $expense_select = (new Query())->from('Expense')
          ->where(['@rid'=>$expense_insert['@rid']])
          ->fetch_plan('executor:0') // `:0` - is level (see more documentation OrientDB fetch_plan)
          ->one();
        
        $this->assertTrue($expense_insert['@rid'] == $expense_select['@rid'], 'compare RID, just insert and select');
        $this->assertTrue($expense_insert['Name'] == $expense_select['Name'], 'compare Name, just insert and select');
        $this->assertTrue($expense_insert['Price'] == $expense_select['Price'], 'compare Price, just insert and select');
        
        $this->assertTrue($expense_select['executor']['Country'] == $organization_insert['Country'], 'compare Country link relation executor, just insert and select');
        $this->assertTrue($expense_select['executor']['OrganizationName'] == $organization_insert['OrganizationName'], 'compare OrganizationName link relation executor, just insert and select');
        
        $test_query_all = (new Query())
          ->from('Expense')
          ->where(['@rid'=>$expense_insert['@rid']])
          ->all();
        
        $this->assertTrue(count($test_query_all) > 0, 'test query all');
        $this->assertTrue($test_query_all[0]['@rid'] == $expense_insert['@rid'] , 'test query all');
    }
    
    
}

