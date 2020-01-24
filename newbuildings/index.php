<?
require( $_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php" );
$sectionType = 'Продажа ';
$APPLICATION->SetTitle($sectionType . "недвижимости в Сочи");

$bAjax = ( $_REQUEST["ajax"] === "Y" ) && ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' );
?>

<?
CModule::IncludeModule('iblock');

$res = CIBlock::GetList(array('SORT' => 'ASC'), array('TYPE' => 'catalog', 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y'), true, array('ID', 'NAME', 'ELEMENTS_NAME'));
while ($ob = $res->GetNext()) {
	if ($ob['CODE'] == "newbuildings") {
		$catalogID = $ob['ID'];
	}
}

$arSelect = Array("ID", "NAME", "IBLOCK_ID", "PROPERTY_title_in_genitive_case");
$arFilter = Array("IBLOCK_ID" => 21, "CODE" => "newbuildings", "ACTIVE" => "Y");
$res      = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while ($ob = $res->Fetch()) {
	$catalogElementsNameForTitle = $ob['PROPERTY_TITLE_IN_GENITIVE_CASE_VALUE'];
	$APPLICATION->SetTitle($sectionType . $catalogElementsNameForTitle . " в Сочи");
}


//сортировка в корневом /sell/
if (true)
{
	$APPLICATION->IncludeComponent(
			"bitrix:catalog.smart.filter",
			"catalog_filter",
			array(
					"ADDITION_FIELDS"		=> \Morealty\Catalog::getNewbuildingAdditionFilterFields(),
					"COMPONENT_TEMPLATE"    => ".default",
					"IBLOCK_TYPE"           => "catalog",
					"IBLOCK_ID"             => 19,
					"SECTION_ID"            => $SECTION_ID,
					"SECTION_CODE"          => "",
					"FILTER_NAME"           => "arrFilter",
					"HIDE_NOT_AVAILABLE"    => "N",
					"TEMPLATE_THEME"        => "blue",
					"FILTER_VIEW_MODE"      => "horizontal",
					"DISPLAY_ELEMENT_COUNT" => "Y",
					"SEF_MODE"              => "Y",
					"CACHE_TYPE"            => "N",
					"CACHE_TIME"            => "36000000",
					"CACHE_GROUPS"          => "Y",
					"SAVE_IN_SESSION"       => "N",
					"INSTANT_RELOAD"        => "Y",
					"PAGER_PARAMS_NAME"     => "arrPager",
					"PRICE_CODE"            => array(
							0 => "BASE",
					),
					"CONVERT_CURRENCY"      => "Y",
					"XML_EXPORT"            => "N",
					"SECTION_TITLE"         => "-",
					"SECTION_DESCRIPTION"   => "-",
					"POPUP_POSITION"        => "left",
					"SEF_RULE"              => "/sell/map/filter/#SMART_FILTER_PATH#/apply/",
					"SECTION_CODE_PATH"     => "",
					"SMART_FILTER_PATH"     => $_REQUEST["SMART_FILTER_PATH"],
					"CURRENCY_ID"           => "RUB",
					"CUR_PAGE"              => $APPLICATION->GetCurPage(false),
			),
			false
			);
}
else 
{
	$APPLICATION->IncludeComponent('wexpert:includer', "filter_under_header",
			array("TIS_NEWBUILD" => "Y"),
			false
			);
}

?>
<?
if ($bAjax) {
	$APPLICATION->RestartBuffer();
} ?>

	<div class="sell-section-ajaxed">
		<?
		//сортировка в корневом /sell/
		$APPLICATION->IncludeComponent('wexpert:includer', "sort_in_sell",
			array("ADDED_CLASS" => "sell-items-corrupter", "NEWBUILD" => "Y"),
			false
		); ?>

		<?
		CModule::IncludeModule('iblock');
		$res = CIBlock::GetList(array('SORT' => 'ASC'), array('TYPE' => 'catalog', 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y'), true, array('ID', 'NAME', 'ELEMENTS_NAME'));
		while ($ob = $res->GetNext()) {
			if ($ob['CODE'] == $catalogType) {
				$catalogID = $ob['ID'];
			}
		}
		//settitle


		$arFilter                              = array();
		$arFilter[$GLOBALS['FILTER_PROPERTY']] = $GLOBALS['FILTER_VALUES'];
		if (intval($_REQUEST["BUILDER"]) > 0) {
			$arFilter["PROPERTY_BUILDER"] = intval($_REQUEST["BUILDER"]);
		}

		if ($_REQUEST['is_find_estate'] == 'true') {
			$types[] = ( $_REQUEST['buy_type1'] == 1 ) ? 'Вторичка' : '';
			$types[] = ( $_REQUEST['buy_type2'] == 1 ) ? 'Новостройки' : '';
			$types[] = ( $_REQUEST['buy_type3'] == 1 ) ? 'Ипотека' : '';

			if ($_REQUEST['currency'] == 'rub') {
				$currency = 'Рубли';
			} else if ($_REQUEST['currency'] == 'usd') {
				$currency = 'Доллары';
			} else if ($_REQUEST['currency'] == 'eur') {
				$currency = 'Евро';
			}

			$_REQUEST['rooms_number'] = explode(',', $_REQUEST['rooms_number']);

			$arFilter = array(
				'!SECTION_ID'                => array(1, 2, 3),
				$GLOBALS['FILTER_PROPERTY']  => $GLOBALS['FILTER_VALUES'],
				'>PROPERTY_price'            => $_REQUEST['price_from'],
				'<PROPERTY_price'            => $_REQUEST['price_to'],
				'>PROPERTY_square'           => $_REQUEST['square_from'],
				'<PROPERTY_square'           => $_REQUEST['square_to'],
				'IBLOCK_ID'                  => $_REQUEST['iblock_id'],
				'PROPERTY_room_number'       => $_REQUEST['rooms_number'],
				'PROPERTY_currency_VALUE'    => $currency,
				'PROPERTY_estate_type_VALUE' => $types,
			);
		}
		$isAllow404 = true;
		if ($_REQUEST["arrFilter_NAME"]) {
			$arFilter["?NAME"] = $_REQUEST["arrFilter_NAME"];
			$isAllow404        = false;
		} else if ($_REQUEST["FILTER_PROPERTY"] == "><PROPERTY_square") {
			$arFilter[] = array(
				"LOGIC"                => "OR",
				"><PROPERTY_square_ot" => explode(",", $_REQUEST["FILTER_VALUE"]),
				"><PROPERTY_square_do" => explode(",", $_REQUEST["FILTER_VALUE"]),
			);
			unset($arFilter["><PROPERTY_square"]);
		}
		if ($_REQUEST["SORT_BY"] == "PROPERTY_price") {
			$_REQUEST["SORT_BY"] = "PROPERTY_price_flat_min";
		}
		if ($_REQUEST["SORT_BY"] == "PROPERTY_price_1m") {
			$_REQUEST["SORT_BY"] = "PROPERTY_price_m2_ot";
		}
		if ($_REQUEST["SORT_BY"] == "PROPERTY_square") {
			$_REQUEST["SORT_BY"] = "PROPERTY_square_ot";
		}
		$arFilter = array_merge($arFilter,(array) $GLOBALS["arrFilter"]);
		
		if (trim($catalogType) != '' && intval($catalogID) == 0) {
			@include_once( $_SERVER["DOCUMENT_ROOT"] . "/404_inc.php" );
		} else {


			$templateByView = ( $_REQUEST["VIEW_TYPE"] === "tiles" || $_SESSION['VIEW_TYPE'] === 'tiles' ) ? "newbuildings_view_tiles" : "newbuildings";
			$APPLICATION->IncludeComponent("wexpert:iblock.list", $templateByView, Array(
				"ORDER"                   => array(strtoupper($_REQUEST["SORT_BY"]) => $_REQUEST["SORT_ORDER"]),
				"FILTER"                  => $arFilter,
				"IBLOCK_ID"               => 19,
				"PAGESIZE"                => 12,
				"GET_PROPERTY"            => "Y",
				"ALWAYS_INCLUDE_TEMPLATE" => "Y",
				//"NAV_TEMPLATE" => "morealty",
				"CACHE_TIME"              => 3600,
				"SET_404"                 => "N",
			));
		}
		?>
	</div>
<? if ($bAjax) {
	die();
}
?>

<? require( $_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php" ); ?>