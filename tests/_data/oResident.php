<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Resident".
 *
 * @property string $@class
 * @property string $@rid
 * @property integer $@version
 * @property string $ContactPhone
 * @property string $PersonMiddleName
 * @property string $PersonName
 * @property string $PersonPost
 * @property string $PersonSurname
 * @property string $dateOfBirth
 * @property string $email
 * @property string $sex
 */
class oResident extends \OrientDBYii2Connector\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Resident';
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
            [['@class', '@rid', 'ContactPhone', 'PersonMiddleName', 'PersonName', 'PersonPost', 'PersonSurname', 'email', 'sex'], 'string'],
            [['@version'], 'integer'],
            [['dateOfBirth'], 'safe'],
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
            'ContactPhone' => 'Contact Phone',
            'PersonMiddleName' => 'Person Middle Name',
            'PersonName' => 'Person Name',
            'PersonPost' => 'Person Post',
            'PersonSurname' => 'Person Surname',
            'dateOfBirth' => 'Date Of Birth',
            'email' => 'Email',
            'sex' => 'Sex',
        ];
    }
}
