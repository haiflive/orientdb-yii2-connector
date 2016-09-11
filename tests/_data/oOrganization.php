<?php

namespace data;

use Yii;

/**
 * This is the model class for table "Organization".
 *
 * @property string $@class
 * @property string $@rid
 * @property integer $@version
 * @property string $Email
 * @property string $OrganizationName
 * @property string $Phone
 * @property string $ShortName
 * @property string $role
 * @property string $services
 */
class oOrganization extends \OrientDBYii2Connector\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Organization';
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
            [['@class', '@rid', 'Email', 'OrganizationName', 'Phone', 'ShortName', 'role', 'services'], 'string'],
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
            'Email' => 'Email',
            'OrganizationName' => 'Organization Name',
            'Phone' => 'Phone',
            'ShortName' => 'Short Name',
            'role' => 'Role',
            'services' => 'Services',
        ];
    }
}
