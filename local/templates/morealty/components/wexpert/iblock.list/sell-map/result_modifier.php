<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die(); ?>
<?
$arTmp = [];
foreach ($arResult["ITEMS"] as $arItem) {
	$arTmp[$arItem['ID']] = $arItem;
}
$arResult["ITEMS"] = $arTmp;

if ($arParams["GET_ELEMENTS_FROM"] && count($arParams['GET_ELEMENTS_FROM']) > 0) {
	$arResult["OBJECTS"] = array();
	$arNewBuildings      = array();
	$arValueKey          = array();
	foreach ($arResult["ITEMS"] as $key => $arItem) {
		if ($arItem["PROPERTY_NEWBUILDING_VALUE"]) {
			$arNewBuildings[]                                    = $arItem["PROPERTY_NEWBUILDING_VALUE"];
			$arValueKey[$arItem["PROPERTY_NEWBUILDING_VALUE"]][] = $key;
		} else {
			if (count($arItem["PROPERTIES"]["photo_gallery"]) > 0 && $arItem["PROPERTIES"]["photo_gallery"][0]["VALUE"]) {
				$oldArr                                = $arItem["PROPERTIES"]["photo_gallery"];
				$arItem["PROPERTIES"]["photo_gallery"] = array();
				foreach ($oldArr as $arValue) {
					$arItem["PROPERTIES"]["photo_gallery"]["VALUE"][] = $arValue["VALUE"];
				}
			}
			$arResult["OBJECTS"][] = array_merge($arItem, array(
					"NAME"            => $arItem["NAME"],
					"DETAIL_PAGE_URL" => "javascript:void(0);",
					"OBJ"             => array($arItem),
					"PRICES"          => $arItem["PROPERTY_PRICE_VALUE"],
					"MIN_PRICE"       => $arItem["PROPERTY_PRICE_VALUE"],
					"MAX_PRICE"       => $arItem["PROPERTY_PRICE_VALUE"]
				)
			);
		}
	}
	$arNewBuildings = array_unique($arNewBuildings);

	if (count($arNewBuildings) > 0) {

		$arSort       = array();
		$arFilter     = array(
			"IBLOCK_ID"            => $arParams["GET_ELEMENTS_FROM"],
			"ACTIVE"               => "Y",
			//"ID" => $arNewBuildings,
			"!PROPERTY_YANDEX_MAP" => false,
		);
		$arSelect     = array(
			"NAME", "ID", "DETAIL_PAGE_URL",
			"PROPERTY_city.NAME", "PROPERTY_district.NAME", "PROPERTY_microdistrict.NAME", "IBLOCK_ID"
		);
		$resBuildings = CIBlockElement::GetList($arSort, $arFilter, false, false, $arSelect);
		$arTemp       = array();
		while ($TisObject = $resBuildings->GetNextElement()) {
			$arObject               = $TisObject->GetFields();
			$arObject["PROPERTIES"] = $TisObject->GetProperties();
			//my_print_r($arObject);
			if (count($arValueKey[$arObject["ID"]]) > 0) {
				foreach ($arValueKey[$arObject["ID"]] as $KeyApart) {
					$arObject["OBJ"][]    = $arResult["ITEMS"][$KeyApart];
					$arObject["PRICES"][] = $arResult["ITEMS"][$KeyApart]["PROPERTY_PRICE_VALUE"];
				}
				if (count($arObject["PRICES"]) > 0) {
					$arObject["MIN_PRICE"] = min($arObject["PRICES"]);
					$arObject["MAX_PRICE"] = max($arObject["PRICES"]);
				}
			}
			if ($arObject["OBJ"] && count($arObject["OBJ"]) > 0) {
				$arResult["OBJECTS"][] = $arObject;
			}
		}
	}
}

?>