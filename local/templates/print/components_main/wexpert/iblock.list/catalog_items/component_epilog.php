<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$count = intval($arResult["NAV_RESULT"]["RECORDS_COUNT"])." ".Suffix($arResult["NAV_RESULT"]["RECORDS_COUNT"], "Объект|Объекта|Объектов");
$APPLICATION->AddViewContent("all_count",$count);
?>
<input type="hidden" id="all_count" value="<?=$count?>">
<?