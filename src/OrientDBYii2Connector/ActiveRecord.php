<?php
namespace OrientDBYii2Connector;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQueryInterface;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\db\BaseActiveRecord;
use OrientDBYii2Connector\DataRreaderOrientDB;

abstract class ActiveRecord extends \yii\db\ActiveRecord /* gii reqire extends from \yii\db\ActiveRecord, default extends from yii\db\BaseActiveRecord */
{
    public function hasOne($class, $link)
    {
        $query = $class::find();
        $query->primaryModel = $this;
        $query->link = $link;
        $query->multiple = false;
        $query->embedded = false;
        return $query;
    }

    public function hasMany($class, $link)
    {
        $query = $class::find();
        $query->primaryModel = $this;
        $query->link = $link;
        $query->multiple = true;
        $query->embedded = false;
        return $query;
    }

    public function embeddedOne($class, $link)
    {
        $query = $class::find();
        $query->primaryModel = $this;
        $query->link = $link;
        $query->multiple = false;
        $query->embedded = true;
        return $query;
    }

    public function embeddedMany($class, $link)
    {
        $query = $class::find();
        $query->primaryModel = $this;
        $query->link = $link;
        $query->multiple = true;
        $query->embedded = true;
        return $query;
    }
    
    // public function __call($name, $args)
    // {
        // if(count($args) !== 2)
            // throw new InvalidConfigException(get_called_class() . ', - embedded relation set must have two parametres.');
        
        // // setAttribute
        // if($this->hasAttribute($name)) {
            // $tmp = $this->getAttribute($name);
            // if(is_array($tmp)) {
                // $tmp[$args[0]] = $args[1];
                // $this->setAttribute($name, $tmp);
            // }
        // }
    // }
    /*
    public function __get($name)
    {
        // lazy loading
        if(
               is_a($this->getAttribute($name), 'PhpOrient\Protocols\Binary\Data\ID')
            || $this->isArrayOfRid($this->getAttribute($name))
        ) {
            $getter = 'get' . $name;
            if (method_exists($this, $getter)) {
                // read property, e.g. getName()
                $query = $this->$getter();
                if($query->multiple) {
                    $rids = $this->getAttribute($query->link);
                    $ridsResult = [];
                    foreach($rids as $rid)
                        array_push($ridsResult, DataRreaderOrientDB::IDtoRid($rid));
                    $query->andWhere(['in', '@rid', $ridsResult]);
                    return $query->all();
                }
                // else
                $rid = $this->getAttribute($query->link);
                $query->andWhere(['=', '@rid', DataRreaderOrientDB::IDtoRid($rid)]);
                return $query->one();
            }
        }
        
        return parent::__get($name);
    }*/
    
    /*
    public function __get($name)
    {
        if (isset($this->_attributes[$name]) || array_key_exists($name, $this->_attributes)) {
            return $this->_attributes[$name];
        } elseif ($this->hasAttribute($name)) {
            return null;
        } else {
            if (isset($this->_related[$name]) || array_key_exists($name, $this->_related)) {
                return $this->_related[$name];
            }
            $value = parent::__get($name);
            if ($value instanceof ActiveQueryInterface) {
                return $this->_related[$name] = $value->findFor($name, $this);
            } else {
                return $value;
            }
        }
    }
    */
    protected function isArrayOfRid($property)
    {
        if(!is_array($property))
            return false;
            
        if(is_array($property)) {
            foreach($property as $rid) {
                if(!is_a($rid, 'PhpOrient\Protocols\Binary\Data\ID'))
                    return false;
            }
        }
        return true;
    }
    
    public function mergeAttribute($name, $value)
    {
        $newValue = $this->getAttribute($name);
        if (!is_array($newValue)) {
            $newValue === null ? [] : [$newValue];
        }
        if (is_array($value)) {
            $this->setAttribute($name, ArrayHelper::merge($newValue, $value));
        } else {
            $newValue[] = $value;
            $this->setAttribute($name, $newValue);
        }
    }
    
    public static function tableName()
    {
        return Inflector::camel2id(StringHelper::basename(get_called_class()), '_');
    }
    
    public static function getTableSchema()
    {
        $tableSchema = static::getDb()
            ->getSchema()
            ->getTableSchema(static::tableName());

        if ($tableSchema === null) {
            throw new InvalidConfigException('The table does not exist: ' . static::tableName());
        }

        return $tableSchema;
    }
    
    public static function primaryKey()
    {
        return ['@rid'];
    }
    
    public static function find()
    {
        /** @var ActiveQuery $query */
        $query = \Yii::createObject(ActiveQuery::className(), [get_called_class()]);
        $query->from(static::tableName()); //->select(static::tableName());
        return $query;
    }
    
    /**
     * @param ActiveRecord $record
     * @param Document|array $row
     */
    public static function populateRecord($record, $row)
    {
        BaseActiveRecord::populateRecord($record, $row);
    }
    
    /**
     * Inserts the record into the database using the attribute values of this record.
     *
     * Usage example:
     *
     * ```php
     * $customer = new Customer;
     * $customer->name = $name;
     * $customer->email = $email;
     * $customer->insert();
     * ```
     *
     * @param boolean $runValidation whether to perform validation before saving the record.
     * If the validation fails, the record will not be inserted into the database.
     * @param array $attributes list of attributes that need to be saved. Defaults to null,
     * meaning all attributes that are loaded from DB will be saved.
     * @param array $options
     * @return boolean whether the attributes are valid and the record is inserted successfully.
     */
    public function insert($runValidation = true, $attributes = null)
    {
        if ($runValidation && !$this->validate($attributes) && !$this->validateEmbedded()) {
            Yii::info('Model not inserted due to validation error.', __METHOD__);
            return false;
        }

        if (!$this->isTransactional(self::OP_INSERT)) {
            return $this->insertInternal($attributes);
        }

        $transaction = static::getDb()->beginTransaction();
        try {
            $result = $this->insertInternal($attributes);
            if ($result === false) {
                $transaction->rollBack();
            } else {
                $transaction->commit();
            }
            return $result;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
    
    protected function insertInternal($attributes = null)
    {
        if (!$this->beforeSave(true)) {
            return false;
        }

        $values = $this->getEmbeddedDirtyAttributes($attributes);

        if (($primaryKeys = static::getDb()->createCommand()->insert($this->tableName(), $values)->execute()) === false) {
            return false;
        }
        
        foreach ($primaryKeys as $name => $value) {
            $this->setAttribute($name, $value);
            $values[$name] = $value;
        } // DataRreaderOrientDB::getRecordData($primaryKeys);

        $changedAttributes = array_fill_keys(array_keys($values), null);
        $this->setOldAttributes($values);
        $this->afterSave(true, $changedAttributes);

        return true;
    }
    
    public function update($runValidation = true, $attributeNames = null)
    {
        // UPDATE `Deal` SET number='0000' WHERE @rid='#12:1'
        if ($runValidation && !$this->validate($attributeNames) && !$this->validateEmbedded()) {
            Yii::info('Model not updated due to validation error.', __METHOD__);
            return false;
        }

        if (!$this->isTransactional(self::OP_UPDATE)) {
            return $this->updateInternal($attributeNames);
        }

        $transaction = static::getDb()->beginTransaction();
        try {
            $result = $this->updateInternal($attributeNames);
            if ($result === false) {
                $transaction->rollBack();
            } else {
                $transaction->commit();
            }
            return $result;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    protected function updateInternal($attributes = null)
    {
        if (!$this->beforeSave(false)) {
            return false;
        }

        $values = $this->getEmbeddedDirtyAttributes($attributes);

        if (empty($values)) {
            $this->afterSave(false, $values);
            return 0;
        }
        $condition = $this->getOldPrimaryKey(true);
        $lock = $this->optimisticLock();
        if ($lock !== null) {
            $values[$lock] = $this->$lock + 1;
            $condition[$lock] = $this->$lock;
        }
        // We do not check the return value of updateAll() because it's possible
        // that the UPDATE statement doesn't change anything and thus returns 0.
        $rows = $this->updateAll($values, $condition);

        if ($lock !== null && !$rows) {
            throw new StaleObjectException('The object being updated is outdated.');
        }

        if (isset($values[$lock])) {
            $this->$lock = $values[$lock];
        }

        $this->setIsNewRecord(false); // $this->setOldAttributes($this->getEmbeddedDirtyAttributes());
        $this->afterSave(false, $values);

        return $rows;
    }

    public static function updateAll($attributes, $condition = '', $params = [])
    {
        $command = static::getDb()->createCommand();
        $command->update(static::tableName(), $attributes, $condition, $params);

        return $command->execute();
    }
    
    /**
     * Returns the connection used by this AR class.
     * @return Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return \Yii::$app->get('dborient');
    }
    
    public static function deleteAll($condition = '', $params = [])
    {
        $command = static::getDb()->createCommand();
        $command->delete(static::tableName(), $condition, $params);

        return $command->execute();
    }
    
    public function delete()
    {
        if (!$this->isTransactional(self::OP_DELETE)) {
            return $this->deleteInternal();
        }

        $transaction = static::getDb()->beginTransaction();
        try {
            $result = $this->deleteInternal();
            if ($result === false) {
                $transaction->rollBack();
            } else {
                $transaction->commit();
            }
            return $result;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
    /**
     * @see ActiveRecord::delete()
     * @throws StaleObjectException
     */
    protected function deleteInternal()
    {
        if (!$this->beforeDelete()) {
            return false;
        }

        // we do not check the return value of deleteAll() because it's possible
        // the record is already deleted in the database and thus the method will return 0
        $condition = $this->getOldPrimaryKey(true);
        $lock = $this->optimisticLock();
        if ($lock !== null) {
            $condition[$lock] = $this->$lock;
        }
        $result = $this->deleteAll($condition);
        if ($lock !== null && !$result) {
            throw new StaleObjectException('The object being deleted is outdated.');
        }
        $this->setOldAttributes(null);
        $this->afterDelete();

        return $result;
    }
    /**
     * Returns a value indicating whether the named attribute has been changed.
     * @param string $name the name of the attribute
     * @return boolean whether the attribute has been changed
     */
    public function isAttributeChanged($name, $depth = 2)
    {
        if (is_array($this->getAttribute($name))) {
            $new = $this->getAttribute($name);
            $old = $this->getOldAttribute($name);
            if ($depth < 1) {
                $depth = 1;
            }
            return self::isArrayChanged($new, $old, $depth);
        } else {
            return BaseActiveRecord::isAttributeChanged($name);
        }
    }
    private static function isArrayChanged(&$new, &$old, $depth)
    {
        if (is_array($new)) {
            if (is_array($old)) {
                if (count($new) != count($old)) {
                    return true;
                } else {
                    $newKeys = array_keys($new);
                    $oldKeys = array_keys($old);
                    if (array_merge(array_diff($newKeys, $oldKeys), array_diff($oldKeys, $newKeys))) {
                        return true;
                    } else {
                        if ($depth > 1) {
                            foreach ($new as $key => $value) {
                                if (self::isArrayChanged($new[$key], $old[$key], $depth--)) {
                                    return true;
                                }
                            }
                        }
                    }
                }
            } else {
                return true;
            }
        } else {
            if (is_array($old)) {
                return true;
            } else {
                return (string)$new != (string)$old;
            }
        }
        return false;
    }
    
    public function init()
    {
        parent::init();
        if ($this->scenario === static::SCENARIO_DEFAULT) {
            $this->setAttributes($this->defaultValues(), false);
        }
    }

    public function defaultValues()
    {
        return [];
    }

    /**
     * recursive validate embedded ActiveRecords
     * @return bool
     */
    public function validateEmbedded()
    {
        $values = $this->getAttributes();

        for($i=0; $i<count($values); $i++) {
            if($values[$i] instanceof ActiveRecord) {
                if(!$values[$i]->validate() && !$values[$i]->validateEmbedded()) {
                    Yii::info('Model not inserted due to validation error Embedded Model.', __METHOD__);
                    return false; // error
                }
            }
        }

        return true;
    }
/* // unused
    public function getEmbeddedAttributes()
    {
        $values = $this->getAttributes();

        foreach($values as $key => $val){
            if($val instanceof ActiveRecord && !$this->isRelationPopulated($key)) { // is record && is not link
                $values[$key] = $val->getEmbeddedAttributes();
            } elseif(is_array($val)) {
                print_r('unready');
                die();
            }
        }

        return $values;
    }
    */
    /**
     * filter embedded relations
     *      embedded relations cant bee NULL
     * @param bool isEmbedded - get all params for embedded records
     * @return array
     */
    public function getEmbeddedDirtyAttributes($isEmbedded = false)
    {
        $values = $this->getAttributes($this->fields());
        $dirtyValues = $this->getDirtyAttributes();

        foreach($values as $key => $val){
            if($val instanceof ActiveRecord && !$this->isRelationPopulated($key)) { // is record && is not link
                $values[$key] = $val->getEmbeddedDirtyAttributes(true);
                $dirtyValues[$key] = null; // embedded records require copy all values
            } elseif(is_array($val)) {
                foreach($val as $key2 => $rec) {
                    if($values[$key][$key2] instanceof ActiveRecord && !$this->isRelationPopulated($key)) { // is record && is not link
                        $values[$key][$key2] = $rec->getEmbeddedDirtyAttributes(true);
                    }
                }
                $dirtyValues[$key] = null; // embedded records require copy all values
            }
        }

        if(!$isEmbedded) // filter fields for main record(use only modified)
            $values = array_intersect_key($values, $dirtyValues);

        return $values;
    }

    public function setAttributes($values, $safeOnly = true)
    {
        if (is_array($values)) {
            $attributes = array_flip($safeOnly ? $this->safeAttributes() : $this->attributes());
            foreach ($values as $name => $value) {
                if($this->trySetupEmbeddedRelation($name, $value)) // if success
                    continue;

                if (isset($attributes[$name])) {
                    $this->$name = $value;
                } elseif ($safeOnly) {
                    $this->onUnsafeAttribute($name, $value);
                }
            }
        }
    }

    public function setAttribute($name, $value)
    {
        if($this->trySetupEmbeddedRelation($name, $value)) // if success
            return ;

        parent::setAttribute($name, $value);
    }

    /**
     * @param $name string
     * @param $value mixed
     * @return bool if true relation setuped
     */
    protected function trySetupEmbeddedRelation($name, $value)
    {
        if(is_array($value)) {
            // try to find embedded relation
            if ($query = $this->getRelation($name, false)) {
                if($query->embedded) {
                    if($query->multiple) {
                        $resultModels = [];
                        foreach($value as $key => $val) {
                            $values = array_fill_keys(array_keys($val), null);
                            $models = $query->populate([$values]);
                            $model = reset($models) ?: null;

                            $model->setAttributes($val); // make getDirtyAttributes

                            $resultModels[$key] = $model;
                        }
                        $this->$name = $resultModels;
                    } else {
                        $values = array_fill_keys(array_keys($value), null);
                        $models = $query->populate([$values]);
                        $this->$name = reset($models) ?: null;

                        $this->$name->setAttributes($value); // make getDirtyAttributes
                    }
                } else { // link
                    return true; //!? link relation not need, it's really unsafe use it?
                }
                return true; // relation founded
            }
        }

        return false;
    }

    /**
     * @param string $name the case sensitive name of the relationship.
     * @param ActiveRecordInterface $model the model to be linked with the current one.
     * @param array $extraColumns - not need inherit BaseActiveRecord class
     */

    public function link($name, $model, $extraColumns = [])
    {
        $relation = $this->getRelation($name);

        if($relation->embedded) {
            throw new InvalidCallException('Unable to link models: embedded relation can\'t bee linked.');
        }

        if($model->getIsNewRecord()) { //? or need auto save
            throw new InvalidCallException('Unable to link models: relation has no @rid.');
        }

        if($relation->multiple) {
            $tmpArr = is_array($this->$name) ? $this->$name : [];
            array_push($tmpArr, $model);
            $this->$name = $tmpArr;
            unset($tmpArr);
        } else {
            $this->$name = $model;
        }
    }

    public function unlink($name, $model, $delete = false)
    {
        $relation = $this->getRelation($name);

        if($relation->embedded) {
            throw new InvalidCallException('Unable to unlink models: embedded relation can\'t bee unlinked.');
        }

        if($model->getIsNewRecord()) { //? impossible
            throw new InvalidCallException('Unable to unlink models: relation has no @rid.');
        }

        if($delete)
            $model->delete();

        if($relation->multiple) {
            if(is_array($this->$name)) {
                $tmpArr = [];
                foreach($this->$name as $rel) {
                    if($rel['@rid'] !== $model['@rid'])
                        array_push($tmpArr, $rel);
                }

                $this->$name = $tmpArr;
                unset($tmpArr);

                if(empty($this->$name)) {
                    $this->$name = null;
//                    unset($this->$name); // will call BaseActiveRecord::__unset
                }
            }
        } else {
            unset($this->$name); // will call BaseActiveRecord::__unset
        }

//        $this->save();
    }

    public function unlinkAll($name, $delete = false)
    {
        $relation = $this->getRelation($name);

        if($relation->embedded) {
            throw new InvalidCallException('Unable to unlink models: embedded relation can\'t bee unlinked All.');
        }

        if($delete) {
            if($relation->multiple) {
                foreach ($this->$name as $rel) {
                    $rel->delete();
                }
            } else {
                $this->$name->delete();
            }
        }

        if($relation->multiple) {
            $this->$name = null;
        } else {
            unset($this->$name); // will call BaseActiveRecord::__unset
        }

//        $this->save(false); //? not need
    }

/*
    public function load($data, $formName = null)
    {
        $scope = $formName === null ? $this->formName() : $formName;
        if ($scope === '' && !empty($data)) {
            $this->setAttributes($data);

            return true;
        } elseif (isset($data[$scope])) {
            $this->setAttributes($data[$scope]);

            return true;
        } else {
            return false;
        }
    }*/
}
