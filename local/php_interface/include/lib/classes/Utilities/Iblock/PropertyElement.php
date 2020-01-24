<?

namespace Utilities\Iblock;


class PropertyElement
{
	
	
	
	
	
	
	
	public static function valuesIDsToName(array $ids)
	{
		$ids = array_filter($ids);
		if (!\Bitrix\Main\Loader::includeModule("iblock") || !$ids || !is_array($ids) || count($ids) <= 0)
			return false;
		
		$return = array();
		
		$res = \CIBlockElement::GetList(array(),array("ID" => $ids),false,false, array("ID" , "NAME"));
		while ($arItem = $res->Fetch())
		{
			$return[$arItem["ID"]] = $arItem["NAME"];
		}
		return $return;
	}
}