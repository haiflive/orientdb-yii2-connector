<?php

namespace data;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property integer $status
 * @property integer $idOrganization
 * @property string $PostalCode
 * @property string $CountryCode
 * @property string $Region
 * @property string $City
 * @property string $StreetHouse
 * @property string $LanguageCode
 */
class tAddress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'idOrganization'], 'integer'],
            [['PostalCode'], 'string', 'max' => 9],
            [['CountryCode', 'LanguageCode'], 'string', 'max' => 2],
            [['Region', 'StreetHouse'], 'string', 'max' => 50],
            [['City'], 'string', 'max' => 35],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'idOrganization' => 'Id Organization',
            'PostalCode' => 'Postal Code',
            'CountryCode' => 'Country Code',
            'Region' => 'Region',
            'City' => 'City',
            'StreetHouse' => 'Street House',
            'LanguageCode' => 'Language Code',
        ];
    }
}
