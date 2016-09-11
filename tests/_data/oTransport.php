<?php

namespace data;

use Yii;

/**
 * This is the model class for table "Transport".
 *
 * @property string $@class
 * @property string $@rid
 * @property integer $@version
 * @property string $TransportIdentifier
 * @property string $driver
 */
class oTransport extends \OrientDBYii2Connector\ActiveRecord
{
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
            [['@class', '@rid', 'TransportIdentifier', 'driver'], 'string'],
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
            'TransportIdentifier' => 'Transport Identifier',
            'driver' => 'Driver',
        ];
    }
}
