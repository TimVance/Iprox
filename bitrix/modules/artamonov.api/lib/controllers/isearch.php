<?php


namespace Artamonov\Api\Controllers;


use Artamonov\Api\Request;
use Artamonov\Api\Response;
use Bitrix\Main\Loader;
use CIBlockElement;
use CUser;
use CFile;
use CIBlockPropertyEnum;


class iSearch
{
    public function get()
    {
        $arResult = $this->getRequest();
        if(!empty($_GET)) {
            if (!empty($arResult["PARAMETERS"]["get-parameter-0"]))
                $iblock_id = $arResult["PARAMETERS"]["get-parameter-0"]; // Получаем id товара из адреса
            else Response::iBadRequest();
            if (!empty($arResult["PARAMETERS"]["get-parameter-1"])) $count = $arResult["PARAMETERS"]["get-parameter-1"];
            else $count = 20;
            if (!empty($arResult["PARAMETERS"]["get-parameter-2"])) $iNumPage = $arResult["PARAMETERS"]["get-parameter-2"];
            else $iNumPage = 1;
        }
        else {
            if (!empty($arResult["PARAMETERS"][0]))
                $iblock_id = $arResult["PARAMETERS"][0]; // Получаем id товара из адреса
            else Response::iBadRequest();
            if (!empty($arResult["PARAMETERS"][1])) $count = $arResult["PARAMETERS"][1];
            else $count = 20;
            if (!empty($arResult["PARAMETERS"][2])) $iNumPage = $arResult["PARAMETERS"][2];
            else $iNumPage = 1;
        }
        $iblock_data = $this->getiBlockData($iblock_id, $count, $iNumPage, $arResult["PARAMETERS"]);
        //$arResult['OPERATING_METHOD'] = 'OBJECT_ORIENTED';
        //Response::ShowResult($arResult, JSON_UNESCAPED_UNICODE);
        if (!empty($iblock_data["items"])) Response::iShowResult($iblock_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        else Response::iNoResultCat($iblock_data);
    }

    // Get current request
    private function getRequest()
    {
        return Request::get();
    }

    // Get data iblock
    private function getiBlockData($id, $count, $iNumPage, $get)
    {
        $data = [];
        $prop_ignore = array(
            "CREATED_BY",
            "IS_ACCEPTED",
            "IS_INFORMED",
            "STATUS",
            "IS_INFORMED_ADMIN",
        );
        if (Loader::includeModule('iblock')) {
            // Получаем товары по его iblockid


            $arSort = Array();
            $arSelect = Array();
            $arFilter = Array(
                "IBLOCK_ID" => $id,
                "ACTIVE_DATE" => "Y",
                "ACTIVE" => "Y",
                array(
                  "LOGIC" => "OR",
                  "ID" => $_GET["q"],
                  "NAME" => $_GET["q"],
                  "PROPERTY_city" => '%'.$_GET["q"].'%',
                  "PROPERTY_street" => '%'.$_GET["q"].'%',
                  "PROPERTY_phone" => '%'.$_GET["q"].'%',
                  "DETAIL_TEXT" => '%'.$_GET["q"].'%',
                  "PREVIEW_TEXT" => '%'.$_GET["q"].'%',
                ),
                //"=PROPERTY_city" => "",
            );

            //print_r($arFilter);

            $res = CIBlockElement::GetList($arSort, $arFilter, false, Array("nPageSize" => $count, "iNumPage" => $iNumPage, 'checkOutOfRange' => true), $arSelect);
            while ($ob = $res->GetNextElement()) {
                $item = $this->array_change_keys(array_change_key_case($ob->GetFields(), CASE_LOWER));
                //print_r($item);
                $items[] = array(
                    "id"    => $item["id"],
                    "name" => $item["name"],
                    //"detailText"  => $item["detailText"],
                    //"shortInfo"  => $item["previewText"],
                    //"createdBy"  => $item["createdBy"],
                    "iblockId"  => $item["iblockId"],
                );
            }

            foreach ($items as $key => $value) {

                // createdBy
                //$creadted_id = $value["createdBy"];
                //unset($items[$key]["createdBy"]);
                //$items[$key]["createdBy"] = $this->getUser($creadted_id);
                // createdBy

                //modifiedBy
                //$modified_id = $value["modifiedBy"];
                //unset($items[$key]["modifiedBy"]);
                //$items[$key]["modifiedBy"] = $this->getUser($modified_id);
                //modifiedBy


                $product_properties = CIBlockElement::GetProperty($value["iblockId"], $value["id"], array("sort" => "asc"), Array());
                $prop_array = array();
                while ($prop = $product_properties->Fetch())
                    if (!empty($prop["VALUE"])) {
                        if (in_array($prop["CODE"], $prop_ignore)) continue;
                        $prop_item = $this->array_change_keys(array_change_key_case($prop, CASE_LOWER));
                        if ($prop_item["propertyType"] == "E") {
                            $prop_item["value"] = CIBlockElement::GetByID($prop_item["value"]);
                            if ($ar_res = $prop_item["value"]->GetNext()) {
                                $prop_item["value"] = $ar_res["NAME"];
                            }
                            //print_r($orgName);
                        }
                        if ($prop_item["multiple"] == "N") $prop_array[$prop_item["code"]] = $prop_item["value"];
                        else {
                            if($prop_item["code"] == "photo_gallery") {
                                if (empty($items[$key]["photo"])) $prop_array["photo"] = CFile::GetPath($prop_item["value"]);
                            }
                            else $prop_array[$prop_item["code"]] = $prop_item["value"];
                        }
                    }
                $items[$key]["price_1m"] = (!empty($prop_array["price_1m"]) ? $prop_array["price_1m"] : "");
                $items[$key]["price"] = (!empty($prop_array["price"]) ? $prop_array["price"] : "");
                $items[$key]["photo"] = (!empty($prop_array["photo"]) ? $prop_array["photo"] : "");
                $map = array();
                $map = explode(",", $prop_array["yandex_map"]);
                if (!empty($map[0])) {
                    $items[$key]["longitude"] = $map[0];
                    $items[$key]["latitude"]= $map[1];
                }
                else {
                    $items[$key]["longitude"] = "";
                    $items[$key]["latitude"]= "";
                }
                $items[$key]["address"] = ''
                    .(!empty($prop_array["city"]) ? $prop_array["city"].', ' : '')
                    //.(!empty($prop_array["district"]) ? $prop_array["district"].', ' : '')
                    .(!empty($prop_array["microdistrict"]) ? $prop_array["microdistrict"].', ' : '')
                    .(!empty($prop_array["street"]) ? $prop_array["street"] : '');
                /*
                echo '<pre>';
                    print_r($prop_array);
                echo '</pre>';
                */
            }


            $arIBlock = GetIBlock($id);
            $data["id"] = (!empty($arIBlock["ID"]) ? $arIBlock["ID"] : "");
            //$data["timestampX"] = $this->formatDateISO8601($arIBlock["TIMESTAMP_X"]);
            //$data["iblockTypeId"] = $arIBlock["IBLOCK_TYPE_ID"];
            //$data["lid"] = $arIBlock["LID"];
            ///$data["code"] = $arIBlock["CODE"];
            $data["name"] = (!empty($arIBlock["NAME"]) ? $arIBlock["NAME"] : "");
            //$data["listPageUrl"] = $arIBlock["LIST_PAGE_URL"];
            $data["iNumPage"] = $iNumPage;
            $data["nPageSize"] = $count;
            $data["totalSize"] = CIBlockElement::GetList(Array(), $arFilter, array(), Array("nPageSize" => $count, "iNumPage" => $iNumPage, 'checkOutOfRange' => true), $arSelect);

            if ($items != null || !empty($items)) $data["items"] = $items;
            else $data["items"] = array();
        }
        return $this->changeFormatDate($data);
    }

    private function changeFormatDate($data)
    {
        $date_properties = array('timestampX', 'dateCreate', 'showCounterStart', 'showCounterStartX', 'createdDate');
        foreach ($data as $key => $value) {
            if (!is_array($value)) {
                if (in_array($key, $date_properties)) {
                    $data[$key] = $this->formatDateISO8601($value);
                }
            } else {
                foreach ($value as $i => $item) {
                    foreach ($item as $j => $item_value) {
                        if (in_array($j, $date_properties)) {
                            $data[$key][$i][$j] = $this->formatDateISO8601($item_value);
                        }
                    }
                }
            }
        }
        return $data;
    }

    private function formatDateISO8601($date)
    {
        return date("Y-m-d\TH:i:s", strtotime($date));
    }

    private function array_change_keys($array)
    {
        $new_array = [];
        foreach ($array as $key => $value) {
            $pos = strpos($key, "~");
            if ($pos !== false) continue;
            $new_key = preg_replace_callback('/(?!^)_([a-z])/', function ($key) {
                return strtoupper($key[1]);
            }, $key);
            $new_array[$new_key] = ($value == null) ? "" : $value;
        }
        return $new_array;
    }

    private function getUser($id)
    {
        $user = CUser::GetByID($id);
        $array_user = $user->Fetch();
        $data = array(
            "id"          => $array_user["ID"],
            "fullName" => trim($array_user["NAME"].' '.$array_user["LAST_NAME"]),
            "photo"       => $array_user["PERSONAL_PHOTO"],
            "phone"      => $array_user["PERSONAL_MOBILE"],
//            "login"       => $array_user["LOGIN"],
//            "active"      => $array_user["ACTIVE"],
//            "name"        => $array_user["NAME"],
//            "lastName"    => $array_user["LAST_NAME"],
//            "email"       => $array_user["EMAIL"],
//            "profession"  => $array_user["PERSONAL_PROFESSION"],
//            "city"        => $array_user["UF_CITY"],
//            "private"     => $array_user["UF_DISPLAY_PRIVATE"],
//            "acceptRules" => $array_user["UF_ACCEPT_RULES"],
//            "skype"       => $array_user["UF_SKYPE"],
//            "aboutMe"     => $array_user["UF_ABOUT_ME"],
//            "post"        => $array_user["UF_PERSON_POST"],
//            "agentName"   => $array_user["UF_AGENT_NAME"],
        );
        return $data;
    }

}