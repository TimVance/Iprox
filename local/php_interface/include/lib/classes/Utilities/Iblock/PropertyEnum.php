<?

namespace Utilities\Iblock;

class PropertyEnum
{
	
	
	
	
	
	
	
	public static function getTextBuyEnumID($propertyResultID, $propertyCode, $iblock_id)
	{
		if (\Bitrix\Main\Loader::includeModule("iblock"))
		{
			$res = \CIBlockPropertyEnum::GetList(
						array(),
						array(
								"CODE" 			=>	$propertyCode,
								"IBLOCK_ID"		=>	$iblock_id,
								"ID"			=>	$propertyResultID
								
						)
					);
			
			if ($arPropertyResult = $res->Fetch())
			{
				return $arPropertyResult["VALUE"];
			}
		}
		return false;
	}
	
	public static function getEnumVariants($propertyCode, $iblock_id)
	{
		$return = array();
		if (\Bitrix\Main\Loader::includeModule("iblock"))
		{
			
			$res = \CIBlockPropertyEnum::GetList(
					array("ID" => "ASC"),
					array(
							"CODE" 			=>	$propertyCode,
							"IBLOCK_ID"		=>	$iblock_id,
							
					)
					);
			
			while ($arPropertyResult = $res->Fetch())
			{
				$return[$arPropertyResult["ID"]] = $arPropertyResult;
			}
			
		}
		return $return;
	}
}