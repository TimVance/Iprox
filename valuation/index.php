<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Заказать оценку объекта");
?>


<?$APPLICATION->IncludeComponent(
	"morealty:valuation.form",
	"",
Array()
);?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>