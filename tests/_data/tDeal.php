<?php

namespace data;

use Yii;

/**
 * This is the model class for table "deal".
 *
 * @property integer $id
 * @property integer $idSender
 * @property integer $idReciver
 * @property integer $idAddressFrom
 * @property integer $idAddressTo
 * @property string $Name
 * @property string $Number
 * @property string $Note
 * @property string $Date
 */
class tDeal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'deal';
    }
    
    public function getSender()
    {
        return $this->hasOne(tOrganization::className(), ['id' => 'idSender']);
    }
    
    public function getReciver()
    {
        return $this->hasOne(tOrganization::className(), ['id' => 'idReciver']);
    }
    
    public function getAddress_from()
    {
        return $this->hasOne(tOrganization::className(), ['id' => 'idAddressFrom']);
    }
    
    public function getAddress_to()
    {
        return $this->hasOne(tOrganization::className(), ['id' => 'idAddressTo']);
    }
    
    public function getExpenses()
    {
        return $this->hasMany(tExpense::className(), ['idDeal' => 'id']);
    }
   
    public function getGoods()
    {
        return $this->hasMany(tGoods::className(), ['idDeal' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['idSender', 'idReciver', 'idAddressFrom', 'idAddressTo'], 'required'],
            [['idSender', 'idReciver', 'idAddressFrom', 'idAddressTo'], 'integer'],
            [['Note'], 'string'],
            [['Date'], 'safe'],
            [['Name', 'Number'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idSender' => 'Id Sender',
            'idReciver' => 'Id Reciver',
            'idAddressFrom' => 'Id Address From',
            'idAddressTo' => 'Id Address To',
            'Name' => 'Name',
            'Number' => 'Number',
            'Note' => 'Note',
            'Date' => 'Date',
        ];
    }
}
