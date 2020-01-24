<?

namespace Utilities\Iblock;


class Element
{
	
	public static function getElementsMinAndMax($iblock_id, $prop_code, $filter = array())
	{
		if (!\Bitrix\Main\Loader::includeModule("iblock"))
			return false;
		
		$return = array("MIN" => 0, "MAX" => 0);
		
		$res = \CIBlockElement::GetList(
				array("PROPERTY_".$prop_code => "asc"),
				array_merge(array("IBLOCK_ID" => $iblock_id), $filter),
				array("PROPERTY_".$prop_code),
				array("nTopCount" => 1)
				);
		while ($arCase = $res->Fetch())
		{
			$return["MIN"] = $arCase["PROPERTY_".strtoupper($prop_code)."_VALUE"];
		}
		$res = \CIBlockElement::GetList(
				array("PROPERTY_".$prop_code => "desc"),
				array_merge(array("IBLOCK_ID" => $iblock_id), $filter),
				array("PROPERTY_".$prop_code),
				array("nTopCount" => 1)
				);
		while ($arCase = $res->Fetch())
		{
			$return["MAX"] = $arCase["PROPERTY_".strtoupper($prop_code)."_VALUE"];
		}
		return $return;
	}
}