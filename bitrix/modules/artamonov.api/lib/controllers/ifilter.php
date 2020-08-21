<?php


namespace Artamonov\Api\Controllers;


use Artamonov\Api\Request;
use Artamonov\Api\Response;
use Bitrix\Main\Data\Cache;
use Bitrix\Main\Loader;
use CIBlockElement;
use CUser;
use CFile;
use CIBlockSection;



class iFilter
{
    public function get()
    {
        $arResult = $this->getRequest();
        if(!empty($_GET)) {
            if (!empty($arResult["PARAMETERS"]["arend"]))
                $iblock_id = 7; // Получаем id товара из адреса
            else Response::iBadRequest();
        }
        else {
            if (!empty($arResult["PARAMETERS"][0]))
                $iblock_id = $arResult["PARAMETERS"][0]; // Получаем id товара из адреса
            else Response::iBadRequest();
        }

        if (!empty($iblock_id)) {
            $cache = Cache::createInstance();
            if ($cache->initCache(1, $iblock_id)) {
                $data = $cache->getVars();
            }
            elseif ($cache->startDataCache()) {
                $data = $this->getProductData($iblock_id);
                $cache->endDataCache($data);
            }
        }

        if(!empty($data)) Response::iShowResult($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        else Response::iNoResult();
    }

    // Get current request
    private function getRequest()
    {
        return Request::get();
    }

    // Get data iblock
    private function getProductData($iblock_id)
    {
        $data = [];
        $accept_cityes = array("Сочи", "Адлер", "Краснодар");// Разрешенные города


        if (Loader::includeModule('iblock')) {
            $arSelect = Array("ID", "NAME");
            $arFilterDistrict = Array("IBLOCK_ID"=>14, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
            $arFilterMicroDistrict = Array("IBLOCK_ID"=>15, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
            $arFilterCity = Array("IBLOCK_ID"=>5, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");

            // Города
            if ($result = CIBlockElement::GetList(Array(), $arFilterCity, false, Array(), $arSelect)) {
                while ($item = $result->fetch()) {
                    if (in_array($item["NAME"], $accept_cityes)) $data["city"][] = array(
                        "id" => $item["ID"],
                        "name" => $item["NAME"],
                    );
                }
            }

            // Районы
            if ($result = CIBlockElement::GetList(Array(), $arFilterDistrict, false, Array(), $arSelect)) {
                while ($item = $result->fetch()) {


                    // Находим привязанных родителей
                    $city_properties = CIBlockElement::GetProperty(14, $item["ID"], array("sort" => "asc"), Array());
                    $parent_id = '';
                    while ($md_prop = $city_properties->Fetch()) {
                        $parent_id = (!empty($md_prop["VALUE"]) ? $md_prop["VALUE"] : '');
                    }


                    $data["district"][] = array(
                        "id" => $item["ID"],
                        "name" => $item["NAME"],
                        "city" => $parent_id,
                    );
                }
            }

            // Микрорайоны
            if ($result = CIBlockElement::GetList(Array(), $arFilterMicroDistrict, false, Array(), $arSelect)) {
                while ($item = $result->fetch()) {


                    // Находим привязанных родителей
                    $microd_properties = CIBlockElement::GetProperty(15, $item["ID"], array("sort" => "asc"), Array("CODE" => "district"));
                    $parent_id = '';
                    while ($md_prop = $microd_properties->Fetch()) {
                        $parent_id = (!empty($md_prop["VALUE"]) ? $md_prop["VALUE"] : '');
                    }



                    $data["microdistrict"][] = array(
                        "id" => $item["ID"],
                        "name" => $item["NAME"],
                        "district" => $parent_id,
                    );
                    //print_r($item);
                }
            }


            // Товары
            $items = array();
            $arSelect = Array();
            $arFilter = Array("IBLOCK_ID" => $iblock_id, "ACTIVE_DATE" => "Y", "ACTIVE" => "Y");
            if(!empty($_GET["arend"])) $arFilter["PROPERTY_advert_type"] = "4";
            $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
            if(empty($res)) return false;
            while ($ob = $res->GetNextElement()) {
                $item = $ob->GetFields();
                $items[] = array(
                    "id"   => $item["ID"],
                    "name" => $item["NAME"],
                    //"detailText"  => $item["detailText"],
                    //"shortInfo"  => $item["previewText"],
                    //"createdBy"  => $item["createdBy"],
                    "iblockId"  => $item["IBLOCK_ID"],
                );
            }

            $min = 0;
            $max = 0;
            foreach ($items as $key => $value) {
                $product_properties = CIBlockElement::GetProperty($value["iblockId"], $value["id"], array("sort" => "asc"), Array("CODE" => "PRICE"));
                while ($prop = $product_properties->Fetch()) {
                    if ($prop["VALUE"] > $max) $max = intval($prop["VALUE"]);
                    if ($prop["VALUE"] < $min) $min = intval($prop["VALUE"]);
                }
            }
            $data["price"]["max"] = intval($max);
            $data["price"]["min"] = intval($min);

            // Площадь жилья
            $min = 0;
            $max = 0;
            foreach ($items as $key => $value) {

                if($iblock_id == 8) $product_properties = CIBlockElement::GetProperty($value["iblockId"], $value["id"], array("sort" => "asc"), Array("CODE" => "summary_apartment_square"));
                elseif ($iblock_id == 10) $product_properties = CIBlockElement::GetProperty($value["iblockId"], $value["id"], array("sort" => "asc"), Array("CODE" => "sector_square"));
                elseif ($iblock_id == 19) $product_properties = CIBlockElement::GetProperty($value["iblockId"], $value["id"], array("sort" => "asc"), Array("CODE" => "square"));
                else $product_properties = CIBlockElement::GetProperty($value["iblockId"], $value["id"], array("sort" => "asc"), Array("CODE" => "SQUARE"));

                while ($prop = $product_properties->Fetch()) {
                    if ($prop["VALUE"] > $max) $max = intval($prop["VALUE"]);
                    if ($prop["VALUE"] < $min) $min = intval($prop["VALUE"]);
                }
            }
            $data["square"]["max"] = intval($max);
            $data["square"]["min"] = intval($min);


            // Площадь участка
            if($iblock_id == 8) {
                $min = 0;
                $max = 0;
                foreach ($items as $key => $value) {

                    $product_properties = CIBlockElement::GetProperty($value["iblockId"], $value["id"], array("sort" => "asc"), Array("CODE" => "sector_square"));

                    while ($prop = $product_properties->Fetch()) {

                        if ($prop["VALUE"] > $max) $max = intval($prop["VALUE"]);
                        if ($prop["VALUE"] < $min) $min = intval($prop["VALUE"]);
                    }
                }
                $data["sector_square"]["max"] = intval($max);
                $data["sector_square"]["min"] = intval($min);
            }

            // Комнат
            if(empty($_GET["arend"])) {
                $room              = array();
                $room_sort         = array();
                $array_for_if_room = array();
                foreach ($items as $key => $value) {
                    $product_properties = CIBlockElement::GetProperty($value["iblockId"], $value["id"], array("sort" => "asc"), Array("CODE" => "ROOM_NUMBER"));
                    while ($prop = $product_properties->Fetch()) {
                        if (!in_array($prop["VALUE"], $array_for_if_room) && $prop["VALUE"] !== null) {
                            $array_for_if_room[]                       = $prop["VALUE"];
                            $room[$prop["PROPERTY_VALUE_ID"]]["value"] = $prop["VALUE"];
                            $room[$prop["PROPERTY_VALUE_ID"]]["id"]    = $prop["VALUE"];
                        }
                    }
                }
                foreach ($room as $room_item) {
                    $room_sort[$room_item["value"]] = $room_item;
                }
                asort($room_sort);
                $is_much_room = false;
                foreach ($room_sort as $room_item) {
                    if (intval($room_item["value"]) > 5) $is_much_room = true;
                    else $data["room"][] = $room_item;
                }
                if ($is_much_room) {
                    $data["room"][] = [
                        "id"    => "much",
                        "value" => "Многокомнатные"
                    ];
                }
            }

            /*
            if(empty($_GET["arend"])) {
                $remont       = array();
                $array_for_if = array();
                foreach ($items as $key => $value) {
                    $product_properties = CIBlockElement::GetProperty($value["iblockId"], $value["id"], array("sort" => "asc"), Array("CODE" => "DECORATION"));
                    while ($prop = $product_properties->Fetch()) {
                        if (!in_array($prop["VALUE_ENUM"], $array_for_if) && $prop["VALUE_ENUM"] !== null) {
                            $array_for_if[]                             = $prop["VALUE_ENUM"];
                            $remont[$prop["PROPERTY_VALUE_ID"]]["name"] = $prop["VALUE_ENUM"];
                            $remont[$prop["PROPERTY_VALUE_ID"]]["id"]   = $prop["VALUE"];
                        }
                    }
                }
                foreach ($remont as $remont_item) {
                    $data["remont"][] = $remont_item;
                }
            }
            */


            // Тип коммерции
            if($iblock_id == 11) {
                $commerc_type = array();
                $array_for_if = array();
                foreach ($items as $key => $value) {
                    $product_properties = CIBlockElement::GetProperty($value["iblockId"], $value["id"], array("sort" => "asc"), Array("CODE" => "commerc_type"));
                    while ($prop = $product_properties->Fetch()) {
                        if (!in_array($prop["VALUE_ENUM"], $array_for_if) && $prop["VALUE_ENUM"] !== null) {
                            $array_for_if[] = $prop["VALUE_ENUM"];
                            $commerc_type[$prop["PROPERTY_VALUE_ID"]]["name"] = $prop["VALUE_ENUM"];
                            $commerc_type[$prop["PROPERTY_VALUE_ID"]]["id"] = $prop["VALUE"];
                        }
                    }
                }
                foreach ($commerc_type as $commerc_type_item) {
                    $data["commerc_type"][] = $commerc_type_item;
                }
            }


        }
        return $data;
    }

}