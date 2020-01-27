<?php


namespace Artamonov\Api;


class Init
{
    const GEOIP = 'Geoip';

    private $parameter;
    public static $realIp;

    public function __construct()
    {
        $this->parameter = new Options();
    }

    public function start()
    {
        // If the module is activated
        if ($this->checkPathApi() && $this->checkUseApi()) {
            $_SESSION["userId"] = '';
            // Check filters
            if ($this->checkFilters()) {
                $_SESSION["auth"] = "success";
                $_SESSION["auth_message"] = 'Авторизация успешна';
                // Set headers
                $this->setHeaders();
                // Run router/dispatch
                $router = new Router();
                $router->start();
            } else {
                // Cross domain
                if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS' && $_SERVER['HTTP_ORIGIN']) {
                    //$_SESSION["auth"] = "deny2";
                    $this->setHeadersPreQuery();
                } else {
                    // Close access
                    //Response::DenyAccess();
                    $_SESSION["auth"] = "deny";
                    // Set headers
                    $this->setHeaders();
                    // Run router/dispatch
                    $router = new Router();
                    $router->start();
                }
            }
            die();
        }
    }

    public function getParameter()
    {
        return $this->parameter;
    }

    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    private function checkUseApi()
    {
        return ($this->getParameter()->getValue('USE_RESTFUL_API') == 'Y') ? true : false;
    }

    private function checkPathApi()
    {
        $apiPath = trim($this->getParameter()->getValue('PATH_RESTFUL_API'), '/');
        $currentModule = explode('/', trim($_SERVER['REQUEST_URI'], '/'))[0];
        $apiModule = explode('/', $apiPath)[0];
        return ($currentModule == $apiModule) ? true : false;
    }

    // FILTERS

    private function checkFilters()
    {
        $filter = new Filter($this->getParameter(), $this->getCountryCode(), $this->getRealIpAddr());
        return $filter->check();
    }

    public function getCountryCode()
    {
        $defaultCountryCode = 'RU';
        if (self::checkLibraryAvailability(self::GEOIP)) {
            $result = ($countryCode = strtoupper(geoip_country_code_by_name(self::$realIp))) ? $countryCode : $defaultCountryCode;
        } else {
            $result = 'Error: the ' . self::GEOIP . ' library was not found';
        }
        return $result;
    }

    public function getRealIpAddr()
    {
        if (!self::$realIp) {
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                self::$realIp = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                self::$realIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                self::$realIp = $_SERVER['REMOTE_ADDR'];
            }
        }
        return self::$realIp;
    }

    // HEADERS

    private function setHeaders()
    {
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization-Token');
        // Cross domain
        header('Access-Control-Allow-Origin: ' . $_SERVER['SERVER_NAME']);
        if ($this->getParameter()->getValue('USE_ACCESS_CONTROL_ALLOW_ORIGIN_FILTER') == 'Y') {
            $ar = $this->getParameter()->getValue('WHITE_LIST_DOMAIN_ACCESS_CONTROL_ALLOW_ORIGIN');
            if (strpos($ar, '*') !== false) {
                header('Access-Control-Allow-Origin: *');
            } else {
                $ar = explode(';', $ar);
                $ar = array_diff($ar, ['']);
                foreach ($ar as &$item) {
                    $item = trim($item);
                    if ($item == $_SERVER['HTTP_ORIGIN']) {
                        header('Access-Control-Allow-Origin: ' . $item);
                        break;
                    }
                }
            }
        }
    }

    private function setHeadersPreQuery()
    {
        header('HTTP/1.0 200');
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization-Token');
        header('Access-Control-Max-Age: 604800'); // 7 days
        if ($this->getParameter()->getValue('USE_ACCESS_CONTROL_ALLOW_ORIGIN_FILTER') == 'Y') {
            $ar = $this->getParameter()->getValue('WHITE_LIST_DOMAIN_ACCESS_CONTROL_ALLOW_ORIGIN');
            if (strpos($ar, '*') !== false) {
                header('Access-Control-Allow-Origin: *');
            } else {
                $ar = explode(';', $ar);
                $ar = array_diff($ar, ['']);
                foreach ($ar as &$item) {
                    $item = trim($item);
                    if ($item == $_SERVER['HTTP_ORIGIN']) {
                        header('Access-Control-Allow-Origin: ' . $item);
                        break;
                    }
                }
            }
        }
    }

    public static function checkLibraryAvailability($libraryCode)
    {
        return array_search(strtolower($libraryCode), get_loaded_extensions()) ? true : false;
    }
}