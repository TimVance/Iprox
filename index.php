<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Мореалти - портал недвижимости");
LocalRedirect("/sell/flat/", true, "301 Moved Permanently");
?>

<?$APPLICATION->IncludeComponent('wexpert:includer', 'main_page_content')?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>