<?
##########################################################################
# Name: kreattika.mlexport                                               #
# http://marketplace.1c-bitrix.ru/solutions/kreattika.mlexport/          #
# File: mlexportlog.php                                                  #
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

class MLExportLogTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'b_kreattika_ml_export_log_entity';
    }

    public static function getMap()
    {
        return array(
            new Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true
            )),
            new Entity\DatetimeField('DATE', array(
                'required' => true
            )),
            new Entity\StringField('TYPE', array(
                'required' => true
            )),
            new Entity\StringField('TEXT', array(
                'required' => false
            )),
            new Entity\TextField('DATA', array(
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

        #$MODULE_ID = GetModuleID(__FILE__);

        $arType = array("UNKNOWN", "SUCCESS", "WARNING", "ERROR", );

        if( !isset($data['TYPE']) || empty($data['TYPE']) ):
            $data['TYPE'] = 'UNKNOWN';
        else:
            if(array_search(strtoupper($data['TYPE']), $arType) === false ):
                $data['TYPE'] = 'UNKNOWN';
            else:
                $data['TYPE'] = strtoupper($data['TYPE']);
            endif;
        endif;

        if( !isset($data['DATE']) || empty($data['DATE']) ):
            $data['DATE'] = new \Bitrix\Main\Type\DateTime(date('Y-m-d H:i:s',time()),'Y-m-d H:i:s');
        endif;

        $result = parent::add($data);

        if (!$result->isSuccess(true)):
            return $result;
        endif;

        return $result;
    }

}
?>