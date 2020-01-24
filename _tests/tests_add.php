<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); ?>
<? 
if ($USER->GetLogin() == "vadim")
{
	/*$res = CIBlockProperty::GetPropertyEnum("IS_ACCEPTED",array(),array("IBLOCK_ID"=>"7"));
	while ($arTemp = $res->GetNext())
	{
		my_print_r($arTemp);
	}*/
	my_print_r($USER->GetByID($USER->GetID())->GetNext());
}	
?>

