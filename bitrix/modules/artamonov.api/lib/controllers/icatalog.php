<?php


namespace Artamonov\Api\Controllers;


use Artamonov\Api\Request;
use Artamonov\Api\Response;
use Bitrix\Main\Loader;
use Cmodule;
use CIBlock;


class iCatalog
{
    public function get()
    {
        $arResult = $this->getRequest();
        $iblock_data = $this->getiBlockData();
        if(!empty($iblock_data["items"])) Response::iShowResult($iblock_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        else Response::iNoResultCat($iblock_data);
    }

    // Get current request
    private function getRequest()
    {
        return Request::get();
    }

    // Get data iblock
    private function getiBlockData()
    {
        $data = [];
        $items = [];
        if(CModule::IncludeModule("iblock")) {
            $result = CIBlock::GetList(
                Array(),
                Array(
                    'TYPE' => 'catalog',
                    'SITE_ID' => SITE_ID,
                    'ACTIVE' => 'Y',
                    "CNT_ACTIVE" => "Y",
                    "!CODE" => 'sda'
                ), true
            );
            while ($ar_res = $result->Fetch()) {
                $ar_res["DETAIL_PAGE_URL"] = str_replace("#ID#", $ar_res["ID"], $ar_res["DETAIL_PAGE_URL"]);
                $ar_res["LIST_PAGE_URL"] = str_replace("#IBLOCK_CODE#", $ar_res["IBLOCK_CODE"], $ar_res["LIST_PAGE_URL"]);
                $ar_res["SECTION_PAGE_URL"] = str_replace("#CODE#", $ar_res["CODE"], $ar_res["SECTION_PAGE_URL"]);
                $items[] = $this->array_change_keys(array_change_key_case($ar_res, CASE_LOWER));
            }
        }
        if ($items != null || !empty($items)) $data["items"] = $items;
        else $data["items"] = '';
        return $this->changeFormatDate($data);
    }

    private function changeFormatDate($data) {
        $date_properties = array('timestampX', 'dateCreate', 'showCounterStart', 'showCounterStartX', 'createdDate');
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
            $pos = strpos($key, "~");
            if ($pos !== false) continue;
            $new_key = preg_replace_callback('/(?!^)_([a-z])/', function($key){return strtoupper($key[1]);}, $key);
            $new_array[$new_key] = ($value == null) ? "" : $value;
        }
        return $new_array;
    }

}