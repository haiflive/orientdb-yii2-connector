<?php

namespace tests\codeception\unit\models;

use yii\codeception\TestCase;
use data\oOrganization;
use data\oExpense;
use data\oGoods;
use data\oAddress;
use data\oServices;
use data\oPrice;
use data\oTransport;
use data\oResident;
use data\oDeal;

/**
 *  example run single test
 *  > codecept run unit models/oDealTest:testCreatePrice --debug
 *  debugging:
 *  codecept_debug($post);
 */

class oDealTest extends TestCase
{
    public $appConfig = '@tests/unit/_config.php';
    
    public function testCreateSender()
    {
        //simulate form input
        $post = [
            'oOrganization' => [
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
        
        $organization = new oOrganization;
        
        $this->assertTrue($organization->load($post), 'Load organization POST data');
        $this->assertTrue($organization->validate(),  'Validate organization');
        $this->assertTrue($organization->save(),      'Create organization');
        
        return $organization;
    }
    
    public function testCreateReciver()
    {
        //simulate form input
        $post = [
            'oOrganization' => [
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
        
        $organization = new oOrganization;
        
        $this->assertTrue($organization->load($post), 'Load organization POST data');
        $this->assertTrue($organization->validate(),  'Validate organization');
        $this->assertTrue($organization->save(),      'Create organization');
        
        return $organization;
    }
    
    public function testCreateExecutor()
    {
        //simulate form input
        $post = [
            'oOrganization' => [
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
        
        $organization = new oOrganization;

        $service1 = $this->testCreatePrice();
        $service2 = $this->testCreatePrice();
        // LINKLIST
        $organization->link('services', $service1);
        $organization->link('services', $service2);

        $this->assertTrue($organization->load($post), 'Load organization POST data');
        $this->assertTrue($organization->validate(),  'Validate organization');
        $this->assertTrue($organization->save(),      'Create organization');

        return $organization;
    }
    
    public function testCreateAddress()
    {
        //simulate form input
        $post = [
            'oAddress' => [
                'PostalCode' => '692500',
                'CountryCode' => 'RU',
                'Region' => 'Primorsky kray',
                'City' => 'Ussuriisk',
                'StreetHouse' => 'Nekrasova street, 25',
                'LanguageCode' => 'EN'
            ]
        ];
        
        $address = new oAddress;
        
        $this->assertTrue($address->load($post), 'Load address POST data');
        $this->assertTrue($address->validate(),  'Validate address');
        $this->assertTrue($address->save(),      'Create address');
        
        return $address;
    }
    
    public function testCreateExpense()
    {
        //simulate form input
        $post = [
            'oExpense' => [
                'Name' => 'Test Expense',
                'Price' => '100.00',
                'CurrencyCode' => 'USD',
                'Margin' => '0',
                'Cost' => '100.00'
            ]
        ];
        
        $expense = new oExpense;
        
        $this->assertTrue($expense->load($post), 'Load expense POST data');
        $this->assertTrue($expense->validate(),  'Validate expense');
        $this->assertTrue($expense->save(),      'Create expense');
        
        return $expense;
    }
    
    public function testCreateGood()
    {
        //simulate form input
        $post = [
            'oGoods' => [
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
                'goodsArticul' => '213-000037',
                'Favorite' => '1' // BUG INTEGER
            ]
        ];
        
        $good = new oGoods;
        
        $this->assertTrue($good->load($post), 'Load good POST data');
        $this->assertTrue($good->validate(),  'Validate good');
        $this->assertTrue($good->save(),      'Create good');
        
        return $good;
    }
    
    public function testCreateServices()
    {
        //simulate form input
        $post = [
            'oServices' => [
                'Name' => 'Service name'
            ]
        ];
        
        $services = new oServices;
        
        $this->assertTrue($services->load($post), 'Load services POST data');
        $this->assertTrue($services->validate(),  'Validate services');
        $this->assertTrue($services->save(),      'Create services');
        
        return $services;
    }
    
    public function testCreatePrice()
    {
        //simulate form input
        $post = [
            'oPrice' => [
                'Price' => '110.00',
                'Cost' => '100.00',
                'Discount' => '0',
                'QuantityMeasure' => '796',
                'Quantity' => '1.00'
            ]
        ];
        
        $price = new oPrice;
        
        $this->assertTrue($price->load($post), 'Load price POST data');
        $this->assertTrue($price->validate(),  'Validate price');
        $this->assertTrue($price->save(),      'Create price');
        
        return $price;
    }
    
    public function testCreateTransport()
    {
        //simulate form input
        $post = [
            'oTransport' => [
                'TransportIdentifier' => 'AB0202AM23',
                'NameMrkCar' => 'KAMAZ',
                'model' => '2207',
                'volume' => '10000',
                'mass' => '4000',
                'Note' => 'Truck'
            ]
        ];
        
        $transport = new oTransport;
        
        $this->assertTrue($transport->load($post), 'Load transport POST data');
        $this->assertTrue($transport->validate(),  'Validate transport');
        $this->assertTrue($transport->save(),      'Create transport');
        
        return $transport;
    }
    
    public function testCreateResident()
    {
        //simulate form input
        $post = [
            'oResident' => [
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
        
        $resident = new oResident;
        
        $this->assertTrue($resident->load($post), 'Load resident POST data');
        $this->assertTrue($resident->validate(),  'Validate resident');
        $this->assertTrue($resident->save(),      'Create resident');
        
        return $resident;
    }

    /* --->*
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
        //simulate form input
        $post = [
            'oDeal' => [
                'CurrencyCode' => 'USD',
                'Date' => date('Y-m-d'),
                'Name' => 'unit_test_deal',
                'Note' => 'testing relations',
                'Number' => '0001',
                // embedded relations:
//                'sender' => [
//                    '@class'=>'Organization', //!
//                    'role' => 'buyer',
//                    'Country' => '643',
//                    'OrganizationName' => 'Sender organization full name',
//                    'ShortName' => 'Sender organization',
//                    'Phone' => '+7988878787',
//                    'Email' => 'org@testSender.te',
//                    'Skype' => 'org_test',
//                    'Site' => 'www.testSender.te',
//                    'INN' => '252525454545',
//                    'CreateDate' => date('Y-m-d')
//                ],
//                'reciver' => [
//                    '@class'=>'Organization', //!
//                    'role' => 'seller',
//                    'Country' => '156',
//                    'OrganizationName' => 'Reciver organization full name',
//                    'ShortName' => 'Reciver organization',
//                    'Phone' => '+8988878787',
//                    'Email' => 'org@testReciver.ch',
//                    'Skype' => 'org_test',
//                    'Site' => 'www.testReciver.te',
//                    'CreateDate' => date('Y-m-d')
//                ],
                'addressTo' => [
                    '@class'=>'Address', //!
                    'PostalCode' => '692500',
                    'CountryCode' => 'RU',
                    'Region' => 'Primorsky kray',
                    'City' => 'Ussuriisk',
                    'StreetHouse' => 'Nekrasova street, 25',
                    'LanguageCode' => 'EN'
                ],
                'addressFrom' => [
                    '@class'=>'Address', //!
                    'PostalCode' => '692500',
                    'CountryCode' => 'RU',
                    'Region' => 'Primorsky kray',
                    'City' => 'Ussuriisk',
                    'StreetHouse' => 'Nekrasova street, 25',
                    'LanguageCode' => 'EN'
                ],
                // embedded  list relations:
                'goods' => [[
                    '@class'=>'Goods', //!
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
                    'goodsArticul' => '213-000037',
                    'Favorite' => '1' // BUG INTEGER
                ],[
                    '@class'=>'Goods', //!
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
                    'goodsArticul' => '213-000037',
                    'Favorite' => '1' // BUG INTEGER
                ]],
                'expenses' => [[
                    '@class'=>'Expense', //!
                    'Name' => 'Test Expense',
                    'Price' => '100.00',
                    'CurrencyCode' => 'USD',
                    'Margin' => '0',
                    'Cost' => '100.00',
                    'prices' => [[ // has_many
                            '@class'=>'Price', //!
                            'Price' => '110.00',
                            'Cost' => '100.00',
                            'Discount' => '0',
                            'QuantityMeasure' => '796',
                            'Quantity' => '1',
                            'transport' => [
                                '@class'=>'Transport', //!
                                'TransportIdentifier' => 'AB0202AM23',
                                'NameMrkCar' => 'KAMAZ',
                                'model' => '2207',
                                'volume' => '10000',
                                'mass' => '4000',
                                'Note' => 'Truck',
                                'driver' => [
                                    '@class'=>'Resident', //!
                                    'role' => '2',
                                    'PersonSurname' => 'Ivanov',
                                    'PersonName' => 'Ivan',
                                    'PersonMiddleName' => 'Ivanovich',
                                    'PersonPost' => 'Driver',
                                    'ContactPhone' => '+7878787777',
                                    'sex' => 'male',
                                    'email' => 'ivanov_1978@test.te'
                                ]
                            ],
                            'goods' => [[
                                    '@class'=>'Goods', //!
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
                                    'goodsArticul' => '213-000037',
                                    'Favorite' => '1' // BUG INTEGER
                                ],[
                                    '@class'=>'Goods', //!
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
                                    'goodsArticul' => '213-000037',
                                    'Favorite' => '1' // BUG INTEGER
                                ]
                            ]
                        ],[
                            '@class'=>'Price', //!
                            'Price' => '110.00',
                            'Cost' => '100.00',
                            'Discount' => '0',
                            'QuantityMeasure' => '796',
                            'Quantity' => '1'
                        ]
                    ]
                ],[
                    '@class'=>'Expense', //!
                    'Name' => 'Test Expense',
                    'Price' => '100.00',
                    'CurrencyCode' => 'USD',
                    'Margin' => '0',
                    'Cost' => '100.00'
                ]],
            ]
        ];

        $deal = new oDeal;
        // TODO create full relations example
        // create relations
        $executor = $this->testCreateExecutor(); // has services(LINKLIST)
        $sender = $this->testCreateSender();
        $reciver = $this->testCreateReciver();
        $address1 = $this->testCreateAddress();
        $address2 = $this->testCreateAddress();
        $driver = $this->testCreateResident();

        $this->assertTrue($deal->load($post), 'Load deal POST data');

        // link relation data
        // 1 test link
        $deal->link('sender', $sender);
        // 2 test magic
        unset($reciver->{'@rid'});
        $deal->reciver = $reciver;

        // link of relation
        $deal->expenses[0]->link('executor', $executor);

        // link of relation of relation
        $deal->expenses[0]->prices[0]->link('delivery', $address1);

        // link of relation of relation of relation
        $deal->expenses[0]->prices[0]->transport->link('driver', $driver); // embedded

        //
        $deal->expenses[0]->prices[1]->link('delivery', $address2);

//        $deal->expenses[1]->executor = $executor; //"-" test magic relation
        $deal->expenses[1]->link('executor', $executor); //"+" test link

        $this->assertTrue($deal->validate(),  'Validate deal');
        $this->assertTrue($deal->save(),      'Create deal');

        $dealFind = oDeal::find()
            ->where(['@rid' => $deal['@rid']])
            ->with([
                'sender',
                'reciver',
                'addressTo',
                'addressFrom',
                'goods',
                'expenses',
                'expenses.executor',
                'expenses.prices',
                'expenses.prices.delivery',
                'expenses.prices.transport',
                'expenses.prices.transport.driver',
                'expenses.prices.goods',
            ])
            ->one();

        $this->assertTrue($dealFind->sender instanceof \yii\db\ActiveRecord, 'Sender Exists');
        $this->assertTrue($dealFind->reciver instanceof \yii\db\ActiveRecord, 'Sender Exists');
        $this->assertTrue($dealFind->addressTo instanceof \yii\db\ActiveRecord, 'addressTo Exists');
        $this->assertTrue($dealFind->addressFrom instanceof \yii\db\ActiveRecord, 'addressFrom Exists');
        $this->assertTrue(count($dealFind->goods) > 0, 'deal goods array items Exists');
        $this->assertTrue($dealFind->goods[0] instanceof \yii\db\ActiveRecord, 'goods Exists');
        $this->assertTrue(count($dealFind->expenses) > 0, 'expenses array items Exists');
        $this->assertTrue($dealFind->expenses[0] instanceof \yii\db\ActiveRecord, 'expense Exists');
        $this->assertTrue($dealFind->expenses[0]->executor instanceof \yii\db\ActiveRecord, 'expense executor Exists');
        $this->assertTrue(count($dealFind->expenses[0]->prices) > 0, 'expense prices array items Exists');
        $this->assertTrue($dealFind->expenses[0]->prices[0] instanceof \yii\db\ActiveRecord, 'expenses price Exists');
        $this->assertTrue($dealFind->expenses[0]->prices[0]->delivery instanceof \yii\db\ActiveRecord, 'expense price delivery Exists');
        $this->assertTrue($dealFind->expenses[0]->prices[0]->transport instanceof \yii\db\ActiveRecord, 'expense price transport Exists');
        $this->assertTrue($dealFind->expenses[0]->prices[0]->transport->driver instanceof \yii\db\ActiveRecord, 'expense price transport driver Exists');
        $this->assertTrue(count($dealFind->expenses[0]->prices[0]->goods) > 0, 'expense price goods array items Exists');
        $this->assertTrue($dealFind->expenses[0]->prices[0]->goods[0] instanceof \yii\db\ActiveRecord, 'expense price good Exists');

        return $deal;
    }


    public function testCreateRelationByRelationDeal()
    {
        //simulate form input
        $post = [
            'oDeal' => [
                'CurrencyCode' => 'USD',
                'Date' => date('Y-m-d'),
                'Name' => 'unit_test_deal',
                'Note' => 'testing relations',
                'Number' => '0001',
                'expenses' => [[
                    '@class'=>'Expense', //!
                    'Name' => 'Test Expense',
                    'Price' => '100.00',
                    'CurrencyCode' => 'USD',
                    'Margin' => '0',
                    'Cost' => '100.00'
                ],[
                    '@class'=>'Expense', //!
                    'Name' => 'Test Expense',
                    'Price' => '100.00',
                    'CurrencyCode' => 'USD',
                    'Margin' => '0',
                    'Cost' => '100.00'
                ]],
            ]
        ];

        $deal = new oDeal;
        // create relations
        $executor = $this->testCreateExecutor(); // has services(LINKLIST)

        $this->assertTrue($deal->load($post), 'Load deal POST data');

        // link of relation
        $deal->expenses[0]->link('executor', $executor);

//        $deal->expenses[1]->executor = $executor; //"-" test magic relation
        $deal->expenses[1]->link('executor', $executor); //"+" test link

        $this->assertTrue($deal->validate(),  'Validate deal');
        $this->assertTrue($deal->save(),      'Create deal');

        $dealFind = oDeal::find()
            ->where(['@rid' => $deal['@rid']])
            ->with([
                'expenses',
                'expenses.executor'
            ])
            ->one();

        $this->assertTrue($dealFind->expenses[0]->executor instanceof \yii\db\ActiveRecord, 'expense executor Exists');

        return $deal;
    }

    
    /* --->*
     * @depends testCreateDeal
     */
    public function testCreateAndLoadDealWithRelations()
    {
        // create deals with relation data
        for($i=0; $i < 2; $i++) {
            $this->testCreateDeal();
        }
        
        // test Eager Loading
        $deals = oDeal::find()
            ->limit(100)
            ->orderBy('@rid DESC')
            ->all();
        
        $this->assertTrue(
                    $deals[0]->sender['role'] == 'buyer'
                &&  $deals[0]->sender['Country'] == '643'
                &&  $deals[0]->sender['OrganizationName'] == 'Sender organization full name'
                &&  $deals[0]->sender['ShortName'] == 'Sender organization'
            , 'Check sender by deal');
        
        $this->assertTrue(
                    $deals[0]->reciver['role'] == 'seller'
                &&  $deals[0]->reciver['Country'] == '156'
                &&  $deals[0]->reciver['OrganizationName'] == 'Reciver organization full name'
                &&  $deals[0]->reciver['ShortName'] == 'Reciver organization'
            , 'Check reciver by deal');
        
        $this->assertTrue(
                    $deals[0]->expenses[0]['prices'][0]['transport']['driver']['PersonSurname'] == 'Ivanov'
                &&  $deals[0]->expenses[0]['prices'][0]['transport']['driver']['PersonName'] == 'Ivan'
                &&  $deals[0]->expenses[0]['prices'][0]['transport']['driver']['PersonMiddleName'] == 'Ivanovich'
                &&  $deals[0]->expenses[0]['prices'][0]['transport']['driver']['PersonPost'] == 'Driver'
            , 'Check driver by deal');
        
        $this->assertTrue(
                    $deals[0]->expenses[0]['prices'][0]['goods'][0]['GoodsShortDescription'] == 'Test Goods Short Description'
                &&  $deals[0]->expenses[0]['prices'][0]['goods'][0]['GoodsDescription'] == 'Full text Goods Description'
                &&  $deals[0]->expenses[0]['prices'][0]['goods'][0]['GoodsQuantity'] == '100'
                &&  $deals[0]->expenses[0]['prices'][0]['goods'][0]['MeasureUnitQualifierCode'] == '796'
                &&  $deals[0]->expenses[0]['prices'][0]['goods'][0]['MeasureUnitQualifierCost'] == '10'
                &&  $deals[0]->expenses[0]['prices'][0]['goods'][0]['Price'] == '10'
                &&  $deals[0]->expenses[0]['prices'][0]['goods'][0]['Cost'] == '9.8'
            , 'Check goods by price by deal');
    }
    
    public function testEmbeddedRelation()
    {
        //simulate form input
        $post = [
            'oPrice' => [
                'Price' => '10.00',
                'Cost' => '10.00',
                'Discount' => '0',
                'QuantityMeasure' => '796',
                'Quantity' => '11.00',
//                'transport' => [ //! equal THAT - will create ActiveRecord if exists, else embedded JSON value(no ActiveRecord)
//                    '@class'=>'Transport', //!
//                    'TransportIdentifier' => 'AB0202AM23',
//                    'NameMrkCar' => 'KAMAZ',
//                    'model' => '2207',
//                    'volume' => '10000',
//                    'mass' => '4000',
//                    'Note' => 'Truck'
//                ],
            ]
        ];
        
        $price = new oPrice;

        $transport = new oTransport;

        $transport->load([
            'oTransport' => [
                '@class'=>'Transport', //!
                'TransportIdentifier' => 'AB0202AM23',
                'NameMrkCar' => 'KAMAZ',
                'model' => '2207',
                'volume' => '10000',
                'mass' => '4000',
                'Note' => 'Truck'
            ]
        ]);

        $price->transport = $transport;  //! equal THAT - will create ActiveRecord if exists

        $this->assertTrue($price->load($post), 'Load price POST data');
        $this->assertTrue($price->validate(),  'Validate price');
        $this->assertTrue($price->save(),      'Create price');

        $priceFind = oPrice::find()
                        ->where(['@rid' => $price['@rid']])
                        ->with(['transport'])
                        ->one();

        $this->assertTrue($priceFind->QuantityMeasure == '796', 'Check QuantityMeasure');
        $this->assertTrue($priceFind->transport->TransportIdentifier == 'AB0202AM23', 'Check relation embedded transport->TransportIdentifier');

        $priceFind->QuantityMeasure = '999';
        $priceFind->transport->TransportIdentifier = 'modify TransportIdentifier';

        $this->assertTrue($priceFind->validate(),  'Validate price');
        $this->assertTrue($priceFind->save(),  'Update price an embedded relation');

        $priceFind2 = oPrice::find()
            ->where(['@rid' => $price['@rid']])
            ->with(['transport'])
            ->one();

        $this->assertTrue($priceFind2->QuantityMeasure == '999', 'Check modify QuantityMeasure');
        $this->assertTrue($priceFind2->transport->TransportIdentifier == 'modify TransportIdentifier', 'Check modify relation embedded transport->TransportIdentifier');
        
        return $priceFind;
    }
    
    public function testEmbeddedListRelation()
    {
        //simulate form input
        $post = [
            'oPrice' => [
                'Price' => '2.00',
                'Cost' => '22.00',
                'Discount' => '0',
                'QuantityMeasure' => '796',
                'Quantity' => '2.00',
                'goods' => [[
                        '@class'=>'Goods', //!
                        'GoodsShortDescription' => 'Test Goods Short Description',
                        'GoodsDescription' => 'Full text Goods Description',
                        'GoodsQuantity' => '100',
                        'MeasureUnitQualifierCode' => '796',
                    ],[
                        '@class'=>'Goods', //!
                        'GoodsShortDescription' => 'Test Goods Short Description 2',
                        'GoodsDescription' => 'Full text Goods Description 2',
                        'GoodsQuantity' => '200',
                        'MeasureUnitQualifierCode' => '796',
                    ]
                ]
            ]
        ];
        
        $price = new oPrice;

        $this->assertTrue($price->load($post), 'Load price POST data');
        $this->assertTrue($price->validate(),  'Validate price');
        $this->assertTrue($price->save(),      'Create price');

        $priceFind = oPrice::find()
                        ->where(['@rid' => $price['@rid']])
                        ->with(['goods'])
                        ->one();

        $this->assertTrue($priceFind->QuantityMeasure == '796', 'Check QuantityMeasure');
        $this->assertTrue($priceFind->goods[0]->GoodsQuantity == '100', 'Check relation embedded goods[0]->GoodsQuantity');

        $priceFind->QuantityMeasure = '999';
        $good = $priceFind->goods[0];
        $good->GoodsQuantity = '222';

        $this->assertTrue($priceFind->validate(),  'Validate price');
        $this->assertTrue($priceFind->save(),  'Update price embedded list relation');

        $this->assertTrue($priceFind->QuantityMeasure == '999', 'Check modify QuantityMeasure');
        $this->assertTrue($priceFind->goods[0]->GoodsQuantity == '222', 'Check relation embedded goods[0]->GoodsQuantity');

        $priceFind2 = oPrice::find()
            ->where(['@rid' => $price['@rid']])
            ->with(['goods'])
            ->one();

        $this->assertTrue($priceFind2->QuantityMeasure == '999', 'Check modify QuantityMeasure');
        $this->assertTrue($priceFind2->goods[0]->GoodsQuantity == '222', 'Check modify relation embedded goods[0]->GoodsQuantity');
        
        return $priceFind;
    }

    public function testLinkRelation()
    {
        //simulate form input
        $post = [
            'oPrice' => [
                'Price' => '2.00',
                'Cost' => '22.00',
                'Discount' => '0',
                'QuantityMeasure' => '796',
                'Quantity' => '2.00'
            ]
        ];

        $price = new oPrice;

        //simulate form input
        $postAddress = [
            'oAddress' => [
                'PostalCode' => '692500',
                'CountryCode' => 'RU',
                'Region' => 'Primorsky kray',
                'City' => 'Ussuriisk',
                'StreetHouse' => 'Nekrasova street, 25',
                'LanguageCode' => 'EN'
            ]
        ];

        $address = new oAddress;

        $this->assertTrue($address->load($postAddress), 'Load address POST data');
        $this->assertTrue($address->validate(),  'Validate address');
        $this->assertTrue($address->save(),      'Create address');

//        $price->delivery = $address;
        $price->link('delivery', $address);

        $this->assertTrue($price->load($post), 'Load price POST data');
        $this->assertTrue($price->validate(),  'Validate price');
        $this->assertTrue($price->save(),      'Create price');

        $priceFind = oPrice::find()
            ->where(['@rid' => $price['@rid']])
            ->with(['delivery'])
            ->one();

        $this->assertTrue($priceFind->QuantityMeasure == '796', 'Check QuantityMeasure');
        $this->assertTrue($priceFind->delivery->PostalCode == '692500', 'Check relation link delivery->PostalCode');

        $priceFind->QuantityMeasure = '999';
        $priceFind->delivery->PostalCode = '692582';

        $this->assertTrue($priceFind->validate(),  'Validate price');
        $this->assertTrue($priceFind->save(),  'Update price');
        $this->assertTrue($priceFind->delivery->save(),  'Update link relation');

        $priceFind2 = oPrice::find()
            ->where(['@rid' => $price['@rid']])
            ->with(['delivery'])
            ->one();

        $this->assertTrue($priceFind2->QuantityMeasure == '999', 'Check modify QuantityMeasure');
        $this->assertTrue($priceFind2->delivery->PostalCode == '692582', 'Check modify relation link delivery->PostalCode');

        $priceFind2->unlink('delivery', $priceFind2->delivery);

        return $priceFind;
    }


    public function testLazyLoadingLinkRelation()
    {
        //simulate form input
        $post = [
            'oPrice' => [
                'Price' => '2.00',
                'Cost' => '22.00',
                'Discount' => '0',
                'QuantityMeasure' => '796',
                'Quantity' => '2.00'
            ]
        ];

        $price = new oPrice;

        //simulate form input
        $postAddress = [
            'oAddress' => [
                'PostalCode' => '692500',
                'CountryCode' => 'RU',
                'Region' => 'Primorsky kray',
                'City' => 'Ussuriisk',
                'StreetHouse' => 'Nekrasova street, 25',
                'LanguageCode' => 'EN'
            ]
        ];

        $address = new oAddress;

        $this->assertTrue($address->load($postAddress), 'Load address POST data');
        $this->assertTrue($address->validate(),  'Validate address');
        $this->assertTrue($address->save(),      'Create address');

//        $price->delivery = $address;
        $price->link('delivery', $address);

        $this->assertTrue($price->load($post), 'Load price POST data');
        $this->assertTrue($price->validate(),  'Validate price');
        $this->assertTrue($price->save(),      'Create price');

        $priceFind = oPrice::find()
            ->where(['@rid' => $price['@rid']])
//            ->with(['delivery'])
            ->one();

        $this->assertTrue($priceFind->QuantityMeasure == '796', 'Check QuantityMeasure');
        $this->assertTrue($priceFind->delivery->PostalCode == '692500', 'Check relation link delivery->PostalCode');

        $priceFind->QuantityMeasure = '999';
        $priceFind->delivery->PostalCode = '692582';

        $this->assertTrue($priceFind->validate(),  'Validate price');
        $this->assertTrue($priceFind->save(),  'Update price');
        $this->assertTrue($priceFind->delivery->save(),  'Update link relation');

        $priceFind2 = oPrice::find()
            ->where(['@rid' => $price['@rid']])
//            ->with(['delivery'])
            ->one();

        $this->assertTrue($priceFind2->QuantityMeasure == '999', 'Check modify QuantityMeasure');
        $this->assertTrue($priceFind2->delivery->PostalCode == '692582', 'Check modify relation link delivery->PostalCode');

        $priceFind2->unlink('delivery', $priceFind2->delivery);

        return $priceFind;
    }

    public function testOnLoadLinkRelations()
    {
        //simulate form input
        $post = [
            'oPrice' => [
                'Price' => '2.00',
                'Cost' => '22.00',
                'Discount' => '0',
                'QuantityMeasure' => '796',
                'Quantity' => '2.00',
                'delivery' => [ // link relation will skipped
                    'PostalCode' => '692500',
                    'CountryCode' => 'RU',
                    'Region' => 'Primorsky kray',
                    'City' => 'Ussuriisk',
                    'StreetHouse' => 'Nekrasova street, 25',
                    'LanguageCode' => 'EN'
                ]
            ]
        ];

        $price = new oPrice;

        $this->assertTrue($price->load($post), 'Load price POST data');
        $this->assertTrue(empty($price->delivery), 'Link relation empty on load');
        $this->assertTrue($price->validate(),  'Validate price');
        $this->assertTrue($price->save(),      'Create price');

        $priceFind = oPrice::find()
            ->where(['@rid' => $price['@rid']])
            ->with(['delivery'])
            ->one();

        $this->assertTrue(empty($priceFind->delivery), 'Link relation empty after save');

        return $priceFind;
    }

    public function testLinkListRelation()
    {
        // create relations
        $price1 = $this->testCreatePrice();
        $price2 = $this->testCreatePrice();

        //simulate form input
        $post = [
            'oOrganization' => [
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

        $organization = new oOrganization;

//        $services = [$price1, $price1];
//        $organization->services = $services;
        $organization->link('services', $price1);
        $organization->link('services', $price2);

        $this->assertTrue($organization->load($post), 'Load organization POST data');
        $this->assertTrue($organization->validate(),  'Validate organization');
        $this->assertTrue($organization->save(),      'Create organization');

        $organizationFind = oOrganization::find()
            ->where(['@rid' => $organization['@rid']])
            ->with(['services'])
            ->one();

        $this->assertTrue($organizationFind->role == 'buyer', 'Check organization role');
        $this->assertTrue($organizationFind->services[0]->QuantityMeasure == '796', 'Check link list relation services[0]->Name');

        $organizationFind->unlink('services', $organizationFind->services[0]);

        $this->assertTrue(count($organizationFind->services) == 1, 'Check link list count after unlink');

        $organizationFind->unlink('services', $organizationFind->services[0]);
//
        $this->assertTrue(empty($organizationFind->services), 'Check link list count after unlink 2');

        $this->assertTrue($organizationFind->save(), 'Create organization');

        $organizationFind2 = oOrganization::find()
            ->where(['@rid' => $organizationFind['@rid']])
            ->with(['services'])
            ->one();

        $this->assertTrue(empty($organizationFind2->services), 'Check link list count after save');

        return $organizationFind2;
    }

    public function testNotFoundedRelation()
    {
        $organizationFind3 = oOrganization::find()
            ->where(['@rid' => '!@#$%^&*\'(YUIKL']) //!!! vulnerability
            ->one();

        $this->assertTrue(empty($organizationFind3),  'Check organization not found: spec symbols rid');

        $organizationFind1 = oOrganization::find()
            ->where(['@rid' => '@15:3']) //! `@`, - bad rid
            ->one();

        $this->assertTrue(empty($organizationFind1), 'Check organization not found: bad rid');

        $organizationFind2 = oOrganization::find()
            ->where(['@rid' => '#-1:-1'])
            ->one();

        $this->assertTrue(empty($organizationFind2),  'Check organization not found: embedded rid');
    }


    public function testLoadLinkRelation()
    {
        $priceFind = oPrice::find()
            ->where(['@rid' => '#13:5'])
            ->with(['delivery'])
            ->one();

//        print_r($priceFind);
//        die();
    }

    public function testLoadLinkListRelationUnlinkAll()
    {
        // create relations
        $price1 = $this->testCreatePrice();
        $price2 = $this->testCreatePrice();

        //simulate form input
        $post = [
            'oOrganization' => [
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

        $organization = new oOrganization;

//        $services = [$price1, $price1];
//        $organization->services = $services;
        $organization->link('services', $price1);
        $organization->link('services', $price2);

        $this->assertTrue($organization->load($post), 'Load organization POST data');
        $this->assertTrue($organization->validate(),  'Validate organization');
        $this->assertTrue($organization->save(),      'Create organization');

        $organizationFind = oOrganization::find()
            ->where(['@rid' => $organization['@rid']])
            ->with(['services'])
            ->one();

        $this->assertTrue($organizationFind->role == 'buyer', 'Check organization role');
        $this->assertTrue($organizationFind->services[0]->QuantityMeasure == '796', 'Check link list relation services[0]->Name');

        $organizationFind->unlinkAll('services');
//
        $this->assertTrue(empty($organizationFind->services), 'Check link list count after unlink 2');

        $this->assertTrue($organizationFind->save(), 'Create organization');

        $organizationFind2 = oOrganization::find()
            ->where(['@rid' => $organizationFind['@rid']])
            ->with(['services'])
            ->one();

        // TODO fix BUG, - LINKLIST $organizationFind2->services return all relations
        $this->assertTrue(empty($organizationFind2->services), 'Check link list count after save');

        return $organizationFind2;
    }

    public function testLoadLinkListRelationEmptyRelation()
    {
        $organizationFind = oOrganization::find()
            ->where(['@rid' => '#15:97'])
            ->with(['services'])
            ->one();

        if(!empty($organizationFind)) {
//            if($organizationFind->services == []) count(*) == 20
//            $this->assertTrue(count($organizationFind->services) !== 20, 'Check link list relation services count == 0');
            $this->assertTrue(empty($organizationFind->services), 'Check link list relation services count == 0');
        }
    }

    /**
     * example [null,null]
     */
    public function testRemovedLinkListRelation()
    {
        // create relations
        $price1 = $this->testCreatePrice();
        $price2 = $this->testCreatePrice();

        //simulate form input
        $post = [
            'oOrganization' => [
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

        $organization = new oOrganization;

//        $services = [$price1, $price1];
//        $organization->services = $services;
        $organization->link('services', $price1);
        $organization->link('services', $price2);

        $this->assertTrue($organization->load($post), 'Load organization POST data');
        $this->assertTrue($organization->validate(),  'Validate organization');
        $this->assertTrue($organization->save(),      'Create organization');

        $organizationFind = oOrganization::find()
            ->where(['@rid' => $organization['@rid']])
            ->with(['services'])
            ->one();

        $this->assertTrue($organizationFind->role == 'buyer', 'Check organization role');
        $this->assertTrue($organizationFind->services[0]->QuantityMeasure == '796', 'Check link list relation services[0]->Name');

        $organizationFind->services[0]->delete();

        $organizationFind2 = oOrganization::find()
            ->where(['@rid' => $organizationFind['@rid']])
            ->with(['services']) //! LINKLIST
            ->one();

        $this->assertTrue(count($organizationFind2->services) == 1, 'Check link list count after save == 1');

        return $organizationFind2;
    }

    public function testQuotaData()
    {
        //simulate form input
        $post = [
            'oOrganization' => [
                'role' => 'buyer',
                'Country' => '643',
                'OrganizationName' => 'Sender organization "asd\'\'!@#$%^&* full name',
                'ShortName' => "Sender '''''asdc !@#!@#\''']'\\\\\\\''''''\'\'\'\'\'\'organization",
                'Phone' => '+7988878787',
                'Email' => 'org@testSender.te',
                'Skype' => 'org_test',
                'Site' => 'www.testSender.te',
                'INN' => '252525454545',
                'CreateDate' => date('Y-m-d')
            ]
        ];

        $organization = new oOrganization;

        $this->assertTrue($organization->load($post), 'Load organization POST data');
        $this->assertTrue($organization->validate(),  'Validate organization');
        $this->assertTrue($organization->save(),      'Create organization');

        $this->assertTrue($organization->Skype == 'org_test', 'Check Skype');

        $this->assertTrue($organization->save(),      'Update organization');

        $organizationFind = oOrganization::find()
            ->where(['@rid' => $organization['@rid']])
            ->one();

        $this->assertTrue($organizationFind->Skype == 'org_test', 'Check Skype');
        $this->assertTrue($organizationFind->ShortName == $post['oOrganization']['ShortName'], 'Check quoted data');
    }
}

