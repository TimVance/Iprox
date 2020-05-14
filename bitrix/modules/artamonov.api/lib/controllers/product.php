<?php


namespace Artamonov\Api\Controllers;


use Artamonov\Api\Request;
use Artamonov\Api\Response;
use Bitrix\Main\Loader;
use CIBlockElement;
use CUser;
use CFile;
use CIBlockSection;



class Product
{
    public function get()
    {
        $arResult = $this->getRequest();
        if(!empty($arResult["PARAMETERS"][0]))
            $product_id = $arResult["PARAMETERS"][0]; // Получаем id товара из адреса
            else Response::BadRequest();
        $product_data = $this->getProductData($product_id);
        //$arResult['OPERATING_METHOD'] = 'OBJECT_ORIENTED';
        //Response::ShowResult($arResult, JSON_UNESCAPED_UNICODE);
        if(!empty($product_data)) Response::ShowResult($product_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        else Response::NoResult();
    }

    // Get current request
    private function getRequest()
    {
        return Request::get();
    }

    // Get data iblock
    private function getProductData($id)
    {
        $product_data = [];
        if (Loader::includeModule('iblock')) {
			// Получаем товар по его id
            if ($result = CIBlockElement::GetByID($id)) {
                while ($product = $result->fetch()) {
                    $product_info = $this->array_change_keys(array_change_key_case($product, CASE_LOWER));

                    $product_prop = array();
                    $product_properties = CIBlockElement::GetProperty($product["IBLOCK_ID"], $product["ID"], array("sort" => "asc"), Array());
                    while($prop = $product_properties->Fetch()) {

                        //print_r($prop);
                        if ($prop["PROPERTY_TYPE"] == "E") {
                            $multiprop = CIBlockElement::GetByID($prop["VALUE"]);
                            if ($ar_res = $multiprop->GetNext()) {
                                if($prop["CODE"] == 'newbuilding') {
                                    $newbuilding_info = CIBlockElement::GetProperty($ar_res["IBLOCK_ID"], $ar_res["ID"], array("sort" => "asc"), Array());
                                    while ($newbuilding = $newbuilding_info->fetch()) {
                                        $newbuilding_props[$newbuilding["CODE"]] = $newbuilding;
                                    }
                                    if (!empty($newbuilding_props["distance_to_sea"]["VALUE"]))
                                        $prop["VALUE"] = $newbuilding_props["distance_to_sea"]["VALUE"].' '.$newbuilding_props["dimension_distance_to_sea"]["VALUE_ENUM"];
                                    else $prop["VALUE"]= '';
                                    $product_prop["map"] = $newbuilding_props["yandex_map"]["VALUE"];
                                }
                                else $prop["VALUE"] = $ar_res["NAME"];
                            }
                        }
                        if ($prop["PROPERTY_TYPE"] == "L") {
                            $prop["VALUE"] = $prop["VALUE_ENUM"];
                        }
                        if ($prop["PROPERTY_TYPE"] == "G") {
                            $res = CIBlockSection::GetByID(27);
                            if($arRes = $res->Fetch())
                            {
                                $prop["VALUE"] = $arRes;
                                //print_r($arRes);
                            }
                        }
                        if($prop["MULTIPLE"] == "N") {
                            if(empty($product_prop[$prop["CODE"]])) $product_prop[$prop["CODE"]] = $prop["VALUE"];
                        }
                        else {
                            if($prop["CODE"] == "photo_gallery") $product_prop["photo_gallery"][] = CFile::GetPath($prop["VALUE"]);
                            else $product_prop[$prop["CODE"]][] = $prop["VALUE"];
                        }

                    }


                    //print_r($_SESSION);

                    $similar_list = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>$product["IBLOCK_ID"], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y"), false, Array("nPageSize"=>5), Array("ID", "NAME", "IBLOCK_ID"));
                    while($ob = $similar_list->GetNextElement())
                    {
                        $similar_list_ob[] = $ob->GetFields();
                    }


                    $product_data["id"] = $product_info["id"];
                    $product_data["name"] = $product_info["name"];
                    $product_data["link"] = 'https://iprox.ru'.str_replace("#ID#", $product_info["id"], $product_info["detailPageUrl"]);
                    $product_data["description"] = $product_info["detailText"];
                    $product_data["photo"] = $product_prop["photo_gallery"];
                    $product_data["price"] = $this->format($product_prop["price"]);
                    if ($product["IBLOCK_ID"] == 19) {
                        $product_data["price"] = $this->format($product_prop["price_flat_min"]);
                    }
                    $product_data["price_1m"] = $this->format($product_prop["price_1m"]);
                    if ($product["IBLOCK_ID"] == 19) {
                        $product_data["price_1m"] = $this->format($product_prop["price_m2_ot"]);
                    }
                    $product_data["address"] = ''
                        .(!empty($product_prop["city"]) ? $product_prop["city"].', ' : '')
                        //.(!empty($prop_array["district"]) ? $prop_array["district"].', ' : '')
                        .(!empty($product_prop["microdistrict"]) ? $product_prop["microdistrict"].', ' : '')
                        .(!empty($product_prop["street"]) ? $product_prop["street"] : '');


                    //print_r($product_prop);
                    //print_r($_SESSION);
                    //print_r($_SERVER);


                    $product_data["status"] = $product_prop["STATUS"];
                    //$product_data["flat_square"] = $product_prop["flat_square"];
                    $product_data["square"] = ($product_prop["square"] ? $product_prop["square"] : "0");
                    if ($product["IBLOCK_ID"] == 19) {
                        $product_data["square"] = $product_prop["square_ot"];
                    }
                    //$product_data["summary_buildings_square"] = $product_prop["summary_buildings_square"];
                    //$product_data["summary_apartment_square"] = $product_prop["summary_apartment_square"];
                    //$product_data["sector_square"] = $product_prop["sector_square"];
                    $product_data["room_number"] = $product_prop["room_number"];
                    $product_data["realtor"] = $this->getUser($product_prop["realtor"]);
                    $product_data["tel"] = $this->formatPhone($product_prop["tel"]);
                    if ($product["IBLOCK_ID"] == 19) {
                        $product_data["tel"] = $this->formatPhone($product_prop["rieltor_phone"]);
                    }
                    $product_data["floor"] = $product_prop["floor"];
                    //$product_data["number_of_storeys"] = $product_prop["number_of_storeys"];
                    $product_data["decoration"] = $product_prop["decoration"];
                    $product_data["distance_to_sea"] = $product_prop["distance_to_sea"];
                    $product_data["wc"] = $product_prop["wc"];
                    $product_data["have_phone"] = $product_prop["have_phone"];
                    $product_data["have_loggia"] = $product_prop["have_loggia"];
                    $product_data["have_balcony"] = $product_prop["have_balcony"];
                    $product_data["have_furniture"] = $product_prop["have_furniture"];
                    $product_data["can_mortgage"] = $product_prop["can_mortgage"];
                    //$product_data["garage"] = $product_prop["garage"];
                    $product_data["build_year"] = $product_prop["build_year"];
                    $product_data["distance_to_sea"] = $product_prop["newbuilding"];
                    $map = array();
                    $map = explode(",", $product_prop["yandex_map"]);
                    if (!empty($map[0])) {
                        $product_data["longitude"] = $map[1];
                        $product_data["latitude"]= $map[0];
                    }
                    else {
                        $product_data["longitude"] = "";
                        $product_data["latitude"]= "";
                    }
                    $product_data["similar"] = array();

                    foreach ($similar_list_ob as $similar) {
                        $similar_properties = CIBlockElement::GetProperty(
                            $similar["IBLOCK_ID"],
                            $similar["ID"],
                            array("sort" => "asc"),
                            Array(
                                "!ID" => $product["ID"],
                                "room_number" => $product_data["room_number"],
                                "floor" => $product_data["floor"],
                                "price" => $this->format($product_data["price"]),
                                "price_1m" => $this->format($product_data["price_1m"]),
                                "square" => $product_data["square"],
                                "summary_buildings_square" => $product_data["summary_buildings_square"],
                                "summary_apartment_square" => $product_data["summary_apartment_square"],
                            )
                        );
                        while($prop = $similar_properties->Fetch()) {
                            $similar_props[$prop["CODE"]] = $prop;
                        }
                        //print_r($similar_props);
                        $product_data["similar"][] = array(
                            "id" => $similar["ID"],
                            "name" => $similar["NAME"],
                            "price" => ($product["IBLOCK_ID"] == 19 ? $this->format($similar_props["price_flat_min"]["VALUE"]) : $this->format($similar_props["price"]["VALUE"])),
                            "price_1m" => ($product["IBLOCK_ID"] == 19 ? $this->format($similar_props["price_m2_ot"]["VALUE"]) : $this->format($similar_props["price_1m"]["VALUE"])),
                            "address" => ''
                                .(!empty($similar_props["city"]["VALUE"]) ? $this->getPropertyName($similar_props["city"]["VALUE"]).', ' : '')
                                //.(!empty($prop_array["district"]) ? $prop_array["district"].', ' : '')
                                .(!empty($similar_props["microdistrict"]["VALUE"]) ? $this->getPropertyName($similar_props["microdistrict"]["VALUE"]).', ' : '')
                                .(!empty($similar_props["street"]["VALUE"]) ? $similar_props["street"]["VALUE"] : ''),
                            "photo" => CFile::GetPath($similar_props["photo_gallery"]["VALUE"]),
                        );
                    }



                    foreach ($product_data as $i => $value) {
                        if ($value === null) $product_data[$i] = '';
                    }



                    //print_r($product_prop);

                    // createdBy
/*                    $creadted_id = $product_data["createdBy"];
                    unset($product_data["createdBy"]);
                    $product_data["createdBy"] = $this->array_change_keys($this->getUser($creadted_id));*/
                    // createdBy


                    //modifiedBy
/*                    $modified_id = $product_data["modifiedBy"];
                    unset($product_data["modifiedBy"]);
                    $product_data["modifiedBy"] = $this->array_change_keys($this->getUser($modified_id));*/
                    //modifiedBy


                    //$product_data["detailPageUrl"] = str_replace("#ID#", $product_data["id"], $product_data["detailPageUrl"]);
                    //$product_data["listPageUrl"] = str_replace("#IBLOCK_CODE#", $product_data["iblockCode"], $product_data["listPageUrl"]);
                    foreach ($product_data["properties"] as $key => $item_properties) {
                       if ($item_properties["code"] == "photo_gallery") {
                            $product_data["properties"][$key]["value"] = '//'.$_SERVER['SERVER_NAME'].CFile::GetPath($item_properties["value"]);
                       }
                    }
                }
            }
        }
        return $this->changeFormatDate($product_data);
    }

    private function format($price) {
        return number_format($price, 0, ",", " ");
    }

    private function formatPhone($phone)
    {
        if (substr($phone, 0, 1) == 7) $phone = '+' . $phone;
        if (substr($phone, 0, 1) == 8) $phone = '+7' . substr($phone, 1, 99);
        return $phone;
        /*
        if (strlen($phone) >= 12) {
            $code_country   = substr($phone, 0, 2);
            $code_operator  = substr($phone, 2, 3);
            $code_three     = substr($phone, 5, 3);
            $code_first_two = substr($phone, 8, 2);
            $code_last_two  = substr($phone, 10, 2);
            $phone = $code_country.' ('.$code_operator.') '.$code_three.' '.$code_first_two.' '.$code_last_two;
        }
        return $phone;
        */
    }

    private function changeFormatDate($data) {
        $date_properties = array('timestampX', 'dateCreate', 'showCounterStart', 'showCounterStartX');
        foreach ($data as $key => $value) {
            if(!is_array($value)) {
                if(in_array($key, $date_properties)) {
                    $data[$key] = $this->formatDateISO8601($value);
                }
            }
            else {
                foreach ($value as $i => $item) {
                    foreach ($item as $j => $item_value) {
                        if(in_array($j, $date_properties)) {
                            $data[$key][$i][$j] = $this->formatDateISO8601($item_value);
                        }
                    }
                }
            }
        }
        return $data;
    }

    private function formatDateISO8601($date) {
        return date("Y-m-d\TH:i:s", strtotime($date));
    }

    private function array_change_keys($array) {
        $new_array = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $pkey => $pvalue) {
                    $new_pkey = preg_replace_callback('/(?!^)_([a-z])/', function($key){return strtoupper($key[1]);}, $pkey);
                    $new_array[$key][$new_pkey] = ($pvalue == null) ? "" : $pvalue;
                }
            }
            else {
                $new_key = preg_replace_callback('/(?!^)_([a-z])/', function($key){return strtoupper($key[1]);}, $key);
                $new_array[$new_key] = ($value == null) ? "" : $value;
            }
        }
        return $new_array;
    }

    private function getUser($id) {
        $user = CUser::GetByID($id);
        $array_user = $user->Fetch();
        $data = array(
            "id" => (!empty($array_user["ID"]) ? $array_user["ID"] : ""),
//            "login" => $array_user["LOGIN"],
//            "active" => $array_user["ACTIVE"],
            "name" => (!empty($array_user["NAME"]) ? $array_user["NAME"] : ""),
            "lastName" => (!empty($array_user["LAST_NAME"]) ? $array_user["LAST_NAME"] : ""),
//            "email" => $array_user["EMAIL"],
//            "profession" => $array_user["PERSONAL_PROFESSION"],
            "photo" => (!empty($array_user["PERSONAL_PHOTO"]) ? CFile::GetPath($array_user["PERSONAL_PHOTO"]) : ''),
            "mobile" => (!empty($array_user["PERSONAL_MOBILE"]) ? $array_user["PERSONAL_MOBILE"] : ""),
//            "city" => $array_user["UF_CITY"],
//            "private" => $array_user["UF_DISPLAY_PRIVATE"],
//            "acceptRules" => $array_user["UF_ACCEPT_RULES"],
//            "skype" => $array_user["UF_SKYPE"],
//            "aboutMe" => $array_user["UF_ABOUT_ME"],
//            "post" => $array_user["UF_PERSON_POST"],
//            "agentName" => $array_user["UF_AGENT_NAME"],
        );
        return $data;
    }

    private function getPropertyName($id) {
        if ($result = CIBlockElement::GetByID($id)) {
            while ($prop = $result->fetch()) {
                $info = $prop;
            }
            return $info["NAME"];
        }
    }
}