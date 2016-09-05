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

class oDealTest extends TestCase
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
        
    }
}
