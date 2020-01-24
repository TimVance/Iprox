<? 
namespace NewbuildingsAdmin;


/**
 * 
 * Класс настроек
 * 
 *
 */
class Settings
{

	/**
	 * Инфоблок новостроек
	 * 
	 * @var int
	 */
	public static $Iblock_newbuildings = 19;
	
	/**
	 * Инфоблок квартир
	 *
	 * @var int
	 */
	public static $Iblock_apparts = 7;
	
	/**
	 * Инфоблок планировок/корпусов
	 *
	 * @var int
	 */
	public static $Iblock_plans = 27;
	
	
	public static $arLinkTemplateElement = array(
			"newbuild_iblock_element_edit.php?newbuild=Y&IBLOCK_ID=#IBLOCK_ID#&type=#IBLOCK_TYPE#&ID=#ELEMENT_ID#&lang=ru&find_section_section=#SECTION_ID#&WF=Y",
			"newbuild_iblock_element_edit.php?IBLOCK_ID=#IBLOCK_ID#&type=#IBLOCK_TYPE#&ID=#ELEMENT_ID#&lang=ru&find_section_section=#SECTION_ID#&form_element_#IBLOCK_TYPE#_active_tab=edit1",
			"newbuild_iblock_element_edit.php?IBLOCK_ID=#IBLOCK_ID#&type=#IBLOCK_TYPE#&ID=#ELEMENT_ID#&lang=ru&find_section_section=#SECTION_ID#"	
	);
	
	public static $LinkTemplateElement = "newbuild_iblock_element_edit.php?newbuild=Y&IBLOCK_ID=#IBLOCK_ID#&type=#IBLOCK_TYPE#&ID=#ELEMENT_ID#&lang=ru&find_section_section=#SECTION_ID#&WF=Y" ;
	public static $LinkTemplateElement2 = "newbuild_iblock_element_edit.php?IBLOCK_ID=#IBLOCK_ID#&type=#IBLOCK_TYPE#&ID=#ELEMENT_ID#&lang=ru&find_section_section=#SECTION_ID#&form_element_#IBLOCK_TYPE#_active_tab=edit1";
	public static $LinkTemplateElement3 = "newbuild_iblock_element_edit.php?IBLOCK_ID=#IBLOCK_ID#&type=#IBLOCK_TYPE#&ID=#ELEMENT_ID#&lang=ru&find_section_section=#SECTION_ID#";
	
	
	
	public static $LinkTemplateSection = "newbuild_iblock_element_admin.php?&newbuild=Y&&IBLOCK_ID=#IBLOCK_ID#&type=#IBLOCK_TYPE#&lang=ru&find_section_section=#SECTION_ID#" ;
	
	public static $LinkTemplateAddApart = "newbuild_iblock_element_edit.php?IBLOCK_ID=7&type=catalog&ID=0&lang=ru&IBLOCK_SECTION_ID=-1&find_section_section=-1&newbuilding=#newbuidling_id#&living_block=#living_block_id#&apart_level=#apart_level#";
	
	private static $IBLOCK_TO_TYPE = array("19"=>"catalog","7"=>"catalog","27"=>"helpers");
	
	
	public static $MESS_add_appart = "Добавить квартиру";
	
	public static $MESS_add_block = "Добавить жилой комплекс";
	
	public static $MESS_add_newbuilding = "Добавить новостройку";
	
	public static $MESS_level_text = "Этаж";
	
	public function getIblockType($ID)
	{
		return self::$IBLOCK_TO_TYPE[$ID];
	}
}