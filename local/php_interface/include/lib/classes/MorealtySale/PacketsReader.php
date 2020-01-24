<? 
namespace MorealtySale;

/**
 * 
 * @deprecated
 * @author sea
 *
 */
class PacketsReader 
{

	/**
	 *
	 *сколько объектов может создавать пользователь судя по пакетам
	 *Тоже что и \MorealtySale\PacketsReader::getAvalCount() только в более удобном виде
	 *
	 * @param int $USER_ID идентификатор пользователя. По умолчанию текущий пользователь
	 * @return array Массив вида IBLOCK_ID => доступное кол-во для добавления
	 */
	public function getAvalCountSimple ($USER_ID = false)
	{
		global $USER;
		if ($USER_ID === false)
			$USER_ID = $USER->GetID();
		$USER_ID = intval($USER_ID);
	
		$PacketsData = self::getAvalCount($USER_ID);
		if ($PacketsData === true)
		{
			return true;
		}
		$Data = array();
		foreach ($PacketsData as $PacketData)
		{
			$Data[$PacketData["ID"]] = $PacketData["VALUE"];
		}

		return $Data;
	
	}
	
	/**
	 * 
	 * 
	 * сколько объектов может создавать пользователь судя по пакетам
	 * 
	 * @param int $USER_ID идентификатор пользователя. По умолчанию текущий пользователь
	 * @return array Массив с ключами ID - идентификатор инфоблока VALUE - сколько объектов может создавать пользователь
	 */
	public function getAvalCount ($USER_ID = false)
	{
		global $USER;
		if ($USER_ID === false)
			$USER_ID = $USER->GetID();
		$USER_ID = intval($USER_ID);
		
		$PacketsData = self::getUserPackets($USER_ID);
		return self::sumPositions($PacketsData);
		
	}
	/**
	 * 
	 * Суммирует возможности данные пакетами
	 * 
	 * @param  $Packets array Элементов 
	 */
	private function sumPositions ($Packets = array())
	{
		$arPostions = array();
		$isInfinite = false;
		foreach ($Packets as $Packet)
		{
			if ($Packet["INFINITE"] === "Y")
			{
				$isInfinite = true;
				break;
			}
			foreach ($Packet["POSITIONS"] as $Postion)
			{
				if ($Postion["VALUE"])
				{
					$arPostions[$Postion["ID"]]["ID"] = $Postion["ID"];
					$arPostions[$Postion["ID"]]["VALUE"] = ($arPostions[$Postion["ID"]]["VALUE"])? $arPostions[$Postion["ID"]]["VALUE"]+intval($Postion["VALUE"]) : $Postion["VALUE"];
				}
			}
		}
		return ($isInfinite)? true : $arPostions;
	}
	/**
	 *
	 * Форматирует массив пакетов
	 *
	 * @param array $arPackets Массив пакетов (Элементы ИБ)
	 */
	private function formatAllPackets($arPackets = array())
	{
		
		$return = array();
		foreach ($arPackets as $Packet)
		{
			$respose = self::formatPacket($Packet);
			if ($respose)
				$return[] = $respose;
		}
		return $return;
	}
	/**
	 * 
	 * Форматирует 1 пакет
	 * 
	 * @param array $pocket Массив пакета (Элемента ИБ)
	 */
	private function formatPacket($pocket = array())
	{
		if (!is_array($pocket) || count($pocket) <= 0)
			return false;
		
		$return = array();
		if ($pocket["ID"])
		{
			$return["ID"] = $pocket["ID"];
		}
		if ($pocket["PACKET_PRICE"])
		{
			$return["PRICE"] = $pocket["PACKET_PRICE"];
		}
		if ($pocket["NAME"])
		{
			$return["NAME"] = $pocket["NAME"];
		}
		if ($pocket["IBLOCK_ID"])
		{
			$return["IBLOCK_ID"] = $pocket["IBLOCK_ID"];
		}
		if ($pocket["PROPERTIES"]["long_month"]["VALUE"])
		{
			$return["DURATION"] = $pocket["PROPERTIES"]["long_month"]["VALUE"];
		}
		if ($pocket["PROPERTIES"]["price"]["VALUE"])
		{
			$return["PRICE"] = $pocket["PROPERTIES"]["price"]["VALUE"];
		}
		if ($pocket["CNT"])
		{
			$return["MULTIPLIER"] = $pocket["CNT"];
		}
		if ($pocket["PACKET_START"])
		{
			$return["START_DATE"] = $pocket["PACKET_START"];
		}
		if ($pocket["PACKET_END"])
		{
			$return["END_DATE"] = $pocket["PACKET_END"];
		}
		else if ($pocket["TIME_END"])
		{
			$return["END_DATE"] = $pocket["TIME_END"];
		}
		$isInfinite = $pocket["PROPERTIES"]["IS_UNLIMITED"]["VALUE"] == "Y";
		if($isInfinite)
			$return["INFINITE"] = "Y";
		
		$return["POSITIONS"] = self::getPacketPositions($pocket);
		return $return;
	}
	
	private function getPacketPositions($packet = array())
	{
		$positions = array();
		$multiplier = $packet["CNT"];
		
		
		foreach ($packet["PROPERTIES"] as $Prop)
		{
			if (strpos($Prop["CODE"], "count_") !== false)
			{
				$PositionId = intval(str_replace("count_", "", $Prop["CODE"]));
				if ($PositionId <= 0)
					continue;
				
				if ($Prop["VALUE"])
				$positions[$PositionId] = array("ID"=>$PositionId,"VALUE"=>intval($Prop["VALUE"]) * $multiplier);
			}
		}
		return $positions;
	}
	/**
	 * 
	 * 
	 * 
	 * @param int  $USER_ID (по умолчанию текущий)
	 * @return array элементы пакетов из ИБ
	 */
	public function getUserPackets($USER_ID = false)
	{
		global $USER;
		if ($USER_ID === false)
			$USER_ID = $USER->GetID();
		$USER_ID = intval($USER_ID);
		
		$OrderData = Packets::getActivePockets($USER_ID);
		
		
		$Packets = array();
		foreach ($OrderData as $Data)
		{
			foreach ($Data["TRAIN"] as $OrderTrain)
			{
				$Packets[] = $OrderTrain;
			}
		}
		
		return self::formatAllPackets($Packets);
	}
}

?>