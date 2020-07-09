<?
##########################################################################
# Name: kreattika.mlexport                                               #
# http://marketplace.1c-bitrix.ru/solutions/kreattika.mlexport/          #
# File: mlexport.php                                                     #
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

class MLExportTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'b_kreattika_ml_export_entity';
    }

    public static function getUfId()
    {
        return 'ML_EXPORT';
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
            new Entity\EnumField('STATUS', array(
                'values' => array('START', 'CREATE', 'END', 'ERROR')
            )),
            new Entity\IntegerField('PROFILE_ID', array(
                'required' => true
            )),
            new Entity\ReferenceField(
                'PROFILE',
                'Kreattika\MLExport\MLExportProfile',
                array('=this.PROFILE_ID' => 'ref.ID'),
                array('join_type' => 'LEFT')
            ),
            new Entity\TextField('CREATE_NS', array(
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
            new Entity\TextField('CREATE_ELEMENT_NS', array(
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
            new Entity\DatetimeField('START', array(
                'required' => true
            )),
            new Entity\DatetimeField('LAST_START', array(
                'required' => false
            )),
            new Entity\DatetimeField('END', array(
                'required' => false
            )),
            new Entity\StringField('COMMENT', array(
                'required' => false
            )),
        );
    }
    public static function add($data)
    {

        #$MODULE_ID = GetModuleID(__FILE__);

        if( !isset($data['LOCK']) || empty($data['LOCK']) ):
            $data['LOCK'] = 'N';
        endif;

        if( !isset($data['STATUS']) || empty($data['STATUS']) ):
            $data['STATUS'] = 'START';
        elseif( $data['STATUS'] == 'START' || $data['STATUS'] == 'CREATE' || $data['STATUS'] == 'END' || $data['STATUS'] == 'ERROR' ):
        else:
            $data['STATUS'] = 'START';
        endif;

        if( !isset($data['START']) || empty($data['START']) ):
            $data['START'] = new \Bitrix\Main\Type\DateTime(date('Y-m-d H:i:s',time()),'Y-m-d H:i:s');
        endif;

        $result = parent::add($data);

        if (!$result->isSuccess(true)):
            return $result;
        endif;

        return $result;
    }
}
?>