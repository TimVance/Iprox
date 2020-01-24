<?
require ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('iblock');

switch($_REQUEST['object']) {
    case 'city': $type = 'DISTRICTS'; $IBLOCK_ID = 14; break;
    case 'district': $type = 'MICRODISTRICTS'; $IBLOCK_ID = 15; break;
    default: ;
}



$object = "PROPERTY_" . $_REQUEST['object'];
$ID = $_REQUEST['ID'];

$res = CIBlockElement::GetList(Array(), Array("IBLOCK_ID" => $IBLOCK_ID, $object => $ID, "ACTIVE"=>"Y"), false, Array("nPageSize"=>50), Array("ID", "NAME"));
while($ob = $res->Fetch()) {
    $arResult[] = $ob;
}
//echo '<pre>';
//print_r($arResult);
//echo '</pre>';
?>

<?foreach($arResult as $property):?>
    <option value="<?=$property["ID"]?>"><?=$property["NAME"]?></option>
<?endforeach;?>
