<?php

namespace data;

use Yii;

/**
 * This is the model class for table "Organization".
 *
 * @property string $@class
 * @property string $@rid
 * @property integer $@version
 * @property integer $Country
 * @property string $CreateDate
 * @property string $Email
 * @property string $Fax
 * @property string $INN
 * @property string $OrganizationLanguage
 * @property string $OrganizationName
 * @property string $OrganizationNameEN
 * @property string $Phone
 * @property string $ShortName
 * @property string $ShortNameEN
 * @property string $Site
 * @property string $Skype
 * @property string $additionRole
 * @property integer $orgForm
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
            [['@class', '@rid', 'Email', 'Fax', 'INN', 'OrganizationLanguage', 'OrganizationName', 'OrganizationNameEN', 'Phone', 'ShortName', 'ShortNameEN', 'Site', 'Skype', 'additionRole', 'role'], 'string'],
            [['@version', 'Country', 'orgForm'], 'integer'],
            [['CreateDate'
//                'services'
            ], 'safe'],
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
            'Country' => 'Country',
            'CreateDate' => 'Create Date',
            'Email' => 'Email',
            'Fax' => 'Fax',
            'INN' => 'Inn',
            'OrganizationLanguage' => 'Organization Language',
            'OrganizationName' => 'Organization Name',
            'OrganizationNameEN' => 'Organization Name En',
            'Phone' => 'Phone',
            'ShortName' => 'Short Name',
            'ShortNameEN' => 'Short Name En',
            'Site' => 'Site',
            'Skype' => 'Skype',
            'additionRole' => 'Addition Role',
            'orgForm' => 'Org Form',
            'role' => 'Role',
            'services' => 'Services',
        ];
    }
}
