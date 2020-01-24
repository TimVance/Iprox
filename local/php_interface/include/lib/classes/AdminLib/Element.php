<?
namespace AdminLib;

class Element
{
	
	
	private static $propDefault = array(
			"ACTIVE"			=> "Y",
			"SORT"				=> 500,
			"DEFAULT_VALUE"		=> "",
			"PROPERTY_TYPE"		=> "E",
			"ROW_COUNT"			=> 1,
			"COL_COUNT"			=> 30,
			"LIST_TYPE"			=> "L",
			"MULTIPLE"			=> "N",
			"MULTIPLE_CNT"		=> "5",
			"LINK_IBLOCK_ID"	=> 0,
			"VERSION"			=> 1,
			"USER_TYPE"			=> "",
			
			
	);
	
	public static function ElementSelector(string $inputName, $value = "",$arSettings)
	{
		if (\CModule::IncludeModule("iblock"))
		{
			$arSet = array();
			if ($arSettings["IBLOCK_ID"])
			{
				$arSet["LINK_IBLOCK_ID"] = $arSettings["IBLOCK_ID"];
			}
			
			
			$arProp = array_merge(static::$propDefault,array("MULTIPLE"=>"N"),(array) $arSet);
			_ShowElementPropertyField($inputName,$arProp,array($value));
		}
	}
	
	public static function ElementsSelector(string $inputName, $value = array(),$arSettings = array())
	{
		if (\CModule::IncludeModule("iblock"))
		{
			$arSet = array();
			if ($arSettings["IBLOCK_ID"])
			{
				$arSet["LINK_IBLOCK_ID"] = $arSettings["IBLOCK_ID"];
			}			
			if ($arSettings["COUNT"])
			{
				$arSet["MULTIPLE_CNT"] = $arSettings["COUNT"];
			}
			if ($value && count($value) > 0)
			{
				$value = array_values(array_filter($value));
			}
			
			$arProp = array_merge(static::$propDefault,array("MULTIPLE"=>"Y"),(array) $arSet);
			_ShowElementPropertyField($inputName, $arProp, $value);
		}
		
	}
}