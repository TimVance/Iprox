<? //if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
require ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('iblock');

if ($_REQUEST['ID'])
{
	$rsUser = CUser::GetByID($_REQUEST['ID']);
	$number = "";
	$arUser = $rsUser->Fetch(); {
		$number = $arUser['PERSONAL_MOBILE'];
	}
	if(!empty($number)) {
		echo $number;
	} else {
		echo "не указан";
	}
}
else if ($_REQUEST["BUILDER"] && intval($_REQUEST["BUILDER"]) > 0)
{
	$res = CIBlockElement::GetList(array(),array("ACTIVE"=>"Y","ID"=>intval($_REQUEST["BUILDER"])),false,false,array("ID","IBLOCK_ID","PROPERTY_PHONE_NUMBER"));
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