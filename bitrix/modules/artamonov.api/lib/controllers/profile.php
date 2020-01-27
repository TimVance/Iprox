<?php


namespace Artamonov\Api\Controllers;


use Artamonov\Api\Request;
use Artamonov\Api\Response;
use Bitrix\Main\Loader;
use CIBlockElement;
use CUser;
use CFile;
use CIBlockPropertyEnum;


class Profile
{
    public function get()
    {
        $arResult = $this->getRequest();

        if (!empty($arResult["PARAMETERS"][0]))
            $id = $arResult["PARAMETERS"][0]; // Получаем id товара из адреса
        else Response::BadRequest();

        $iblock_data = $this->getiBlockData($id);
        //$arResult['OPERATING_METHOD'] = 'OBJECT_ORIENTED';
        //Response::ShowResult($arResult, JSON_UNESCAPED_UNICODE);
        if (!empty($iblock_data)) Response::ShowResult($iblock_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        else Response::NoResultProfile($iblock_data);
    }

    public function fields()
    {
        $data = $this->getFields();
        //$arResult['OPERATING_METHOD'] = 'OBJECT_ORIENTED';
        //Response::ShowResult($arResult, JSON_UNESCAPED_UNICODE);
        if (!empty($data)) Response::ShowResult($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        else Response::NoResultProfile($data);
    }

    public function reg()
    {
        $arResult = $this->getRequest();
        if (!empty($arResult["PARAMETERS"])) {
            $data = $this->regUser($arResult["PARAMETERS"]);
            if ($data == "Пользователь успешно добавлен") Response::ShowResult($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            else Response::NoResultReg($data, "Error");
        }
        else Response::BadRequest();
    }

    public function auth() {
        $arResult = $this->getRequest();
        if (!empty($arResult["PARAMETERS"])) {
            $data = $this->authUser($arResult["PARAMETERS"]);
            if ($data == "Пользователь успешно добавлен") Response::ShowResult($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            else Response::NoResultReg($data, "Error");
        }
        else Response::BadRequest();
    }

    public function update()
    {
        $arResult = $this->getRequest();
        if (!empty($arResult["PARAMETERS"])) {
            $data = $this->updateUser($arResult["PARAMETERS"]);
            if ($data == "Данные пользователя успешно обновлены") Response::ShowResult($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            else Response::NoResultReg($data, "Error");
        }
        else Response::BadRequest();
    }

    // Get current request
    private function getRequest()
    {
        return Request::get();
    }

    // Get data iblock
    private function getiBlockData($id)
    {
        $user = $this->getUser($id);
        if (!empty($user)) {
            $data = $user;
        }
        return $this->changeFormatDate($data);
    }

    private function getFields()
    {
        return $data = array(
            "name"        => "string(50)",
            "second_name" => "string(50)",
            "lastName"    => "string(50)",
            "photo"       => "file",
            "phone"       => "string(255)",
            "email"       => "string(255)",
        );
    }

    private function regUser($params)
    {
        $name             = (!empty($params["name"]) ? $params["name"] : "");
        $second_name      = (!empty($params["second_name"]) ? $params["second_name"] : "");
        $last_name        = (!empty($params["last_name"]) ? $params["last_name"] : "");
        $login            = (!empty($params["login"]) ? $params["login"] : "");
        $email            = (!empty($params["email"]) ? $params["email"] : "");
        $password         = (!empty($params["password"]) ? $params["password"] : "");
        $confirm_password = (!empty($params["confirm_password"]) ? $params["confirm_password"] : "");

        $operator = mb_substr($login, 0, 3);
        $code_country = mb_substr($login, 3, 3);
        $first_double = mb_substr($login, 6, 2);
        $second_double = mb_substr($login, 8, 2);
        $login = '+7('.$operator.')'.$code_country.'-'.$first_double.'-'.$second_double;

        //print_r($login);

        //print_r($login);
        //$arIMAGE = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/images/photo.gif");
        //$arIMAGE["MODULE_ID"] = "main";

        $user     = new CUser;
        $arFields = Array(
            "NAME"             => $name,
            "LAST_NAME"        => $last_name,
            "EMAIL"            => $email,
            "LOGIN"            => $login,
            "LID"              => "ru",
            "ACTIVE"           => "N",
            "GROUP_ID"         => array(17),
            "PASSWORD"         => $password,
            "CONFIRM_PASSWORD" => $confirm_password,
            //"PERSONAL_PHOTO"   => $arIMAGE
        );


        $ID = $user->Add($arFields);
        if (intval($ID) > 0)
            return "Пользователь успешно добавлен";
        else
            return strip_tags($user->LAST_ERROR);

    }

    private function authUser($params) {
        /*
        print_r($params);
        global $USER;
        if (!is_object($USER)) $USER = new CUser;
        $arAuthResult = $USER->Login($params["login"], $params["password"], "Y");
        $APPLICATION->arAuthResult = $arAuthResult;


        $rsUser = CUser::GetByID($USER->GetID());
        $arUser = $rsUser->Fetch();
        if($arUser) {
            echo "<pre>"; print_r($arUser); echo "</pre>";
        }
        else echo 'Авторизация не удалась';

        print_r($_SESSION);
        print_r($_COOKIE);
        */
    }

    private function updateUser($params)
    {

        $id               = (!empty($params["id"]) ? $params["id"] : "");
        $name             = (!empty($params["name"]) ? $params["name"] : "");
        $second_name      = (!empty($params["second_name"]) ? $params["second_name"] : "");
        $last_name        = (!empty($params["last_name"]) ? $params["last_name"] : "");
        //$login            = (!empty($params["login"]) ? $params["login"] : "");
        $email            = (!empty($params["email"]) ? $params["email"] : "");
        $password         = (!empty($params["password"]) ? $params["password"] : "");
        $confirm_password = (!empty($params["confirm_password"]) ? $params["confirm_password"] : "");

        if (!empty($id)) {

            $user   = new CUser;

            if (!empty($name)) $fields["NAME"] = $name;
            if (!empty($second_name)) $fields["SECOND_NAME"] = $second_name;
            if (!empty($last_name)) $fields["LAST_NAME"] = $last_name;
            if (!empty($email)) $fields["EMAIL"] = $email;
            if (!empty($password)) $fields["PASSWORD"] = $password;
            if (!empty($confirm_password)) $fields["CONFIRM_PASSWORD"] = $confirm_password;

            //print_r($fields);

            if (count($fields) > 0) {
                $user->Update($id, $fields);
                $strError .= $user->LAST_ERROR;
                if (!empty($strError)) return $strError;
                else return "Данные пользователя успешно обновлены";
            }
            else {
                return "Не указаны данные для изменения";
            }
        }
        else return "Не указан индектификатор полшьзователя";
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
            $new_key             = preg_replace_callback('/(?!^)_([a-z])/', function ($key) {
                return strtoupper($key[1]);
            }, $key);
            $new_array[$new_key] = ($value == null) ? "" : $value;
        }
        return $new_array;
    }

    private function getUser($id)
    {
        $user       = CUser::GetByID($id);
        $array_user = $user->Fetch();
        if (empty($array_user)) return;
        $data = array(
            "id"          => ($array_user["ID"] ? $array_user["ID"] : ""),
            "name"        => ($array_user["NAME"] ? $array_user["NAME"] : ""),
            "second_name" => ($array_user["SECOND_NAME"] ? $array_user["SECOND_NAME"] : ""),
            "lastName"    => ($array_user["LAST_NAME"] ? $array_user["LAST_NAME"] : ""),
            "photo"       => ($array_user["PERSONAL_PHOTO"] ? CFile::GetPath($array_user["PERSONAL_PHOTO"]) : ""),
            "phone"       => ($array_user["PERSONAL_MOBILE"] ? $array_user["PERSONAL_MOBILE"] : ""),
            "login"       => ($array_user["LOGIN"] ? $array_user["LOGIN"] : ""),
            "email"       => ($array_user["EMAIL"] ? $array_user["EMAIL"] : ""),
            "skype"       => ($array_user["UF_SKYPE"] ? $array_user["UF_SKYPE"] : ""),
            "aboutMe"     => ($array_user["UF_ABOUT_ME"] ? $array_user["UF_ABOUT_ME"] : ""),
        );
        return $data;
    }

}