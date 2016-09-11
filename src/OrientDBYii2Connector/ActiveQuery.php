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
     *  @brief Brief
     */
    public function populate($rows)
    {
        if (empty($rows)) {
            return [];
        }

        $models = $this->createModels($rows);
        // if (!empty($this->join) && $this->indexBy === null) {
            // $models = $this->removeDuplicatedModels($models);
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
    
    public function populateRelation($name, &$primaryModels)
    {
        // if (!is_array($this->link)) {
            // throw new InvalidConfigException('Invalid link: it must be an array of key-value pairs.');
        // }
        
        // viaTable not need int this database
        // $this->filterByModels($primaryModels);
        
        //! BUG need recursive
        if (!$this->multiple && count($primaryModels) === 1) {
            foreach ($primaryModels as $i => $primaryModel) {
                if ($primaryModel instanceof ActiveRecordInterface) { // ??? for what
                    $model = $this->one();
                    $primaryModel->populateRelation($name, $model);
                } else {
                    $rows = $primaryModels[$i][$name];
                    if(empty($rows))
                        return [];
                    $model = $this->populate([$rows]);
                    
                    $primaryModels[$i][$name] = reset($model) ?: $this->one();
                }
                // if ($this->inverseOf !== null) { // ??? for what
                    // $this->populateInverseRelation($primaryModels, [$model], $name, $this->inverseOf);
                // }
            }

            return [$model];
        } else {
            $link = $this->link;
            $models = [];
            foreach ($primaryModels as $i => $primaryModel) {
                $test;
                var_dump($link);
                die();
                if ($this->multiple && count($link) === 1 && is_array($rows = $primaryModel[$link])) {
                    $model = $this->populate($rows);
                    $value = $model ?: $this->all();
                    
                    if ($primaryModel instanceof ActiveRecordInterface) { // ??? for what
                        $primaryModel->populateRelation($name, $value);
                    } else {
                        $primaryModels[$i][$name] = $value;
                    }
                    array_push($models, $model);
                }
            }
            // if ($this->inverseOf !== null) {
                // $this->populateInverseRelation($primaryModels, $models, $name, $this->inverseOf);
            // }

            return $models;
        }
    }
}
