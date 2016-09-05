<?php
return [
    'id' => 'app-console',
    'class' => 'yii\console\Application',
    'basePath' => \Yii::getAlias('@tests'),
    'runtimePath' => \Yii::getAlias('@tests/_output'),
    'bootstrap' => [],
    'components' => [
        'db' => [
            'class' => '\yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=logistics',
            'username' => 'logistics',
            'password' => 'logistics'
        ],
        'dborient' => [
            'class' => 'OrientDBYii2Connector\Connection',
            'hostname' => 'localhost',
            'port' => 2424,
            'dbname' => 'logistics',
            'username' => 'root',
            'password' => '369',
        ]
    ]
];