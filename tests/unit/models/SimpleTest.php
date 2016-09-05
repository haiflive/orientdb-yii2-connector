<?php

namespace tests\codeception\unit\models;

use yii\codeception\TestCase;
use data\tDeal;

class SimpleTest extends TestCase
{
    public $appConfig = '@tests/unit/_config.php';
    
    public function testCreateDeal()
    {
        //simulate form input
        $post = [
            'tDeal' => [
                'Name' => 'Test deal',
                'Number' => 'Test deal',
                'Note' => 'some text note by deal',
                'Date' => date('Y-m-d')
            ]
        ];
        
        $deal = new tDeal(/*$post['tDeal']*/);
        
        $this->assertTrue($deal->load($post), 'Load POST data');
        
        $this->assertTrue($deal->save(), 'Create deal');
        
        $this->assertEquals(1, $deal->delete(), 'Remove just created deal');
    }
}
