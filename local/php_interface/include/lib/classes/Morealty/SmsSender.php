<? 
namespace Morealty;
use \Morealty\Settings as CSettings;

class SmsSender
{
	
	protected $params = array();
	
	public function __construct($params = array())
	{
		$this->params = $params;
	}
	
	public static function getInstance($params = array())
	{
		return new static($params);
	}
	
	public function sendMessage($number, $message)
	{
		if (!is_array($number))
		{
			$number = array($number);
		}
		$numbersReady = array();
		$NumbersMap = array();
		foreach ($number as $NumberToTest)
		{
			$matches = array();
			if (preg_match_all("/\d+/", $NumberToTest, $matches))
			{
				if ($matches[0] && count($matches[0]) > 0)
				{
					$newNumber = implode("", $matches[0]);
					$NumbersMap[$newNumber] = $NumberToTest;
					$numbersReady[] = $newNumber;
				}
				
			}
		}
		if (!$numbersReady || count($numbersReady) <= 0)
			return false;
		
		$url = new \Bitrix\Main\Web\Uri($this->getApiUrl());
		$url->addParams(array(
				"api_id" => $this->getApiKey(),
				"to" => implode(",", $numbersReady),
				"msg" => $message,
				"json" => 1,
				//"test" => 1
		));
		$body = json_decode(file_get_contents($url->getUri()), true);
		if (!$body || $body["status"] != "OK")
			return false;
		$return = array();
		foreach ($body["sms"] as $numberFromSms => $arSms)
		{
			$realNumber = $NumbersMap[$numberFromSms];
			if ($realNumber)
			{
				$return[$realNumber] = $arSms["status"] == "OK";
			}
			
		}
		return $return;
	}
	
	private function getApiKey()
	{
		return CSettings::BILLING_API_KEY;
	}
	
	private function getApiUrl()
	{
		return CSettings::BILLING_API_URL;
	}
}