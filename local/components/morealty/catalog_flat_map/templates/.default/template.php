<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);?>
<?

$arMapData = array();
$count = 0;

	//my_print_r($arItem);
	//if (count($arItem["OBJ"]) <= 0) continue;
	
$arMapData["PLACEMARKS"] = $arResult["PLACEMARKS"];
if ($arParams["MAP_CENTER_POS"])
{
	$CenterPos = preg_split("/[\s,]+/", $arParams["MAP_CENTER_POS"]);
	$arMapData["yandex_lat"] = $CenterPos[0];
	$arMapData["yandex_lon"] = $CenterPos[1];
}
if ($arParams["MAP_ZOOM"])
{
	$arMapData["yandex_scale"] = $arParams["MAP_ZOOM"];
}
$cnt = count($arMapData["PLACEMARKS"]);
$templateData = array_merge(array(array("CNT" => $cnt." ".Suffix($cnt, "Объект|Объекта|Объектов"))), $arMapData["PLACEMARKS"]);

//my_print_r($arMapData,true,false);?>
<?$this->SetViewTarget('sell-map-map');?>
<? 
$templateMapByUser = ($USER->GetLogin() == "vadim")?  "sell-map-2.1" : "sell-map";
?>
<?$APPLICATION->IncludeComponent(
		"bitrix:map.yandex.view",
		"sell-map-2.1",
		array(
				"INIT_MAP_TYPE" => "MAP",
				"MAP_DATA" => serialize($arMapData),
				"MAP_WIDTH" => "auto",
				"MAP_HEIGHT" => "600",
				"CONTROLS" => array(
						//0 => "ZOOM",
						//1 => "SMALLZOOM",
						//2 => "SCALELINE",
				),
				"OPTIONS" => array(
						0 => "ENABLE_DBLCLICK_ZOOM",
						1 => "ENABLE_DRAGGING",
				),
				"MAP_ID" => "yam_1",
				"COMPONENT_TEMPLATE" => "sell-map-2.1",
				"ONMAPREADY"=> "EventMapReady",
				"CACHE_TYPE" => "N",
				"CACHE_TIME" => 0
		),
		$component
);?>
<?$this->EndViewTarget();?>