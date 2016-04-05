# orientdb-yii2-connector
Yii2 connector to orientDB, php7
Contains:
 - Connection
 - Quota data
 - Command builder
 - Query builder

###### Warning:
> OrientDB PHP binary protocol has no PDO or Quota methods, this library can bee unsafe

# composer install 

```bash
composer require "ostico/phporient:dev-master"
```

# Usage

## web.php:
```php
...
'db' => require(__DIR__ . '/db.php'),
'dborient' => require(__DIR__ . '/dborient.php'),
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

# Stability
Not stable

# License
The MIT License (MIT)
