<?
class YandexMap
{
	private $Data = false;
	
	public function Init()
	{
		return new self;
	}
	public function GetAddressInfo($addres)
	{
		
		$this->query("results=1&geocode=".urlencode($addres));
		return $this->Data;
	}
	public function GetPointByAddress($addres)
	{
		$result = $this->GetAddressInfo($addres);//->featureMember->GeoObject["POINT"]
		$ResultPoint = $result->GeoObjectCollection->featureMember->GeoObject->Point->pos;
		if ($ResultPoint)
		{
			$PointData = array_reverse(preg_split('/\s+/', (string)$ResultPoint));
			return implode(",", $PointData);
		}
		else 
		{
			return false;
		}
		
	}
	
	private function query($query)
	{
		$response = file_get_contents("https://geocode-maps.yandex.ru/1.x/?".$query);
		$this->Data = new SimpleXMLElement($response);
	}
	
}