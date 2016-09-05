<?php

namespace data;

use Yii;

/**
 * This is the model class for table "expense".
 *
 * @property integer $id
 * @property integer $idExecutor
 * @property string $Name
 * @property string $Price
 * @property string $CurrencyCode
 * @property string $Margin
 * @property string $Cost
 */
class tExpense extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'expense';
    }
    
    public function getPrices()
    {
        return $this->hasMany(tPrice::className(), ['idExpense' => 'id']);
    }
    
    public function getExecutor()
    {
        return $this->hasOne(tOrganization::className(), ['id' => 'idExecutor']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['idExecutor'], 'required'],
            [['idExecutor'], 'integer'],
            [['Price', 'Margin', 'Cost'], 'number'],
            [['Name'], 'string', 'max' => 250],
            [['CurrencyCode'], 'string', 'max' => 3],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idExecutor' => 'Id Executor',
            'Name' => 'Name',
            'Price' => 'Price',
            'CurrencyCode' => 'Currency Code',
            'Margin' => 'Margin',
            'Cost' => 'Cost',
        ];
    }
}
