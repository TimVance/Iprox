<?php


namespace Artamonov\Api;


class Response
{
    private static function setHeaders()
    {
        //header('Powered: Artamonov Denis Pro 2016-'.date('Y'));
        //header('Support: http://artamonov.pro');
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json; charset=utf-8');
    }

    public static function ShowResult($data, $options = false)
    {
        self::setHeaders();
        header('HTTP/1.1 200');
        $error["message"] = 'Successful operation';
        $error["code"] = '200';
        $result = json_encode(['isSuccess' => "true", 'auth' => $_SESSION["auth"], 'auth_message' => $_SESSION["auth_message"], 'auth_user_id' => $_SESSION["userId"], 'data' => $data, "error" => $error], $options);

        if ($error = self::ckeckError()) {
            header('HTTP/1.1 500');
            $result = json_encode(['isSuccess' => "false", 'auth' => $_SESSION["auth"], 'auth_message' => $_SESSION["auth_message"], 'auth_user_id' => $_SESSION["userId"], 'data' => $data, 'result' => $error]);
        }

        echo $result;
        die();
    }

    public static function iShowResult($data, $options = false)
    {
        self::setHeaders();
        header('HTTP/1.1 200');
        $error["message"] = 'Successful operation';
        $error["code"] = '200';
        $result = json_encode(['isSuccess' => "true", 'data' => $data, "error" => $error], $options);

        if ($error = self::ckeckError()) {
            header('HTTP/1.1 500');
            $result = json_encode(['isSuccess' => "false", 'data' => $data, 'result' => $error]);
        }

        echo $result;
        die();
    }

    public static function NoResult($message = 'Product not found', $code = '200')
    {
        self::setHeaders();
        $error["message"] = $message;
        $error["code"] = $code;
        header('HTTP/1.1 404');
        echo json_encode(['isSuccess' => "true", 'auth' => $_SESSION["auth"], 'auth_message' => $_SESSION["auth_message"], 'auth_user_id' => $_SESSION["userId"], "data" => array(), 'error' => $error], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        die();
    }

    public static function iNoResult($message = 'Product not found', $code = '200')
    {
        self::setHeaders();
        $error["message"] = $message;
        $error["code"] = $code;
        header('HTTP/1.1 404');
        echo json_encode(['isSuccess' => "true", "data" => array(), 'error' => $error], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        die();
    }

    public static function NoResultCat($data, $message = 'Products not found', $code = '200')
    {
        self::setHeaders();
        $error["message"] = $message;
        $error["code"] = $code;
        header('HTTP/1.1 404');
        echo json_encode(['isSuccess' => "true", 'auth' => $_SESSION["auth"], 'auth_message' => $_SESSION["auth_message"], 'auth_user_id' => $_SESSION["userId"], "data" => $data, 'error' => $error], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        die();
    }

    public static function iNoResultCat($data, $message = 'Products not found', $code = '200')
    {
        self::setHeaders();
        $error["message"] = $message;
        $error["code"] = $code;
        header('HTTP/1.1 404');
        echo json_encode(['isSuccess' => "true", "data" => $data, 'error' => $error], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        die();
    }

    public static function NoResultProfile($data, $message = 'User not found', $code = '200')
    {
        self::setHeaders();
        $error["message"] = $message;
        $error["code"] = $code;
        header('HTTP/1.1 404');
        echo json_encode(['isSuccess' => "true", 'auth' => $_SESSION["auth"], 'auth_message' => $_SESSION["auth_message"], 'auth_user_id' => $_SESSION["userId"], "data" => $data, 'error' => $error], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        die();
    }

    public static function iNoResultProfile($data, $message = 'User not found', $code = '200')
    {
        self::setHeaders();
        $error["message"] = $message;
        $error["code"] = $code;
        header('HTTP/1.1 404');
        echo json_encode(['isSuccess' => "true", "data" => $data, 'error' => $error], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        die();
    }

    public static function NoResultReg($data, $message = 'User not found', $code = '200')
    {
        self::setHeaders();
        $error["message"] = $message;
        $error["code"] = $code;
        header('HTTP/1.1 404');
        echo json_encode(['isSuccess' => "false", "data" => $data, 'error' => $error], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        die();
    }

    public static function BadRequest($message = 'Bad Request', $code = 404)
    {
        self::setHeaders();
        $error["message"] = $message;
        $error["code"] = $code;
        header('HTTP/1.1 400');
         echo json_encode(['isSuccess' => "false", 'auth' => $_SESSION["auth"], 'auth_message' => $_SESSION["auth_message"], 'auth_user_id' => $_SESSION["userId"], "data" => array(), 'error' => $error],JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        die();
    }

    public static function iBadRequest($message = 'Bad Request', $code = 404)
    {
        self::setHeaders();
        $error["message"] = $message;
        $error["code"] = $code;
        header('HTTP/1.1 400');
        echo json_encode(['isSuccess' => "false", "data" => array(), 'error' => $error],JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        die();
    }

    public static function DenyAccess()
    {
        self::setHeaders();
        header('HTTP/1.1 403');
        echo json_encode(['status' => 403, 'error' => 'Forbidden']);
        die();
    }

    private static function ckeckError() {

        $result = false;

        switch (json_last_error()) {

            case JSON_ERROR_DEPTH:
                $result = 'JSON_ERROR_DEPTH';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $result = 'JSON_ERROR_STATE_MISMATCH';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $result = 'JSON_ERROR_CTRL_CHAR';
                break;
            case JSON_ERROR_SYNTAX:
                $result = 'JSON_ERROR_SYNTAX';
                break;
            case JSON_ERROR_UTF8:
                $result = 'JSON_ERROR_UTF8';
                break;
        }

        return $result;
    }
}