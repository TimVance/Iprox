<?php


namespace Artamonov\Api;


use Bitrix\Main\Application;
use CUser;

class Filter
{
    private $access = true;
    private $parameter;
    private $countryCode;
    private $realIp;
    private $userId;

    public function __construct($parameter, $countryCode, $realIp)
    {
        $this->parameter = $parameter;
        $this->countryCode = $countryCode;
        $this->realIp = $realIp;
    }

    public function check()
    {
        $ar = [
            'checkLoginPassword',
            'checkToken',
            'checkGroup',
            'checkCountry',
            'checkAddress',
            'checkHttps'
        ];
        foreach ($ar as $filter) {
            if ($this->access) {
                $this->$filter();
            } else {
                break;
            }
        }
        return $this->access;
    }

    private function checkLoginPassword()
    {
        if(!empty($_SERVER['HTTP_AUTHORIZATION_LOGIN']) && !empty($_SERVER['HTTP_AUTHORIZATION_PASSWORD'])) {
            $_SESSION["auth_message"] = 'Пользователь не авторизован';
            if ($this->getParameter()->getValue('USE_AUTH_BY_LOGIN_PASSWORD') == 'Y') {
                $this->access = false;


                $login = $_SERVER['HTTP_AUTHORIZATION_LOGIN'];

                $operator = mb_substr($login, 0, 3);
                $code_country = mb_substr($login, 3, 3);
                $first_double = mb_substr($login, 6, 2);
                $second_double = mb_substr($login, 8, 2);
                $login = '+7('.$operator.')'.$code_country.'-'.$first_double.'-'.$second_double;

                $sql = '
                    SELECT
                        ID,
                        PASSWORD,
                        LOGIN
                    FROM
                        b_user
                    WHERE
                        LOGIN="' . $this->DB()->getSqlHelper()->forSql($login, 60) . '"                     
                    LIMIT 1';
                if ($ar = $this->DB()->query($sql)->fetch()) {
                    $_SESSION["auth_message"] = 'Неверный логин и/или пароль';
                    //print_r($ar);
                    $salt = (strlen($ar['PASSWORD']) > 32) ? substr($ar['PASSWORD'], 0, strlen($ar['PASSWORD']) - 32) : '';
                    $this->userId = $ar['ID'];
                    $this->access = ($salt . md5($salt . $_SERVER['HTTP_AUTHORIZATION_PASSWORD']) == $ar['PASSWORD']);
                    //echo 'res='.$this->access;
                    if ($this->access) $_SESSION["userId"] = $this->userId;
                }
                else {
                    $_SESSION["auth_message"] = 'Неверный логин и/или пароль';
                }
            }
        }
        else {
            $this->access = false;
            $_SESSION["auth_message"] = 'Поле логин и/или пароль не заполнены';
        }
    }

    private function checkToken()
    {
        if ($this->getParameter()->getValue('USE_AUTH_TOKEN') == 'Y') {
            $this->access = false;
            if ($token = $_SERVER['HTTP_AUTHORIZATION_TOKEN']) {
                $keyword = str_replace(' ', '', $this->getParameter()->getValue('TOKEN_KEYWORD'));
                if ($keyword) $keyword .= ':';
                $checkKeyword = substr($token, 0, strlen($keyword));
                if ($checkKeyword != $keyword) {
                    $this->access = false;
                    return;
                }
                $token = trim(substr($token, strlen($keyword)));
                $sql = '
                        SELECT
                            VALUE_ID
                        FROM
                            b_uts_user
                        WHERE
                            ' . $this->getParameter()->getUserFieldCodeApiToken() . '="' . $this->DB()->getSqlHelper()->forSql($token, 80) . '"                     
                        LIMIT 1';
                if ($userId = $this->DB()->query($sql)->fetch()['VALUE_ID']) {
                    $this->userId = $userId;
                    $this->access = true;
                    $this->access = true;
                }
            }
        }
    }

    private function checkGroup()
    {
        if (
            $this->getParameter()->getValue('USE_CHECK_USER_GROUP') == 'Y' &&
            (
                $this->getParameter()->getValue('USE_AUTH_BY_LOGIN_PASSWORD') == 'Y' ||
                $this->getParameter()->getValue('USE_AUTH_TOKEN') == 'Y'
            )) {
            $this->access = (array_intersect(CUser::GetUserGroup($this->getUserId()), explode('|', $this->getParameter()->getValue('GROUP_LIST'))));
        }
    }

    private function checkCountry()
    {
        if ($this->getParameter()->getValue('USE_LIST_COUNTRY_FILTER') == 'Y') {
            $ar = $this->getParameter()->getValue('WHITE_LIST_COUNTRY');
            $ar = explode(';', $ar);
            $ar = array_diff($ar, ['']);
            foreach ($ar as &$item) {
                $item = trim($item);
                $item = strtoupper($item);
            }
            if (!in_array($this->getCountryCode(), $ar)) {
                $this->access = false;
            }
        }
    }

    private function checkAddress()
    {
        if ($this->getParameter()->getValue('USE_BLACK_LIST_ADDRESS_FILTER') == 'Y') {
            // Black list
            $arBlack = $this->getParameter()->getValue('BLACK_LIST_ADDRESS');
            $arBlack = explode(';', $arBlack);
            $arBlack = array_diff($arBlack, ['']);
            foreach ($arBlack as &$item) {
                $item = trim($item);
            }
            if (in_array($this->getRealIp(), $arBlack)) {
                $this->access = false;
            }
        }
        if ($this->getParameter()->getValue('USE_WHITE_LIST_ADDRESS_FILTER') == 'Y') {
            // White list
            $arWhite = $this->getParameter()->getValue('WHITE_LIST_ADDRESS');
            $arWhite = explode(';', $arWhite);
            $arWhite = array_diff($arWhite, ['']);
            foreach ($arWhite as &$item) {
                $item = trim($item);
            }
            $this->access = (in_array($this->getRealIp(), $arWhite));
        }
    }

    private function checkHttps()
    {
        if ($this->getParameter()->getValue('ONLY_HTTPS_EXCHANGE') == 'Y' && $_SERVER['SERVER_PORT'] != 443) {
            $this->access = false;
        }
    }

    private function getParameter()
    {
        return $this->parameter;
    }

    private function getCountryCode()
    {
        return $this->countryCode;
    }

    private function getRealIp()
    {
        return $this->realIp;
    }

    private function DB()
    {
        return Application::getConnection();
    }

    private function getUserId()
    {
        return $this->userId;
    }
}