<?php

namespace data;

use Yii;

/**
 * This is the model class for table "Address".
 *
 * @property string $@class
 * @property string $@rid
 * @property integer $@version
 * @property string $City
 * @property string $CountryCode
 * @property string $LanguageCode
 * @property string $PostalCode
 * @property string $Region
 * @property string $StreetHouse
 */
class oAddress extends \OrientDBYii2Connector\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Address';
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
            [['@class', '@rid', 'City', 'CountryCode', 'LanguageCode', 'PostalCode', 'Region', 'StreetHouse'], 'string'],
            [['@version'], 'integer'],
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
            'City' => 'City',
            'CountryCode' => 'Country Code',
            'LanguageCode' => 'Language Code',
            'PostalCode' => 'Postal Code',
            'Region' => 'Region',
            'StreetHouse' => 'Street House',
        ];
    }
}
