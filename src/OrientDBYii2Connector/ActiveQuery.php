<?php
namespace OrientDBYii2Connector;

use yii\db\ActiveQueryInterface;
//use OrientDBYii2Connector\ActiveQueryTrait;
//use yii\db\ActiveRelationTrait;
use OrientDBYii2Connector\DataRreaderOrientDB;

class ActiveQuery extends Query implements ActiveQueryInterface
{
    use ActiveQueryTrait;
//    use ActiveRelationTrait;
    use ActiveRelationEmbeddedTrait;


    public function __construct($modelClass, $config = [])
    {
        $this->modelClass = $modelClass;
        parent::__construct($config);
    }
    
    public function all($db = null)
    {
        return parent::all($db);
    }
    
    public function one($db = null)
    {
        $row = parent::one($db);

        if ($row !== null) {
            $models = $this->populate([$row]);

            for($i = 0; $i< count($models); $i++) { //! BUG fix reset old attributes after find for embedded records
                $models[$i]->setIsNewRecord(false); // $models[$i]->setOldAttributes($models[$i]->getAttributes());
            }

            return reset($models) ?: null;
        } else {
            return null;
        }
    }

    /**
     * @param array $rows array of data
     * @return array|\yii\db\ActiveRecord[]
     */
    public function populate($rows)
    {
        if (empty($rows)) {
            return [];
        }

        $models = $this->createModels($rows);

        // if (!empty($this->join) && $this->indexBy === null) {
            // $models = $this->removeDuplicatedModels($models); // not need in this database
        // }
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
