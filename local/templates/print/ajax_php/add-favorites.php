<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>

<?
$ID = $_REQUEST['ID'];
$favId = CFavorites::Add(array(
	'USER_ID' => $USER->GetID(),
	'URL' => $ID
));

echo $favId;

?>

