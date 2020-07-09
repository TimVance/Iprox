<?
##########################################################################
# Name: kreattika.mlexport                                               #
# http://marketplace.1c-bitrix.ru/solutions/kreattika.mlexport/          #
# File: mlexporttpl.php                                                  #
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

class MLExportTemplateTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'b_kreattika_ml_export_template_entity';
    }

    public static function getUfId()
    {
        return 'ML_EXPORT_TEMPLATE';
    }

    public static function getMap()
    {
        return array(
            new Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true
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
            new Entity\EnumField('EXPORT_TO', array(
                'values' => array('GOOGLE_MERCHANT', 'YANDEX_MARKET')
            )),
            new Entity\StringField('CLASS_NAME', array(
                'required' => true
            )),
            new Entity\TextField('FIELD', array(
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

        );
    }
    public static function add($data)
    {

        $MODULE_ID = GetModuleID(__FILE__);

        if( !isset($data['ACTIVE']) || empty($data['ACTIVE']) ):
            $data['ACTIVE'] = 'Y';
        endif;

        if( !isset($data['SORT']) || empty($data['SORT']) ):
            $data['SORT'] = 500;
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

        $result = parent::add($data);

        if (!$result->isSuccess(true)):
            return $result;
        endif;

        return $result;
    }
}
?>