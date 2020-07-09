<?
##########################################################################
# Name: kreattika.mlexport                                               #
# http://marketplace.1c-bitrix.ru/solutions/kreattika.mlexport/          #
# File: mlexportwarning.php                                              #
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

class MLExportWarningTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'b_kreattika_ml_export_warning_entity';
    }

    public static function getMap()
    {
        return array(
            new Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true
            )),
            new Entity\StringField('TEXT', array(
                'required' => true
            )),
            new Entity\StringField('DATA', array(
                'required' => true
            )),
        );
    }

    public static function add($data)
    {

        #$MODULE_ID = GetModuleID(__FILE__);

        $result = parent::add($data);

        if (!$result->isSuccess(true)):
            return $result;
        endif;

        return $result;
    }

}
?>