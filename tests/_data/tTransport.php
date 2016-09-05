<?php

namespace data;

use Yii;

/**
 * This is the model class for table "transport".
 *
 * @property integer $id
 * @property integer $status
 * @property integer $idPrice
 * @property integer $idDriver
 * @property string $TransportIdentifier
 * @property string $TrailerIdentifier
 * @property string $SecondTrailerIdentifier
 * @property string $TransportModeCode
 * @property string $TransportMeansNationalityCode
 * @property string $VINID
 * @property integer $PakageTypeCode
 * @property string $ContainerKind
 * @property integer $Capacity
 * @property integer $EuroPalletCapacity
 * @property integer $type
 * @property string $NameMrkCar
 * @property string $model
 * @property string $volume
 * @property string $mass
 * @property integer $submersible
 * @property string $Note
 * @property string $CreateDate
 * @property integer $archive
 * @property integer $mvcc_increment
 */
class tTransport extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transport';
    }
    
    public function getDriver()
    {
        return $this->hasOne(tResident::className(), ['id' => 'idDriver']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'idPrice', 'idDriver', 'PakageTypeCode', 'Capacity', 'EuroPalletCapacity', 'type', 'submersible', 'archive', 'mvcc_increment'], 'integer'],
            // [['idPrice', 'idDriver', 'Note'], 'required'],
            [['volume', 'mass'], 'number'],
            [['CreateDate'], 'safe'],
            [['TransportIdentifier', 'TrailerIdentifier', 'SecondTrailerIdentifier', 'VINID'], 'string', 'max' => 40],
            [['TransportModeCode', 'TransportMeansNationalityCode', 'ContainerKind'], 'string', 'max' => 2],
            [['NameMrkCar', 'model'], 'string', 'max' => 50],
            [['Note'], 'string', 'max' => 255],
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
            'idPrice' => 'Id Price',
            'idDriver' => 'Id Driver',
            'TransportIdentifier' => 'Transport Identifier',
            'TrailerIdentifier' => 'Trailer Identifier',
            'SecondTrailerIdentifier' => 'Second Trailer Identifier',
            'TransportModeCode' => 'Transport Mode Code',
            'TransportMeansNationalityCode' => 'Transport Means Nationality Code',
            'VINID' => 'Vinid',
            'PakageTypeCode' => 'Pakage Type Code',
            'ContainerKind' => 'Container Kind',
            'Capacity' => 'Capacity',
            'EuroPalletCapacity' => 'Euro Pallet Capacity',
            'type' => 'Type',
            'NameMrkCar' => 'Name Mrk Car',
            'model' => 'Model',
            'volume' => 'Volume',
            'mass' => 'Mass',
            'submersible' => 'Submersible',
            'Note' => 'Note',
            'CreateDate' => 'Create Date',
            'archive' => 'Archive',
            'mvcc_increment' => 'Mvcc Increment',
        ];
    }
}
