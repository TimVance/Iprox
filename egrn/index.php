<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Заказать ЕГРН выписку");
?>

<? $APPLICATION->IncludeComponent("morealty:morealty.form", "egrn", Array("IBLOCK_ID" => 35, "mail_template" => "NEW_VALUATION")); ?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>