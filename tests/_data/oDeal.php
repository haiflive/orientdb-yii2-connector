<?php

namespace data;

use Yii;

/**
 * This is the model class for table "Deal".
 *
 * @property string $@class
 * @property string $@rid
 * @property integer $@version
 * @property string $CurrencyCode
 * @property string $Date
 * @property string $Name
 * @property string $Note
 * @property string $Number
 * @property string $addressFrom
 * @property string $addressTo
 * @property string $expenses
 * @property string $goods
 * @property string $reciver
 * @property string $sender
 */
class oDeal extends \OrientDBYii2Connector\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Deal';
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
            [['@class', '@rid', 'CurrencyCode', 'Name', 'Note', 'Number'], 'string'],
            [['@version'], 'integer'],
            [['Date', 'addressFrom', 'addressTo', 'expenses', 'goods', 'reciver', 'sender'], 'safe'],
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
            'CurrencyCode' => 'Currency Code',
            'Date' => 'Date',
            'Name' => 'Name',
            'Note' => 'Note',
            'Number' => 'Number',
            'addressFrom' => 'Address From',
            'addressTo' => 'Address To',
            'expenses' => 'Expenses',
            'goods' => 'Goods',
            'reciver' => 'Reciver',
            'sender' => 'Sender',
        ];
    }
}
