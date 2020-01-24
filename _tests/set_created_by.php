<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); ?>
<? 
if ($USER->GetLogin() != "vadim") die();


CModule::IncludeModule("iblock");
$newEle = new CIBlockElement;
$rs = CIBlockElement::GetList(array(),array("IBLOCK_TYPE"=>"catalog","IBLOCK_ACTIVE"=>"Y","PROPERTY_CREATED_BY.NAME"=>"Вадим"),false,false,array("PROPERTY_CREATED_BY","ID","IBLOCK_ID"));

while ($arItem = $rs->GetNext())
{
	my_print_r($arItem);
	//$newEle->SetPropertyValuesEx($arItem["ID"], $arItem["IBLOCK_ID"], array("CREATED_BY"=>$arItem["CREATED_BY"]));
}
?>
