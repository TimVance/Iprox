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

function saveFormFields($iblock_id, $template) {
    $request = Context::getCurrent()->getRequest();
    $post = $request->getPostList()->toArray();
    $el = new CIBlockElement;
    GLOBAL $USER;

    $PROP       = array();
    $array_prop = getFormFields($iblock_id);

    foreach ($array_prop as $item) {
        if($item["PROPERTY_TYPE"] == "S") $PROP[$item["CODE"]] = $post[$item["CODE"]];
        elseif($item["PROPERTY_TYPE"] == "L") $PROP[$item["CODE"]] = array("VALUE" => $post[$item["CODE"]]);
        elseif($item["PROPERTY_TYPE"] == "F") {
            $files = array();
            foreach ($_FILES["files"]["tmp_name"] as $i => $file) {
                $file = array(
                    'name' => $_FILES["files"]["name"][$i],
                    'size' => $_FILES["files"]["size"][$i],
                    'tmp_name' => $_FILES["files"]["tmp_name"][$i],
                    'type' => '',
                    'old_file' => '',
                    'del' => '',
                    'MODULE_ID' => 'iblock'
                );
                $files[$i] = CFile::MakeFileArray(CFile::SaveFile($file, 'iblock'));
                $files[$i]["MODULE_ID"] = 'iblock';
            }
        }
    }
    if(isset($files)) {
        $PROP['files'] = $files;
    }

    $arLoadProductArray = Array(
        "MODIFIED_BY"       => $USER->GetID(),
        "IBLOCK_SECTION_ID" => false,
        "IBLOCK_ID"         => $iblock_id,
        "PROPERTY_VALUES"   => $PROP,
        "NAME"              => $PROP["phone"],
        "ACTIVE"            => "Y",
    );

    if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {
        sendMail($template, $array_prop, $post, $iblock_id, $PRODUCT_ID);
        return 'success';
    }
    else return $el->LAST_ERROR;
}

function sendMail($template, $array_prop, $post, $iblock_id, $id) {
    $text = '';
    foreach ($array_prop as $item) {
        if($item["PROPERTY_TYPE"] == "S") $text .= $item["NAME"].': '.$post[$item["CODE"]].'<br />';
        elseif($item["PROPERTY_TYPE"] == "L") {
            foreach ($item["SELECT"] as $select) {
                if($select["ID"] == $post[$item["CODE"]]) {
                    $text .= $item["NAME"].': '.$select["VALUE"].'<br />';
                    continue;
                }
            }
        }
    }

    $files = array();
    $filesResult = CIBlockElement::GetProperty($iblock_id, $id, array("sort" => "asc"), Array("CODE"=>"files"));
    while ($ob = $filesResult->GetNext()) {
        $files[] = $ob["VALUE"];
    }


    $name = '';
    if ($iblock_id == 35) $name = 'ЕГРН выписка';
    elseif ($iblock_id == 34) $name = 'Оценка объекта';
    $arEventField = array("TEXT" => $text, "NAME_FORM" => $name);
    CEvent::Send($template, 's1', $arEventField, "N", "", $files);
}

$iblock_id = $arParams["IBLOCK_ID"];
$template = $arParams["mail_template"];

if (!empty($_POST["name"])) {
    $arResult["add"] = saveFormFields($iblock_id, $template);
    if ($arResult["add"] != "success") $arResult["form"] = getFormFields($iblock_id);
}
else $arResult["form"] = getFormFields($iblock_id);


$this->includeComponentTemplate();
