<?php


namespace Artamonov\Api\Controllers;


use Artamonov\Api\Request;
use Artamonov\Api\Response;
use Bitrix\Main\Loader;
use Bitrix\Main\Context;
use CIBlockElement;
use CUser;
use CFile;
use CIBlockPropertyEnum;
use CIBlockProperty;


class Valuation
{

    var $iblock = 34;
    var $template = "NEW_VALUATION";

    public function get()
    {
        $arResult = $this->getRequest();

        if (!empty($arResult["PARAMETERS"][0]))
            $id = $arResult["PARAMETERS"][0]; // Получаем id товара из адреса
        else Response::BadRequest();

        $iblock_data = $this->getiBlockData($id);
        //$arResult['OPERATING_METHOD'] = 'OBJECT_ORIENTED';
        //Response::ShowResult($arResult, JSON_UNESCAPED_UNICODE);
        if (!empty($iblock_data)) Response::ShowResult($iblock_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        else Response::NoResultProfile($iblock_data);
    }

    public function fields()
    {
        $data = $this->getFormFieldsForApp(34);
        if (!empty($data)) Response::ShowResult($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        else Response::NoResultProfile($data);
    }

    public function insert() {
        $iblock_id = 34;
        $template = 'NEW_VALUATION';
        $data = '';
        if (!empty($_REQUEST["name"])) {
            $data = $this->saveFormFields($iblock_id, $template);
        }
        else Response::BadRequest();
        if ($data == "success") Response::ShowResult($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        else Response::ShowError($data);
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

    private function getFormFieldsForApp($iblock_id)
    {
        if (Loader::includeModule('iblock')) {
            $properties = CIBlockProperty::GetList(Array("sort" => "asc", "name" => "asc"), Array("ACTIVE" => "Y", "IBLOCK_ID" => $iblock_id));
            while ($prop_fields = $properties->GetNext()) {
                if ($prop_fields["PROPERTY_TYPE"] == "L") {
                    $prop_fields["SELECT"] = $this->getPropertyEnum($iblock_id, $prop_fields["CODE"]);
                }
                //print_r($prop_fields);
                $data = array(
                    "name" => $prop_fields["CODE"],
                    "title" => $prop_fields["NAME"]
                );
                if(!empty($prop_fields['SELECT'][0]['ID'])) {
                    foreach ($prop_fields['SELECT'] as $select) {
                        $data['select'][] = array(
                            'id' => $select["ID"],
                            'title' => $select["VALUE"],
                        );
                    }
                }
                $arrData[] = $data;
            }
            return $arrData;
        }
    }

    private function getFormFields($iblock_id)
    {
        if (Loader::includeModule('iblock')) {
            $properties = CIBlockProperty::GetList(Array("sort" => "asc", "name" => "asc"), Array("ACTIVE" => "Y", "IBLOCK_ID" => $iblock_id));
            while ($prop_fields = $properties->GetNext()) {
                if ($prop_fields["PROPERTY_TYPE"] == "L") {
                    $prop_fields["SELECT"] = $this->getPropertyEnum($iblock_id, $prop_fields["CODE"]);
                }
                $data = $prop_fields;
                if(!empty($prop_fields['SELECT'][0]['ID'])) {
                    foreach ($prop_fields['SELECT'] as $select) {
                        $data['select'][] = array(
                            'id' => $select["ID"],
                            'title' => $select["VALUE"],
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
        if (Loader::includeModule('iblock')) {
            $el = new CIBlockElement;
            GLOBAL $USER;

            $PROP       = array();
            $array_prop = $this->getFormFields($iblock_id);

            foreach ($array_prop as $item) {
                if ($item["PROPERTY_TYPE"] == "S") $PROP[$item["CODE"]] = $post[$item["CODE"]];
                elseif ($item["PROPERTY_TYPE"] == "L") $PROP[$item["CODE"]] = array("VALUE" => $post[$item["CODE"]]);
                elseif ($item["PROPERTY_TYPE"] == "F") {
                    $files = array();
                    foreach ($_FILES["files"]["tmp_name"] as $i => $file) {
                        $file                   = array(
                            'name'      => $_FILES["files"]["name"][$i],
                            'size'      => $_FILES["files"]["size"][$i],
                            'tmp_name'  => $_FILES["files"]["tmp_name"][$i],
                            'type'      => '',
                            'old_file'  => '',
                            'del'       => '',
                            'MODULE_ID' => 'iblock'
                        );
                        $files[$i]              = CFile::MakeFileArray(CFile::SaveFile($file, 'iblock'));
                        $files[$i]["MODULE_ID"] = 'iblock';
                    }
                }
            }
            if (isset($files)) {
                $PROP['files'] = $files;
            }

            if(!$USER)
            {
                $USER = new CUser;
            }
            $arLoadProductArray = Array(
                "MODIFIED_BY"       => $USER->GetID(),
                "IBLOCK_SECTION_ID" => false,
                "IBLOCK_ID"         => $iblock_id,
                "PROPERTY_VALUES"   => $PROP,
                "NAME"              => $PROP["name"],
                "ACTIVE"            => "Y",
            );

            if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {
                $this->sendMail($array_prop, $post, $PRODUCT_ID);
                return 'success';
            }
            else return $el->LAST_ERROR;
        }
    }

    private function sendMail($array_prop, $post, $id) {
        $text = '';
        $iblock_id = $this->iblock;
        $template = $this->template;

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

        $name = 'Оценка объекта';
        $arEventField = array("TEXT" => $text, "NAME_FORM" => $name);
        \Bitrix\Main\Mail\Event::sendImmediate(array(
            'EVENT_NAME' => $template,
            'LID' => 's1',
            'C_FIELDS' => $arEventField,
            'FILE' => $files
        ));
    }

}