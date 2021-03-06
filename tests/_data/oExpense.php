<?php

namespace data;

use Yii;

/**
 * This is the model class for table "Expense".
 *
 * @property string $@class
 * @property string $@rid
 * @property integer $@version
 * @property string $Cost
 * @property string $CurrencyCode
 * @property string $Margin
 * @property string $Name
 * @property string $Price
 * @property string $executor
 * @property string $prices
 */
class oExpense extends \OrientDBYii2Connector\ActiveRecord
{
    public function getPrices()
    {
        return $this->embeddedMany(oPrice::className(), 'prices');
    }


    public function getExecutor()
    {
        return $this->hasOne(oOrganization::className(), 'executor');
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Expense';
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
            [['@class', '@rid', 'CurrencyCode', 'Name', 'executor'], 'string'],
            [['@version'], 'integer'],
            [['Cost', 'Margin', 'Price'], 'number'],
            [['prices'], 'safe'],
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
            'CurrencyCode' => 'Currency Code',
            'Margin' => 'Margin',
            'Name' => 'Name',
            'Price' => 'Price',
            'executor' => 'Executor',
            'prices' => 'Prices',
        ];
    }
}
