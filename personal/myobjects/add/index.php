<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Личный кабинет");
?>
<?
if(empty($_REQUEST['IBLOCK_ID']) && empty($_REQUEST['PRODUCT_ID'])) {
	LocalRedirect('/personal/');
}
$APPLICATION->IncludeComponent(
	"morealty:personal.myobjects.add",
	"",
	Array(
	),
	false
);?>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>