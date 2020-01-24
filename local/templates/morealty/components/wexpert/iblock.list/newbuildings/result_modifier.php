<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();


foreach ($arResult["ITEMS"] as $itemKey => $arItem) {
	$arFilter = Array("IBLOCK_ID" => 7, "ACTIVE" => "Y", "PROPERTY_newbuilding" => $arItem['ID'], "!PROPERTY_IS_ACCEPTED" => false);
	$res      = CIBlockElement::GetList(
		Array('PROPERTY_price' => 'asc,nulls'),
		$arFilter,
		array('PROPERTY_price'),
		array("nTopCount" => 1)
	);
	while ($arSub = $res->GetNext()) {
		if ($arSub["PROPERTY_PRICE_VALUE"]) {
			$arResult["ITEMS"][$itemKey]["PROPERTIES"]["price_flat_min"]["VALUE"] = $arSub["PROPERTY_PRICE_VALUE"];
		}
	}
}
