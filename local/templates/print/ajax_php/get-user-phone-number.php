<? //if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
require ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('iblock');

$rsUser = CUser::GetByID($_REQUEST['ID']);
$arUser = $rsUser->Fetch(); {
	echo $arUser['PERSONAL_MOBILE'];
}
?>