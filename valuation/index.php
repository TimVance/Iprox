<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Заказать оценку объекта");
?>

<? $APPLICATION->IncludeComponent("morealty:morealty.form", "", Array("IBLOCK_ID" => 34, "mail_template" => "NEW_VALUATION")); ?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>