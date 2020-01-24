<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<?
$sectionType = 'Аренда ';
$APPLICATION->SetTitle($sectionType . "недвижимости в Сочи");
CModule::IncludeModule("iblock");
$isCatalog = false;

//определяем каталог ли /sell/#CATALOG#/
if(!empty($_REQUEST['catalog'])) {
	$isCatalog = true;
	$catalogType = $_REQUEST['catalog'];
}

?>
	<div class="rielters temp-hidden">Новостройки</div>
	<div class="rielters temp-hidden"><a href="#">Вторичка</a></div>

	<?
	//сортировка в корневом /sell/
	$APPLICATION->IncludeComponent('wexpert:includer', "filter_under_header",
		array('OK' => 'no'),
		false
	);?>
	<?
	//сортировка в корневном /sell/
	$APPLICATION->IncludeComponent('wexpert:includer', "sort_in_sell",
		array(
			'OK' => 'no'
		),
		false
	);?>

<?


if(!$isCatalog) {

	$arr = explode(',', CATALOG_IBLOCKS);

	$APPLICATION->IncludeComponent("wexpert:iblock.list",$templateByView,Array(
		"ORDER"                             => array($_GET["SORT_BY"] => $_GET["SORT_ORDER"]),
		"FILTER"                            => array($GLOBALS['FILTER_PROPERTY'] => $GLOBALS['FILTER_VALUES'], 'SECTION_ID' => array(1, 2, 3)),
		"IBLOCK_ID"							=> $arr,
		"PAGESIZE"						    => 4,
		"SELECT"						    => "DATE_CREATE",
		"GET_PROPERTY"						=> "Y",
		"CACHE_TIME"    => 3600 * 24 * 10
	));
}
else if (!empty($catalogType)) {
	CModule::IncludeModule('iblock');
	$res = CIBlock::GetList(array('SORT'=>'ASC'), array('TYPE'=> 'catalog', 'ACTIVE'=>'Y', 'GLOBAL_ACTIVE'=>'Y'), true, array('ID', 'NAME', 'ELEMENTS_NAME'));
	while ($ob = $res->GetNext()) {
		if($ob['CODE'] == $catalogType) {
			$catalogID = $ob['ID'];
			$catalogElementsNameForTitle = $ob['ELEMENTS_NAME'];
		}
	}
	//settitle
	$APPLICATION->SetTitle($sectionType . $catalogElementsNameForTitle . " в Сочи");

	$arFilter = array();
	$arFilter[$GLOBALS['FILTER_PROPERTY']] = $GLOBALS['FILTER_VALUES'];


	if($_REQUEST['is_find_estate'] == 'true') {
		$types[] = ($_REQUEST['buy_type1'] == 1) ? 'Вторичка' : '';
		$types[] = ($_REQUEST['buy_type2'] == 1) ? 'Новостройки' : '';
		$types[] = ($_REQUEST['buy_type3'] == 1) ? 'Ипотека' : '';

		if($_REQUEST['currency'] == 'rub') {
			$currency = 'Рубли';
		} else if($_REQUEST['currency'] == 'usd') {
			$currency = 'Доллары';
		} else if($_REQUEST['currency'] == 'eur') {
			$currency = 'Евро';
		}

		$arFilter = array(
			'SECTION_ID' => array(1, 2, 3),
			$GLOBALS['FILTER_PROPERTY'] => $GLOBALS['FILTER_VALUES'],
			'>PROPERTY_price' => $_REQUEST['price_from'],
			'<PROPERTY_price' => $_REQUEST['price_to'],
			'IBLOCK_ID' => $_REQUEST['iblock_id'],
			'PROPERTY_room_number' => $_REQUEST['rooms_number'],
			'PROPERTY_currency_VALUE' => $currency,
			'PROPERTY_estate_type_VALUE' => $types,
		);

	}

	if (trim($catalogType) != '' && intval($catalogID) == 0) {
		@include_once($_SERVER["DOCUMENT_ROOT"] . "/404_inc.php");
	} else {
		$APPLICATION->IncludeComponent("wexpert:iblock.list",$templateByView,Array(
			"ORDER"                             => array($_GET["SORT_BY"] => $_GET["SORT_ORDER"]),
			"FILTER"                            => $arFilter,
			"IBLOCK_ID"							=> $catalogID,
			"PAGESIZE"						    => 4,
			"GET_PROPERTY"						=> "Y",
			"CHECK_PERMISSIONS" => "N",
			"CACHE_TIME"    => 3600 * 24 * 10,
		));
	}
}


?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>