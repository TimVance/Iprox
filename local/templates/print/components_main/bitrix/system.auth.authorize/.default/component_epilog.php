<? 
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $USER,$APPLICATION;
if (!$USER->IsAuthorized())
{
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
	die();
}
else 
{
	$APPLICATION->SetTitle("Доступ закрыт");
}
