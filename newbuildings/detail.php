<? require( $_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php" ); ?>
<?
$url = explode('/', $_SERVER['REQUEST_URI']);
$urls =  str_replace('/', '', $url[2]);


$isCatalogItem = false;

//элемент каталога ли /sell/#CATALOG#/#ELEMENT_ID#/
if (!empty( $_REQUEST['ID'] )) {
	$isCatalogItem = true;
	$ELEMENT_ID     = $_REQUEST['ID'];
	
}

/**
 * @todo А это собстна зачем?
 * 
 */
if(($urls != $ELEMENT_ID && (!strstr($_SERVER['REQUEST_URI'] , '?'))) || (!empty($url[3]) && $url[3] != "questions" && $url[3] != "plan") && (!strstr($_SERVER['REQUEST_URI'] , '?')) ) {
	
	header("HTTP/1.0 404 Not Found\r\n");
	@define("ERROR_404","Y");
}

$PAGE = $_REQUEST['PAGE'];
//если /sell/some_catalog/#ELEMENT_ID#/

if (empty($PAGE)) {
	$APPLICATION->IncludeComponent("wexpert:iblock.detail", "newbuildings", Array(
		//"ORDER"                             => array($_GET["SORT_BY"] => $_GET["SORT_ORDER"]),
		//"ORDER"                             => array('PROPERTY_price' => 'asc'),
		//"FILTER"                            => $arrFilter,
		"ID"           => $ELEMENT_ID,
		"SELECT"       => array('SHOW_COUNTER'),
		"GET_PROPERTY" => "Y",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME"    => 0, //вернуть
		"SHOW_PROPERTY" => array(
			"class", "type", "floors", "ceilings_height",
			"decoration", "parking", "distance_to_sea", "lift", "square_ot", "square_do",
			"price_flat_min", "price_m2_ot", "price_m2_do", "deadline"),
		"SHOW_EMPTY_PROPERTY" => 'Y',
		"SET_404"  => "Y",
	));
} else if($PAGE == 'questions'){
	$APPLICATION->IncludeComponent("wexpert:iblock.detail", "newbuildings_questions", Array(
		"ID"           => $ELEMENT_ID,
		"SELECT"       => array('SHOW_COUNTER'),
		"GET_PROPERTY" => "Y",
		"CACHE_TIME"    => 0,
		"SHOW_PROPERTY" => array(
				"class", "type", "floors", "ceilings_height",
				"decoration", "parking", "distance_to_sea", "lift", "square_ot", "square_do",
				"price_flat_min", "price_m2_ot", "price_m2_do", "deadline"),
				"SHOW_EMPTY_PROPERTY" => 'Y',
				"SET_404"  => "Y",
	));
}
else if($PAGE == 'plan') {
	$APPLICATION->IncludeComponent("wexpert:iblock.detail", "newbuildings_plan", Array(
		"ID"           => $ELEMENT_ID,
		"SELECT"       => array('SHOW_COUNTER'),
		"GET_PROPERTY" => "Y",
		"CACHE_TIME"    => 3600,
		"INCLUDE_SEO"	=> 'N',
		"SET_404"  => "Y",
	));
}
?>

<? require( $_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php" ); ?>