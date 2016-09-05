<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Services".
 *
 * @property string $@class
 * @property string $@rid
 * @property integer $@version
 * @property string $Name
 */
class oServices extends \OrientDBYii2Connector\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Services';
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
            [['@class', '@rid', 'Name'], 'string'],
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
            'Name' => 'Name',
        ];
    }
}
