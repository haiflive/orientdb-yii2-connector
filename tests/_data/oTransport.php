<?php

namespace data;

use Yii;

/**
 * This is the model class for table "Transport".
 *
 * @property string $@class
 * @property string $@rid
 * @property integer $@version
 * @property integer $Capacity
 * @property string $ContainerKind
 * @property string $CreateDate
 * @property integer $EuroPalletCapacity
 * @property string $NameMrkCar
 * @property string $Note
 * @property integer $PakageTypeCode
 * @property string $SecondTrailerIdentifier
 * @property string $TrailerIdentifier
 * @property string $TransportIdentifier
 * @property string $TransportMeansNationalityCode
 * @property string $TransportModeCode
 * @property string $VINID
 * @property string $driver
 * @property string $mass
 * @property string $model
 * @property integer $submersible
 * @property integer $type
 * @property string $volume
 */
class oTransport extends \OrientDBYii2Connector\ActiveRecord
{
    public function getDriver()
    {
        return $this->embeddedOne(oResident::className(), 'driver');
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Transport';
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
            [['@class', '@rid', 'ContainerKind', 'NameMrkCar', 'Note', 'SecondTrailerIdentifier', 'TrailerIdentifier', 'TransportIdentifier', 'TransportMeansNationalityCode', 'TransportModeCode', 'VINID', 'model'], 'string'],
            [['@version', 'Capacity', 'EuroPalletCapacity', 'PakageTypeCode', 'submersible', 'type'], 'integer'],
            [['CreateDate'
//                'driver',
            ], 'safe'],
            [['mass', 'volume'], 'number'],
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
            'Capacity' => 'Capacity',
            'ContainerKind' => 'Container Kind',
            'CreateDate' => 'Create Date',
            'EuroPalletCapacity' => 'Euro Pallet Capacity',
            'NameMrkCar' => 'Name Mrk Car',
            'Note' => 'Note',
            'PakageTypeCode' => 'Pakage Type Code',
            'SecondTrailerIdentifier' => 'Second Trailer Identifier',
            'TrailerIdentifier' => 'Trailer Identifier',
            'TransportIdentifier' => 'Transport Identifier',
            'TransportMeansNationalityCode' => 'Transport Means Nationality Code',
            'TransportModeCode' => 'Transport Mode Code',
            'VINID' => 'Vinid',
            'driver' => 'Driver',
            'mass' => 'Mass',
            'model' => 'Model',
            'submersible' => 'Submersible',
            'type' => 'Type',
            'volume' => 'Volume',
        ];
    }
}
