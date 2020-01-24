<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $APPLICATION;

if (!is_null($arResult["COUNT_OBJECTS"]))
{
	global $APPLICATION;
	
	$APPLICATION->AddViewContent("all_count",$arResult["COUNT_OBJECTS"]." ".Suffix($arResult["COUNT_OBJECTS"], "Объект|Объекта|Объектов"));
	//RECORDS_COUNT
}