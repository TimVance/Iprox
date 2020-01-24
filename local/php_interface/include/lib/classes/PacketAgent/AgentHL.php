<?
namespace PacketAgent;

/**
 * Поля 
 * UF_PA_USER - пользователь которому нужно дать пакет
 * UF_PA_PACKET - пакет который нужно дать
 * UF_PA_COUNT	-	Количество
 * 
 * 
 *
 */
class AgentHL extends \Utilities\HL\Highload
{
	
	
	public static function getEntityClass() {
		return "PacketAgent";
	}

	public static function getTableID() {
		return 2;
	}

	
	
	
	
	
	public static function giveUserPacket($USER_ID, $PACKET_ID, $count = 1)
	{
		$DM = static::getDataObject();
		$DM::add(array(
			"UF_PA_USER" => $USER_ID,
			"UF_PA_PACKET"	=> $PACKET_ID,
			"UF_PA_COUNT"	=> $count
		));
	}
	
	public static function removeUserPacketsFromQueue($USER_ID, $PACKET_ID)
	{
		$DM = static::getDataObject();
		/**
		 * 
		 * 
		 * @var \Bitrix\Main\DB\Result $res
		 */
		$res = $DM::getList(array(
				"select" => array("*"),
				"order"	 => array("UF_PA_USER" => "ASC"),
				"filter" => array("UF_PA_USER" => $USER_ID, "UF_PA_PACKET" => $PACKET_ID)
		));
		while ($arObj = $res->fetch())
		{
			if ($arObj["ID"])
			{
				$DM::delete($arObj["ID"]);
			}
		}
	}
	
	
	/**
	 * 
	 * 
	 * @return \Bitrix\Main\DB\Result
	 */
	public static function getAgentDBResult()
	{
		$DM = static::getDataObject();
		return $DM::getList(array(
				"select" => array("*"),
				"order"	 => array("UF_PA_USER" => "ASC"),
		));
	}
}