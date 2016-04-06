<?php
namespace OrientDBYii2Connector;

use Yii;
use yii\db\ActiveQueryInterface;
use yii\db\ActiveQueryTrait;
use yii\db\ActiveRelationTrait;

use OrientDBYii2Connector\DataRreaderOrientDB;
use OrientDBYii2Connector\OrientDBException;

class ActiveQuery extends Query implements ActiveQueryInterface
{
    use ActiveQueryTrait;
    use ActiveRelationTrait;
    public function __construct($modelClass, $config = [])
    {
        $this->modelClass = $modelClass;
        parent::__construct($config);
    }
    
    public function all($db = null)
    {
        return (new DataRreaderOrientDB(parent::all($db)))->getTree();
    }
    
    public function one($db = null)
    {
        $row = parent::one($db);
        if ($row !== false) {
            $models = $this->populate([$row]);
            return reset($models) ?: null;
        } else {
            return null;
        }
    }
}
