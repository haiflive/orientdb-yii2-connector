<?php

namespace data;

use Yii;

/**
 * This is the model class for table "goods".
 *
 * @property integer $id
 * @property integer $status
 * @property integer $idPrice
 * @property integer $idDeal
 * @property integer $GTDGoodsNumber
 * @property string $GoodFeatures
 * @property string $GoodsShortDescription
 * @property string $GoodsDescription
 * @property string $AdditionalDescription
 * @property string $GoodsDescriptionGroop
 * @property string $GoodsQuantity
 * @property string $MeasureUnitQualifierCode
 * @property string $MeasureUnitQualifierCost
 * @property string $GoodsQuantity1
 * @property string $MeasureUnitQualifierCode1
 * @property string $MeasureUnitQualifier1Cost
 * @property string $GoodsQuantity2
 * @property string $MeasureUnitQualifierCode2
 * @property string $MeasureUnitQualifier2Cost
 * @property string $CommercialUnitQualifierCode
 * @property string $CommercialUnitQualifierCost
 * @property string $CommercialQuantity
 * @property string $MeasureUnitQualifierCodePay
 * @property string $useCode
 * @property string $codePrice
 * @property string $PriceKG
 * @property string $Price
 * @property string $Cost
 * @property string $CustomsCost
 * @property string $StatisticalCost
 * @property string $GoodsTNVEDCode
 * @property string $GoodsAddTNVEDCode
 * @property integer $SeriesSign
 * @property string $NormDocs
 * @property string $PartySize
 * @property string $ProductCode
 * @property string $AppendNumber
 * @property string $AppendFormNumber
 * @property string $DeliveryTime
 * @property string $DeliveryTimeEND
 * @property string $CurrencyCode
 * @property string $PlaceKind
 * @property integer $PlacesQuantity
 * @property string $PlaceWeightQuantity
 * @property string $PlaceNetWeightQuantity
 * @property string $NetWeightQuantity
 * @property string $GrossWeightQuantity
 * @property string $NetWeightQuantity2
 * @property string $SupplementaryQualifierName
 * @property string $Discount
 * @property integer $OriginCountryCode
 * @property string $PlaceGoodsQuantity
 * @property string $Length
 * @property string $Width
 * @property string $Height
 * @property string $GoodsVolume
 * @property string $VolumeUnitQualifierName
 * @property string $PlacesDescription
 * @property string $PackingCode
 * @property integer $PlacesPartQuantity
 * @property string $HazardousCargoCode
 * @property string $PackingMark
 * @property integer $GoodsClassificationCode
 * @property integer $AdditionalSign
 * @property integer $IntellectPropertySign
 * @property integer $CIMSign
 * @property string $BeginPeriodDate
 * @property string $EndPeriodDate
 * @property string $CustomsCostCorrectMethod
 * @property string $QuantityFact
 * @property string $Manufacturer
 * @property string $TradeMark
 * @property string $GoodsMark
 * @property string $GoodsModel
 * @property string $GoodsMarking
 * @property string $GoodsStandart
 * @property string $GoodsSort
 * @property string $WoodSortiment
 * @property string $WoodKind
 * @property string $Dimensions
 * @property string $DateIssue
 * @property string $SerialNumber
 * @property string $CustomsTax
 * @property string $CustomsDuty
 * @property string $Excise
 * @property string $Rate
 * @property string $LanguageGoods
 * @property integer $PakagePartQuantity
 * @property string $QuotaQuantity
 * @property string $QuotaMeasureUnitQualifierCode
 * @property string $QuotaCurrencyQuantity
 * @property string $QuotaCurrencyCode
 * @property string $PrecedingCustomsModeCode
 * @property string $GoodsTransferFeature
 * @property string $OilField
 * @property string $RKTNVED
 * @property string $goodsArticul
 * @property string $OneWeight
 * @property string $TareWeight
 * @property string $CreateDate
 * @property integer $Favorite
 * @property integer $archive
 * @property string $tags
 * @property integer $mvcc_increment
 */
class tGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'idPrice', 'idDeal', 'GTDGoodsNumber', 'SeriesSign', 'PlacesQuantity', 'OriginCountryCode', 'PlacesPartQuantity', 'GoodsClassificationCode', 'AdditionalSign', 'IntellectPropertySign', 'CIMSign', 'PakagePartQuantity', 'Favorite', 'archive', 'mvcc_increment'], 'integer'],
            // [['idPrice', 'idDeal', 'GoodsShortDescription', 'AdditionalDescription', 'MeasureUnitQualifierCodePay', 'NormDocs', 'ProductCode', 'AppendNumber', 'AppendFormNumber', 'goodsArticul', 'OneWeight', 'TareWeight', 'tags'], 'required'],
            [['GoodFeatures', 'GoodsDescription', 'AdditionalDescription', 'GoodsDescriptionGroop', 'useCode', 'PlacesDescription', 'PackingMark', 'OilField'], 'string'],
            [['GoodsQuantity', 'MeasureUnitQualifierCost', 'GoodsQuantity1', 'MeasureUnitQualifier1Cost', 'GoodsQuantity2', 'MeasureUnitQualifier2Cost', 'CommercialUnitQualifierCost', 'CommercialQuantity', 'PriceKG', 'Price', 'Cost', 'CustomsCost', 'StatisticalCost', 'PlaceWeightQuantity', 'PlaceNetWeightQuantity', 'NetWeightQuantity', 'GrossWeightQuantity', 'NetWeightQuantity2', 'Discount', 'PlaceGoodsQuantity', 'Length', 'Width', 'Height', 'GoodsVolume', 'QuantityFact', 'QuotaQuantity', 'QuotaCurrencyQuantity', 'OneWeight', 'TareWeight'], 'number'],
            [['DeliveryTime', 'DeliveryTimeEND', 'BeginPeriodDate', 'EndPeriodDate', 'DateIssue', 'CreateDate'], 'safe'],
            [['GoodsShortDescription', 'NormDocs'], 'string', 'max' => 250],
            [['MeasureUnitQualifierCode', 'MeasureUnitQualifierCode1', 'MeasureUnitQualifierCode2', 'CommercialUnitQualifierCode', 'MeasureUnitQualifierCodePay', 'codePrice', 'AppendNumber', 'CurrencyCode', 'HazardousCargoCode', 'QuotaMeasureUnitQualifierCode', 'QuotaCurrencyCode', 'GoodsTransferFeature'], 'string', 'max' => 3],
            [['GoodsTNVEDCode', 'ProductCode', 'AppendFormNumber', 'RKTNVED'], 'string', 'max' => 10],
            [['GoodsAddTNVEDCode'], 'string', 'max' => 4],
            [['PartySize'], 'string', 'max' => 5],
            [['PlaceKind', 'PackingCode', 'CustomsTax', 'CustomsDuty', 'Rate', 'LanguageGoods', 'PrecedingCustomsModeCode'], 'string', 'max' => 2],
            [['SupplementaryQualifierName'], 'string', 'max' => 13],
            [['VolumeUnitQualifierName', 'tags'], 'string', 'max' => 255],
            [['CustomsCostCorrectMethod', 'Excise'], 'string', 'max' => 1],
            [['Manufacturer', 'TradeMark'], 'string', 'max' => 150],
            [['GoodsMark', 'GoodsModel', 'GoodsMarking', 'GoodsStandart', 'GoodsSort', 'Dimensions', 'SerialNumber', 'goodsArticul'], 'string', 'max' => 50],
            [['WoodSortiment'], 'string', 'max' => 30],
            [['WoodKind'], 'string', 'max' => 20],
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
            'idDeal' => 'Id Deal',
            'GTDGoodsNumber' => 'Gtdgoods Number',
            'GoodFeatures' => 'Good Features',
            'GoodsShortDescription' => 'Goods Short Description',
            'GoodsDescription' => 'Goods Description',
            'AdditionalDescription' => 'Additional Description',
            'GoodsDescriptionGroop' => 'Goods Description Groop',
            'GoodsQuantity' => 'Goods Quantity',
            'MeasureUnitQualifierCode' => 'Measure Unit Qualifier Code',
            'MeasureUnitQualifierCost' => 'Measure Unit Qualifier Cost',
            'GoodsQuantity1' => 'Goods Quantity1',
            'MeasureUnitQualifierCode1' => 'Measure Unit Qualifier Code1',
            'MeasureUnitQualifier1Cost' => 'Measure Unit Qualifier1 Cost',
            'GoodsQuantity2' => 'Goods Quantity2',
            'MeasureUnitQualifierCode2' => 'Measure Unit Qualifier Code2',
            'MeasureUnitQualifier2Cost' => 'Measure Unit Qualifier2 Cost',
            'CommercialUnitQualifierCode' => 'Commercial Unit Qualifier Code',
            'CommercialUnitQualifierCost' => 'Commercial Unit Qualifier Cost',
            'CommercialQuantity' => 'Commercial Quantity',
            'MeasureUnitQualifierCodePay' => 'Measure Unit Qualifier Code Pay',
            'useCode' => 'Use Code',
            'codePrice' => 'Code Price',
            'PriceKG' => 'Price Kg',
            'Price' => 'Price',
            'Cost' => 'Cost',
            'CustomsCost' => 'Customs Cost',
            'StatisticalCost' => 'Statistical Cost',
            'GoodsTNVEDCode' => 'Goods Tnvedcode',
            'GoodsAddTNVEDCode' => 'Goods Add Tnvedcode',
            'SeriesSign' => 'Series Sign',
            'NormDocs' => 'Norm Docs',
            'PartySize' => 'Party Size',
            'ProductCode' => 'Product Code',
            'AppendNumber' => 'Append Number',
            'AppendFormNumber' => 'Append Form Number',
            'DeliveryTime' => 'Delivery Time',
            'DeliveryTimeEND' => 'Delivery Time End',
            'CurrencyCode' => 'Currency Code',
            'PlaceKind' => 'Place Kind',
            'PlacesQuantity' => 'Places Quantity',
            'PlaceWeightQuantity' => 'Place Weight Quantity',
            'PlaceNetWeightQuantity' => 'Place Net Weight Quantity',
            'NetWeightQuantity' => 'Net Weight Quantity',
            'GrossWeightQuantity' => 'Gross Weight Quantity',
            'NetWeightQuantity2' => 'Net Weight Quantity2',
            'SupplementaryQualifierName' => 'Supplementary Qualifier Name',
            'Discount' => 'Discount',
            'OriginCountryCode' => 'Origin Country Code',
            'PlaceGoodsQuantity' => 'Place Goods Quantity',
            'Length' => 'Length',
            'Width' => 'Width',
            'Height' => 'Height',
            'GoodsVolume' => 'Goods Volume',
            'VolumeUnitQualifierName' => 'Volume Unit Qualifier Name',
            'PlacesDescription' => 'Places Description',
            'PackingCode' => 'Packing Code',
            'PlacesPartQuantity' => 'Places Part Quantity',
            'HazardousCargoCode' => 'Hazardous Cargo Code',
            'PackingMark' => 'Packing Mark',
            'GoodsClassificationCode' => 'Goods Classification Code',
            'AdditionalSign' => 'Additional Sign',
            'IntellectPropertySign' => 'Intellect Property Sign',
            'CIMSign' => 'Cimsign',
            'BeginPeriodDate' => 'Begin Period Date',
            'EndPeriodDate' => 'End Period Date',
            'CustomsCostCorrectMethod' => 'Customs Cost Correct Method',
            'QuantityFact' => 'Quantity Fact',
            'Manufacturer' => 'Manufacturer',
            'TradeMark' => 'Trade Mark',
            'GoodsMark' => 'Goods Mark',
            'GoodsModel' => 'Goods Model',
            'GoodsMarking' => 'Goods Marking',
            'GoodsStandart' => 'Goods Standart',
            'GoodsSort' => 'Goods Sort',
            'WoodSortiment' => 'Wood Sortiment',
            'WoodKind' => 'Wood Kind',
            'Dimensions' => 'Dimensions',
            'DateIssue' => 'Date Issue',
            'SerialNumber' => 'Serial Number',
            'CustomsTax' => 'Customs Tax',
            'CustomsDuty' => 'Customs Duty',
            'Excise' => 'Excise',
            'Rate' => 'Rate',
            'LanguageGoods' => 'Language Goods',
            'PakagePartQuantity' => 'Pakage Part Quantity',
            'QuotaQuantity' => 'Quota Quantity',
            'QuotaMeasureUnitQualifierCode' => 'Quota Measure Unit Qualifier Code',
            'QuotaCurrencyQuantity' => 'Quota Currency Quantity',
            'QuotaCurrencyCode' => 'Quota Currency Code',
            'PrecedingCustomsModeCode' => 'Preceding Customs Mode Code',
            'GoodsTransferFeature' => 'Goods Transfer Feature',
            'OilField' => 'Oil Field',
            'RKTNVED' => 'Rktnved',
            'goodsArticul' => 'Goods Articul',
            'OneWeight' => 'One Weight',
            'TareWeight' => 'Tare Weight',
            'CreateDate' => 'Create Date',
            'Favorite' => 'Favorite',
            'archive' => 'Archive',
            'tags' => 'Tags',
            'mvcc_increment' => 'Mvcc Increment',
        ];
    }
}
