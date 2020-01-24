<?
//if(strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != strtolower('XMLHttpRequest')) { die(); }

require ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
?>
<?/* 
ob_start();
$PlanResult =  $APPLICATION->IncludeComponent("morealty:newbuildings.plan","", array("MOTHER_ID"=>intval($_REQUEST["parents"]),"CACHE_TIME"    => 3600));
ob_get_clean();*/
?>

<?
if ($_REQUEST["parents"])
{
	$filter["PROPERTY_newbuilding"] =  intval($_REQUEST["parents"]);
}
if ($_REQUEST["roomes_from"])
{
	$filter[">=PROPERTY_room_number"] = intval($_REQUEST["roomes_from"]);
}
if ($_REQUEST["roomes_to"])
{
	$filter["<=PROPERTY_room_number"] = intval($_REQUEST["roomes_to"]);
}
if ($_REQUEST["roomes"])
{
	$filter["=PROPERTY_room_number"] = intval($_REQUEST["roomes"]);
}

if ($_REQUEST["price_from"])
{
	$filter[">=PROPERTY_price"] = intval($_REQUEST["price_from"]);
}
if ($_REQUEST["price_to"])
{
	$filter["<=PROPERTY_price"] = intval($_REQUEST["price_to"]);
}
if ($_REQUEST["price_m1_from"])
{
	$filter[">=PROPERTY_price_1m"] = intval($_REQUEST["price_m1_from"]);
}
if ($_REQUEST["price_m1_to"])
{
	$filter["<=PROPERTY_price_1m"] = intval($_REQUEST["price_m1_to"]);
}
if ($_REQUEST["square_from"])
{
	$filter[">=PROPERTY_square"] = intval($_REQUEST["square_from"]);
}
if ($_REQUEST["square_to"])
{
	$filter["<=PROPERTY_square"] = intval($_REQUEST["square_to"]);
}

?>
<?	$APPLICATION->IncludeComponent("wexpert:iblock.list", "apartaments_at_newbuildings",Array(
		"ORDER"                             => array("SORT" => "ASC"),
		"FILTER"                            => $filter,
		"IBLOCK_ID"							=> 7,
		"SELECT"						    => array('ID', 'NAME', 'IBLOCK_ID', 'PROPERTY_*', 'SHOW_COUNTER', 'TIMESTAMP_X',"CREATED_BY"),
		"GET_PROPERTY"						=> "Y",
		"CACHE_TIME"    => 3600,
		"PARENT_NAME"						=> $arResult["NAME"],
		"ALWAYS_INCLUDE_TEMPLATE"			=> "Y",
		"AJAX"								=> "Y"
	)); ?>