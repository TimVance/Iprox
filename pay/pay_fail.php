<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<?$APPLICATION->IncludeComponent("bitrix:sale.order.payment.receive","",Array(
        "PAY_SYSTEM_ID_NEW" => "2"
    )
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>