<?


class writeInfo
{

    var $iblock = 33;
    var $template = "NEW_VALUATION";

    function writeBase($post)
    {
        CModule::IncludeModule("iblock");
        $el = new CIBlockElement;
        global $USER;

        $PROP = array();

        $array_prop = $this->getFormFields($this->iblock_id);

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

        $arLoadProductArray = array(
            "MODIFIED_BY"       => $USER->GetID(),
            "IBLOCK_SECTION_ID" => false,
            "IBLOCK_ID"         => $this->iblock,
            "PROPERTY_VALUES"   => $PROP,
            "NAME"              => $PROP["phone"],
            "ACTIVE"            => "Y",
        );

        if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {
            $this->sendMail($array_prop, $post, $PROP);
            return "success";
        }
        else
            return "Error: " . $el->LAST_ERROR;
    }

    function sendMail($array_prop, $post, $PROP) {
        $text = '';
        $template = $this->template;
        $iblock_id = $this->iblock;
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

        $name = '';
        $name = 'Заявка на Ипотеку';
        $arEventField = array("TEXT" => $text, "NAME_FORM" => $name);
        CEvent::Send($template, 's1', $arEventField, "N", $PROP["files"]);
    }

    function getFormFields() {
        $iblock_id = $this->iblock;
        $properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$iblock_id));
        while ($prop_fields = $properties->GetNext())
        {
            if($prop_fields["PROPERTY_TYPE"] == "L") {
                $prop_fields["SELECT"] = $this->getPropertyEnum($iblock_id, $prop_fields["CODE"]);
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

}


?>