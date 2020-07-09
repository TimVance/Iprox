<?
define("STOP_STATISTICS", true);
define("NOT_CHECK_PERMISSIONS", true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("sale");

//file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/payonline.payment/tools/result.log", print_r($_GET, true), FILE_APPEND);

$correctPayment = true;
$errorText = "";

if($_GET["result"] === false){
    $correctPayment = false;
    $errorText = GetMessage("OO_PAYONLINE_PROIZOSLA_OSIBKA_PRI");
}

//check order
$arOrder = CSaleOrder::GetByID(IntVal($_GET["OrderId"]));
if (!$arOrder){
    $correctPayment = false;
    $errorText = GetMessage("OO_PAYONLINE_ZAKAZ_S_NOMEROM") . $_GET["OrderId"] . " ".GetMessage("OO_PAYONLINE_NE_SUSESTVUET");
}

//check hash
if($correctPayment){
    CSalePaySystemAction::InitParamArrays($arOrder, $arOrder["ID"]);

    $hash = "DateTime=".$_GET["DateTime"]."&".
        "TransactionID=".$_GET["TransactionID"]."&".
        "OrderId=".$_GET["OrderId"]."&".
        "Amount=".$_GET["Amount"]."&".
        "Currency=".$_GET["Currency"]."&".
        "PrivateSecurityKey=".CSalePaySystemAction::GetParamValue("SECURITY_KEY");
    $hash = md5($hash);

    if($hash != $_GET["SecurityKey"]){
        $correctPayment = false;
        $errorText = GetMessage("OO_PAYONLINE_PODPISI_NE_SOVPADAUT") . $hash . " != " . $_GET["SecurityKey"];
    }
}

//check sum
if($correctPayment){
    if(floor($arOrder["PRICE"]) != floor($_GET["PaymentAmount"])){
        $correctPayment = false;
        $errorText = GetMessage("OO_PAYONLINE_SUMMY_PLATEJA_NE_SOV") .$arOrder["PRICE"] . ". ".GetMessage("OO_PAYONLINE_OPLACENO") . $_GET["PaymentAmount"];
    }
}

//mark order
if($correctPayment){
    $arFields = array(
        'PS_RESPONSE_DATE' => Date(CDatabase::DateFormatToPHP(CLang::GetDateFormat("FULL", LANG))),
        'PS_SUM' => $_GET["PaymentAmount"],
        'PS_STATUS' => "Y",
        "PS_STATUS_CODE" => "200",
        'PS_STATUS_DESCRIPTION' => "",
        //'PS_STATUS_MESSAGE' => print_r($_GET, true),
        'USER_ID' => $arOrder["USER_ID"],
        'STATUS_ID' => "P",
        'PAYED' => "Y"
    );
    if(!$arOrder["PAYED"] == "Y"){
        CSaleOrder::PayOrder($_GET["OrderId"], "Y");
    }
    CSaleOrder::Update($_GET["OrderId"], $arFields);
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>