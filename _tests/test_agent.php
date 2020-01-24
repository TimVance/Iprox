<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); ?>
<? 
if ($USER->GetLogin() == "vadim")
{
	CheckUnInformedObjectsAdmin();
}
?>
