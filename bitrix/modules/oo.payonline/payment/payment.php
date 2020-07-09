<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
IncludeModuleLangFile(__FILE__);
if(!CModule::IncludeModule("oo.payonline")) return;

$hash = "MerchantId=".CSalePaySystemAction::GetParamValue("MERCHANT_ID")."&".
    "OrderId=".CSalePaySystemAction::GetParamValue("ORDER_ID")."&".
    "Amount=".number_format(CSalePaySystemAction::GetParamValue("SHOULD_PAY"), 2, ".", "")."&".
    "Currency=".CSalePaySystemAction::GetParamValue("CURRENCY")."&".
    "PrivateSecurityKey=".CSalePaySystemAction::GetParamValue("SECURITY_KEY");
$hash = md5($hash);
?>

<form id="payOnline" name="payOnline" method="POST" action="https://secure.payonlinesystem.com/ru/payment/">
    <input type="hidden" name="MerchantId" value="<?=CSalePaySystemAction::GetParamValue("MERCHANT_ID")?>">
    <input type="hidden" name="OrderId" value="<?=CSalePaySystemAction::GetParamValue("ORDER_ID")?>">
    <input type="hidden" name="Amount" value="<?=number_format(CSalePaySystemAction::GetParamValue("SHOULD_PAY"), 2, ".", "")?>">
    <input type="hidden" name="Currency" value="<?=CSalePaySystemAction::GetParamValue("CURRENCY")?>">
    <input type="hidden" name="SecurityKey" value="<?=$hash?>">
    <input type="hidden" name="ReturnUrl" value="<?=CSalePaySystemAction::GetParamValue("RETURN_URL")?>">
    <input type="hidden" name="FailUrl" value="<?=CSalePaySystemAction::GetParamValue("FAIL_URL")?>">
    <input type="submit" value="<?=GetMessage("OO_PAYONLINE_OPLATITQ")?>"/>
</form>

<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script>
    /*if (window.jQuery) {
     $(document).ready(function() { $("#pay").submit(); });
     }
     else{
     google.load("jquery", "1.4.3");
     google.setOnLoadCallback(function() {
     $("#pay").submit();
     });
     }*/
</script>