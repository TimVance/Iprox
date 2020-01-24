<?
require( $_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php" );
$APPLICATION->SetPageProperty("title", "Однокомнатные квартиры в новостройках Сочи: купить на портале «Мореалти»");
$APPLICATION->SetPageProperty("description", "Купить однокомнатную квартиру в новостройке в Сочи. Продажа однокомнатных квартир в новостройках Сочи на портале недвижимости «Мореалти».");
$APPLICATION->SetTitle("Однокомнатные квартиры в новостройках");
?>

<?
//определяем каталог ли /sell/#CATALOG#/
$isCatalog = false;
$catalogType = "flat";

$SECTION_ID = 51;

//сортировка в корневом /sell/
$APPLICATION->IncludeComponent('wexpert:includer', "filter_under_header",
	array(),
	false
);
?>
<? if ($_REQUEST["ajax"] === "Y")
{
	$APPLICATION->RestartBuffer();
}?>

<div class="sell-section-ajaxed">
<?
//сортировка в корневом /sell/
$APPLICATION->IncludeComponent('wexpert:includer', "sort_in_sell",
	array("ADDED_CLASS"=>"sell-items-corrupter"),
	false
);?>

<?

	if(!$isCatalog) {
		$FilterCatalog = array($GLOBALS['FILTER_PROPERTY'] => $GLOBALS['FILTER_VALUES'], '!SECTION_ID' => array(1, 2, 3),"!PROPERTY_IS_ACCEPTED"=>false);
		$FilterCatalog["PROPERTY_living_block"] = false;
		if ($SECTION_ID)
		{
			$FilterCatalog["SECTION_ID"] = $SECTION_ID;
			$FilterCatalog["INCLUDE_SUBSECTIONS"] = "Y";
		}
		$APPLICATION->IncludeComponent("wexpert:iblock.list", $templateByView,Array(
			"ORDER"                             => array($_GET["SORT_BY"] => $_GET["SORT_ORDER"]),
			"FILTER"                            => $FilterCatalog,
			"IBLOCK_ID"							=> $GLOBALS['CATALOG_IBLOCKS_ARRAY'],
			"PAGESIZE"						    => 20,
			"SELECT"						    => "DATE_CREATE",
			"GET_PROPERTY"						=> "Y",
			"CACHE_TIME"    => 3600
		));

	}
	else if (!empty($catalogType)) {
		CModule::IncludeModule('iblock');
		$res = CIBlock::GetList(array('SORT'=>'ASC'), array('TYPE'=> 'catalog', 'ACTIVE'=>'Y', 'GLOBAL_ACTIVE'=>'Y'), true, array('ID', 'NAME', 'ELEMENTS_NAME'));
		while ($ob = $res->GetNext()) {
			if($ob['CODE'] == $catalogType) {
				$catalogID = $ob['ID'];
			}
		}

		$arFilter = array();
		$arFilter[$GLOBALS['FILTER_PROPERTY']] = $GLOBALS['FILTER_VALUES'];

		
		if($_REQUEST['is_find_estate'] == 'true') {
			
			$arAddFilter = $APPLICATION->IncludeComponent('morealty:catch_main_filter', "",
					array(),
					false
			);
		/*	$types[] = ($_REQUEST['buy_type1'] == 1) ? 'Вторичка' : '';
			$types[] = ($_REQUEST['buy_type2'] == 1) ? 'Новостройки' : '';
			$types[] = ($_REQUEST['buy_type3'] == 1) ? 'Ипотека' : '';

			if($_REQUEST['currency'] == 'rub') {
				$currency = 'Рубли';
				} else if($_REQUEST['currency'] == 'usd') {
				$currency = 'Доллары';
				} else if($_REQUEST['currency'] == 'eur') {
				$currency = 'Евро';
			}

			$_REQUEST['rooms_number'] = explode(',', $_REQUEST['rooms_number']);

			$arFilter = array(
				'!SECTION_ID' => array(1, 2, 3),
				$GLOBALS['FILTER_PROPERTY'] => $GLOBALS['FILTER_VALUES'],
				'>PROPERTY_price'  => $_REQUEST['price_from'],
				'<PROPERTY_price'  => $_REQUEST['price_to'],
				'>PROPERTY_square' => $_REQUEST['square_from'],
				'<PROPERTY_square' => $_REQUEST['square_to'],
				'IBLOCK_ID' => $_REQUEST['iblock_id'],
				'PROPERTY_room_number' => $_REQUEST['rooms_number'],
				'PROPERTY_currency_VALUE' => $currency,
				'PROPERTY_estate_type_VALUE' => $types,
			);*/
			$arFilter = array($GLOBALS['FILTER_PROPERTY'] => $GLOBALS['FILTER_VALUES'], '!SECTION_ID' => array(1, 2, 3),"!PROPERTY_IS_ACCEPTED"=>false);
			if (count($arAddFilter) > 0)
			{
				$arFilter = array_merge($arFilter,$arAddFilter);
			}
		}

		if (trim($catalogType) != '' && intval($catalogID) == 0) {
			@include_once($_SERVER["DOCUMENT_ROOT"] . "/404_inc.php");
		} else {
			$arFilter["!PROPERTY_IS_ACCEPTED"] = false;
			$arFilter["PROPERTY_living_block"] = false;
			if ($USER->GetLogin() == "vadim")
			{
				my_print_r("TEMPLATE :".$templateByView);
			}
			if ($SECTION_ID)
			{
				$arFilter["SECTION_ID"] = $SECTION_ID;
				$arFilter["INCLUDE_SUBSECTIONS"] = "Y";
			}
			$APPLICATION->IncludeComponent("wexpert:iblock.list", $templateByView, Array(
				"ORDER"                             => array($_GET["SORT_BY"] => $_GET["SORT_ORDER"],$_GET["SORT_BY"] => $_GET["SORT_ORDER"]),
				"FILTER"                            => $arFilter,
				"IBLOCK_ID"							=> $catalogID,
				"PAGESIZE"						    => 20,
				"SELECT"						    => "DATE_CREATE",
				"GET_PROPERTY"						=> "Y",
				//"NAV_TEMPLATE"						=> "morealty",
				"CACHE_TIME"    => 3600
			));
		}
	}

?>
</div>
<? if ($_REQUEST["ajax"] === "Y")
{
	die();
}?>

<? require( $_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php" ); ?>