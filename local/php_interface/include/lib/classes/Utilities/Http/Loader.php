<?

namespace Utilities\Http;

class Loader
{
	private static $timeoutDefault = 10;
	private static $portDefault = 80;
	private static $arTemplate = array(
			
			"TIMEOUT" => CURLOPT_TIMEOUT,
			"URL"	  => CURLOPT_URL,
			"HEADER"  => CURLOPT_HTTPHEADER,
			"PORT"	  => CURLOPT_PORT,
			
	);
	
	
	
	private $curl = false;
	private $url;
	private $params = array();
	private $customParams = array();
	private $status = false;
	private $result = false;
	
	
	function __construct($url, $arParams = array())
	{
		$this->url = $url;
		$this->setParams($arParams);
	}
	
	public function setParams($arParams)
	{
		if ($arParams["TIMEOUT"])
			$this->setTimeout($arParams["TIMEOUT"]);
		else 
			$this->setTimeout(static::$timeoutDefault);
		
			
		if ($arParams["PORT"])
			$this->setPort($arParams["PORT"]);
		/*else
			$this->setPort(static::$timeoutDefault);*/
		
			
		if ($arParams["HEADER"])
			$this->setHeader($arParams["HEADER"]);
		else 
			$this->setHeader(0);
			
		if ($arParams["CUSTOMPARAMS"] && is_array($arParams["CUSTOMPARAMS"]) && count($arParams["CUSTOMPARAMS"]) > 0)
			$this->customParams = $arParams["CUSTOMPARAMS"];
		
		
	}
	
	public function setTimeout($secs)
	{
		$this->params["TIMEOUT"] = $secs;
	}
	
	public function setPort($port)
	{
		$this->params["PORT"] = $port;
	}
	
	public function setHeader($header)
	{
		$this->params["HEADER"] = $header;
	}
	
	
	private function prepareRequest()
	{
		$this->curl = curl_init();
		curl_setopt($this->curl, CURLOPT_URL, $this->url);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER	, true);
		foreach ($this->params as $paramName => $paramValue)
		{
			$CurlConst = static::$arTemplate[$paramName];
			if ($CurlConst)
			{
				curl_setopt($this->curl, $CurlConst, $paramValue);
			}
		}
		foreach ($this->customParams as $paramConst => $paramValue)
		{
			curl_setopt($this->curl, $paramConst, $paramValue);
		}
	}
	
	private function proccessRequest()
	{
		$this->status = curl_getinfo($this->result, CURLINFO_HTTP_CODE);
	}
	
	public function request()
	{
		$this->prepareRequest();
		
		$this->result = curl_exec($this->curl);
		curl_close($this->curl);
		$this->proccessRequest();
		return $this->result;
	}
	
	public static function getInstance($url,$arParams = array())
	{
		return new static($url, $arParams);
	}
	
	
}