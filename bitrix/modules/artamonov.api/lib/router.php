<?php


namespace Artamonov\Api;


class Router extends Init
{
    private static $apiPath;
    private static $apiVersion;
    private static $controller;
    private static $action;
    private static $pathParts;
    private static $params;

    public function start()
    {
        self::$pathParts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        // Api path
        self::$apiPath = strtolower(current(self::$pathParts));
        array_shift(self::$pathParts);
        // Get version
        if ($this->checkUseVersion()) {
            if (current(self::$pathParts)) {
                self::$apiVersion = strtolower(current(self::$pathParts));
                array_shift(self::$pathParts);
            }
        }
        // Get controller
        if (current(self::$pathParts)) {
            self::$controller = strtolower(current(self::$pathParts));
            array_shift(self::$pathParts);
        }
        // Get action
        if (current(self::$pathParts)) {
            self::$action = strtolower(current(self::$pathParts));
            array_shift(self::$pathParts);
        }
        // Get params
        if (parent::getMethod() == Helper::GET) {
            $this->getParamsRequestUri();
        } elseif (parent::getMethod() == Helper::POST) {
            $this->getParamsRequestUri();
            if ($ar = (strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) ? json_decode(file_get_contents('php://input'), true) : $_POST) {
                foreach ($ar as $param => $value) {
                    self::$pathParts[$param] = $value;
                }
            }
        } elseif (
            (
                parent::getMethod() == Helper::PUT ||
                parent::getMethod() == Helper::PATCH ||
                parent::getMethod() == Helper::DELETE ||
                parent::getMethod() == Helper::OPTIONS
            ) &&
            strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false
        ) {
            $this->getParamsRequestUri();
            if ($ar = json_decode(file_get_contents('php://input'), true)) {
                foreach ($ar as $param => $value) {
                    self::$pathParts[$param] = $value;
                }
            }
        } else {
            Response::BadRequest();
        }
        self::$params = (count(self::$pathParts) > 0) ? self::$pathParts : [];
        // Run controller
        if ($this->getController() && $this->getAction()) {
            $controller = new Controller();
            $controller->run();
        } else {
            Response::BadRequest();
        }
        die();
    }

    // ADDITIONAL METHODS

    // PARAMETERS

    private function checkUseVersion()
    {
        return (parent::getParameter()->getValue('USE_VERSIONS') == 'Y') ? true : false;
    }

    public function getApiPath()
    {
        return self::$apiPath;
    }

    public function getApiVersion()
    {
        return self::$apiVersion;
    }

    public function getController()
    {
        return self::$controller;
    }

    public function getAction()
    {
        return self::$action;
    }

    public function getParameters()
    {
        return self::$params;
    }

    private function getParamsRequestUri()
    {
        $_SERVER['REQUEST_URI'] = str_replace('/?', '?', $_SERVER['REQUEST_URI']);
        if (strpos($_SERVER['REQUEST_URI'], '?') !== false) {
            if (strpos(self::$action, '?') !== false) {
                self::$action = explode('?', self::$action)[0];
            }
            array_pop(self::$pathParts);
            if (count(self::$pathParts) > 1) {
                foreach (self::$pathParts as $param => $value) {
                    $tmp['get-parameter-' . $param] = $value;
                }
            } else {
                $tmp = [];
            }
            self::$pathParts = explode('?', $_SERVER['REQUEST_URI']);
            self::$pathParts = (self::$pathParts[1]) ? explode('&', self::$pathParts[1]) : [];
            if (self::$pathParts) {
                foreach (self::$pathParts as $item) {
                    $item = explode('=', $item);
                    $tmp[urldecode($item[0])] = urldecode($item[1]);
                }
                self::$pathParts = $tmp;
            }
        }
    }
}