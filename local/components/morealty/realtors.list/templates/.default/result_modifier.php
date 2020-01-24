<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (true)
{
	
	if (\Bitrix\Main\Loader::includeModule("iblock"))
	{
		$Ids = array_filter(array_column($arResult['ITEMS'], "ID"));
		$arGroups = array();
		$rs = CIBlockElement::GetList(
			array(),
			array(
					"IBLOCK_TYPE" => "catalog",
					"PROPERTY_realtor" => $Ids,
					"ACTIVE" => "Y",
					"!PROPERTY_IS_ACCEPTED"=>false
			),
			array("PROPERTY_REALTOR", "IBLOCK_ID")
		);
		while ($arGroup = $rs->Fetch())
		{
			
			if ($arGroup["PROPERTY_REALTOR_VALUE"])
			{
				$arGroups[$arGroup["PROPERTY_REALTOR_VALUE"]][$arGroup["IBLOCK_ID"]] = array("NAME" => \Morealty\Catalog::getIblockDataField($arGroup["IBLOCK_ID"], "NAME"), "CNT" => $arGroup['CNT']);
			}
			
		}
		foreach ($arResult["ITEMS"] as &$arRealtor)
		{
			if ($arGroups[$arRealtor["ID"]])
			{
				$arRealtor["GROUPS"] = $arGroups[$arRealtor["ID"]];
			}
		}
	}
}