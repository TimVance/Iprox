<?php


namespace Artamonov\Api;


class Request extends Router
{
    public static function get()
    {
        $ar = [
            'DATE' => date('Y-m-d H:i:s'),
            'REQUEST_METHOD' => parent::getMethod(),
            'IP_ADDRESS' => parent::getRealIpAddr(),
            'COUNTRY_CODE' => parent::getCountryCode(),
            'CONTROLLER' => parent::getController(),
            'ACTION' => parent::getAction(),
            'PARAMETERS' => parent::getParameters()
        ];
        if (parent::getApiVersion()) {
            $ar['API_VERSION'] = parent::getApiVersion();
        }
        if ($_SERVER['HTTP_AUTHORIZATION_TOKEN']) {
            $ar['AUTHORIZATION_TOKEN'] = $_SERVER['HTTP_AUTHORIZATION_TOKEN'];
        }
        if ($_SERVER['HTTP_AUTHORIZATION_LOGIN']) {
            $ar['AUTHORIZATION_LOGIN'] = $_SERVER['HTTP_AUTHORIZATION_LOGIN'];
        }
        if ($_SERVER['HTTP_AUTHORIZATION_PASSWORD']) {
            $ar['AUTHORIZATION_PASSWORD'] = $_SERVER['HTTP_AUTHORIZATION_PASSWORD'];
        }
        return $ar;
    }
}