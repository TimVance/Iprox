<?php

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule('iblock');


Class getObject {
    var $IBLOCK_ID = 7;
    var $DOMAIN = 'https://iprox.ru';


    function get() {
        $arResult = CIBlockElement::GetList (
            Array("ID" => 'DESC'),
            Array(
                "IBLOCK_ID" => $this->IBLOCK_ID,
                "ACTIVE" => "Y",
                "!PROPERTY_facebook_published" => "1",
                "=PROPERTY_IS_ACCEPTED" => "417",
            ),
            array("ID", "IBLOCK_ID", "NAME", "DETAIL_TEXT"),
            Array("nPageSize" => 1)
        );

        while($item = $arResult->GetNext()) {
            $data["id"] = $item["ID"];
            $data["name"] = $item["NAME"];
            $data["text"] = $item["DETAIL_TEXT"];
            if (CModule::IncludeModule("iblock")) {
                $arProperty = CIBlockElement::GetProperty($this->IBLOCK_ID, $item["ID"], "sort", "asc", array("CODE" => "photo_gallery"));
                while ($property_item = $arProperty->GetNext()) {
                    $data[$property_item["CODE"]][] = $this->DOMAIN.CFile::GetPath($property_item["VALUE"]);
                }
            }
        }
        return $data;
    }


    function setMarker($id) {
        return CIBlockElement::SetPropertyValuesEx($id, false, array("facebook_published" => 1));
    }
}


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_after.php");