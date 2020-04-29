<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("GeoCode");

include_once("./lib/ApiYandexMap.php");

$api = new ApiYandexMap();



exit;




//$pos = $api->getPos("бульвар зеленые аллеи 3");
//$pos = $api->getPos("40 Лет Победы ул");
//print_r($pos);


//exit;



$page = (!empty($_GET["page"]) ? $_GET["page"] : 1);

$arResult = CIBlockElement::GetList (
    Array("ID" => 'DESC'),
    Array(
        "IBLOCK_ID" => "19",
        "ACTIVE" => "Y",
        "!PROPERTY_geo_generate" => "2",
        "PROPERTY_city" => "13",
    ),
    array("ID", "IBLOCK_ID", "NAME"),
    Array("nPageSize" => 100, "iNumPage" => $page)
);

$all_count = CIBlockElement::GetList (
    Array("ID" => 'DESC'),
    Array(
        "IBLOCK_ID" => "19",
        "ACTIVE" => "Y",
        "!PROPERTY_geo_generate" => "2",
        "PROPERTY_city" => "13",
    ),
    Array(),
    false,
    Array()
);

$i = 0;
$count_good = 0;
while($item = $arResult->GetNext()) {
    $arFields = $item;
    $count_good++;
    //if ($count_good > 1) break;

    //Получаем свойство значение св-ва $PROPERTY_CODE
    $yandex_map = CIBlockElement::GetProperty($arFields["IBLOCK_ID"], $arFields["ID"], "sort", "asc", array("CODE" => "yandex_map"));
    $street = CIBlockElement::GetProperty($arFields["IBLOCK_ID"], $arFields["ID"], "sort", "asc", array("CODE" => "street"));
    while ($ob = $yandex_map->GetNext()) $property[$ob["CODE"]] = $ob["VALUE"];
    while ($ob = $street->GetNext()) $property[$ob["CODE"]] = $ob["VALUE"];

    //print_r($property);

    $city = 'Сочи';
    $pos = $api->getPos($city.' '.$property["street"]);
    $geo = $pos[1].','.$pos[0];
    echo '<div>'.$item["ID"].') '.$city.' '.$property["street"].' ('.$property["yandex_map"].' => '.$geo.')</div>';
    if(!empty($geo)) {
        CIBlockElement::SetPropertyValuesEx($arFields["ID"], false, array("geo_generate" => "2"));
        CIBlockElement::SetPropertyValuesEx($arFields["ID"], false, array("yandex_map" => $geo));
    }

}

echo '<br />';
echo '<br />';
echo '<br />';
echo '<br />';


echo '<div>Сколько товаров обработано: '.$count_good.'</div>';
echo '<div>Сколько всего осталось товаров: '.$all_count.'</div>';










sleep(10);
if ($page < $all_count / 100) echo "<script>document.location.href='https://iprox.ru/geocode/';</script>";



require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>