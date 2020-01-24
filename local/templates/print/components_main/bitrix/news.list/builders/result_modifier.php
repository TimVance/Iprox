<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? /*
if ($USER->GetLogin() == "vadim") {

    $arBuildersIds = array();
    foreach ($arResult["ITEMS"] as $arItem) {
        $arBuildersIds[] = $arItem["ID"];
    }
    if (count($arBuildersIds) > 0) {
        $res = CIBlockElement::GetList(array(), array("ACTIVE" => "Y", "IBLOCK_ID" => "19", "PROPERTY_BUILDER" => $arBuildersIds), false, false, array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL"));
        while ($arObject = $res->GetNext()) {
            $arResult['OBJECTS'][$arObject["ID"]] = $arObject;
        }
    }
    //my_print_r($arResult['OBJECTS']);
}
*/

$res = CIBlockElement::GetList(
    array("PROPERTY_BUILDER" => "ASC"),
    array("ACTIVE" => "Y", "IBLOCK_ID" => "19", "!PROPERTY_BUILDER" => false),
    false,
    false,
    array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", "PROPERTY_BUILDER")
);

while ($arObject = $res->GetNext()) {
    $arObject['PROPERTY_BUILDER_VALUE'];
    $arObjects[$arObject['PROPERTY_BUILDER_VALUE']][] = $arObject;
}

$arSelect = Array('ID', 'IBLOCK_ID', 'PROPERTY_newbuilding');
$arFilter = Array("IBLOCK_ID" => 7, "ACTIVE_DATE" => "Y", "ACTIVE" => "Y", "PROPERTY_IS_ACCEPTED" => "417");
$res = CIBlockElement::GetList(Array(), $arFilter, array('PROPERTY_newbuilding'), false, $arSelect);

while ($ob = $res->GetNextElement()) {
    $jk[] = $ob->GetFields();
}
foreach ($arResult["ITEMS"] as &$arItem) {
    $arItem["PROPERTIES"]['COUNT_ITEMS'] = intval(0);
    if ($arObjects[$arItem['ID']]) {
        $arItem["PROPERTIES"]["OBJECTS_FOR_SALE"]["VALUE"] = $arObjects[$arItem['ID']];
        foreach ($arItem["PROPERTIES"]["OBJECTS_FOR_SALE"]["VALUE"] as $arVal){
            $id[] = $arVal['ID'];
        }
        foreach ($jk as $value){
            if (in_array($value['PROPERTY_NEWBUILDING_VALUE'], $id)){
                $arItem["PROPERTIES"]['COUNT_ITEMS'] += intval($value['CNT']);
            }
        }
        unset($id);
    }
}
?>