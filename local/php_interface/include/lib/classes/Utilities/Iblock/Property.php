<?

namespace Utilities\Iblock;


class Property
{
	
	
	
	
	
	
	
	public static function PropertiesCodeToID(array $props,int $iblock_id)
	{
		if (!\Bitrix\Main\Loader::includeModule("iblock") || !$props || !is_array($props) || count($props) <= 0)
			return false;
		
		$return = array();
		foreach ($props as $PropCode)
		{
			if (!$PropCode)
				continue;
			$res = \CIBlockProperty::GetList(
					array(),
					array(
							"IBLOCK_ID"	=> $iblock_id,
							"CODE"		=> $PropCode
					)
					);
			
			if ($arProp = $res->GetNext())
			{
				$return[$arProp["CODE"]] = $arProp["ID"];
			}
		}

		return $return;
	}
}