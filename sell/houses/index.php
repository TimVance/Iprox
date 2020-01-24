<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$_REQUEST["catalog"] = "houses";
require($_SERVER["DOCUMENT_ROOT"]."/sell/section.php");
$APPLICATION->SetPageProperty("title", "Продажа домов, коттеджей в Сочи");
$APPLICATION->SetTitle("Продажа домов, коттеджей в Сочи");?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>