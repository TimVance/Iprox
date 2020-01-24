<?
die();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if (!\Bitrix\Main\Loader::includeModule("iblock"))
	die();

	

	
\Morealty\Catalog::updateNewbuildingProps(93);
	
$newEle = new CIBlockElement;
$res = CIBlockElement::GetList(
		array(),
		array(
				"IBLOCK_ID" => 19,
				"ACTIVE" => "Y",
				"GLOBAL_ACTIVE" => "Y"
		),
		false,
		false,
		array("ID", "IBLOCK_ID", "PROPERTY_system_price_from", "PROPERTY_system_price_to", "PROPERTY_square_ot", "PROPERTY_square_do")
);
while ($arItem = $res->Fetch())
{
	\Morealty\Catalog::updateNewbuildingProps($arItem["ID"]);
	/*my_print_r($arItem);
	$price = array("MIN" => 0, "MAX" => 0);
	$square = array("MIN" => 0, "MAX" => 0);
	$apartRes = CIBlockElement::GetList(
			array("PROPERTY_price" => "asc"),
			array("IBLOCK_ID" => 7, "PROPERTY_newbuilding" => $arItem["ID"]),
			array("PROPERTY_price"),
			array("nTopCount" => 1)
			);
	while ($arAparts = $apartRes->Fetch())
	{
		$price["MIN"] = $arAparts["PROPERTY_PRICE_VALUE"];
	}
	$apartRes2 = CIBlockElement::GetList(
			array("PROPERTY_price" => "desc"),
			array("IBLOCK_ID" => 7, "PROPERTY_newbuilding" => $arItem["ID"]),
			array("PROPERTY_price"),
			array("nTopCount" => 1)
			);
	while ($arAparts = $apartRes2->Fetch())
	{
		$price["MAX"] = $arAparts["PROPERTY_PRICE_VALUE"];
	}
	
	
	$apartRes3 = CIBlockElement::GetList(
			array("PROPERTY_square" => "asc"),
			array("IBLOCK_ID" => 7, "PROPERTY_newbuilding" => $arItem["ID"]),
			array("PROPERTY_square"),
			array("nTopCount" => 1)
			);
	while ($arAparts = $apartRes3->Fetch())
	{
		$square["MIN"] = $arAparts["PROPERTY_SQUARE_VALUE"];
	}
	
	
	$apartRes4 = CIBlockElement::GetList(
			array("PROPERTY_square" => "desc"),
			array("IBLOCK_ID" => 7, "PROPERTY_newbuilding" => $arItem["ID"]),
			array("PROPERTY_square"),
			array("nTopCount" => 1)
			);
	while ($arAparts = $apartRes4->Fetch())
	{
		$square["MAX"] = $arAparts["PROPERTY_SQUARE_VALUE"];
	}
	my_print_r(array(
			"system_price_from" => intval($price["MIN"]),
			"system_price_to" => intval($price["MAX"]),
			"square_ot" => intval($square["MIN"]),
			"square_do" => intval($square["MAX"])
	));
	$toUpdate = array(
			"system_price_from" => intval($price["MIN"]),
			//"price_flat_min" => intval($price["MIN"]),
			"system_price_to" => intval($price["MAX"]),
			"square_ot" => intval($square["MIN"]),
			"square_do" => intval($square["MAX"])
	);
	if (intval($price["MIN"]) > 0)
	{
		$toUpdate["price_flat_min"] = intval($price["MIN"]);
	}
	$newEle->SetPropertyValuesEx($arItem["ID"], $arItem["IBLOCK_ID"], array(
			"system_price_from" => intval($price["MIN"]),
			//"price_flat_min" => intval($price["MIN"]),
			"system_price_to" => intval($price["MAX"]),
			"square_ot" => intval($square["MIN"]),
			"square_do" => intval($square["MAX"])
	));*/
}