<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if (! $USER->IsAuthorized()) {
	exit;
}

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

CModule::IncludeModule("sale");
CModule::IncludeModule("catalog");
$Return = array();



	/*if (!CSite::InGroup(array($arTemp["ID"])))
	{
		$AddedPosition = Add2BasketByProductID(intval($_REQUEST["prod_id"]),1);
		$Return["status"] = ($AddedPosition)? $AddedPosition : "error";
		$Return["response"] = ($AddedPosition)? Loc::getMessage("BASKET_CREATED") : Loc::getMessage("BASKET_NOT_CREATED");
	}
	else {
		$Return["status"] = "error";
		$Return["response"] = Loc::getMessage("BASKET_ALRAEDY_OWN");
	}*/


	
	
/*else {
	$Return["status"] = "error";
	$Return["response"] = Loc::getMessage("BASKET_WRONG_PACKET");
}
*/
$Quant = ($_REQUEST["quant"]) ? intval($_REQUEST["quant"]) : false;

if (\MorealtySale\User::canUserBuyIt(intval($_REQUEST["prod_id"]), $Quant))
{
	$AddedPosition = Add2BasketByProductID(intval($_REQUEST["prod_id"]),$Quant);
	$Return["status"] = ($AddedPosition)? $AddedPosition : "error";
	$Return["response"] = ($AddedPosition)? Loc::getMessage("BASKET_CREATED") : Loc::getMessage("BASKET_NOT_CREATED");
}
else 
{
	$Return["status"] = "need-balance";
	$Return["response"] = Loc::getMessage("BASKET_NOT_ENOUGH_ACCOUNT");
}




echo json_encode($Return);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>