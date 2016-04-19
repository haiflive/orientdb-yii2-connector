<?php

namespace OrientDBYii2Connector\restfull;

use yii\rest\UrlRule;

class OrientDBUrlRule extends UrlRule
{
    public $tokens = [
        '{id}' => '<id:\\d[\\d,]*:\\d[\\d,]*>',
    ];
    
}
