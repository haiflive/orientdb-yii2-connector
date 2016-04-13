<?php
namespace OrientDBYii2Connector;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQueryInterface;
use yii\db\BaseActiveRecord;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use OrientDBYii2Connector\DataRreaderOrientDB;

abstract class ActiveRecord extends BaseActiveRecord
{
    /**
     * The insert operation. This is mainly used when overriding [[transactions()]] to specify which operations are transactional.
     */
    const OP_INSERT = 0x01;
    /**
     * The update operation. This is mainly used when overriding [[transactions()]] to specify which operations are transactional.
     */
    const OP_UPDATE = 0x02;
    /**
     * The delete operation. This is mainly used when overriding [[transactions()]] to specify which operations are transactional.
     */
    const OP_DELETE = 0x04;
    /**
     * All three operations: insert, update, delete.
     * This is a shortcut of the expression: OP_INSERT | OP_UPDATE | OP_DELETE.
     */
    const OP_ALL = 0x07;
    
    public function attributes()
    {
        throw new InvalidConfigException(get_called_class() . ' must have attributes() method.');
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
    public static function clusterName()
    {
        return Inflector::camel2id(StringHelper::basename(get_called_class()), '_');
    }
    
    public static function primaryKey()
    {
        return ['@rid'];
    }
    
    public static function find()
    {
        /** @var ActiveQuery $query */
        $query = \Yii::createObject(ActiveQuery::className(), [get_called_class()]);
        $query->from(static::clusterName()); //->select(static::clusterName());
        return $query;
    }
    
    /**
     * @param ActiveRecord $record
     * @param Document|array $row
     */
    public static function populateRecord($record, $row)
    {
        if ($row instanceof Document) {
            $row = $row->getAll();
        }
        parent::populateRecord($record, $row);
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
        if ($runValidation && !$this->validate($attributes)) {
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
        $values = $this->getDirtyAttributes($attributes);
        
        if (($primaryKeys = static::getDb()->createCommand()->insert($this->clusterName(), $values)->execute()) === false) {
            return false;
        }
        
        $values = DataRreaderOrientDB::getRecordData($primaryKeys);

        $changedAttributes = array_fill_keys(array_keys($values), null);
        $this->setOldAttributes($values);
        $this->afterSave(true, $changedAttributes);

        return true;
    }
    
    public function update($runValidation = true, $attributeNames = null)
    {
        //! bug need convert _rid to rif
        // UPDATE `Deal` SET number='0000' WHERE @rid='#12:1'
        if ($runValidation && !$this->validate($attributeNames)) {
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
    
    public static function updateAll($attributes, $condition = '', $params = [])
    {
        $command = static::getDb()->createCommand();
        $command->update(static::clusterName(), $attributes, $condition, $params);

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
        $command->delete(static::clusterName(), $condition, $params);

        return $command->execute();
    }
    
    public function save($runValidation = true, $attributeNames = null, $options = [])
    {
        if ($this->getIsNewRecord()) {
            return $this->insert($runValidation, $attributeNames, $options);
        } else {
            return $this->update($runValidation, $attributeNames, $options) !== false;
        }
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
            return parent::isAttributeChanged($name);
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
    
    public function transactions()
    {
        return [];
    }
    
    public function isTransactional($operation)
    {
        $scenario = $this->getScenario();
        $transactions = $this->transactions();

        return isset($transactions[$scenario]) && ($transactions[$scenario] & $operation);
    }
    
    /**
     *  relations:
     *  @EMBEDDED
     *  @EMBEDDEDLIST
     *  -EMBEDDEDMAP
     *  -EMBEDDEDSET
     *  
     *  @LINK
     *  @LINKLIST
     *  -LINKMAP
     *  -LINKSET
     */
    public function hasOne($class, $link)
    {
        return self::hasLink($class, $link);
    }
    
    public function hasMany($class, $link)
    {
        return self::hasLinkList($class, $link);
    }
    
    public function hasEmbedded($class, $property)
    {
        return ['hello'];
    }
    
    public function hasEmbeddedList($class, $property)
    {
        return ['hello'];
    }
    
    public function hasLink($class, $property)
    {
        /* @var $class ActiveRecordInterface */
        /* @var $query ActiveQuery */
        // $query = $class::find();
        // $query->primaryModel = $this;
        // $query->link = $link;
        // $query->multiple = false;
        return null;
    }
    
    public function hasLinkList($class, $property)
    {
        /* @var $class ActiveRecordInterface */
        /* @var $query ActiveQuery */
        // $query = $class::find();
        // $query->primaryModel = $this;
        // $query->link = $link;
        // $query->multiple = true;
        // return $query;
        return null;
    }
}
