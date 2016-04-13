<?php
namespace OrientDBYii2Connector;

use Yii;
use yii\db\ActiveQueryInterface;
use yii\db\ActiveQueryTrait;
use yii\db\ActiveRelationTrait;

use OrientDBYii2Connector\DataRreaderOrientDB;

class ActiveQuery extends Query implements ActiveQueryInterface
{
    use ActiveQueryTrait;
    use ActiveRelationTrait;
    public function __construct($modelClass, $config = [])
    {
        $this->modelClass = $modelClass;
        parent::__construct($config);
    }
    
    // public function all($db = null)
    // {
        // return (new DataRreaderOrientDB(parent::all($db)))->getTree();
    // }
    
    public function all($db = null)
    {
        return parent::all($db);
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
    
    /**
     *  @brief Brief
     */
    public function populate($rows)
    {
        if (empty($rows)) {
            return [];
        }

        $models = $this->createModels($rows);
        if (!empty($this->join) && $this->indexBy === null) {
            $models = $this->removeDuplicatedModels($models);
        }
        if (!empty($this->with)) {
            $this->findWith($this->with, $models);
        }
        if (!$this->asArray) {
            foreach ($models as $model) {
                $model->afterFind();
            }
        }

        return $models;
    }
}
