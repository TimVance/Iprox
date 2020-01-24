<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<?

$isCatalogItem = false;


//элемент каталога ли /sell/#CATALOG#/#ELEMENT_ID#/
if(!empty($_REQUEST['ID'])) {
	$isCatalogItem = true;
	$catalogID = $_REQUEST['ID'];
}



//если /sell/some_catalog/#ELEMENT_ID#/
if($isCatalogItem) {
	$APPLICATION->IncludeComponent("wexpert:iblock.detail","sell_catalog_item",Array(
		//"ORDER"                             => array($_GET["SORT_BY"] => $_GET["SORT_ORDER"]),
		//"ORDER"                             => array('PROPERTY_price' => 'asc'),
		//"FILTER"                            => $arrFilter,
		"ID"							=> $catalogID,
		"GET_PROPERTY"						=> "Y",
		//"CACHE_TIME"    => 3600 * 24 * 10
	));
}
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>