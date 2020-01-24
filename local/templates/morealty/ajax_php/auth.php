<?
if ($_REQUEST["ajax"] != "Y" || empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')
{
	die();
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if ($_REQUEST["USER_PHONE"] && $_REQUEST["USER_PHONE_CODE"])
{
	echo(json_encode(array(
			"status" => (bool) \Morealty\User::authByCode($_REQUEST["USER_PHONE"], $_REQUEST["USER_PHONE_CODE"]),
			"error"  => ($APPLICATION->GetException())? $APPLICATION->GetException()->__toString() : false
	)));
}
else if ($_REQUEST["USER_PHONE"])
{
	echo(json_encode(array(
			"status" => (bool) \Morealty\User::startCodeAuth($_REQUEST["USER_PHONE"]),
			"error"  => ($APPLICATION->GetException())? $APPLICATION->GetException()->__toString() : false
	)));
}