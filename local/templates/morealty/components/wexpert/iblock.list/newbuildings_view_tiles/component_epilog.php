<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!is_null($arResult["NAV_RESULT"]["RECORDS_COUNT"]))
{
	global $APPLICATION;
	
	$APPLICATION->AddViewContent("all_count", $arResult["NAV_RESULT"]["RECORDS_COUNT"]." ".Suffix($arResult["NAV_RESULT"]["RECORDS_COUNT"], "Объект|Объекта|Объектов"));
	//RECORDS_COUNT
}