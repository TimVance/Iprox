<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $APPLICATION;

$cnt = intval($arResult["CNT"]);
$CntObjectsString = $cnt." ".Suffix($cnt, "Объект|Объекта|Объектов");
$APPLICATION->AddViewContent("all_count", $CntObjectsString);
if (\Morealty\Main::isAjax())
{
	$APPLICATION->RestartBuffer();
	echo(json_encode(array_merge(array(array("CNT" => $CntObjectsString)), $arResult["PLACEMARKS"])));
	die();
}
