<?php
namespace OrientDBYii2Connector;

use yii\db\ActiveQueryInterface;
use yii\db\ActiveQueryTrait;
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
        if ($row !== false) {
            $models = $this->populate([$row]);

            for($i = 0; $i< count($models); $i++) { //! BUG fix reset old attributes after find for embedded records
                $models[$i]->setIsNewRecord(false); // $models[$i]->setOldAttributes($models[$i]->getAttributes());
            }

            return reset($models) ?: null;
        } else {
            return null;
        }
    }
    
    public function with()
    {
        $with = func_get_args();
        if (isset($with[0]) && is_array($with[0])) {
            // the parameter is given as an array
            $with = $with[0];
        }

        if (empty($this->with)) {
            $this->with = $with;
        } elseif (!empty($with)) {
            foreach ($with as $name => $value) {
                if (is_int($name)) {
                    // repeating relation is fine as normalizeRelations() handle it well
                    $this->with[] = $value;
                } else {
                    $this->with[$name] = $value;
                }
            }
        }
        
        // setup fetch_plan
        $fetch_plan = [];
        foreach($this->with as $rel) {
            array_push($fetch_plan, $rel.':0');
        }
        
        $this->fetch_plan($fetch_plan);

        return $this;
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
    
    public function findWith($with, &$models)
    {
        $primaryModel = new $this->modelClass;
        $relations = $this->normalizeRelations($primaryModel, $with);

        /* @var $relation ActiveQuery */
        foreach ($relations as $name => $relation) {
            if ($relation->asArray === null) {
                // inherit asArray from primary query
                $relation->asArray($this->asArray);
            }

            $relation->populateRelation($name, $models);
        }
    }
    

}
