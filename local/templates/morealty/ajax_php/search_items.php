<?
if (!$_REQUEST["q"] || $_REQUEST["ajax"] != "Y" || empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')
{
	die();
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

?>

<?
//$readyPhone = "%".implode("%", explode("", \Morealty\User::getClearedPhone($_REQUEST["q"])))."%";
$readyPhone = implode("%",preg_split("//", \Morealty\User::getClearedPhone($_REQUEST["q"])));
$arFilter = array(
	"IBLOCK_ID" => array(8, 7, 11, 10, 19),
	"ACTIVE" => "Y",
	"GLOBAL_SECTION_ACTIVE" => "Y",
	array(
		"LOGIC" => "OR",
		"?NAME" => "%".$_REQUEST["q"]."%",
		"ID" => intval($_REQUEST["q"]),
		"%PROPERTY_street" => $_REQUEST["q"],
		"%PROPERTY_tel" => array(\Morealty\User::getClearedPhone($_REQUEST["q"]), $readyPhone),
		"PROPERTY_realtor" => \Morealty\Realtor::getRealtorsByPhone($_REQUEST["q"])
	)
);

?>
<?$APPLICATION->IncludeComponent("wexpert:iblock.list", "search_items", Array(
"ORDER"                             => array(),
"FILTER"                            => $arFilter,
"IBLOCK_ID"							=> array(8, 7, 11, 10),
"PAGESIZE"						    => 20,
"SELECT"						    => "",
"GET_PROPERTY"						=> "Y",
//"NAV_TEMPLATE"						=> "morealty",
"CACHE_TIME"    => 3600,
"TEMPLATE_THEME" => \Morealty\Catalog::getCatalogViewType(),
"ALWAYS_INCLUDE_TEMPLATE" => "Y"
));?>