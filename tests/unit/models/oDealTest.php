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
                'sender' => [
                    '@class'=>'Organization', //!
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
                ],
                'reciver' => [
                    '@class'=>'Organization', //!
                    'role' => 'seller',
                    'Country' => '156',
                    'OrganizationName' => 'Reciver organization full name',
                    'ShortName' => 'Reciver organization',
                    'Phone' => '+8988878787',
                    'Email' => 'org@testReciver.ch',
                    'Skype' => 'org_test',
                    'Site' => 'www.testReciver.te',
                    'CreateDate' => date('Y-m-d')
                ],
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
        
        $this->assertTrue($deal->load($post), 'Load deal POST data');
        $this->assertTrue($deal->validate(),  'Validate deal');
        $this->assertTrue($deal->save(),      'Create deal');
        
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
}
