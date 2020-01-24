<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$iblockID = intval($arParams["IBLOCK_ID"]);
CiblockElement::getlist();
$resIblock = CIBlock::GetByID($iblockID);
if ($arIblock = $resIblock->GetNext())
{
	$arResult["FORM_ACTION"] = $arIblock["LIST_PAGE_URL"] = str_replace(array("#IBLOCK_CODE#"), array($arIblock['CODE']), $arIblock["LIST_PAGE_URL"]);
	$arResult["IBLOCK"] = $arIblock;
	//my_print_r($arIblock);
}
$arItems = array();
$itemsToLoad = array();
$itemsLoaded = array();
$currentItemsCodes = array();
foreach ($arResult["ITEMS"] as $itemIndex => $arItem)
{
	$currentItemsCodes[$itemIndex] = $arItem["CODE"];
}
foreach ($arParams["MAIN_PROPS"] as $PropCode)
{
	if (!in_array($PropCode, $currentItemsCodes))
	{
		$itemsToLoad[] = $PropCode;
	}
}
foreach ($arParams["ADDITIONAL_PROPS"] as $PropCode)
{
	if (!in_array($PropCode, $currentItemsCodes))
	{
		$itemsToLoad[] = $PropCode;
	}
}
if ($itemsToLoad && count($itemsToLoad) > 0)
{
	$arMapProps = CIBlockSectionPropertyLink::GetArray($iblockID, $this->__component->SECTION_ID);
	foreach ($itemsToLoad as $CodeCode)
	{
		
		$resProp = CIBlockProperty::GetList(array(), array("CODE" => $CodeCode, "IBLOCK_ID" => $iblockID));
		if ($arProperty = $resProp->GetNext())
		{
			$arLink = $arMapProps[$arProperty["ID"]];
			
			$arValues = array();
			
			if ($arProperty["PROPERTY_TYPE"] == "L")
			{
				$resEnum = CIBlockPropertyEnum::GetList(array("ID" => "ASC"), array("IBLOCK_ID" => $iblockID, "PROPERTY_ID" => $arProperty["ID"]));
				while ($arEnumResult = $resEnum->Fetch())
				{
					$safeValue = abs(crc32($arEnumResult["ID"]));
					//$safeValue = $arEnumResult["ID"];
					$arValues[$arEnumResult["ID"]] = array(
							"CONTROL_ID" => $arParams["FILTER_NAME"]."_".$arProperty["ID"]."_".$safeValue,
							"CONTROL_NAME" => $arParams["FILTER_NAME"]."_".$arProperty["ID"]."_".$safeValue,
							"CONTROL_NAME_ALT" => $arParams["FILTER_NAME"]."_".$arProperty["ID"],
							"HTML_VALUE_ALT" => $safeValue,
							"VALUE"	=> $arEnumResult['VALUE'],
							"SORT" => $arEnumResult["SORT"],
							"DEF" => $arEnumResult["DEF"]
							
					);
					if (isset($_REQUEST[$arParams["FILTER_NAME"]."_".$arProperty["ID"]]) && $_REQUEST["del_filter"] != "Y")
					{
						$arValues[$arEnumResult["ID"]]["HTML_VALUE"] = $arEnumResult["VALUE"];
					}
				}
			}
			
			$itemsLoaded[$arProperty["CODE"]] = array(
					"ID" => $arProperty["ID"],
					'CODE' => $arProperty["CODE"],
					"~NAME" => $arProperty["~NAME"],
					"NAME" => $arProperty['NAME'],
					"PROPERTY_TYPE" => $arProperty["PROPERTY_TYPE"],
					"DISPLAY_TYPE" => $arLink["DISPLAY_TYPE"],
					"DISPLAY_EXPANDED" => $arLink["DISPLAY_EXPANDED"],
					"FILTER_HINT" => $arLink["FILTER_HINT"],
					"VALUES" => $arValues,
			);
		}
	}
}

foreach ($arParams["MAIN_PROPS"] as $PropCode)
{
	$LoadedKey = array_search($PropCode, $currentItemsCodes);
	if ($LoadedKey !== false)
	{
		$arItems[$LoadedKey] = $arResult["ITEMS"][$LoadedKey];
	}
	else if ($itemsLoaded[$PropCode])
	{
		$arItems[$PropCode] = $itemsLoaded[$arProperty["CODE"]];
	}
	 
}

foreach ($arParams["ADDITIONAL_PROPS"] as $PropCode)
{
	$LoadedKey = array_search($PropCode, $currentItemsCodes);
	if ($LoadedKey !== false)
	{
		$arItem = $arResult["ITEMS"][$LoadedKey];
		$arItem["ADDITIONAL"] = "Y";
		$arItems[$LoadedKey] = $arItem;
	}
	else if ($itemsLoaded[$PropCode])
	{
		$arItem = $itemsLoaded[$arProperty["CODE"]];
		$arItem["ADDITIONAL"] = "Y";
		$arItems[$PropCode] = $arItem;
	}
	
}

$resValute = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $iblockID, "CODE" => "currency"));
if ($arValute = $resValute->Fetch())
{
	$arResult["VALUTE"] = $arValute;
	$resEnumValutes = CIBlockPropertyEnum::GetList(array("ID" => "ASC"), array("IBLOCK_ID" => $iblockID, "PROPERTY_ID" => $arValute["ID"]));
	while ($arEnumValue = $resEnumValutes->Fetch())
	{
		$safeValue = abs(crc32($arEnumValue["ID"]));
		//$safeValue = $arEnumValue["ID"];
		$arResult["VALUTE"]["VALUES"][] = array(
				"CONTROL_ID" => $arParams["FILTER_NAME"]."_".$arValute["ID"]."_".$safeValue,
				"CONTROL_NAME" => $arParams["FILTER_NAME"]."_".$arValute["ID"]."_".$safeValue,
				"CONTROL_NAME_ALT" => $arParams["FILTER_NAME"]."_".$arValute["ID"],
				"HTML_VALUE_ALT" => $safeValue,
				"VALUE"	=> str_replace(array("RUB"), array("Руб."), $arEnumValue['VALUE']),
				"SORT" => $arEnumValue["SORT"],
		);
	}
}
$arResult["ITEMS"] = $arItems;

