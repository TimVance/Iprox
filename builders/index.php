<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Застройщики Сочи, инвесторы");
?>

<div class="rielters"><a href="/newbuildings/">Новостройки</a></div>
<div class="rielters"><a href="/newbuildings/">Квартиры от застройщика</a></div>



<?$APPLICATION->IncludeComponent('wexpert:includer', 'search_in_catalog', array("FORM_NAME" => "builder_form"))?>
<?$APPLICATION->IncludeComponent('wexpert:includer', 'sort_in_catalog')?>


<?
$APPLICATION->IncludeComponent("bitrix:news.list","builders",Array(
	"DISPLAY_DATE"						=> "N",
	"DISPLAY_NAME"						=> "Y",
	"DISPLAY_PICTURE"					=> "Y",
	"DISPLAY_PREVIEW_TEXT"				=> "Y",
	"AJAX_MODE"							=> "N",
	"IBLOCK_TYPE"						=> "info",
	"IBLOCK_ID"							=> 4,
	"NEWS_COUNT"						=> 10,
	"SORT_BY1"							=> $_GET["SORT_BY"],
	"SORT_ORDER1"						=> $_GET["SORT_ORDER"],
	"FILTER_NAME"						=> "arrFilter",
	"FIELD_CODE"						=> array("ID"),
	"PROPERTY_CODE"						=> array("PREVIEW_PICTURE", "DETAIL_PICTURE"),
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
	"AJAX_OPTION_ADDITIONAL"			=> ""
));
?>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>