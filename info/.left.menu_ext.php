<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$aMenuLinks = \Wexpert\BitrixUtils\PhpCacher::returnCacheDataAndSave('left_menu_ext_info', 3600 * 24 * 5, function () {
	CModule::IncludeModule('iblock');
	$arSelect = Array('ID', 'NAME');
	$arFilter = Array('TYPE'=> 'info',"!ID"=>"29", 'ACTIVE'=>'Y', 'GLOBAL_ACTIVE'=>'Y');
	$res = CIBlock::GetList(Array('SORT'=>'ASC'), $arFilter, true, false, $arSelect);
	while($item = $res->GetNext()) {
		$ob[] = $item;
	}

	$myarr = array();
	foreach($ob as $object) {
		$newObject = '';
		$newObject[0] = $object['NAME'];
		$newObject[1] = '/info/' . $object['CODE'] . '/';
		$newObject[2] = Array();
		$newObject[3] = Array();
		$newObject[4] = "";
		if(!empty($object['NAME'])) {
			array_push($myarr, $newObject);
		}
	}
	return $myarr;
});
$aMenuLinks[] = array(
		"Онлайн оценка квартиры",
		"/cost/",
		Array(),
		Array(),
		""
);
?>