<? if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die(); ?>
<? CModule::IncludeModule('iblock'); ?>

<?
$cUser = new CUser;
/*$sort_by = "ID";
$sort_ord = "ASC";
$dbUsers = $cUser->GetList($sort_by, $sort_ord, array('ID' => $USER->GetID()), array('SELECT' => array('UF_MYOBJECTS'), 'FIELDS' => array('ID')));
if($arUser = $dbUsers->Fetch()) {
	$tempArr = explode('|', $arUser['UF_MYOBJECTS']);
	?><pre><? print_r($tempArr)?></pre><?
	$objectsIDs = array();
	for($i = 0;$i < count($tempArr) - 1;$i++) {
		$tempArr2 = explode('-', $tempArr[$i]);
		$objectsIDs[] = $tempArr2[1];
	}
}*/


$objectsIDs = array_filter($objectsIDs);
if (count($objectsIDs) == 0) {
	$objectsIDs = false;
}

if(empty($_REQUEST['IBLOCK_ID'])) {
	$IBLOCK_ID = array(7, 10, 8, 13, 11, 9, 12);
} else {
	$IBLOCK_ID = $_REQUEST['IBLOCK_ID'];
}


$IBLOCKS = array();
$arSelect = Array();
$arFilter = Array('TYPE'=> 'catalog', 'ACTIVE'=>'Y', 'GLOBAL_ACTIVE'=>'Y');
$res = CIBlock::GetList(Array('SORT'=>'ASC'), $arFilter, true, $arSelect);
while($item = $res->GetNext()) {
	$IBLOCKS[] = $item;
	$IBLOCK_COUNTS[$item['ID']]['SELL']['COUNTER'] = 0;
}

$arSelect = Array("ID", "NAME", "IBLOCK_ID");
$arFilter = Array("IBLOCK_ID" => array(7, 10, 8, 13, 11, 9),/* 'ID' => $objectsIDs,*/
		
		array(
		"LOGIC" => "OR",
		"PROPERTY_realtor"=>$USER->GetID(),
		"CREATED_BY" => $USER->GetID(),
				),);
$objectsIDs = array();
$res = CIBlockElement::GetList(Array(), $arFilter, false, /*Array("nPageSize"=>50)*/false, $arSelect);
while($ob = $res->Fetch()) {
	$objectsIDs[] = $ob["ID"];
	$id = $ob['IBLOCK_ID'];
	switch($id) {
		case 7:  $IBLOCK_COUNTS[7]['SELL']['COUNTER']++;  break;
		case 8:  $IBLOCK_COUNTS[8]['SELL']['COUNTER']++;  break;
		case 9:  $IBLOCK_COUNTS[9]['SELL']['COUNTER']++;  break;
		case 10: $IBLOCK_COUNTS[10]['SELL']['COUNTER']++; break;
		case 11: $IBLOCK_COUNTS[11]['SELL']['COUNTER']++; break;
		case 12: $IBLOCK_COUNTS[12]['SELL']['COUNTER']++; break;
		case 13: $IBLOCK_COUNTS[13]['SELL']['COUNTER']++; break;
		default: break;
	}
}
if (count($objectsIDs) <= 0)
{
	$objectsIDs = false;
}
/*$arSelect = Array("ID", "NAME", "IBLOCK_ID");

$arFilter = Array("IBLOCK_ID" => array(7, 10, 8, 13, 11, 9), 'ID' => $objectsIDs, "ACTIVE"=>"Y", '!SECTION_ID' => null);
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
while($ob = $res->Fetch()) {

	$id = $ob['IBLOCK_ID'];
	switch($id) {
		case 7:  $IBLOCK_COUNTS[7]['AREND']['COUNTER']++;  break;
		case 8:  $IBLOCK_COUNTS[8]['AREND']['COUNTER']++;  break;
		case 9:  $IBLOCK_COUNTS[9]['AREND']['COUNTER']++;  break;
		case 10: $IBLOCK_COUNTS[10]['AREND']['COUNTER']++; break;
		case 11: $IBLOCK_COUNTS[11]['AREND']['COUNTER']++; break;
		case 12: $IBLOCK_COUNTS[12]['AREND']['COUNTER']++; break;
		case 13: $IBLOCK_COUNTS[13]['AREND']['COUNTER']++; break;
		default: break;
	}
}*/



$GLOBALS['IBLOCKS'] = $IBLOCKS;
$GLOBALS['objectsIDs'] = $objectsIDs;
$GLOBALS['IBLOCK_ID'] = $IBLOCK_ID;
$GLOBALS['IBLOCK_COUNTS'] = $IBLOCK_COUNTS;

?>
