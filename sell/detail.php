<?
define("CATALOG_DETAIL_PAGE", true);
require( $_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php" ); ?>
<?
$url = explode('/', $_SERVER['REQUEST_URI']);
$urls =  str_replace('/', '', $url[3]);

$isCatalogItem = false;


//элемент каталога ли /sell/#CATALOG#/#ELEMENT_ID#/
if (!empty( $_REQUEST['ID'] )) {
	$isCatalogItem = true;
	$catalogID     = $_REQUEST['ID'];
}
if($urls != $catalogID && !strstr($_SERVER['REQUEST_URI'] , '?') || count($url) > 5) {
	header("HTTP/1.0 404 Not Found\r\n");
	@define("ERROR_404","Y");
}

//если /sell/some_catalog/#ELEMENT_ID#/
if ($isCatalogItem) {
	$APPLICATION->IncludeComponent("wexpert:iblock.detail", "sell_catalog_item", Array(
		//"ORDER"                             => array($_GET["SORT_BY"] => $_GET["SORT_ORDER"]),
		//"ORDER"                             => array('PROPERTY_price' => 'asc'),
		//"FILTER"                            => $arrFilter,
		"FILTER" => array("!PROPERTY_IS_ACCEPTED"=>false),
		"ID"           => $catalogID,
		"IBLOCK_ID"     => $GLOBALS['CATALOG_IBLOCKS_ARRAY'],
		"SELECT"       => array('SHOW_COUNTER', 'DATE_CREATE',"DETAIL_PICTURE","CREATED_BY"),
		"GET_PROPERTY" => "Y",
		"INCLUDE_SEO"=> "N",
		"SET_404"  => "Y",
		//"CACHE_TIME"    => 3600 * 24 * 10
	));
}
?>

<? require( $_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php" ); ?>