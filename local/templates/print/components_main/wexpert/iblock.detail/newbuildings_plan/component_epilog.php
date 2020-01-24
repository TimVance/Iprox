<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?
$title = $arResult['NAME'] . " — планировки";
$APPLICATION->SetTitle($title);
$APPLICATION->AddChainItem($arResult['NAME'], "/newbuildings/" . $_REQUEST['ID'] . '/');
$APPLICATION->AddChainItem("Планировки", $APPLICATION->GetCurDir());
?>