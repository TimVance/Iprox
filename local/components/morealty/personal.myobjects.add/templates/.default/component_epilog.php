<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$assets = \Bitrix\Main\Page\Asset::getInstance();
$assets->addJs(SITE_DIR."bitrix/js/main/dd.js", true);

// фикс чтобы можно было сортировать изображения у объекта