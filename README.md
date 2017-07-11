# orientdb-yii2-connector
Yii2 connector to orientDB, php7
Contains:
 - Connection
 - Quota data
 - Command builder
 - Query builder
 - ActiveRecord
  - EMBEDDED and EMBEDDEDLIST relations
  - LINK and LINKLIST relations
 - RESTfull
 - gii support(generate ActiveRecord)

###### Warning:
> OrientDB PHP binary protocol has no PDO or Quota methods, this library can bee unsafe. 
> If you found vulnerability in quota data methods, please create Issues


#### Requires
- PHP Version >= 5.4 ( Socket extension enabled )
- Orientdb version 2.2.14 or later.

# composer install 

```bash
composer require "haiflive/orientdb-yii2-connector"
```

# Usage

## web.php:
```php
...
'db' => require(__DIR__ . '/db.php'),
'dborient' => require(__DIR__ . '/dborient.php'), \\ << -- add this line
...
```

## dborient.php
```php

<?php

return [
    'class' => 'OrientDBYii2Connector\Connection',
    'hostname' => 'localhost',
    'port' => 2424,
    'dbname' => 'OpenBeer',
    'username' => 'root',
    'password' => 'password',
];
```

## controller:
#### header
```php
use PhpOrient\PhpOrient;
use PhpOrient\Protocols\Binary\Data\Record;
use OrientDBYii2Connector\Query;
```
#### ActiveQuery usage
```php
    $client = Yii::$app->dborient->createCommand();
    
    // create new record
    $just_inserted = $client->insert('beer', [
          'name' => 'test2',
          'descript' => $longText,
    ])->execute();
    
    // select just created record by RID
    $data = (new Query())->from('beer')
      ->where(['@rid'=>$just_inserted['@rid']])
      ->one();
      
    var_dump($data);
    
    // get list of records:
    $data_list = (new Query())
      ->from('beer')
      ->fetch_plan(['out_HasCategory.in:0', 'out_HasBrewery.in:0', 'out_HasStyle.in:0'])
      // ->select('name', 'descript', 'out_HasCategory.in.exclude(\'in_HasCategory\')')
      // ->where(['name' => 'Hocus Pocus'])
      // ->limit(10)
      // support all Yii methods, without join (no join in OrientDB)
      ->all();
    
    foreach($data as $key => $record) {
        var_dump($record);
        // do something
    }
    
    // remove record
    $sql = $client->delete('beer', [
        'name' => 'test2',
    ])->execute();
```

#### ActiveQuery relations
```php
    $good_insert = $client->insert('Goods', [
        'GoodsDescription' => 'test Query GoodsDescription',
        'GoodsQuantity' => '11',
    ])->execute();

    // get by RID
    $good_select = (new Query())->from('Goods')
      ->where(['@rid'=>$good_insert['@rid']])
      ->one();
      
    // or query All
    $test_query_all = (new Query())
      ->from('Goods')
      ->all();

// -- embedded relation
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
      
//-- link relation
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
        'executor' => $organization_insert['@rid'] // link
    ])->execute();

    // get by RID and load with link relation
    $expense_select = (new Query())->from('Expense')
      ->where(['@rid'=>$expense_insert['@rid']])
      ->fetch_plan('executor:0') // `:0` - is level (read more in OrientDB documentation about fetch_plan)
      ->one();
```

## ActiveRecord
Embedded and EmbeddeList in ActiveRecord

See all models declarations and DB dump here: https://github.com/haiflive/orientdb-yii2-connector/tree/master/tests/_data

#### Deal.php
```php
<?php

namespace data;

use Yii;

class oDeal extends \OrientDBYii2Connector\ActiveRecord
{
    public static function tableName()
    {
        return 'Deal';
    }

    public function getSender()
    {
        return $this->embeddedOne(oOrganization::className(), 'sender');
    }

    public function getGoods()
    {
        return $this->embeddedMany(oGoods::className(), 'goods');
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['@class', '@rid', 'CurrencyCode', 'Name', 'Note', 'Number'], 'string'],
            [['@version'], 'integer'],
            [['Date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '@class' => '@class',
            '@rid' => '@rid',
            '@version' => '@version',
            'CurrencyCode' => 'Currency Code',
            'Date' => 'Date',
            'Name' => 'Name',
            'Note' => 'Note',
            'Number' => 'Number',
            'addressFrom' => 'Address From',
            'addressTo' => 'Address To',
            'expenses' => 'Expenses',
            'goods' => 'Goods',
            'reciver' => 'Reciver',
            'sender' => 'Sender',
        ];
    }
}

```

#### pagination widgets usage
```php

$dataProvider = new ActiveDataProvider([
    'query' => Deal::find(),
    'pagination' => ['pageSize' => 10],
]);

echo yii\grid\GridView::widget([
    'dataProvider' => $dataProvider,
]);

// also use pager:
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$dataProvider->pagination,
]);
```

#### Create ActiveRecord with realtions
```php
//simulate form input
$post = [
  'oDeal' => [
    'CurrencyCode' => 'USD',
    'Date' => date('Y-m-d'),
    'Name' => 'unit_test_deal',
    'Note' => 'testing relations',
    'Number' => '0001',
    // embedded relations data:
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
      'GoodsDescription' => 'Full text Goods Description'
    ],[
      '@class'=>'Goods', //!
      'GoodsShortDescription' => 'Test Goods Short Description',
      'GoodsDescription' => 'Full text Goods Description'
    ]],
    // embedded list relations:
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
          'NameMrkCar' => 'KAMAZ'
          'driver' => [
             '@class'=>'Resident', //!
             'role' => '2',
             'PersonSurname' => 'Ivanov',
             'PersonName' => 'Ivan'
          ]
        ],
        'goods' => [[
           '@class'=>'Goods', //!
           'GoodsShortDescription' => 'Test Goods Short Description',
           'GoodsQuantity' => '100',
           'MeasureUnitQualifierCode' => '796'
        ],[
           '@class'=>'Goods', //!
           'GoodsShortDescription' => 'Test Goods Short Description',
           'GoodsQuantity' => '100',
           'MeasureUnitQualifierCode' => '796'
        ]]
      ],[
        '@class'=>'Price', //!
        'Price' => '110.00',
        'Cost' => '100.00',
        'Discount' => '0',
        'QuantityMeasure' => '796',
        'Quantity' => '1'
      ]]
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
$sender = $this->testCreateSender();
$reciver = $this->testCreateReciver();
$address1 = $this->testCreateAddress();
$address2 = $this->testCreateAddress();
$driver = $this->testCreateResident();

$deal->load($post) // Load deal POST data;

// link relation data
// link data as embedded via link() (@rid will be automatically removed because `sender` relation is embedded)
$deal->link('sender', $sender); // embedded relation (embeddedOne)

// or you can use magic methods instead link() method
unset($reciver->{'@rid'}); // embedded relation require remove @rid attribute
$deal->reciver = $reciver; // embedded relation (embeddedOne)

// link of relation
$deal->expenses[0]->link('executor', $executor); // link relation (hasOne)

// link of relation of relation
$deal->expenses[0]->prices[0]->link('delivery', $address1); // link relation (hasOne)

// link of relation of relation of relation
$deal->expenses[0]->prices[0]->transport->link('driver', $driver); // embedded relation (embeddedOne)

// access to second `price` early loaded in POST data
$deal->expenses[0]->prices[1]->link('delivery', $address2); // link relation (hasOne)

$deal->expenses[1]->link('executor', $executor);  // link relation (hasOne)

if($deal->validate()) { // will validate recursively all related data
    if($deal->save()){
      echo "success!";
      
      // and now you can get full tree of data, all with EMBEDDED and LINK relations:
      $dealFind = oDeal::find()
            ->where(['@rid' => $deal['@rid']])
            ->with([
                'sender', //embedded
                'reciver',
                'addressTo',
                'addressFrom',
                'goods', //embedded list
                'expenses',
                'expenses.executor', // link
                'expenses.prices',
                'expenses.prices.delivery',
                'expenses.prices.transport',
                'expenses.prices.transport.driver',
                'expenses.prices.goods',
            ])
            ->one();
    }
}

return $deal;
```

#### Lazy Loading
```php
// SELECT * FROM `Price` WHERE `@rid` = '#13:5'
$price = oPrice::find()
            ->where(['@rid' => '#13:5'])
            ->one();

// SELECT * FROM `Address` WHERE `@rid` = '#14:10'
$delivery = $price->delivery; // link
```

#### Eager Loading
```php
SELECT * FROM `Price` WHERE `@rid` = '#13:5' fetchplan delivery:0
$price = oPrice::find()
            ->where(['@rid' => '#13:5'])
            ->with(['delivery']) // link
            ->one();

// no SQL executed
$delivery = $price->delivery;
```

#### Embedded loading
if you want init embedded records as ActiveRecords (add true validate) you must define relations in `with()` method
```php
$priceFind = oPrice::find()
               ->where(['@rid' => $price['@rid']])
               ->with(['transport']) // embedded
               ->one();
```


# Stability
RC

# License
The MIT License (MIT)
