<?php

namespace data;

use Yii;

/**
 * This is the model class for table "price".
 *
 * @property integer $id
 * @property integer $idDelivery
 * @property integer $idService
 * @property integer $idExpense
 * @property integer $idTransport
 * @property string $Price
 * @property string $Cost
 * @property integer $Discount
 * @property integer $QuantityMeasure
 * @property string $Quantity
 */
class tPrice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'price';
    }
    
    public function getDelivery()
    {
        return $this->hasOne(tAddress::className(), ['id' => 'idDelivery']);
    }
    
    public function getService()
    {
        return $this->hasOne(tServices::className(), ['id' => 'idService']);
    }
    
    public function getGoods()
    {
        return $this->hasMany(tGoods::className(), ['idPrice' => 'id']);
    }
    
    public function getTransport()
    {
        return $this->hasOne(tTransport::className(), ['id' => 'idTransport']);
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['idDelivery', 'idService', 'idExpense', 'idTransport'], 'required'],
            [['idDelivery', 'idService', 'idExpense', 'idTransport', 'Discount', 'QuantityMeasure'], 'integer'],
            [['Price', 'Cost', 'Quantity'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idDelivery' => 'Id Delivery',
            'idService' => 'Id Service',
            'idExpense' => 'Id Expense',
            'idTransport' => 'Id Transport',
            'Price' => 'Price',
            'Cost' => 'Cost',
            'Discount' => 'Discount',
            'QuantityMeasure' => 'Quantity Measure',
            'Quantity' => 'Quantity',
        ];
    }
}
