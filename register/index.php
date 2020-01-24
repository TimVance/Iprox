<?
if ($_REQUEST["REGISTER"]["PERSONAL_MOBILE"])
{
	$_REQUEST["REGISTER"]["LOGIN"] = $_REQUEST["REGISTER"]["PERSONAL_MOBILE"];
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Регистрация");
ShowMessage($arParams["~AUTH_RESULT"]);



$APPLICATION->IncludeComponent(
	"bitrix:main.register",
	"main",
	Array(
		"USER_PROPERTY_NAME" => "UF_CITY",
		"SEF_MODE" => "N",
		"SHOW_FIELDS" => Array("NAME", "SECOND_NAME", "LAST_NAME", "PERSONAL_CITY", "PERSONAL_MOBILE", "PERSONAL_NOTES", "WORK_PROFILE"),
		"REQUIRED_FIELDS" => array("NAME","LAST_NAME","PERSONAL_MOBILE","SECOND_NAME"),
		"AUTH" => "Y",
		"USE_BACKURL" => "Y",
		"SUCCESS_PAGE" => $APPLICATION->GetCurPageParam('',array('backurl')),
		"SET_TITLE" => "N",
		"USER_PROPERTY" => Array()
	)
);


?><p><a href="<?=$arResult["AUTH_AUTH_URL"]?>"><b><?=GetMessage("AUTH_AUTH")?></b></a></p><?

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>