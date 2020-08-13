<? if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die(); ?>
<? CModule::IncludeModule('iblock'); ?>

<?
$arID = $arParams['arID'];
$query = $arParams['QUERY'];
$arResult = array();


$arSelect = Array("ID", "NAME", "IBLOCK_ID");
$arFilter = Array("IBLOCK_ID" => $GLOBALS['CATALOG_IBLOCKS_ARRAY'], "ID" => $arID, "ACTIVE"=>"Y", "%NAME" => $query);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while($ob = $res->Fetch())
{
	$arResult[] = $ob['ID'];
}

$GLOBALS['FILTER_RESULT'] = $arResult;
?>
