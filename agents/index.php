<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Агентства недвижимости Сочи");
?>

<div class="rielters"><a href="/realtors/">Риэлторы</a></div>

<?$APPLICATION->IncludeComponent('wexpert:includer', 'search_in_catalog', array("IS_MAP" => $_REQUEST["map"], "LABEL" => "Название агенства, адрес"))?>
<?$APPLICATION->IncludeComponent('wexpert:includer', "sort_in_catalog",
	array(
		'OK' => 'no',
		"MAP_LINK" => ($_REQUEST["map"] != "Y")? "?map=Y" : false,
		"BACK_LINK"=> ($_REQUEST["map"] == "Y")? $APPLICATION->GetCurPage() : false,
	),
	false
);?>
<?

//поиск по имени или адресу
global $arrFilter;
$arrFilter = array(array(
	                   "LOGIC" => "OR",
	                   "%NAME" => $_GET["search"],
	                   "PROPERTY_ADDRESS" => $_GET["search"]
                   ));
$templateByView = ($_REQUEST["map"] == "Y")? "agents_map" : "agents";
$APPLICATION->IncludeComponent("bitrix:news.list",$templateByView,Array(
	"DISPLAY_DATE"						=> "N",
	"DISPLAY_NAME"						=> "Y",
	"DISPLAY_PICTURE"					=> "Y",
	"DISPLAY_PREVIEW_TEXT"				=> "N",
	"AJAX_MODE"							=> "N",
	"IBLOCK_TYPE"						=> "info",
	"IBLOCK_ID"							=> 3,
	"NEWS_COUNT"						=> ($_REQUEST["map"] == "Y")? false : 10,
	"SORT_BY1"							=> $_GET["SORT_BY"],
	"SORT_ORDER1"						=> $_GET["SORT_ORDER"],
	"FILTER_NAME"						=> "arrFilter",
	"FIELD_CODE"                        => Array("ID", "PREVIEW_PICTURE", "DETAIL_PICTURE"),
	"PROPERTY_CODE"						=> array("agents", "agents_section"),
	"CHECK_DATES"						=> "Y",
	"DETAIL_URL"						=> "",
	"PREVIEW_TRUNCATE_LEN"				=> "",
	"ACTIVE_DATE_FORMAT"				=> "d.m.Y",
	"SET_TITLE"							=> "N",
	"SET_STATUS_404"					=> "Y",
	"INCLUDE_IBLOCK_INTO_CHAIN"			=> "N",
	"ADD_SECTIONS_CHAIN"				=> "N",
	"HIDE_LINK_WHEN_NO_DETAIL"			=> "Y",
	"PARENT_SECTION"					=> "",
	"PARENT_SECTION_CODE" 				=> "",
	"INCLUDE_SUBSECTIONS"				=> "Y",
	"CACHE_TYPE"						=> "A",
	"CACHE_TIME"						=> 3600 * 24,
	"CACHE_FILTER"						=> "Y",
	"CACHE_GROUPS"						=> "N",
	"DISPLAY_TOP_PAGER"					=> "Y",
	"DISPLAY_BOTTOM_PAGER"				=> "Y",
	"PAGER_SHOW_ALWAYS"					=> "N",
	"PAGER_TEMPLATE"					=> "",
	//"PAGER_PARAMS_NAME"					=> "arNavParams",
	"PAGER_DESC_NUMBERING"				=> "N",
	"PAGER_DESC_NUMBERING_CACHE_TIME"   => 3600 * 24,
	"PAGER_SHOW_ALL"					=> "N",
	"AJAX_OPTION_JUMP"					=> "N",
	"AJAX_OPTION_STYLE"					=> "Y",
	"AJAX_OPTION_HISTORY"				=> "N",
	"AJAX_OPTION_ADDITIONAL"			=> "",
	"MAP_CENTER_POS"					=> "43.656486087657,39.657800255294",
	"MAP_ZOOM"							=> "14",
));
?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>