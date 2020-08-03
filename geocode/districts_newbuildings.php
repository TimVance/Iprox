<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("GeoCode");

exit();

include_once("./lib/ApiYandexMap.php");

$api = new ApiYandexMap();


$page = (!empty($_GET["page"]) ? $_GET["page"] : 1);

$filter = Array(
    "IBLOCK_ID" => "19",
    "ACTIVE" => "Y",
    "!PROPERTY_district_write" => "1"
);

$arResult = CIBlockElement::GetList (
    Array("ID" => 'DESC'),
    $filter,
    array("ID", "IBLOCK_ID", "NAME"),
    Array("nPageSize" => 100, "iNumPage" => $page)
);

$arResultCityes = CIBlockElement::GetList (
    Array("ID" => 'DESC'),
    array("ACTIVE" => "Y", "IBLOCK_ID" => 5),
    array("ID", "IBLOCK_ID", "NAME"),
    Array()
);
$arCities = array();
while($itemCity = $arResultCityes->GetNext()) {
    $arCities[$itemCity["ID"]] = $itemCity["NAME"];
}


$all_count = CIBlockElement::GetList (
    Array("ID" => 'DESC'),
    $filter,
    Array(),
    false,
    Array()
);

$i = 0;
$count_good = 0;
while($item = $arResult->GetNext()) {
    $arFields = $item;
    $count_good++;

    //if ($count_good > 2) break;

    //Получаем свойство значение св-ва $PROPERTY_CODE
    $street = CIBlockElement::GetProperty($arFields["IBLOCK_ID"], $arFields["ID"], "sort", "asc", array("CODE" => "street"));
    while ($ob = $street->GetNext()) $property[$ob["CODE"]] = $ob["VALUE"];

    $city = CIBlockElement::GetProperty($arFields["IBLOCK_ID"], $arFields["ID"], "sort", "asc", array("CODE" => "city"));
    while ($ob = $city->GetNext()) $property[$ob["CODE"]] = $ob["VALUE"];

    $district = CIBlockElement::GetProperty($arFields["IBLOCK_ID"], $arFields["ID"], "sort", "asc", array("CODE" => "district"));
    while ($ob = $district->GetNext()) $property[$ob["CODE"]] = $ob["VALUE"];

    $microdistrict = CIBlockElement::GetProperty($arFields["IBLOCK_ID"], $arFields["ID"], "sort", "asc", array("CODE" => "microdistrict"));
    while ($ob = $microdistrict->GetNext()) $property[$ob["CODE"]] = $ob["VALUE"];

    $yandex_map = CIBlockElement::GetProperty($arFields["IBLOCK_ID"], $arFields["ID"], "sort", "asc", array("CODE" => "yandex_map"));
    while ($ob = $yandex_map->GetNext()) $property[$ob["CODE"]] = $ob["VALUE"];
    $map_array = explode(",", $property["yandex_map"]);

    $data = $api->getDistricts($map_array[1].",".$map_array[0]);

    $district = '';
    $microdistrict = '';

    if (!empty($data[0])) {
        foreach ($data as $object) {
            foreach ($object["GeoObject"]["metaDataProperty"]["GeocoderMetaData"]["Address"]["Component"] as $itemobj) {
                if ($itemobj["kind"] == "district") {
                    if (strpos($itemobj["name"] . '1', 'район1') !== false) $district = trim(str_replace("район", "", $itemobj["name"]));
                    if (strpos('1' . $itemobj["name"], '1микрорайон') !== false) $microdistrict = trim(str_replace("микрорайон", "", $itemobj["name"]));
                }
            }
        }
    }
    else {
        foreach ($data["GeoObject"]["metaDataProperty"]["GeocoderMetaData"]["Address"]["Component"] as $itemobj) {
            if ($itemobj["kind"] == "district") {
                if (strpos($itemobj["name"] . '1', 'район1') !== false) $district = trim(str_replace("район", "", $itemobj["name"]));
                if (strpos('1' . $itemobj["name"], '1микрорайон') !== false) $microdistrict = trim(str_replace("микрорайон", "", $itemobj["name"]));
            }
        }
    }

    $arDistric = array();
    if (!empty($district)) {
        if (empty($property["district"])) {
            // Если район найден и объект имеет пустое значение
            $resDistric = CIBlockElement::GetList(Array(), array("NAME" => $district, "IBLOCK_ID" => 14), false, Array(), $arSelect);
            while($ob = $resDistric->GetNextElement()) $arDistric = $ob->GetFields();
            if (!empty($arDistric["ID"])) {
                // Район найден
                echo 'Район '.$arDistric["NAME"].' найден';
                CIBlockElement::SetPropertyValuesEx($arFields["ID"], false, array("district" => $arDistric["ID"]));
            }
            else {
                // Создаем район и добавляем его id в объект
                $el = new CIBlockElement;
                Global $USER;

                $PROP = array();
                $PROP[185] = $property["city"];

                $arLoadProductArray = Array(
                    "MODIFIED_BY"    => $USER->GetID(),
                    "IBLOCK_SECTION_ID" => false,
                    "IBLOCK_ID"      => 14,
                    "PROPERTY_VALUES"=> $PROP,
                    "NAME"           => $district,
                    "ACTIVE"         => "Y",
                );

                if($PRODUCT_ID = $el->Add($arLoadProductArray)) {
                    CIBlockElement::SetPropertyValuesEx($arFields["ID"], false, array("district" => $PRODUCT_ID));
                    echo '<div>Создаем район '.$district.'</div>';
                }
                else echo "Error: ".$el->LAST_ERROR;
            }
        }
    }
    if (!empty($microdistrict)) {
        if (empty($property["microdistrict"])) {
            // Если микрорайон найден и объект имеет пустое значение
            $resMicroDistric = CIBlockElement::GetList(Array(), array("NAME" => $microdistrict, "IBLOCK_ID" => 15), false, Array(), $arSelect);
            while($ob = $resMicroDistric->GetNextElement()) $arMicroDistric = $ob->GetFields();
            if (!empty($arMicroDistric["ID"])) {
                // Микрорайон найден
                echo 'Микрорайон '.$arMicroDistric["NAME"].' найден';
                CIBlockElement::SetPropertyValuesEx($arFields["ID"], false, array("microdistrict" => $arMicroDistric["ID"]));
            }
            else {
                // Микрорайон не найден (Добавляем новый)
                // Создаем район и добавляем его id в объект
                $el = new CIBlockElement;
                Global $USER;

                $PROP = array();
                if (!empty($arDistric["ID"])) $PROP[186] = $arDistric["ID"];

                $arLoadProductArray = Array(
                    "MODIFIED_BY"    => $USER->GetID(),
                    "IBLOCK_SECTION_ID" => false,
                    "IBLOCK_ID"      => 15,
                    "PROPERTY_VALUES"=> $PROP,
                    "NAME"           => $microdistrict,
                    "ACTIVE"         => "Y",
                );

                if($PRODUCT_ID = $el->Add($arLoadProductArray)) {
                    CIBlockElement::SetPropertyValuesEx($arFields["ID"], false, array("microdistrict" => $PRODUCT_ID));
                echo '<div>Создаем микрорайон '.$microdistrict.'</div>';
                }
                else echo "Error: ".$el->LAST_ERROR;
            }
        }
    }
    echo '<div>'.$item["ID"].')';
    if (!empty($district)) echo ' Район - '.$district.'; ';
    if (!empty($microdistrict)) echo 'Микрорайон - '.$microdistrict.'</div>';
    CIBlockElement::SetPropertyValuesEx($arFields["ID"], false, array("district_write" => "1"));

}

echo '<br />';
echo '<br />';
echo '<br />';
echo '<br />';


echo '<div>Сколько товаров обработано: '.$count_good.'</div>';
echo '<div>Сколько всего осталось товаров: '.$all_count.'</div>';









sleep(10);
if ($page < $all_count / 100) echo "<script>document.location.href='https://iprox.ru/geocode/districts_newbuildings.php';</script>";



require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>