<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
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
}

?>