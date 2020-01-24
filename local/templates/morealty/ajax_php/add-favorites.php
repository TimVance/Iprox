<?if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') { die(); }
require ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");?>

<?
$ID = $_REQUEST['ID'];
$favId = CFavorites::Add(array(
	'USER_ID' => $USER->GetID(),
	'URL' => $ID
));
$arResult["NEW_ID"] = $favId;
$res = CFavorites::GetList(array(),array("USER_ID"=>$USER->GetID()));
$arResult["COUNT"] = $res->AffectedRowsCount();
echo (json_encode($arResult));

?>

