<?php


namespace Artamonov\Api\Controllers;


use Artamonov\Api\Request;
use Artamonov\Api\Response;
use Bitrix\Main\Loader;
use CIBlockElement;
use CUser;
use CFile;
use CIBlockPropertyEnum;
use CIBlockProperty;


class iValuation
{
    public function get()
    {
        $arResult = $this->getRequest();

        if (!empty($arResult["PARAMETERS"][0]))
            $id = $arResult["PARAMETERS"][0]; // Получаем id товара из адреса
        else Response::BadRequest();

        $iblock_data = $this->getiBlockData($id);
        //$arResult['OPERATING_METHOD'] = 'OBJECT_ORIENTED';
        //Response::ShowResult($arResult, JSON_UNESCAPED_UNICODE);
        if (!empty($iblock_data)) Response::iShowResult($iblock_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        else Response::iNoResultProfile($iblock_data);
    }

    public function fields()
    {
        $data = $this->getFormFields(34);
        if (!empty($data)) Response::iShowResult($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        else Response::iNoResultProfile($data);
    }

    // Get current request
    private function getRequest()
    {
        return Request::get();
    }

    // Get data iblock
    private function getiBlockData($id)
    {
        $user = $this->getUser($id);
        if (!empty($user)) {
            $data = $user;
        }
        return $this->changeFormatDate($data);
    }

    private function array_change_keys($array)
    {
        $new_array = [];
        foreach ($array as $key => $value) {
            $pos = strpos($key, "~");
            if ($pos !== false) continue;
            $new_key             = preg_replace_callback('/(?!^)_([a-z])/', function ($key) {
                return strtoupper($key[1]);
            }, $key);
            $new_array[$new_key] = ($value == null) ? "" : $value;
        }
        return $new_array;
    }

    private function getFormFields($iblock_id)
    {
        if (Loader::includeModule('iblock')) {
            $properties = CIBlockProperty::GetList(Array("sort" => "asc", "name" => "asc"), Array("ACTIVE" => "Y", "IBLOCK_ID" => $iblock_id));
            while ($prop_fields = $properties->GetNext()) {
                if ($prop_fields["PROPERTY_TYPE"] == "L") {
                    $prop_fields["SELECT"] = $this->getPropertyEnum($iblock_id, $prop_fields["CODE"]);
                }
                $data = array(
                  'id' => $prop_fields['CODE'],
                  'name' => $prop_fields['NAME'],
                );
                if(!empty($prop_fields['SELECT'][0]['ID'])) {
                    foreach ($prop_fields['SELECT'] as $select) {
                        $data['select'][] = array(
                            'id' => $select["ID"],
                            'name' => $select["VALUE"],
                        );
                    }
                }
                $arrData[] = $data;
            }
            return $arrData;
        }
    }

    private  function getPropertyEnum($iblock_id, $code) {
        $property_enums = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID" => $iblock_id, "CODE"=>$code));
        while($enum_fields = $property_enums->GetNext())
        {
            $list_fields[] = $enum_fields;
        }
        return $list_fields;
    }

    private function saveFormFields($iblock_id) {
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

        if ($PRODUCT_ID = $el->Add($arLoadProductArray))
            return 'success';
        else return $el->LAST_ERROR;
    }

    private function sendMail() {
        CEvent::Send("NEW_VALUATION", 's1', array());
    }

}