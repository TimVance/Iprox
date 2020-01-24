<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); ?>

<?
$arSort = array('SORT' => 'ASC');
$arFilter = array('USER_ID' => $USER->GetID());
$rsItems = CFavorites::GetList($arSort, array());
$obArray = '';
while($ob = $rsItems->Fetch()) {
	$obArray[] = $ob;
}

echo '<pre>';
print_r($obArray);
echo '</pre>';
?>