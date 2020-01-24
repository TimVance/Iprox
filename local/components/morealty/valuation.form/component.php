<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Context;

function getFormFields($iblock_id) {
    $properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$iblock_id));
    while ($prop_fields = $properties->GetNext())
    {
        if($prop_fields["PROPERTY_TYPE"] == "L") {
            $prop_fields["SELECT"] = getPropertyEnum($iblock_id, $prop_fields["CODE"]);
        }
        $data[] = $prop_fields;
    }
    return $data;
}

function getPropertyEnum($iblock_id, $code) {
    $property_enums = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID" => $iblock_id, "CODE"=>$code));
    while($enum_fields = $property_enums->GetNext())
    {
        $list_fields[] = $enum_fields;
    }
    return $list_fields;
}

function saveFormFields($iblock_id) {
    $request = Context::getCurrent()->getRequest();
    $post = $request->getPostList()->toArray();
    $el = new CIBlockElement;
    GLOBAL $USER;

    $PROP       = array();
    $array_prop = getFormFields($iblock_id);

    //print_r($post);
    //print_r($_FILES);
    //exit();
    foreach ($array_prop as $item) {
        if($item["PROPERTY_TYPE"] == "S") $PROP[$item["CODE"]] = $post[$item["CODE"]];
        elseif($item["PROPERTY_TYPE"] == "L") $PROP[$item["CODE"]] = array("VALUE" => $post[$item["CODE"]]);
        elseif($item["PROPERTY_TYPE"] == "F") $PROP[$item["CODE"]] = CFile::MakeFileArray($post[$item["CODE"]]);
    }
//    $arr_file=Array(
//        "name" => $_FILES[IMAGE_ID][name],
//        "size" => $_FILES[IMAGE_ID][size],
//        "tmp_name" => $_FILES[IMAGE_ID][tmp_name],
//        "type" => "",
//        "old_file" => "",
//        "del" => "Y",
//        "MODULE_ID" => "iblock");
    $arLoadProductArray = Array(
        "MODIFIED_BY"       => $USER->GetID(),
        "IBLOCK_SECTION_ID" => false,
        "IBLOCK_ID"         => $iblock_id,
        "PROPERTY_VALUES"   => $PROP,
        "NAME"              => $PROP["phone"],
        "ACTIVE"            => "Y",
    );

    if ($PRODUCT_ID = $el->Add($arLoadProductArray))
        return 'success';
    else return $el->LAST_ERROR;
}

function sendMail() {
    CEvent::Send("NEW_VALUATION", 's1', array());
}

$iblock_id = 34;

if (!empty($_POST["name"])) {
    $arResult["add"] = saveFormFields($iblock_id);
    if ($arResult["add"] != "success") $arResult["form"] = getFormFields($iblock_id);
    else sendMail();
}
else $arResult["form"] = getFormFields($iblock_id);
//print_r($arResult);
$this->includeComponentTemplate();
