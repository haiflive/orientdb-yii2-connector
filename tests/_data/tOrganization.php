<?php

namespace data;

use Yii;

/**
 * This is the model class for table "organization".
 *
 * @property integer $id
 * @property integer $status
 * @property string $role
 * @property string $additionRole
 * @property integer $idC_address
 * @property integer $Country
 * @property integer $orgForm
 * @property string $OrganizationName
 * @property string $ShortName
 * @property string $OrganizationNameEN
 * @property string $ShortNameEN
 * @property string $OrganizationLanguage
 * @property string $Phone
 * @property string $Fax
 * @property string $Telex
 * @property string $Email
 * @property string $Skype
 * @property string $Site
 * @property string $OKPOID
 * @property integer $OKATOCode
 * @property integer $OGRN
 * @property integer $INN
 * @property integer $KPP
 * @property integer $UNP
 * @property string $BIN
 * @property string $IIN
 * @property string $CategoryCode
 * @property string $KATOCode
 * @property string $RNN
 * @property string $ITNReserv
 * @property string $LicenseID
 * @property string $WarehouseLicense
 * @property string $CreateDate
 * @property string $OrgRegistryDate
 * @property string $RegistrationNumber
 * @property string $RegistrationDate
 * @property string $OrganizationNumber
 * @property string $CertificateDate
 * @property string $FieldOfAction
 * @property string $AddInformation
 * @property string $RegistrationAuthority
 * @property string $logo
 * @property integer $archive
 */
class tOrganization extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'organization';
    }
    
    public function getServices()
    {
        return $this->hasMany(tPrice::className(), ['idExpense' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'idC_address', 'Country', 'orgForm', 'OKATOCode', 'OGRN', 'INN', 'KPP', 'UNP', 'archive'], 'integer'],
            [['role'], 'string'],
            // [['additionRole', 'idC_address', 'OrgRegistryDate', 'RegistrationNumber', 'RegistrationDate', 'OrganizationNumber', 'CertificateDate', 'FieldOfAction', 'AddInformation', 'RegistrationAuthority'], 'required'],
            [['CreateDate', 'OrgRegistryDate', 'RegistrationDate', 'CertificateDate'], 'safe'],
            [['additionRole'], 'string', 'max' => 44],
            [['OrganizationName', 'OrganizationNameEN'], 'string', 'max' => 150],
            [['ShortName', 'ShortNameEN', 'FieldOfAction'], 'string', 'max' => 120],
            [['OrganizationLanguage', 'CategoryCode', 'KATOCode'], 'string', 'max' => 2],
            [['Phone', 'Fax', 'Telex', 'OKPOID'], 'string', 'max' => 24],
            [['Email', 'Skype', 'Site', 'logo'], 'string', 'max' => 255],
            [['BIN', 'IIN', 'RNN'], 'string', 'max' => 12],
            [['ITNReserv', 'LicenseID'], 'string', 'max' => 36],
            [['WarehouseLicense'], 'string', 'max' => 25],
            [['RegistrationNumber'], 'string', 'max' => 20],
            [['OrganizationNumber'], 'string', 'max' => 50],
            [['AddInformation', 'RegistrationAuthority'], 'string', 'max' => 250],
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
            'additionRole' => 'Addition Role',
            'idC_address' => 'Id C Address',
            'Country' => 'Country',
            'orgForm' => 'Org Form',
            'OrganizationName' => 'Organization Name',
            'ShortName' => 'Short Name',
            'OrganizationNameEN' => 'Organization Name En',
            'ShortNameEN' => 'Short Name En',
            'OrganizationLanguage' => 'Organization Language',
            'Phone' => 'Phone',
            'Fax' => 'Fax',
            'Telex' => 'Telex',
            'Email' => 'Email',
            'Skype' => 'Skype',
            'Site' => 'Site',
            'OKPOID' => 'Okpoid',
            'OKATOCode' => 'Okatocode',
            'OGRN' => 'Ogrn',
            'INN' => 'Inn',
            'KPP' => 'Kpp',
            'UNP' => 'Unp',
            'BIN' => 'Bin',
            'IIN' => 'Iin',
            'CategoryCode' => 'Category Code',
            'KATOCode' => 'Katocode',
            'RNN' => 'Rnn',
            'ITNReserv' => 'Itnreserv',
            'LicenseID' => 'License ID',
            'WarehouseLicense' => 'Warehouse License',
            'CreateDate' => 'Create Date',
            'OrgRegistryDate' => 'Org Registry Date',
            'RegistrationNumber' => 'Registration Number',
            'RegistrationDate' => 'Registration Date',
            'OrganizationNumber' => 'Organization Number',
            'CertificateDate' => 'Certificate Date',
            'FieldOfAction' => 'Field Of Action',
            'AddInformation' => 'Add Information',
            'RegistrationAuthority' => 'Registration Authority',
            'logo' => 'Logo',
            'archive' => 'Archive',
        ];
    }
}
