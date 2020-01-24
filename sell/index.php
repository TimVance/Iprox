<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<?
$bAjax = ($_REQUEST["ajax"] === "Y") && (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');


$sectionType = 'Продажа ';
$APPLICATION->SetTitle($sectionType . "недвижимости в Сочи");
CModule::IncludeModule("iblock");
$isCatalog = false;


//определяем шаблон отображения

//определяем каталог ли /sell/#CATALOG#/
if(!empty($_REQUEST['catalog'])) {
	$isCatalog = true;
	$catalogType = $_REQUEST['catalog'];
}
$SECTION_ID = $_REQUEST["SECTION_ID"];
?>
<div class="rielters temp-hidden">Новостройки</div>
<div class="rielters temp-hidden"><a href="javascript:void(0);">Вторичка</a></div>
<?
if ($isCatalog)
{
	
	$APPLICATION->IncludeComponent(
		"bitrix:catalog.smart.filter",
		"catalog_newstyle_filter",
		array(
			"COMPONENT_TEMPLATE"    => ".default",
			"IBLOCK_TYPE"           => "catalog",
			"IBLOCK_ID"             => \Morealty\Catalog::getIblockByCode($catalogType),
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
	?>
	<?
	$APPLICATION->IncludeComponent("wexpert:includer", "sort_catalog", array(
		"IBLOCK_ID"             => \Morealty\Catalog::getIblockByCode($catalogType),
		"FILTER_NAME"           => "arrFilter",
		"PROPERTY_FILTER"		=> "realtor",
		
	));
	?>
	
	<?
}
else if ($isCatalog)
{
	$APPLICATION->IncludeComponent(
			"bitrix:catalog.smart.filter",
			"catalog_filter",
			array(
					"COMPONENT_TEMPLATE"    => ".default",
					"IBLOCK_TYPE"           => "catalog",
					"IBLOCK_ID"             => \Morealty\Catalog::getIblockByCode($catalogType),
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
			array(),
			false
			);
}
?>
<? if ($bAjax)
{
	$APPLICATION->RestartBuffer();
}?>

<div class="sell-section-ajaxed">
<?
//сортировка в корневом /sell/
/*$APPLICATION->IncludeComponent('wexpert:includer', "sort_in_sell",
	array("ADDED_CLASS"=>"sell-items-corrupter"),
	false
);*/?>

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

		$arSelect = Array("ID", "NAME", "IBLOCK_ID", "PROPERTY_title_in_genitive_case");
		$arFilter = Array("IBLOCK_ID" => 21, "CODE" => $catalogType, "ACTIVE"=>"Y");

		$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
		while($ob = $res->Fetch()) {
			$catalogElementsNameForTitle = $ob['PROPERTY_TITLE_IN_GENITIVE_CASE_VALUE'];
		}

		//settitle
		$APPLICATION->SetTitle($sectionType . $catalogElementsNameForTitle . " в Сочи");

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
			if ($SECTION_ID)
			{
				$arFilter["SECTION_ID"] = $SECTION_ID;
				$arFilter["INCLUDE_SUBSECTIONS"] = "Y";
			}
			$arFilter = array_merge($arFilter, (array) $GLOBALS["arrFilter"]);
			
			$APPLICATION->IncludeComponent("wexpert:iblock.list", "catalog_items", Array(
				"ORDER"                             => \Morealty\Catalog::getCurrentSort(),
				"FILTER"                            => $arFilter,
				"IBLOCK_ID"							=> $catalogID,
				//"USE_GETNEXTELEMENT"				=> true,
				"PAGESIZE"						    => 20,
				"SELECT"						    => array(),
				"GET_PROPERTY"						=> "Y",
				//"NAV_TEMPLATE"						=> "morealty",
				"CACHE_TIME"    => 3600,
				"TEMPLATE_THEME" => \Morealty\Catalog::getCatalogViewType(),
				"ALWAYS_INCLUDE_TEMPLATE" => "Y"
			));
		}
	}

?>
</div>
<? if ($bAjax)
{
	die();
}?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>