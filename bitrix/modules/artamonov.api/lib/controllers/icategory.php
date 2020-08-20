<?php


namespace Artamonov\Api\Controllers;


use Artamonov\Api\Request;
use Artamonov\Api\Response;
use Bitrix\Main\Loader;
use CIBlockElement;
use CUser;
use CFile;
use CIBlockPropertyEnum;


class iCategory
{
    public function get()
    {
        $arResult = $this->getRequest();
        if(!empty($_GET)) {
            if (!empty($arResult["PARAMETERS"]["get-parameter-0"]))
                $iblock_id = $arResult["PARAMETERS"]["get-parameter-0"]; // Получаем id товара из адреса
            else Response::BadRequest();
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
            $arSelect = Array();


            // City
            if(!empty($_GET["city"])) {
                if(is_array($_GET["city"])) {
                    $city = array("LOGIC" => "OR");
                    foreach ($_GET["city"] as $gkey => $gval) {
                        $city[] = array("=PROPERTY_city" => $gval);
                    }
                }
                else {
                    $city = $_GET["city"];
                }
            }
            else {
                $city = '';
            }


            // District
            if(!empty($_GET["district"])) {
                if(is_array($_GET["district"])) {
                    $district = array("LOGIC" => "OR");
                    foreach ($_GET["district"] as $gkey => $gval) {
                        $district[] = array("=PROPERTY_district" => $gval);
                    }
                }
                else {
                    $district = $_GET["district"];
                }
            }
            else {
                $district = '';
            }


            // Microdistrict
            if(!empty($_GET["microdistrict"])) {
                if(is_array($_GET["microdistrict"])) {
                    $district = array("LOGIC" => "OR");
                    foreach ($_GET["microdistrict"] as $gkey => $gval) {
                        $microdistrict[] = array("=PROPERTY_microdistrict" => $gval);
                    }
                }
                else {
                    $microdistrict = $_GET["microdistrict"];
                }
            }
            else {
                $microdistrict = '';
            }



            // Price
            if(isset($_GET["price_from"]) && isset($_GET["price_to"])) {
                $price = array(
                    "LOGIC" => "AND",
                    array("<=PROPERTY_price" => preg_replace('/\s/', '', $_GET["price_to"])),
                    array(">=PROPERTY_price" => preg_replace('/\s/', '', $_GET["price_from"])),
                );
            }
            else {
                $price = '';
            }


            // Square
            if(isset($_GET["square_from"]) && isset($_GET["square_to"])) {
                $square = array(
                    "LOGIC" => "AND",
                    array("<=PROPERTY_square" => $_GET["square_to"]),
                    array(">=PROPERTY_square" => $_GET["square_from"]),
                );
            }
            else {
                $square = '';
            }


            // Sector Square
            if(isset($_GET["sector_square_from"]) && isset($_GET["sector_square_to"])) {
                $sector_square = array(
                    "LOGIC" => "AND",
                    array("<=PROPERTY_sector_square" => $_GET["sector_square_to"]),
                    array(">=PROPERTY_sector_square" => $_GET["sector_square_from"]),
                );
            }
            else {
                $sector_square = '';
            }


            // Room
            if(!empty($_GET["room"])) {
                if(is_array($_GET["room"])) {
                    $room = array("LOGIC" => "OR");
                    foreach ($_GET["room"] as $gkey => $gval) {
                        if ($gval == "much") {
                            $room[] = array("=PROPERTY_room_number" => 6);
                            $room[] = array("=PROPERTY_room_number" => 7);
                            $room[] = array("=PROPERTY_room_number" => 8);
                            $room[] = array("=PROPERTY_room_number" => 9);
                            $room[] = array("=PROPERTY_room_number" => 10);
                            $room[] = array("=PROPERTY_room_number" => 11);
                            $room[] = array("=PROPERTY_room_number" => 12);
                        }
                        else $room[] = array("=PROPERTY_room_number" => $gval);
                    }
                }
                else {
                    if ($_GET["room"] == "much") {
                        $room = array("LOGIC" => "OR");
                        $room[] = array("=PROPERTY_room_number" => 6);
                        $room[] = array("=PROPERTY_room_number" => 7);
                        $room[] = array("=PROPERTY_room_number" => 8);
                        $room[] = array("=PROPERTY_room_number" => 9);
                        $room[] = array("=PROPERTY_room_number" => 10);
                        $room[] = array("=PROPERTY_room_number" => 11);
                        $room[] = array("=PROPERTY_room_number" => 12);
                    }
                    else $room = $_GET["room"];
                }
            }
            else {
                $room = '';
            }


            // Remont
            if(!empty($_GET["remont"])) {
                if(is_array($_GET["remont"])) {
                    $remont = array("LOGIC" => "OR");
                    foreach ($_GET["remont"] as $gkey => $gval) {
                        $remont[] = array("PROPERTY_decoration" => $gval);
                    }
                }
                else {
                    $remont = $_GET["remont"];
                }
            }
            else {
                $remont = '';
            }


            // Commect Type
            if(!empty($_GET["commerc_type"])) {
                if(is_array($_GET["commerc_type"])) {
                    $commerc_type = array("LOGIC" => "OR");
                    foreach ($_GET["commerc_type"] as $gkey => $gval) {
                        $commerc_type[] = array("=PROPERTY_commerc_type" => $gval);
                    }
                }
                else {
                    $commerc_type = $_GET["commerc_type"];
                }
            }
            else {
                $commerc_type = '';
            }


            if(!empty($_GET["arend"])) {
                $arend = "rent";
            }
            else {
                $arend = "sell";
            }


            if(!empty($_GET["sort"])) {
                if($_GET["sort"] == "price" && $_GET["order"] == "asc") $arSort = array('PROPERTY_price' => 'ASC');
                if($_GET["sort"] == "price" && $_GET["order"] == "desc") $arSort = array('PROPERTY_price' => 'DESC');
                if($_GET["sort"] == "id" && $_GET["order"] == "asc") $arSort = array('ID' => 'ASC');
                if($_GET["sort"] == "id" && $_GET["order"] == "desc") $arSort = array('ID' => 'DESC');
            }


            $arFilter = Array(
                "IBLOCK_ID" => IntVal($id),
                "ACTIVE" => "Y",
            );


            $property_accepted = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$id, "CODE"=>"IS_ACCEPTED"));
            while($enum_fields = $property_accepted->GetNext())
            {
                $accepted[$enum_fields["VALUE"]] = $enum_fields["ID"];
            }
            $arFilter["PROPERTY_IS_ACCEPTED"] = $accepted["Да"];


            if(!empty($city)) {
                if (!is_array($city))  $arFilter["=PROPERTY_city"] = $city;
                else $arFilter[0] = $city;
            }
            if(!empty($district)) {
                if (!is_array($district))  $arFilter["=PROPERTY_district"] = $district;
                else $arFilter[1] = $district;
            }
            if(!empty($microdistrict)) {
                if (!is_array($microdistrict))  $arFilter["=PROPERTY_microdistrict"] = $microdistrict;
                else $arFilter[2] = $microdistrict;
            }
            if(!empty($price)) {
                if (!is_array($price))  $arFilter["=PROPERTY_price"] = $price;
                else $arFilter[3] = $price;
            }
            if(!empty($square)) {
                if (!is_array($square))  $arFilter["=PROPERTY_square"] = $square;
                else $arFilter[4] = $square;
            }
            if(!empty($room)) {
                if (!is_array($room))  $arFilter["=PROPERTY_room_number"] = $room;
                else $arFilter[5] = $room;
            }
            if(!empty($remont)) {
                if (!is_array($remont))  $arFilter["PROPERTY_decoration"] = $remont;
                else $arFilter[6] = $remont;
            }
            if(!empty($sector_square)) {
                if (!is_array($sector_square))  $arFilter["=PROPERTY_sector_square"] = $sector_square;
                else $arFilter[7] = $sector_square;
            }
            if(!empty($commerc_type)) {
                if (!is_array($commerc_type))  $arFilter["=PROPERTY_commerc_type"] = $commerc_type;
                else $arFilter[8] = $commerc_type;
            }
            if(!empty($arend)) {
                $property_enums = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$id, "CODE"=>"advert_type"));
                while($enum_fields = $property_enums->GetNext())
                {
                    $arend_array[$enum_fields["VALUE"]] = $enum_fields["ID"];
                }
                if($arend == "rent") $arFilter["PROPERTY_advert_type"] = $arend_array["Аренда"];
                if($arend == "sell") $arFilter["PROPERTY_advert_type"] = $arend_array["Продажа"];
            }

            $res = CIBlockElement::GetList($arSort, $arFilter, false, Array("nPageSize" => $count, "iNumPage" => $iNumPage, 'checkOutOfRange' => true), $arSelect);
            while ($ob = $res->GetNextElement()) {
                $item = $this->array_change_keys(array_change_key_case($ob->GetFields(), CASE_LOWER));
                $items[] = array(
                    "id"    => $item["id"],
                    "name" => html_entity_decode($item["name"]),
                    "iblockId"  => $item["iblockId"],
                );
            }

            foreach ($items as $key => $value) {
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
                        }
                        if ($prop_item["multiple"] == "N") $prop_array[$prop_item["code"]] = $prop_item["value"];
                            else {
                                if($prop_item["code"] == "photo_gallery") {
                                    if (empty($items[$key]["photo"])) $prop_array["photo"] = CFile::GetPath($prop_item["value"]);
                                }
                                    else $prop_array[$prop_item["code"]] = $prop_item["value"];
                            }
                    }
                $items[$key]["price"] = (!empty($prop_array["price"]) ? $this->format($prop_array["price"]) : "0");
                $items[$key]["price_1m"] = (!empty($prop_array["price_1m"]) ? $this->format($prop_array["price_1m"]) : "0");
                if ($value["iblockId"] == 19) {
                    $items[$key]["price"] = (!empty($prop_array["price_flat_min"]) ? $this->format($prop_array["price_flat_min"]) : 0);
                    $items[$key]["price_1m"] = (!empty($prop_array["price_m2_ot"]) ? $this->format($prop_array["price_m2_ot"]) : 0);
                }

                $items[$key]["photo"] = (!empty($prop_array["photo"]) ? $prop_array["photo"] : "");
                $map = array();
                $map = explode(",", $prop_array["yandex_map"]);
                if (!empty($map[0])) {
                    $items[$key]["longitude"] = $map[1];
                    $items[$key]["latitude"]= $map[0];
                }
                else {
                    $items[$key]["longitude"] = "";
                    $items[$key]["latitude"]= "";
                }
                $items[$key]["address"] = (!empty($prop_array["street"]) ? $prop_array["street"] : '');

                $items[$key]["address"] = $this->clear_tags($items[$key]["address"]);
            }


            $arIBlock = GetIBlock($id);
            $data["id"] = $arIBlock["ID"];
            $data["name"] = $arIBlock["NAME"];
            $data["iNumPage"] = $iNumPage;
            $data["nPageSize"] = $count;
            $data["totalSize"] = CIBlockElement::GetList(Array(), $arFilter, array(), Array("nPageSize" => $count, "iNumPage" => $iNumPage, 'checkOutOfRange' => true), $arSelect);

            if ($items != null || !empty($items)) $data["items"] = $items;
            else $data["items"] = array();
        }
        return $this->changeFormatDate($data);
    }

    private function format($price) {
        return number_format($price, 0, ",", " ");
    }

    private function clear_tags($data) {
        $matches = ["&amp;", "amp;", "quot;"];
        return trim(str_replace($matches, "", strip_tags($data)));
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
        );
        return $data;
    }

}