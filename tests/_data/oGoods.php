<?php

namespace data;

use Yii;

/**
 * This is the model class for table "Goods".
 *
 * @property string $@class
 * @property string $@rid
 * @property integer $@version
 * @property string $AdditionalDescription
 * @property integer $AdditionalSign
 * @property string $BeginPeriodDate
 * @property string $CommercialQuantity
 * @property string $CommercialUnitQualifierCode
 * @property string $CommercialUnitQualifierCost
 * @property string $Cost
 * @property string $CreateDate
 * @property string $CurrencyCode
 * @property string $CustomsCost
 * @property string $CustomsCostCorrectMethod
 * @property string $CustomsDuty
 * @property string $CustomsTax
 * @property string $DateIssue
 * @property string $DeliveryTime
 * @property string $DeliveryTimeEND
 * @property string $Dimensions
 * @property string $Discount
 * @property string $EndPeriodDate
 * @property string $Excise
 * @property integer $Favorite
 * @property string $GoodsAddTNVEDCode
 * @property integer $GoodsClassificationCode
 * @property string $GoodsDescription
 * @property string $GoodsDescriptionGroop
 * @property string $GoodsMark
 * @property string $GoodsMarking
 * @property string $GoodsModel
 * @property string $GoodsQuantity
 * @property string $GoodsQuantity1
 * @property string $GoodsQuantity2
 * @property string $GoodsShortDescription
 * @property string $GoodsSort
 * @property string $GoodsStandart
 * @property string $GoodsTNVEDCode
 * @property string $GoodsTransferFeature
 * @property string $GoodsVolume
 * @property string $GrossWeightQuantity
 * @property string $HazardousCargoCode
 * @property string $Height
 * @property integer $IntellectPropertySign
 * @property string $LanguageGoods
 * @property string $Length
 * @property string $Manufacturer
 * @property string $MeasureUnitQualifier1Cost
 * @property string $MeasureUnitQualifier2Cost
 * @property string $MeasureUnitQualifierCode
 * @property string $MeasureUnitQualifierCode1
 * @property string $MeasureUnitQualifierCode2
 * @property string $MeasureUnitQualifierCodePay
 * @property string $MeasureUnitQualifierCost
 * @property string $NetWeightQuantity
 * @property string $NetWeightQuantity2
 * @property string $OilField
 * @property string $OneWeight
 * @property integer $OriginCountryCode
 * @property string $PackingCode
 * @property string $PackingMark
 * @property integer $PakagePartQuantity
 * @property string $PlaceGoodsQuantity
 * @property string $PlaceKind
 * @property string $PlaceNetWeightQuantity
 * @property string $PlaceWeightQuantity
 * @property string $PlacesDescription
 * @property integer $PlacesPartQuantity
 * @property integer $PlacesQuantity
 * @property string $PrecedingCustomsModeCode
 * @property string $Price
 * @property string $PriceKG
 * @property string $QuantityFact
 * @property string $QuotaCurrencyCode
 * @property string $QuotaCurrencyQuantity
 * @property string $QuotaMeasureUnitQualifierCode
 * @property string $QuotaQuantity
 * @property string $RKTNVED
 * @property string $Rate
 * @property string $SerialNumber
 * @property string $StatisticalCost
 * @property string $SupplementaryQualifierName
 * @property string $TareWeight
 * @property string $TradeMark
 * @property string $VolumeUnitQualifierName
 * @property string $Width
 * @property string $WoodKind
 * @property string $WoodSortiment
 * @property string $codePrice
 * @property string $goodsArticul
 * @property string $tags
 * @property string $useCode
 */
class oGoods extends \OrientDBYii2Connector\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Goods';
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
            [['@class', '@rid', 'AdditionalDescription', 'CommercialUnitQualifierCode', 'CurrencyCode', 'CustomsCostCorrectMethod', 'CustomsDuty', 'CustomsTax', 'Dimensions', 'Excise', 'GoodsAddTNVEDCode', 'GoodsDescription', 'GoodsDescriptionGroop', 'GoodsMark', 'GoodsMarking', 'GoodsModel', 'GoodsShortDescription', 'GoodsSort', 'GoodsStandart', 'GoodsTNVEDCode', 'GoodsTransferFeature', 'HazardousCargoCode', 'LanguageGoods', 'Manufacturer', 'MeasureUnitQualifierCode', 'MeasureUnitQualifierCode1', 'MeasureUnitQualifierCode2', 'MeasureUnitQualifierCodePay', 'OilField', 'PackingCode', 'PackingMark', 'PlaceKind', 'PlacesDescription', 'PrecedingCustomsModeCode', 'QuotaCurrencyCode', 'QuotaMeasureUnitQualifierCode', 'RKTNVED', 'Rate', 'SerialNumber', 'SupplementaryQualifierName', 'TradeMark', 'VolumeUnitQualifierName', 'WoodKind', 'WoodSortiment', 'codePrice', 'goodsArticul', 'tags', 'useCode'], 'string'],
            [['@version', 'AdditionalSign', 'Favorite', 'GoodsClassificationCode', 'IntellectPropertySign', 'OriginCountryCode', 'PakagePartQuantity', 'PlacesPartQuantity', 'PlacesQuantity'], 'integer'],
            [['BeginPeriodDate', 'CreateDate', 'DateIssue', 'DeliveryTime', 'DeliveryTimeEND', 'EndPeriodDate'], 'safe'],
            [['CommercialQuantity', 'CommercialUnitQualifierCost', 'Cost', 'CustomsCost', 'Discount', 'GoodsQuantity', 'GoodsQuantity1', 'GoodsQuantity2', 'GoodsVolume', 'GrossWeightQuantity', 'Height', 'Length', 'MeasureUnitQualifier1Cost', 'MeasureUnitQualifier2Cost', 'MeasureUnitQualifierCost', 'NetWeightQuantity', 'NetWeightQuantity2', 'OneWeight', 'PlaceGoodsQuantity', 'PlaceNetWeightQuantity', 'PlaceWeightQuantity', 'Price', 'PriceKG', 'QuantityFact', 'QuotaCurrencyQuantity', 'QuotaQuantity', 'StatisticalCost', 'TareWeight', 'Width'], 'number'],
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
            'AdditionalDescription' => 'Additional Description',
            'AdditionalSign' => 'Additional Sign',
            'BeginPeriodDate' => 'Begin Period Date',
            'CommercialQuantity' => 'Commercial Quantity',
            'CommercialUnitQualifierCode' => 'Commercial Unit Qualifier Code',
            'CommercialUnitQualifierCost' => 'Commercial Unit Qualifier Cost',
            'Cost' => 'Cost',
            'CreateDate' => 'Create Date',
            'CurrencyCode' => 'Currency Code',
            'CustomsCost' => 'Customs Cost',
            'CustomsCostCorrectMethod' => 'Customs Cost Correct Method',
            'CustomsDuty' => 'Customs Duty',
            'CustomsTax' => 'Customs Tax',
            'DateIssue' => 'Date Issue',
            'DeliveryTime' => 'Delivery Time',
            'DeliveryTimeEND' => 'Delivery Time End',
            'Dimensions' => 'Dimensions',
            'Discount' => 'Discount',
            'EndPeriodDate' => 'End Period Date',
            'Excise' => 'Excise',
            'Favorite' => 'Favorite',
            'GoodsAddTNVEDCode' => 'Goods Add Tnvedcode',
            'GoodsClassificationCode' => 'Goods Classification Code',
            'GoodsDescription' => 'Goods Description',
            'GoodsDescriptionGroop' => 'Goods Description Groop',
            'GoodsMark' => 'Goods Mark',
            'GoodsMarking' => 'Goods Marking',
            'GoodsModel' => 'Goods Model',
            'GoodsQuantity' => 'Goods Quantity',
            'GoodsQuantity1' => 'Goods Quantity1',
            'GoodsQuantity2' => 'Goods Quantity2',
            'GoodsShortDescription' => 'Goods Short Description',
            'GoodsSort' => 'Goods Sort',
            'GoodsStandart' => 'Goods Standart',
            'GoodsTNVEDCode' => 'Goods Tnvedcode',
            'GoodsTransferFeature' => 'Goods Transfer Feature',
            'GoodsVolume' => 'Goods Volume',
            'GrossWeightQuantity' => 'Gross Weight Quantity',
            'HazardousCargoCode' => 'Hazardous Cargo Code',
            'Height' => 'Height',
            'IntellectPropertySign' => 'Intellect Property Sign',
            'LanguageGoods' => 'Language Goods',
            'Length' => 'Length',
            'Manufacturer' => 'Manufacturer',
            'MeasureUnitQualifier1Cost' => 'Measure Unit Qualifier1 Cost',
            'MeasureUnitQualifier2Cost' => 'Measure Unit Qualifier2 Cost',
            'MeasureUnitQualifierCode' => 'Measure Unit Qualifier Code',
            'MeasureUnitQualifierCode1' => 'Measure Unit Qualifier Code1',
            'MeasureUnitQualifierCode2' => 'Measure Unit Qualifier Code2',
            'MeasureUnitQualifierCodePay' => 'Measure Unit Qualifier Code Pay',
            'MeasureUnitQualifierCost' => 'Measure Unit Qualifier Cost',
            'NetWeightQuantity' => 'Net Weight Quantity',
            'NetWeightQuantity2' => 'Net Weight Quantity2',
            'OilField' => 'Oil Field',
            'OneWeight' => 'One Weight',
            'OriginCountryCode' => 'Origin Country Code',
            'PackingCode' => 'Packing Code',
            'PackingMark' => 'Packing Mark',
            'PakagePartQuantity' => 'Pakage Part Quantity',
            'PlaceGoodsQuantity' => 'Place Goods Quantity',
            'PlaceKind' => 'Place Kind',
            'PlaceNetWeightQuantity' => 'Place Net Weight Quantity',
            'PlaceWeightQuantity' => 'Place Weight Quantity',
            'PlacesDescription' => 'Places Description',
            'PlacesPartQuantity' => 'Places Part Quantity',
            'PlacesQuantity' => 'Places Quantity',
            'PrecedingCustomsModeCode' => 'Preceding Customs Mode Code',
            'Price' => 'Price',
            'PriceKG' => 'Price Kg',
            'QuantityFact' => 'Quantity Fact',
            'QuotaCurrencyCode' => 'Quota Currency Code',
            'QuotaCurrencyQuantity' => 'Quota Currency Quantity',
            'QuotaMeasureUnitQualifierCode' => 'Quota Measure Unit Qualifier Code',
            'QuotaQuantity' => 'Quota Quantity',
            'RKTNVED' => 'Rktnved',
            'Rate' => 'Rate',
            'SerialNumber' => 'Serial Number',
            'StatisticalCost' => 'Statistical Cost',
            'SupplementaryQualifierName' => 'Supplementary Qualifier Name',
            'TareWeight' => 'Tare Weight',
            'TradeMark' => 'Trade Mark',
            'VolumeUnitQualifierName' => 'Volume Unit Qualifier Name',
            'Width' => 'Width',
            'WoodKind' => 'Wood Kind',
            'WoodSortiment' => 'Wood Sortiment',
            'codePrice' => 'Code Price',
            'goodsArticul' => 'Goods Articul',
            'tags' => 'Tags',
            'useCode' => 'Use Code',
        ];
    }
}
