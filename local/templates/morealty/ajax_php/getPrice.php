<?if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') { die(); }
require ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");?>

<?
CModule::IncludeModule("catalog");
CModule::IncludeModule("sale");
$arResult = array("status"=>"ok");
$PRODUCT_ID = intval($_REQUEST["prod_id"]);
$Quantity = intval($_REQUEST["qant"]);
if ($Quantity > 0)
{
	$arResult["price"] = CurrencyFormat(\MorealtySale\User::getProductPrice($PRODUCT_ID,$Quantity), "RUB");
}
else 
{
	$arResult["status"] = "error";
	$arResult["error"] = "Количество не может быть отрицательным";
}
echo (json_encode($arResult));

?>

