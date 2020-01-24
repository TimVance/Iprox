<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); ?>
<? 
if ($USER->GetLogin() == "vadim")
{
	CustomUsers::GetUsersGourps(array("1","42","44"));
}
?>
