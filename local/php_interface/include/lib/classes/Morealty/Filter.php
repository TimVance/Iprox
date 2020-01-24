<?
namespace Morealty;
use \Morealty\Settings as CSettings;

class Filter
{
	
	
	public static function twinDistrictMicroDistrict($arDistrict, $arMicrodistrict)
	{
		//my_print_r($arDistrict);
		//my_print_r($arMicrodistrict);
		$values = array();
		$districts = static::loadDistricts();
		if (!$districts)
			return false;
		$presentDistrictValues = array_column($districts, 'HTML_VALUE');
		$IDstoSearch = array();
		foreach ($arDistrict["VALUES"] as $DistrictValue)
		{
			$key = array_search($DistrictValue["HTML_VALUE_ALT"], $presentDistrictValues);
			if ($key !== false)
			{
				$IDstoSearch[] = $districts[$key]["ID"];
			}
		}
		if ($IDstoSearch && count($IDstoSearch) > 0)
		{
			
			$micro = static::loadMiscrodistricts($IDstoSearch);
			$microParamValues = array_column($micro, "HTML_VALUE");
			$readyMicro = array();
			foreach ($arMicrodistrict["VALUES"] as $FindedMicro)
			{
				
				$key = array_search($FindedMicro["HTML_VALUE_ALT"], $microParamValues);
				if ($key !== false)
				{
					$readyMicro[] = array_merge($FindedMicro, array("CHILD" => "Y", "PARENT_HTML_VALUE" => $micro[$key]["PARENT_HTML_VALUE"]));
				}
			}
			
			foreach ($arDistrict["VALUES"] as $arValue)
			{
				
				if (in_array($arValue["HTML_VALUE_ALT"], $presentDistrictValues))
				{
					$values[] = $arValue;
					foreach (array_filter($readyMicro, function($a) use($arValue){
						
						return $a['PARENT_HTML_VALUE'] == $arValue["HTML_VALUE_ALT"];
					}) as $Child)
					{
						$values[] = $Child;
					}
				}
			}
			$arDistrict["VALUES"] = $values;
		}
		
		return $arDistrict;
	}
	
	private function loadDistricts()
	{
		$res = \CIBlockElement::GetList(
			array(),
			array("IBLOCK_ID" => CSettings::IBLOCK_DISTRICT, "ACTIVE" => "Y"),
			false,
			false,
			array("ID" , "IBLOCK_ID")
		);
		$return = array();
		while ($arItem = $res->Fetch())
		{
			$return[] = array("ID" => $arItem["ID"], "HTML_VALUE" => abs(crc32($arItem["ID"])));
		}
		return $return;
	}
	
	private function loadMiscrodistricts($districts)
	{
		$res = \CIBlockElement::GetList(
			array(),
			array("IBLOCK_ID" => CSettings::IBLOCK_MICRODISTRICT, "ACTIVE" => "Y", "PROPERTY_".CSettings::PROPERTY_MICRODISTRICT_DISTRICT => $districts, "!PROPERTY_".CSettings::PROPERTY_MICRODISTRICT_DISTRICT => false),
			false,
			false,
			array("ID" , "IBLOCK_ID", "PROPERTY_".CSettings::PROPERTY_MICRODISTRICT_DISTRICT)
			);
		$return = array();
		while ($arItem = $res->Fetch())
		{
			$return[] = array(
				"ID" => $arItem["ID"],
				"HTML_VALUE" => abs(crc32($arItem["ID"])),
				"PARENT" => $arItem["PROPERTY_".strtoupper(CSettings::PROPERTY_MICRODISTRICT_DISTRICT)."_VALUE"],
				"PARENT_HTML_VALUE" => abs(crc32($arItem["PROPERTY_".strtoupper(CSettings::PROPERTY_MICRODISTRICT_DISTRICT)."_VALUE"]))
				
			);
		}
		return $return;
	}
}