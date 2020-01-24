<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die(); ?>
<?
global $APPLICATION,$USER;
if(!empty($arResult["I_PROPS"]['ELEMENT_META_DESCRIPTION']))
	$APPLICATION->SetPageProperty('description', $arResult["I_PROPS"]['ELEMENT_META_DESCRIPTION']);
if(!empty($arResult["I_PROPS"]['ELEMENT_META_KEYWORDS']))
	$APPLICATION->SetPageProperty('keywords', $arResult["I_PROPS"]['ELEMENT_META_KEYWORDS']);
if(!empty($arResult["I_PROPS"]['ELEMENT_META_TITLE']))
	$APPLICATION->SetPageProperty('title', $arResult["I_PROPS"]['ELEMENT_META_TITLE']);

$APPLICATION->AddChainItem($arResult["NAME"],$arResult["DETAIL_PAGE_URL"]);

$APPLICATION->SetTitle($arResult["NAME"]);
?>