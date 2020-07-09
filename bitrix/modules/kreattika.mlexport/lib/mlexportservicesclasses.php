<?
##########################################################################
# Name: kreattika.mlexport                                               #
# http://marketplace.1c-bitrix.ru/solutions/kreattika.mlexport/          #
# File: mlexportservicesclasses.php                                      #
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

class MLExportServicesClassesTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'b_kreattika_ml_export_services_classes_entity';
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
            new Entity\StringField('CODE', array(
                'required' => true
            )),
            new Entity\StringField('NAME', array(
                'required' => true
            )),
        );
    }

    public static function add($data)
    {

        #$MODULE_ID = GetModuleID(__FILE__);

        if( !isset($data['ACTIVE']) || empty($data['ACTIVE']) ):
            $data['ACTIVE'] = 'Y';
        endif;

        if( !isset($data['SORT']) || empty($data['SORT']) ):
            $data['SORT'] = 500;
        endif;

        $result = parent::add($data);

        if (!$result->isSuccess(true)):
            return $result;
        endif;

        return $result;
    }

}
?>