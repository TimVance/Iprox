<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if ($arParams["PROPERTY_FILTER"] && $arParams["FILTER_NAME"])
{
$All = (bool) !$_REQUEST[$arParams["PROPERTY_FILTER"]] || $_REQUEST[$arParams["PROPERTY_FILTER"]] == "all";
$bProp = $_REQUEST[$arParams["PROPERTY_FILTER"]] == "Y";
	if (!$All)
	{
		$sign = ($bProp)? "!" : "";
		
		$GLOBALS[$arParams["FILTER_NAME"]][$sign."PROPERTY_".$arParams["PROPERTY_FILTER"]] = false;
	}
}