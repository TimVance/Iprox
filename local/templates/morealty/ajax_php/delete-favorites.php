<?if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') { die(); }
require ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");?>
<?
$ID = '';

$arSort = array('SORT' => 'ASC');
$arFilter = array('USER_ID' => $USER->GetID());
$rsItems = CFavorites::GetList($arSort, $arFilter);
$arID = array();
while ($Res = $rsItems->Fetch()) {
	if($Res['URL'] == $_REQUEST['ID']) {
		$ID = $Res['ID'];
	}
}

if(!empty($ID)) {
	CFavorites::Delete($ID);
	
	$res = CFavorites::GetList(array(),array("USER_ID"=>$USER->GetID()));
	$arResult["COUNT"] = $res->AffectedRowsCount();
	echo (json_encode($arResult));
	
}

?>