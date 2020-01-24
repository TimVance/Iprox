<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Офис продаж ЖК &ldquo;Люксембург&rdquo;");

?>

<?
$sectionId = $APPLICATION->IncludeComponent("bitrix:news.detail", "builders_element", Array(
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"USE_SHARE" => "Y",
		"SHARE_HIDE" => "N",
		"SHARE_TEMPLATE" => "",
		"SHARE_HANDLERS" => array("delicious"),
		"SHARE_SHORTEN_URL_LOGIN" => "",
		"SHARE_SHORTEN_URL_KEY" => "",
		"AJAX_MODE" => "N",
		"IBLOCK_TYPE" => "groups",
		"IBLOCK_ID" => "4",
		"ELEMENT_ID" => $_REQUEST["ID"],
		"ELEMENT_CODE" => "",
		//"CHECK_DATES" => "Y",
		"FIELD_CODE" => Array("ID", "PREVIEW_PICTURE", "DETAIL_PICTURE"),
		"PROPERTY_CODE" => Array("DESCRIPTION", "PREVIEW_PICTURE", "DETAIL_PICTURE"),
		//"IBLOCK_URL" => "news.php?ID=#IBLOCK_ID#\"",
		"DETAIL_URL" => "",
		"SET_TITLE" => "Y",
		"SET_CANONICAL_URL" => "Y",
		"SET_BROWSER_TITLE" => "Y",
		"BROWSER_TITLE" => "-",
		"SET_META_KEYWORDS" => "Y",
		"META_KEYWORDS" => "-",
		"SET_META_DESCRIPTION" => "Y",
		"META_DESCRIPTION" => "-",
		//"SET_STATUS_404" => "Y",
		"SET_LAST_MODIFIED" => "Y",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"ADD_ELEMENT_CHAIN" => "Y",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		//"USE_PERMISSIONS" => "Y",
		//"GROUP_PERMISSIONS" => Array("1"),
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		//"CACHE_GROUPS" => "Y",
		"DISPLAY_TOP_PAGER" => "Y",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Страница",
		"PAGER_TEMPLATE" => "",
		"PAGER_SHOW_ALL" => "Y",
		"PAGER_BASE_LINK_ENABLE" => "Y",
		"SET_STATUS_404" => "Y",
		"SHOW_404" => "Y",
		//"MESSAGE_404" => "",
		"PAGER_BASE_LINK" => "",
		"PAGER_PARAMS_NAME" => "arrPager",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CHILD_ORDER_TYPE"		=> $_REQUEST["SORT_BY"],
		"CHILD_ORDER_ORDER"		=> $_REQUEST["SORT_ORDER"],
	)
);
$url = explode('/', $_SERVER['REQUEST_URI']);
$urls =  str_replace('/', '', $url[2]);

if($urls != $sectionId && !strstr($urls , '?') || count($url) >4) {
	header("HTTP/1.0 404 Not Found\r\n");
	@define("ERROR_404","Y");
}
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>