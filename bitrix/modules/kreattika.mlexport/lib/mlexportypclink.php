<?
##########################################################################
# Name: kreattika.mlexport                                               #
# http://marketplace.1c-bitrix.ru/solutions/kreattika.mlexport/          #
# File: mlexportypclink.php                                              #
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

class MLExportYPCLinkTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'b_kreattika_ml_export_ypc_link_entity';
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
            new Entity\IntegerField('YPC_ID', array(
                'required' => true
            )),
            new Entity\ReferenceField(
                'YPC',
                'Kreattika\MLExport\MLExportYPC',
                array('=this.YPC_ID' => 'ref.ID'),
                array('join_type' => 'LEFT')
            ),

            new Entity\IntegerField('SECTION_ID', array(
                'required' => false
            )),
            new Entity\IntegerField('ELEMENT_ID', array(
                'required' => false
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

        if( !isset($data['SECTION_ID']) || empty($data['SECTION_ID']) ):
            if( !isset($data['ELEMENT_ID']) || empty($data['ELEMENT_ID']) ):
                return false;
            endif;
        elseif( !isset($data['ELEMENT_ID']) || empty($data['ELEMENT_ID']) ):
            if( !isset($data['SECTION_ID']) || empty($data['SECTION_ID']) ):
                return false;
            endif;
        endif;

        $result = parent::add($data);

        if (!$result->isSuccess(true)):
            return $result;
        endif;

        return $result;
    }

}
?>