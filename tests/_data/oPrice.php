<?php

namespace data;

use Yii;

/**
 * This is the model class for table "Price".
 *
 * @property string $@class
 * @property string $@rid
 * @property integer $@version
 * @property string $Cost
 * @property integer $Discount
 * @property string $Price
 * @property string $Quantity
 * @property integer $QuantityMeasure
 * @property string $delivery
 * @property string $goods
 * @property string $service
 * @property string $transport
 */
class oPrice extends \OrientDBYii2Connector\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Price';
    }
    
    public function getTransport()
    {
        return $this->hasOne(oTransport::className(), 'transport');
    }
    
    public function getGoods()
    {
        return $this->hasMany(oGoods::className(), 'goods');
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('dborient');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['@class', '@rid'], 'string'],
            [['@version', 'Discount', 'QuantityMeasure'], 'integer'],
            [['Cost', 'Price', 'Quantity'], 'number'],
            [['delivery', 'service',
//                'transport', 'goods'
            ], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '@class' => '@class',
            '@rid' => '@rid',
            '@version' => '@version',
            'Cost' => 'Cost',
            'Discount' => 'Discount',
            'Price' => 'Price',
            'Quantity' => 'Quantity',
            'QuantityMeasure' => 'Quantity Measure',
            'delivery' => 'Delivery',
            'goods' => 'Goods',
            'service' => 'Service',
            'transport' => 'Transport',
        ];
    }
}
