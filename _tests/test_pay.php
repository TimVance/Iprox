<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");?>
<? 
if ($USER->GetLogin() != "vadim")
{
	die();
}
?>
<?$APPLICATION->IncludeComponent("bitrix:sale.personal.account","",Array(
        "SET_TITLE" => "Y"
    )
);
//CSaleUserAccount::UpdateAccount($USER->GetID(), "+100000", "RUB","Test");
?>
<?$APPLICATION->IncludeComponent("bitrix:sale.account.pay",
    "",
    Array(
        "ELIMINATED_PAY_SYSTEMS" => array("1"),
        "PATH_TO_BASKET" => "/personal/cart",
        "PATH_TO_PAYMENT" => "/test_pay.php",
        "PERSON_TYPE" => "1",
        "REFRESHED_COMPONENT_MODE" => "Y",
    	"SELL_CURRENCY" => "RUB",

    )
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>