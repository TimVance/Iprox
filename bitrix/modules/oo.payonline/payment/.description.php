<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?><?
IncludeModuleLangFile(__FILE__);

$psTitle = "PayOnline";
$psDescription  = GetMessage("OO_PAYONLINE_PLATEJNAA_SISTEMA");

$arPSCorrespondence = array(
    "MERCHANT_ID" => Array(
        "NAME" => GetMessage("OO_PAYONLINE_VAS_IDENTIFIKATOR_V"),
        "DESCR" => GetMessage("OO_PAYONLINE_VY_POLUCITE_EGO_POSL"),
        "VALUE" => "",
        "TYPE" => "",
    ),
    "ORDER_ID" => Array(
        "NAME" => GetMessage("OO_PAYONLINE_NOMER_ZAKAZA"),
        "DESCR" => GetMessage("OO_PAYONLINE_NOMER_ZAKAZA_NA_VASE"),
        "VALUE" => "ID",
        "TYPE" => "ORDER",
    ),
    "SHOULD_PAY" => Array(
        "NAME" => GetMessage("OO_PAYONLINE_SUMMA_ZAKAZA"),
        "DESCR" => "",
        "VALUE" => "SHOULD_PAY",
        "TYPE" => "ORDER",
    ),
    "CURRENCY" => Array(
        "NAME" => GetMessage("OO_PAYONLINE_VALUTA_ZAKAZA"),
        "DESCR" => "",
        "TYPE" => "SELECT",
        "VALUE" => array(
            "RUB" => array("NAME" => GetMessage("OO_PAYONLINE_RUBLI")),
            "USD" => array("NAME" => GetMessage("OO_PAYONLINE_DOLLARY_SSA")),
            "EUR" => array("NAME" => GetMessage("OO_PAYONLINE_EVRO"))
        ),
    ),
    "SECURITY_KEY" => Array(
        "NAME" => GetMessage("OO_PAYONLINE_VAS_PRIVATNYY_KLUC"),
        "DESCR" => GetMessage("OO_PAYONLINE_SVOY_KLUC_VY_MOJETE"),
        "VALUE" => "",
        "TYPE" => "",
    ),
    "RETURN_URL" => Array(
        "NAME" => GetMessage("OO_PAYONLINE_ADRES_STRANICY_NA_K"),
        "DESCR" => "",
        "VALUE" => "",
        "TYPE" => "",
    ),
    "FAIL_URL" => Array(
        "NAME" => GetMessage("OO_PAYONLINE_ADRES_STRANICY_NA_K1"),
        "DESCR" => "",
        "VALUE" => "",
        "TYPE" => "",
    ),
);
?>
