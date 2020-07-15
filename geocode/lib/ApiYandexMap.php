<?php

Class ApiYandexMap {

    var $key = '42aa3ffd-97e2-4858-8cbb-e2f1d0089394';

    function getPos($address) {
        $responce = file_get_contents("https://geocode-maps.yandex.ru/1.x/?apikey=".$this->key."&geocode=".urlencode($address));
        $xml      = simplexml_load_string($responce);
        $json     = json_encode($xml);
        $array    = json_decode($json, TRUE);
        if(!empty($array["GeoObjectCollection"]["featureMember"][0]))
            return explode(" ", $array["GeoObjectCollection"]["featureMember"][0]["GeoObject"]["Point"]["pos"]);
            else return explode(" ", $array["GeoObjectCollection"]["featureMember"]["GeoObject"]["Point"]["pos"]);
    }

    function getDistricts($address) {
        $responce = file_get_contents("https://geocode-maps.yandex.ru/1.x/?apikey=".$this->key."&geocode=".urlencode($address)."&kind=district");
        $xml      = simplexml_load_string($responce);
        $json     = json_encode($xml);
        $array    = json_decode($json, TRUE);
        $data = array();
        if(!empty($array["GeoObjectCollection"]["featureMember"]))
            $data = $array["GeoObjectCollection"]["featureMember"];
        return $data;
    }

}