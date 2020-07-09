<?
##########################################################################
# Name: kreattika.mlexport                                               #
# http://marketplace.1c-bitrix.ru/solutions/kreattika.mlexport/          #
# File: mlexportprofile.php                                              #
# Version: 1.0.5                                                         #
# (c) 2011-2016 Kreattika, Sedov S.Y.                                    #
# Proprietary licensed                                                   #
# http://kreattika.ru/                                                   #
# mailto:info@kreattika.ru                                               #
##########################################################################
?>
<?
namespace Kreattika\MLExport;

use Bitrix\Main\Entity;

class MLExportProfileTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'b_kreattika_ml_export_profile_entity';
    }

    public static function getUfId()
    {
        return 'ML_EXPORT_PROFILE';
    }

    public static function getMap()
    {
        return array(
            new Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true
            )),
            new Entity\StringField('LOCK', array(
                'required' => true
            )),
            new Entity\StringField('ACTIVE', array(
                'required' => true
            )),
            new Entity\IntegerField('SORT', array(
                'required' => true
            )),
            new Entity\StringField('NAME', array(
                'required' => true
            )),
            new Entity\IntegerField('MAX_EXECUTE_TIME', array(
                'required' => true
            )),
            new Entity\IntegerField('RECORD_LIMIT', array(
                'required' => true
            )),
            new Entity\StringField('SITE_ID', array(
                'required' => true
            )),
            new Entity\StringField('IBLOCK_TYPE', array(
                'required' => false
            )),
            new Entity\IntegerField('IBLOCK_ID', array(
                'required' => true
            )),
            new Entity\EnumField('EXPORT_TO', array(
                'values' => array('GOOGLE_MERCHANT', 'YANDEX_MARKET')
            )),
            new Entity\IntegerField('TEMPLATE_ID', array(
                'required' => true
            )),
            new Entity\ReferenceField(
                'TEMPLATE',
                'Kreattika\MLExport\MLExportTemplate',
                array('=this.TEMPLATE_ID' => 'ref.ID'),
                array('join_type' => 'LEFT')
            ),
            new Entity\TextField('TEMPLATE_FIELD_VALUE', array(
                'save_data_modification' => function () {
                    return array(
                        function ($value) {
                            return serialize($value);
                        }
                    );
                },
                'fetch_data_modification' => function () {
                    return array(
                        function ($value) {
                            return unserialize($value);
                        }
                    );
                }
            )),
            new Entity\StringField('EXPORT_ALL', array(
                'required' => false
            )),
            new Entity\StringField('EXPORT_ONLY_STORE', array(
                'required' => false
            )),
            new Entity\StringField('EXPORT_ONLY_PRICE', array(
                'required' => false
            )),
            new Entity\StringField('EXPORT_PRICE_ID', array(
                'required' => false
            )),
            new Entity\StringField('EXPORT_DISCOUNT', array(
                'required' => false
            )),
            new Entity\StringField('EXPORT_PRICE_PROPERTY', array(
                'required' => false
            )),
            new Entity\StringField('EXPORT_PRICE_CURRENCY', array(
                'required' => false
            )),
            new Entity\TextField('LOG', array(
                'save_data_modification' => function () {
                    return array(
                        function ($value) {
                            return serialize($value);
                        }
                    );
                },
                'fetch_data_modification' => function () {
                    return array(
                        function ($value) {
                            return unserialize($value);
                        }
                    );
                }
            )),
            new Entity\StringField('FOLDER_PATH', array(
                'required' => true
            )),
            new Entity\StringField('FILE_NAME', array(
                'required' => true
            )),
            new Entity\EnumField('FILE_ENCODE', array(
                'values' => array('UTF-8', 'windows-1251')
            )),
            new Entity\StringField('CLASS_NAME', array(
                'required' => true
            )),

        );
    }
    public static function add($data)
    {

        $MODULE_ID = GetModuleID(__FILE__);

        if( !isset($data['LOCK']) || empty($data['LOCK']) ):
            $data['LOCK'] = 'N';
        endif;

        if( !isset($data['ACTIVE']) || empty($data['ACTIVE']) ):
            $data['ACTIVE'] = 'Y';
        endif;

        if( !isset($data['SORT']) || empty($data['SORT']) ):
            $data['SORT'] = 500;
        endif;

        if( !isset($data['MAX_EXECUTE_TIME']) || empty($data['MAX_EXECUTE_TIME']) ):
            $data['MAX_EXECUTE_TIME'] = \Bitrix\Main\Config\Option::get($MODULE_ID, "mlexport_time");
        endif;

        if( !isset($data['RECORD_LIMIT']) || empty($data['RECORD_LIMIT']) ):
            $data['RECORD_LIMIT'] = \Bitrix\Main\Config\Option::get($MODULE_ID, "mlexport_record_limit");
        endif;

        if( !isset($data['EXPORT_TO']) || empty($data['EXPORT_TO']) ):
            return false;
        elseif( $data['EXPORT_TO'] == 'GOOGLE_MERCHANT' ):
            $data['CLASS_NAME'] = 'CMLExportGoogleMerchant';
        elseif( $data['EXPORT_TO'] == 'YANDEX_MARKET' ):
            $data['CLASS_NAME'] = 'CMLExportYandexMarketSimple';
        else:
            return false;
        endif;

        if( !isset($data['TEMPLATE_ID']) || empty($data['TEMPLATE_ID']) ):
            $data['TEMPLATE_ID'] = '1'; //temp value
        endif;

        if( !isset($data['EXPORT_ALL']) || empty($data['EXPORT_ALL']) ):
            $data['EXPORT_ALL'] = 'N';
        endif;

        if( !isset($data['EXPORT_ONLY_STORE']) || empty($data['EXPORT_ONLY_STORE']) ):
            $data['EXPORT_ONLY_STORE'] = 'N';
        endif;

        if( !isset($data['EXPORT_ONLY_PRICE']) || empty($data['EXPORT_ONLY_PRICE']) ):
            $data['EXPORT_ONLY_PRICE'] = 'Y';
        endif;

        if( !isset($data['EXPORT_PRICE_CURRENCY']) || empty($data['EXPORT_PRICE_CURRENCY']) ):
            $data['EXPORT_PRICE_CURRENCY'] = 'RUB';
        endif;

        if( !isset($data['FOLDER_PATH']) || empty($data['FOLDER_PATH']) ):
            $data['FOLDER_PATH'] = \Bitrix\Main\Config\Option::get($MODULE_ID, "mlexport_folder_path");
        endif;

        if( !isset($data['FILE_NAME']) || empty($data['FILE_NAME']) ):
            $fileNamePrefix = \Bitrix\Main\Config\Option::get($MODULE_ID, "mlexport_fale_name_prefix");
            $rnd = substr(str_shuffle(str_repeat('0123456789',1)),0,6);
            $data['FILE_NAME'] = $fileNamePrefix.$rnd;
        endif;

        if( !isset($data['FILE_ENCODE']) || empty($data['FILE_ENCODE']) ):
            $data['FILE_ENCODE'] = 'UTF-8';
        endif;

        $result = parent::add($data);

        if (!$result->isSuccess(true)):
            return $result;
        endif;

        return $result;
    }
}
?>