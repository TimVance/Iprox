<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $APPLICATION;

if ($arResult["NAV_OBJECT"])
{
	/**
	 * 
	 * 
	 * @var \Bitrix\Main\UI\PageNavigation $nav
	 */
	$nav = $arResult["NAV_OBJECT"];
	
	$Recordcount = $nav->getRecordCount();
	$count = intval($Recordcount)." ".Suffix($Recordcount, "Риэлтор|Риэлтора|Риэлторов");
	$APPLICATION->AddViewContent("all_count",$count);
}