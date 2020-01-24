<?
require( $_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php" );
$APPLICATION->SetTitle("Продажа недвижимости в Сочи");

?>
<?
$CurIb = GetIbByCode($_REQUEST["CODE"]);


?>


<?
if (intval($CurIb["ID"]) > 0) {
	?>
	<?
	$APPLICATION->IncludeComponent(
		"bitrix:catalog.smart.filter",
		"catalog_newstyle_filter",
		array(
			"COMPONENT_TEMPLATE"    => ".default",
			"IBLOCK_TYPE"           => "catalog",
			"IBLOCK_ID"             => $CurIb["ID"],
			"SECTION_ID"            => "",
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
			"MORE_INFO"             => array(
				"flat"       => array("NAME" => "Квартиры", "TOP" => "Y"),
				"houses"     => array("NAME" => "Дома, коттеджи, таунхаусы"),
				"complex"    => array("NAME" => "Коттеджные поселки и комплексы таунхаусов"),
				"land"       => array("NAME" => "Земельные участки"),
				"commercial" => array("NAME" => "Коммерческая недвижимость"),
				"markets"    => array("NAME" => "Торговые и бизнес-центры"),
				"projects"   => array("NAME" => "Инвестиционные проекты"),
			),
			"CUR_PAGE"              => $APPLICATION->GetCurPage(false),
			"ISMAP"	=> "Y"
		),
		false
	); ?>


	<?
	/*$arSortAr = $APPLICATION->IncludeComponent('morealty:sort', "",
		array(
			"SORT_FIELDS" => array(
				"PROPERTY_PRICE"    => "Цена",
				"PROPERTY_price_1m" => "Цена м<sup>2</sup>",
				"PROPERTY_square"   => "Площадь",
				"created"           => "Дата добавления"
			)
		),
		false
	); */?>
	
	
	<?$APPLICATION->IncludeComponent("wexpert:includer", "sort_catalog", array(
		"IBLOCK_ID"             => $CurIb["ID"],
		"FILTER_NAME"           => "arrFilter",
		"PROPERTY_FILTER"		=> "realtor",
		"ISMAP"	=> "Y"
	));
	?>
	<?
	
	$arrFilter = array_merge(array("ACTIVE" => "Y", "!PROPERTY_IS_ACCEPTED" => false), $arrFilter);

	$APPLICATION->IncludeComponent("wexpert:iblock.list", "sell-map-custom", Array(
		"ORDER"                   => $arSortAr,
		"FILTER"                  => $arrFilter,
		"IBLOCK_ID"               => $CurIb["ID"],
		//"NTOPCOUNT"               => "300",
		"SELECT"                  => array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", "PROPERTY_city.NAME", "PROPERTY_district.NAME", "PROPERTY_microdistrict.NAME", "PROPERTY_price", "PROPERTY_currency", "PROPERTY_YANDEX_MAP"),
		"GET_PROPERTY"            => "Y",
		"CACHE_TIME"              => 3600,
		"CACHE_TYPE"              => "A",
		"MAP_ZOOM"                => "12",
		"ALWAYS_INCLUDE_TEMPLATE" => "Y",
		"MAP_CENTER_POS"          => "43.656486087657,39.657800255294",
	)); ?>
	<?
} else {
	ShowError("Неверный параметр");
}
?>


<? require( $_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php" ); ?>