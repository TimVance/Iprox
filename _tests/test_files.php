<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); ?>
<? 
if ($USER->GetLogin() == "vadim")
{
	$res = CIBlockElement::GetProperty(7, 301,array("id"=>"ASC"),array("CODE"=>"photo_gallery"));
	$arDeleteValuesId = array();
	while ($arCase = $res->GetNext())
	{
		$arDeleteValuesId[] = $arCase["PROPERTY_VALUE_ID"];
		my_print_r($arCase);
		
	}
	my_print_r($arDeleteValuesId);
}
?>
