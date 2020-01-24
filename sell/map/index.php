<?
require( $_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php" );
$APPLICATION->SetTitle("Продажа квартир в Сочи");

?>
<?
if ($_REQUEST["square_from"] && intval($_REQUEST["square_from"]) > 0) {
	$_GET["arrFilter_157_MIN"] = intval($_REQUEST["square_from"]);
}
if ($_REQUEST["square_to"] && intval($_REQUEST["square_to"]) > 0) {
	$_GET["arrFilter_157_MAX"] = intval($_REQUEST["square_to"]);
}
if ($_REQUEST["price_from"] && intval($_REQUEST["price_from"]) > 0) {
	$_GET["arrFilter_88_MIN"] = intval($_REQUEST["price_from"]);
}
if ($_REQUEST["price_to"] && intval($_REQUEST["price_to"]) > 0) {
	$_GET["arrFilter_88_MAX"] = intval($_REQUEST["price_to"]);
}
//arrFilter_88_MIN
//price_from
?>


<? $APPLICATION->IncludeComponent(
	"bitrix:catalog.smart.filter",
	"catalog_newstyle_filter",
	array(
		"COMPONENT_TEMPLATE"    => ".default",
		"IBLOCK_TYPE"           => "catalog",
		"IBLOCK_ID"             => "7",
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
			"PROPERTY_PRICE"    => "Цена квартиры",
			"PROPERTY_price_1m" => "Цена м<sup>2</sup>",
			"PROPERTY_square"   => "Площадь",
			"created"           => "Дата добавления"
		)
	),
	false
); */?>

<?$APPLICATION->IncludeComponent("wexpert:includer", "sort_catalog", array(
	"IBLOCK_ID"             => 7,
	"FILTER_NAME"           => "arrFilter",
	"PROPERTY_FILTER"		=> "realtor",
	"ISMAP"	=> "Y"
));
?>


<?
if (true)
{
	
	$APPLICATION->IncludeComponent("morealty:catalog_flat_map", "", array(
		"FILTER" => $GLOBALS["arrFilter"],
		"CACHE_TYPE" => "Y",
		"CACHE_TIME" => 3600,
		"IBLOCK_ID" => 7,
		"MAP_CENTER_POS"          => "43.656486087657,39.657800255294",
		"MAP_ZOOM"                => "9",
	));
	
	
	
}
else 
{
	$APPLICATION->IncludeComponent("wexpert:iblock.list", "sell-map", Array(
		"ORDER"                   => \Morealty\Catalog::getCurrentSort(),
		"FILTER"                  => $arrFilter,
		"IBLOCK_ID"               => 7,
		//"NTOPCOUNT"               => "300",
		"SELECT"                  => array(
			"ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", "PROPERTY_city.NAME", "PROPERTY_district.NAME", "PROPERTY_microdistrict.NAME", 'PROPERTY_street',
			"PROPERTY_newbuilding", "PROPERTY_NEWBUILDING.NAME", "PROPERTY_price", "PROPERTY_currency", "PROPERTY_yandex_map"
		),
		"GET_PROPERTY"            => "Y",
		"GET_ELEMENTS_FROM"       => $GLOBALS["CATALOG_NEWBUILDING"],
		"CACHE_TIME"              => 3600,
		"CACHE_TYPE"              => "A",
		"MAP_CENTER_POS"          => "43.656486087657,39.657800255294",
		"MAP_ZOOM"                => "12",
		"ALWAYS_INCLUDE_TEMPLATE" => "Y",
	)); 
}
?>


<? /* ?>
			<div class="rielters"><a href="#">Новостройки</a></div>
			<div class="rielters"><a href="#">Вторичка</a></div>
			
			  <div class="filter-line">
				<div class="f-visible">
				  <div class="sub_wrap">
					<div class="float_wrapper">
					  <div class="secs">
						<div class="sec">
						  <div class="search"></div>
						</div>
						<div class="sec">
						  <div class="sec-head">
							<span>Цена <strong>&nbsp;от 4 200 000 до 12 000 000</strong></span>
						  </div>
						  <div class="form slide-pop">
							<span class="label">Площадь</span>
							  <div class="field">
								<input type="number" min="10" name="from" placeholder="От">
								<input type="number" min="10" name="to" placeholder="До">
							  </div>
							  <span>м<sup>2</sup></span>
						  </div>
						</div>
						<div class="sec">
						  <div class="sec-head">
							<span>Тип квартиры</span>
						  </div>
						  <div class="form slide-pop">
							<span class="label">Площадь</span>
							  <div class="field">
								<input type="number" min="10" name="from" placeholder="От">
								<input type="number" min="10" name="to" placeholder="До">
							  </div>
							  <span>м<sup>2</sup></span>
						  </div>
						</div>
						<div class="sec">
						  <div class="sec-head">
							<span>Площадь</span>
						  </div>
						  <div class="form slide-pop">
							<span class="label">Площадь</span>
							  <div class="field">
								<input type="number" min="10" name="from" placeholder="От">
								<input type="number" min="10" name="to" placeholder="До">
							  </div>
							  <span>м<sup>2</sup></span>
						  </div>
						</div>
						<div class="sec">
						  <div class="sec-head">
							<span>Расположение</span>
						  </div>
						  <div class="form slide-pop">
							<span class="label">Площадь</span>
							  <div class="field">
								<input type="number" min="10" name="from" placeholder="От">
								<input type="number" min="10" name="to" placeholder="До">
							  </div>
							  <span>м<sup>2</sup></span>
						  </div>
						</div>
					  </div>
					  <a href="#" class="more">Еще детали</a>
				  </div>
				  </div>
				</div>
				<div class="f-toggle slide-pop">
				  <div class="sub_wrap"></div>
				</div>
			  </div>
			
			  <div class="sort-line">
				<div class="sub_wrap">
				  <div class="float_wrapper">
					<div class="items_wrap">
					  <span class="count">3 589 объектов</span>
					  <div class="items">
						<span class="items_label">Сортировка:</span>
						<ul class="items_list">
						  <li><strong><a href="#">Цена квартиры</a></strong></li>
						  <li><a href="#">Цена м<sup>2</sup></a></li>
						  <li><a href="#">Площадь</a></li>
						  <li><a href="#">Дата добавления</a></li>
						</ul>
					  </div>
					</div>
					<div class="more">
					  <a href="#" id="show_map_list">Список</a>
					</div>
				  </div>
				</div>
			  </div>
<?*/ ?>


<? require( $_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php" ); ?>