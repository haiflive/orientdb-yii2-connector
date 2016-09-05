<?php

namespace tests\codeception\unit\models;

use yii\codeception\TestCase;
use data\tOrganization;
use data\tExpense;
use data\tGoods;
use data\tAddress;
use data\tServices;
use data\tPrice;
use data\tTransport;
use data\tResident;
use data\tDeal;

class tDealTest extends TestCase
{
    public $appConfig = '@tests/unit/_config.php';
    
    public function testCreateSender()
    {
        //simulate form input
        $post = [
            'tOrganization' => [
                'role' => 'buyer',
                'Country' => '643',
                'OrganizationName' => 'Sender organization full name',
                'ShortName' => 'Sender organization',
                'Phone' => '+7988878787',
                'Email' => 'org@testSender.te',
                'Skype' => 'org_test',
                'Site' => 'www.testSender.te',
                'INN' => '252525454545',
                'CreateDate' => date('Y-m-d')
            ]
        ];
        
        $organization = new tOrganization;
        
        $this->assertTrue($organization->load($post), 'Load organization POST data');
        $this->assertTrue($organization->validate(),  'Validate organization');
        $this->assertTrue($organization->save(),      'Create organization');
        
        return $organization;
    }
    
    public function testCreateReciver()
    {
        //simulate form input
        $post = [
            'tOrganization' => [
                'role' => 'seller',
                'Country' => '156',
                'OrganizationName' => 'Reciver organization full name',
                'ShortName' => 'Reciver organization',
                'Phone' => '+8988878787',
                'Email' => 'org@testReciver.ch',
                'Skype' => 'org_test',
                'Site' => 'www.testReciver.te',
                'CreateDate' => date('Y-m-d')
            ]
        ];
        
        $organization = new tOrganization;
        
        $this->assertTrue($organization->load($post), 'Load organization POST data');
        $this->assertTrue($organization->validate(),  'Validate organization');
        $this->assertTrue($organization->save(),      'Create organization');
        
        return $organization;
    }
    
    public function testCreateExecutor()
    {
        //simulate form input
        $post = [
            'tOrganization' => [
                'role' => 'default',
                'Country' => '156',
                'OrganizationName' => 'Executor organization full name',
                'ShortName' => 'Executor organization',
                'Phone' => '+8988878787',
                'Email' => 'org@testExecutor.ch',
                'Skype' => 'org_test',
                'Site' => 'www.testExecutor.te',
                'CreateDate' => date('Y-m-d')
            ]
        ];
        
        $organization = new tOrganization;
        
        $this->assertTrue($organization->load($post), 'Load organization POST data');
        $this->assertTrue($organization->validate(),  'Validate organization');
        $this->assertTrue($organization->save(),      'Create organization');
        
        return $organization;
    }
    
    public function testCreateAddress()
    {
        //simulate form input
        $post = [
            'tAddress' => [
                'PostalCode' => '692500',
                'CountryCode' => 'RU',
                'Region' => 'Primorsky kray',
                'City' => 'Ussuriisk',
                'StreetHouse' => 'Nekrasova street, 25',
                'LanguageCode' => 'EN'
            ]
        ];
        
        $address = new tAddress;
        
        $this->assertTrue($address->load($post), 'Load address POST data');
        $this->assertTrue($address->validate(),  'Validate address');
        $this->assertTrue($address->save(),      'Create address');
        
        return $address;
    }
    
    public function testCreateExpense()
    {
        //simulate form input
        $post = [
            'tExpense' => [
                'Name' => 'Test Expense',
                'Price' => '100.00',
                'CurrencyCode' => 'USD',
                'Margin' => '0',
                'Cost' => '100.00'
            ]
        ];
        
        $expense = new tExpense;
        
        $this->assertTrue($expense->load($post), 'Load expense POST data');
        $this->assertTrue($expense->validate(),  'Validate expense');
        $this->assertTrue($expense->save(),      'Create expense');
        
        return $expense;
    }
    
    public function testCreateGood()
    {
        //simulate form input
        $post = [
            'tGoods' => [
                'GoodsShortDescription' => 'Test Goods Short Description',
                'GoodsDescription' => 'Full text Goods Description',
                'GoodsQuantity' => '100',
                'MeasureUnitQualifierCode' => '796',
                'MeasureUnitQualifierCost' => '10',
                'Price' => '10',
                'Cost' => '9.8',
                'GoodsTNVEDCode' => '8467211000',
                'GoodsAddTNVEDCode' => '0000',
                'CurrencyCode' => 'USD',
                'PlacesQuantity' => '56',
                'Length' => '1000',
                'Width' => '15000',
                'Height' => '23000',
                'PackingCode' => 'CT',
                'Manufacturer' => 'SHENZHEN YOYI TOOLS INDUSTRY CO., LTD',
                'TradeMark' => 'noname',
                'GoodsMark' => '37',
                'GoodsModel' => 'X7',
                'GoodsMarking' => '00-37-445',
                'DateIssue' => date('Y-m-d'),
                'SerialNumber' => '00011111122',
                'goodsArticul' => '213-000037'
            ]
        ];
        
        $good = new tGoods;
        
        $this->assertTrue($good->load($post), 'Load good POST data');
        $this->assertTrue($good->validate(),  'Validate good');
        $this->assertTrue($good->save(),      'Create good');
        
        return $good;
    }
    
    public function testCreateServices()
    {
        //simulate form input
        $post = [
            'tServices' => [
                'Name' => 'Service name'
            ]
        ];
        
        $services = new tServices;
        
        $this->assertTrue($services->load($post), 'Load services POST data');
        $this->assertTrue($services->validate(),  'Validate services');
        $this->assertTrue($services->save(),      'Create services');
        
        return $services;
    }
    
    public function testCreatePrice()
    {
        //simulate form input
        $post = [
            'tPrice' => [
                'Price' => '110.00',
                'Cost' => '100.00',
                'Discount' => '0',
                'QuantityMeasure' => '796',
                'Quantity' => '1'
            ]
        ];
        
        $price = new tPrice;
        
        $this->assertTrue($price->load($post), 'Load price POST data');
        $this->assertTrue($price->validate(),  'Validate price');
        $this->assertTrue($price->save(),      'Create price');
        
        return $price;
    }
    
    public function testCreateTransport()
    {
        //simulate form input
        $post = [
            'tTransport' => [
                'TransportIdentifier' => 'AB0202AM23',
                'NameMrkCar' => 'KAMAZ',
                'model' => '2207',
                'volume' => '10000',
                'mass' => '4000',
                'Note' => 'Truck'
            ]
        ];
        
        $transport = new tTransport;
        
        $this->assertTrue($transport->load($post), 'Load transport POST data');
        $this->assertTrue($transport->validate(),  'Validate transport');
        $this->assertTrue($transport->save(),      'Create transport');
        
        return $transport;
    }
    
    public function testCreateResident()
    {
        //simulate form input
        $post = [
            'tResident' => [
                'role' => '2',
                'PersonSurname' => 'Ivanov',
                'PersonName' => 'Ivan',
                'PersonMiddleName' => 'Ivanovich',
                'PersonPost' => 'Driver',
                'ContactPhone' => '+7878787777',
                'sex' => 'male',
                'email' => 'ivanov_1978@test.te'
            ]
        ];
        
        $resident = new tResident;
        
        $this->assertTrue($resident->load($post), 'Load resident POST data');
        $this->assertTrue($resident->validate(),  'Validate resident');
        $this->assertTrue($resident->save(),      'Create resident');
        
        return $resident;
    }
    
    /**
     * @depends testCreateSender
     * @depends testCreateReciver
     * @depends testCreateExecutor
     * @depends testCreateAddress
     * @depends testCreateExpense
     * @depends testCreateGood
     * @depends testCreateServices
     * @depends testCreatePrice
     * @depends testCreateTransport
     * @depends testCreateResident
     */
    public function testCreateDeal()
    {
        // create raltion data
        $sender       = $this->testCreateSender();
        $reciver      = $this->testCreateReciver();
        $executor     = $this->testCreateExecutor();
        $address_from = $this->testCreateAddress();
        $address_to   = $this->testCreateAddress();
        
        $delivery   = $this->testCreateAddress();
        
        $expense1 = $this->testCreateExpense();
        $expense2 = $this->testCreateExpense();
        
        $good1 = $this->testCreateGood();
        $good2 = $this->testCreateGood();
        
        $price1 = $this->testCreatePrice();
        $price2 = $this->testCreatePrice();
        
        $service1 = $this->testCreateServices();
        $service2 = $this->testCreateServices();
        
        $transport = $this->testCreateTransport();
        $driver = $this->testCreateResident();
        
        // check data
        $this->assertEquals('Sender organization', $sender->ShortName, 'Check exists sender data');
        $this->assertEquals('Reciver organization', $reciver->ShortName, 'Check exists reciver data');
        $this->assertEquals('100.0000', $expense1->Cost, 'Check exists expense1 data');
        $this->assertEquals('100.0000', $expense2->Cost, 'Check exists expense2 data');
        
        // --
        // simulate form input
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
        
        // hasOne
        $deal->link('sender', $sender); // it call `$deal->save()`
        $deal->link('reciver', $reciver);
        $deal->link('address_from', $address_from);
        $deal->link('address_to', $address_to);
        
        // hasMany
        $deal->link('expenses', $expense1);
        $deal->link('expenses', $expense2);
        
        $price1->link('service', $service1);
        $price2->link('service', $service2);
        
        $price1->link('delivery', $delivery);
        $price2->link('delivery', $delivery);
        
        $price1->link('goods', $good1);
        $price1->link('goods', $good2);
        
        $transport->link('driver', $driver);
        $price1->link('transport', $transport);
        
        $expense1->link('prices', $price1);
        $expense1->link('prices', $price2);
        $expense1->link('executor', $executor);
        
        // hasMany
        $deal->link('goods', $good1);
        $deal->link('goods', $good2);
        
        $this->assertTrue($deal->validate(), 'Validate Deal');
        
        $this->assertTrue($deal->save(), 'Create deal');
        
        return $deal;
    }
    
    /**
     * @depends testCreateDeal
     */
    public function testCreateAndLoadDealWithRelations()
    {
        // create deals with relation data
        for($i=0; $i < 2; $i++) {
            $this->testCreateDeal();
        }
        
        // test Eager Loading
        $deals = tDeal::find()
            ->with([
                'sender',
                'reciver',
                'address_from',
                'address_to',
                'goods',
                'expenses',
                'expenses.prices',
                'expenses.prices.service',
                'expenses.prices.delivery',
                'expenses.prices.goods',
                'expenses.prices.transport',
                'expenses.prices.transport.driver',
                'expenses.executor',
            ])
            ->limit(100)
            ->all();
        
        $this->assertTrue(
                    $deals[0]->sender->role == 'buyer'
                &&  $deals[0]->sender->Country == '643'
                &&  $deals[0]->sender->OrganizationName == 'Sender organization full name'
                &&  $deals[0]->sender->ShortName == 'Sender organization'
            , 'Check sender by deal');
        
        $this->assertTrue(
                    $deals[0]->reciver->role == 'seller'
                &&  $deals[0]->reciver->Country == '156'
                &&  $deals[0]->reciver->OrganizationName == 'Reciver organization full name'
                &&  $deals[0]->reciver->ShortName == 'Reciver organization'
            , 'Check reciver by deal');
            
        $this->assertTrue(
                    $deals[0]->expenses[0]->prices[0]->transport->driver->PersonSurname == 'Ivanov'
                &&  $deals[0]->expenses[0]->prices[0]->transport->driver->PersonName == 'Ivan'
                &&  $deals[0]->expenses[0]->prices[0]->transport->driver->PersonMiddleName == 'Ivanovich'
                &&  $deals[0]->expenses[0]->prices[0]->transport->driver->PersonPost == 'Driver'
            , 'Check driver by deal');
        
        $this->assertTrue(
                    $deals[0]->expenses[0]->prices[0]->goods[0]->GoodsShortDescription == 'Test Goods Short Description'
                &&  $deals[0]->expenses[0]->prices[0]->goods[0]->GoodsDescription == 'Full text Goods Description'
                &&  $deals[0]->expenses[0]->prices[0]->goods[0]->GoodsQuantity == '100'
                &&  $deals[0]->expenses[0]->prices[0]->goods[0]->MeasureUnitQualifierCode == '796'
                &&  $deals[0]->expenses[0]->prices[0]->goods[0]->MeasureUnitQualifierCost == '10'
                &&  $deals[0]->expenses[0]->prices[0]->goods[0]->Price == '10'
                &&  $deals[0]->expenses[0]->prices[0]->goods[0]->Cost == '9.8'
            , 'Check goods by price by deal');
    }
}
