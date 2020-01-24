<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

function getCoordNameByAddress($address){
    // удаление лишних пробелов между словами
    $address = preg_replace("/ {2,}/", " ", $address);
    // замена пробелов на плюсы
    $address = str_replace(" ", "+", $address);
    // формируется урл для запроса
    $url_get_coord = "https://geocode-maps.yandex.ru/1.x/?geocode={$address}&format=json&results=1";
    $result = @file_get_contents($url_get_coord);
    // если произошла ошибка при отправке запроса или ответе сервера
    if(!$result) return false;
    $result = json_decode($result);
    // если ни чего не нашлось
    if(count($result->response->GeoObjectCollection->featureMember) == 0) return false;
    // получение координат точки
    $coord = $result->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos;
    return explode(" ", $coord);
}

print_r(getCoordNameByAddress("Сочи, улица Тимирязева, 34Б"));