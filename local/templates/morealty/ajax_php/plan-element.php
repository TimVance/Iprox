<?if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') { die(); }
require ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");?>


<?	$APPLICATION->IncludeComponent("wexpert:iblock.detail", "plan-element",Array(
		"IBLOCK_ID"							=> 7,
		"SELECT"						    => array('ID', 'NAME', 'IBLOCK_ID', 'PROPERTY_*', 'DETAIL_PAGE_URL'),
		"GET_PROPERTY"						=> "Y",
		"CACHE_TIME"    					=> 3600,
		"CACHE_TYPE"						=> "Y",
		"ID"								=> $_REQUEST["ID"],
		"ALWAYS_INCLUDE_TEMPLATE"			=> "Y",
		"AJAX"								=> "Y",
		'FLOOR'								=> intval($_REQUEST["floor"]),
	)); ?>