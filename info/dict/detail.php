<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if($_REQUEST['ajax']=='Y'){
   $APPLICATION->RestartBuffer();
   ?><div class="close"></div><?
   $APPLICATION->IncludeComponent("bitrix:news.detail","dictajax",Array(
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
   		"AJAX_MODE" => "Y",
   		"IBLOCK_TYPE" => "info",
   		"IBLOCK_ID" => "2",
   		"ELEMENT_ID" => $_REQUEST["ELEMENT_ID"],
   		"ELEMENT_CODE" => "",
   		"CHECK_DATES" => "Y",
   		"FIELD_CODE" => Array("ID"),
   		"PROPERTY_CODE" => Array("DESCRIPTION"),
   		"IBLOCK_URL" => "/info/dict/",
   		"DETAIL_URL" => "",
   		"SET_TITLE" => "N",
   		"SET_CANONICAL_URL" => "Y",
   		"SET_BROWSER_TITLE" => "Y",
   		"BROWSER_TITLE" => "-",
   		"SET_META_KEYWORDS" => "Y",
   		"META_KEYWORDS" => "-",
   		"SET_META_DESCRIPTION" => "Y",
   		"META_DESCRIPTION" => "-",
   		"SET_STATUS_404" => "Y",
   		"SET_LAST_MODIFIED" => "Y",
   		"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
   		"ADD_SECTIONS_CHAIN" => "Y",
   		"ADD_ELEMENT_CHAIN" => "N",
   		"ACTIVE_DATE_FORMAT" => "d.m.Y",
   		"USE_PERMISSIONS" => "N",
   		"GROUP_PERMISSIONS" => Array("1"),
   		"CACHE_TYPE" => "A",
   		"CACHE_TIME" => "3600",
   		"CACHE_GROUPS" => "Y",
   		"DISPLAY_TOP_PAGER" => "Y",
   		"DISPLAY_BOTTOM_PAGER" => "Y",
   		"PAGER_TITLE" => "Страница",
   		"PAGER_TEMPLATE" => "",
   		"PAGER_SHOW_ALL" => "Y",
   		"PAGER_BASE_LINK_ENABLE" => "Y",
   		"SET_STATUS_404" => "Y",
   		"SHOW_404" => "Y",
   		"MESSAGE_404" => "",
   		"PAGER_BASE_LINK" => "",
   		"PAGER_PARAMS_NAME" => "arrPager",
   		"AJAX_OPTION_JUMP" => "N",
   		"AJAX_OPTION_STYLE" => "Y",
   		"AJAX_OPTION_HISTORY" => "N"
   )
   );
}
if($_REQUEST['ajax']=='Y'){
   die();
}
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>