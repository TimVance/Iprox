<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die(); ?>
<?
CModule::IncludeModule('iblock');


$arResult = $arParams['PROPERTIES'];
$this->IncludeComponentTemplate();
?>