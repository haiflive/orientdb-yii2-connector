# orientdb-yii2-connector
Yii2 connector to orientDB, php7
Contains:
 - Connection
 - Quota data
 - Command builder
 - Query builder
 - ActiveRecord
 - RESTfull
 - gii support

###### Warning:
> OrientDB PHP binary protocol has no PDO or Quota methods, this library can bee unsafe

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
use OrientDBYii2Connector\DataRreaderOrientDB;
```
#### code
```php
$client = Yii::$app->dborient->createCommand();
$sql = $client->insert('beer', [
      'name' => 'test2',
      'descript' => $longText,
  ])->execute();
$testQ = (new Query())
      // ->select('name', 'descript', 'out_HasCategory.in.exclude(\'in_HasCategory\')')
      // ->select('name')
      ->from('beer')
      // ->limit(2)
      // ->where(['name' => 'Hocus Pocus'])
      ->fetch_plan(['out_HasCategory.in:0', 'out_HasBrewery.in:0', 'out_HasStyle.in:0'])
      // ->limit(10)
      // ->exists(Yii::$app->dborient);
      ->all(Yii::$app->dborient);
    
    // use tree builder(associate records and relations)
    $data = (new DataRreaderOrientDB($testQ))->getTree();
    
    // result:
    echo json_encode($data);
    
    // OR result as is
    // records
    foreach($testQ['records'] as $key => $value) {
        echo $value->__toString();
    }
    // fetch relations
    foreach($testQ['relations'] as $key => $value) {
        echo $value->__toString();
    }
    
    
    // remove record
    $sql = $client->delete('beer', [
        'name' => 'test2',
    ])->execute();
```

## ActiveRecord
You can use ActiveRecord and pagination widgets
#### Deal.php
```php

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Deals".
 *
 * @property string $@class
 * @property string $@rid
 * @property integer $@version
 * @property string $Date
 * @property string $Name
 * @property string $Note
 * @property string $Number
 * @property string $from_address
 * @property string $points
 * @property string $reciver
 * @property string $sender
 * @property string $to_address
 */
class Deals extends \OrientDBYii2Connector\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Deals';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('dborient');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['@class', '@rid', 'Name', 'Note', 'Number', 'from_address', 'points', 'reciver', 'sender', 'to_address'], 'string'],
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
            'Date' => 'Date',
            'Name' => 'Name',
            'Note' => 'Note',
            'Number' => 'Number',
            'from_address' => 'From Address',
            'points' => 'Points',
            'reciver' => 'Reciver',
            'sender' => 'Sender',
            'to_address' => 'To Address',
        ];
    }
}


```

#### View
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

# Stability
Beta

# License
The MIT License (MIT)
