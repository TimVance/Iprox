<?
namespace MorealtyEntities;
use \SupElements\Element as TMPElement;


class Packet extends TMPElement
{
	
	
	
	function __afterConstruct()
	{
		parent::__afterConstruct();
		
	}
	
	/**
	 * Получаем пакет по ID
	 *
	 * @param int $id
	 * @return static|false
	 */
	public static function getPacketById($id)
	{
		if (\Bitrix\Main\Loader::includeModule("iblock"))
		{
			$res = \CIBlockElement::GetList(
					array(),
					array(
							"IBLOCK_ID" => 6,
							"ID" => intval($id)
							
					)
					);
			if ($objItem = $res->GetNextElement())
			{
				return static::getByCIBEL($objItem);
			}
		}
		
		return false;
	}
	
	/**
	 * Получаем время жизни пакета в секундах
	 *
	 * @param int $id
	 */
	public static function getPacketLifeTime($id)
	{
		$packet = static::getPacketById($id);
		if ($packet)
		{
			return $packet->getLifeTime();
		}
	}
	
	
	public function getDays()
	{
		return $this->getPropValue("long_days");
	}
	
	
	public function getLifeTime()
	{
		return $this->getDays() * 24 * 3600;
	}
	
}