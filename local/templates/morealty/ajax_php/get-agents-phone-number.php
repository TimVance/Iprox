<? //if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
require ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('iblock');

if ($_REQUEST["agents"] && intval($_REQUEST["agents"]) > 0 )
{
	$res = CIBlockElement::GetList(array(),array("ACTIVE"=>"Y","IBLOCK_ID"=>3,"ID"=>intval($_REQUEST["agents"])),false,false,array("ID","IBLOCK_ID","PROPERTY_PHONE_NUMBER"));
	if ($arItem = $res->GetNext())
	{
		echo ($arItem["PROPERTY_PHONE_NUMBER_VALUE"])? $arItem["PROPERTY_PHONE_NUMBER_VALUE"] : "не указан" ;
	}
	else
	{
		echo "не указан";
	}
}

?>