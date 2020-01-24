<?
die();
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if (\Bitrix\Main\Loader::includeModule("iblock"))
{
	$newEle = new CIBlockElement();
	$rs = CIBlockElement::GetList(array(), array("IBLOCK_ID" => 7));
	while ($objItem = $rs->GetNextElement())
	{
		$arItem = $objItem->GetFields();
		$arItem["PROPERTIES"] = $objItem->GetProperties();
		/*$newEle->SetPropertyValuesEx($arItem["ID"], $arItem["IBLOCK_ID"], array(
				"price_1m" => intval($arItem["PROPERTIES"]["price_1m"]["VALUE"]),
		));*/
		$newEle->Update($arItem["ID"], array("ACTIVE" => $arItem["ACTIVE"]));
		
	}
}