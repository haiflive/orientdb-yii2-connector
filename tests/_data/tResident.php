<?php

namespace data;

use Yii;

/**
 * This is the model class for table "resident".
 *
 * @property integer $id
 * @property integer $status
 * @property integer $role
 * @property string $PersonSurname
 * @property string $PersonName
 * @property string $PersonMiddleName
 * @property string $PersonPost
 * @property string $ContactPhone
 * @property string $ComplationAuthorityDate
 * @property string $dateOfBirth
 * @property string $sex
 * @property string $email
 * @property string $fioForDocuments
 * @property string $CreateDate
 * @property string $photo
 * @property integer $archive
 * @property integer $mvcc_increment
 */
class tResident extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'resident';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'role', 'archive', 'mvcc_increment'], 'integer'],
            // [['role'], 'required'],
            [['ComplationAuthorityDate', 'dateOfBirth', 'CreateDate'], 'safe'],
            [['sex'], 'string'],
            [['PersonSurname', 'PersonName', 'PersonMiddleName'], 'string', 'max' => 150],
            [['PersonPost'], 'string', 'max' => 250],
            [['ContactPhone'], 'string', 'max' => 24],
            [['email'], 'string', 'max' => 50],
            [['fioForDocuments'], 'string', 'max' => 120],
            [['photo'], 'string', 'max' => 255],
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
            'role' => 'Role',
            'PersonSurname' => 'Person Surname',
            'PersonName' => 'Person Name',
            'PersonMiddleName' => 'Person Middle Name',
            'PersonPost' => 'Person Post',
            'ContactPhone' => 'Contact Phone',
            'ComplationAuthorityDate' => 'Complation Authority Date',
            'dateOfBirth' => 'Date Of Birth',
            'sex' => 'Sex',
            'email' => 'Email',
            'fioForDocuments' => 'Fio For Documents',
            'CreateDate' => 'Create Date',
            'photo' => 'Photo',
            'archive' => 'Archive',
            'mvcc_increment' => 'Mvcc Increment',
        ];
    }
}
