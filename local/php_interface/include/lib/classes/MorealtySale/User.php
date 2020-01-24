<? 
namespace MorealtySale;

class User 
{
	static $Currency = "RUB";
	
	public static $catalog_ib_type = "catalog";
	
	

	/**
	 * Количество объектов созданных пользователем
	 * 
	 * @param int|false $USER_ID
	 * @param int|array $IBLOCK_ID
	 */
	public function currentObjects($USER_ID = false,$IBLOCK_ID = false)
	{
		global $USER;
		if ($USER_ID === false)
			$USER_ID = $USER->GetID();
		$USER_ID = intval($USER_ID);
		
		$arUserCount = array();
		$filter = array(array(
				"LOGIC" => "OR",
				"PROPERTY_CREATED_BY" => $USER_ID,
				"CREATED_BY" => $USER_ID,
		),"IBLOCK_TYPE"=>self::$catalog_ib_type);
		if ($IBLOCK_ID)
		{
			$filter["IBLOCK_ID"] = $IBLOCK_ID;
		}
		$res = \CIBlockElement::GetList(array(),$filter,array("IBLOCK_ID"));
		while ($arItem = $res->GetNext())
		{
			$arUserCount[$arItem["IBLOCK_ID"]] = $arItem["CNT"];
		}
		return $arUserCount;
		
	}
	
	/**
	 * Количество объектов в которых пользователь указан как реелтор
	 *
	 * @param int|false $USER_ID
	 * @param int|array $IBLOCK_ID
	 */
	public function realtorObjects($USER_ID = false,$IBLOCK_ID = false)
	{
		global $USER;
		if ($USER_ID === false)
			$USER_ID = $USER->GetID();
		$USER_ID = intval($USER_ID);
	
		$arUserCount = array();
		$filter = array("PROPERTY_realtor"=>$USER_ID,"IBLOCK_TYPE"=>self::$catalog_ib_type);
		if ($IBLOCK_ID)
		{
			$filter["IBLOCK_ID"] = $IBLOCK_ID;
		}
		$res = \CIBlockElement::GetList(array(),$filter,array("IBLOCK_ID"));
		while ($arItem = $res->GetNext())
		{
			$arUserCount[$arItem["IBLOCK_ID"]] = $arItem["CNT"];
		}
		return $arUserCount;
	
	}
	/**
	 * 
	 * 
	 * 
	 * @param int $to ID инфоблока куда хотим добавлять
	 * @param int $USER_ID По умолчанию текущий пользователь
	 */
	public function canUserAddTo($to,$USER_ID = false)
	{
		return true;
		/*$USER_ADDED_OBJECTS = ($GLOBALS['objectsIDs'] === false)? 0 : count($GLOBALS['objectsIDs']);
		if ($USER_ADDED_OBJECTS == 0)
			return true;*/
		$return = Ability::HowMuchUserCanAddTo($to,$USER_ID);
		if ($return === true)
			return true;
		else 
		return  $return > 0;
		
	}
	
	/**
	 * 
	 * Возращает текущее состояние счета
	 * 
	 * 
	 * @param int $USER_ID по умолчанию текущий
	 */
	public function getUserAccount ($USER_ID = false)
	{
		global $USER;
		if ($USER_ID === false)
			$USER_ID = $USER->GetID();
		$USER_ID = intval($USER_ID);
		$user_Account = \CSaleUserAccount::GetByUserID($USER_ID, self::$Currency);
		if ($user_Account["LOCKED"] != "Y" && $user_Account["CURRENT_BUDGET"])
		{
			return $user_Account["CURRENT_BUDGET"];
		}
		return false;
	}
	
	
	/**
	 * 
	 * Это потом нужно доработать, бо сделал, то что первое пришло на ум.
	 * 
	 * 
	 * @param int $PRODUCT_ID ID продукта
	 * @param int $quantity по умолчанию 1
	 * @return boolean|number
	 */
	public function getProductPrice ($PRODUCT_ID,$quantity = false)
	{
		$PRODUCT_ID = intval($PRODUCT_ID);
		if ($PRODUCT_ID <= 0) {
			return false;
		}
		
			
		$arBasePrice = \CCatalogProduct::GetOptimalPrice($PRODUCT_ID,$quantity);
		if ($arBasePrice["RESULT_PRICE"]["DISCOUNT_PRICE"])
		{
			if ($quantity)
			{
				return $arBasePrice["RESULT_PRICE"]["DISCOUNT_PRICE"]*intval($quantity);
			}
			else 
			{
				return $arBasePrice["RESULT_PRICE"]["DISCOUNT_PRICE"];
			}
		}
		return false;
	}
	
	public function canUserBuyIt($PRODUCT_ID,$quantity,$USER_ID = false)
	{
		global $USER;
		if ($USER_ID === false)
			$USER_ID = $USER->GetID();
		$USER_ID = intval($USER_ID);
		
		
		return self::getUserAccount($USER_ID) >= self::getProductPrice($PRODUCT_ID,$quantity);
		
	}
	
	/**
	 * 
	 * Возхвращает текущие пакеты и их количество
	 * 
	 * @param int USER_ID по умолчанию текущий
	 * @return array Вида ID=>ИД пакета  NAME=> Название пакета CNT=> количетсов данного пакета
	 */
	public function getActivePackets($USER_ID = false)
	{
		global $USER;
		if ($USER_ID === false)
			$USER_ID = $USER->GetID();
		$USER_ID = intval($USER_ID);
		$OrderedPackets = PacketsReader::getUserPackets($USER_ID);
		$arCurrentObjects = static::currentObjects($USER_ID);
		
		$arpackets = array();
		foreach ($OrderedPackets as $arPacket)
		{
			
			if ($arpackets[$arPacket["ID"]])
			{
				$arpackets[$arPacket["ID"]]["CNT"] +=$arPacket["MULTIPLIER"];
			}
			else 
			{
				$arpackets[$arPacket["ID"]] = array("NAME"=>$arPacket["NAME"],"CNT"=>$arPacket["MULTIPLIER"],'ID'=>$arPacket["ID"], "PRICE" => $arPacket["PRICE"]);
			}
			
			if ($arPacket["START_DATE"] && $arPacket["END_DATE"])
			{
				$arpackets[$arPacket["ID"]]["DATES"][] = array("START_DATE" => $arPacket["START_DATE"], "END_DATE" => $arPacket["END_DATE"]);
			}
			if ($arPacket["POSITIONS"])
			{
				foreach ($arPacket["POSITIONS"] as $IblockID => $arIblockInfo)
				{
					if ($arpackets[$arPacket["ID"]]["POSITIONS"][$IblockID])
					{
						$arpackets[$arPacket["ID"]]["POSITIONS"][$IblockID]["VALUE"] += $arIblockInfo["VALUE"];
					}
					else 
					{
						$arpackets[$arPacket["ID"]]["POSITIONS"][$IblockID] = $arIblockInfo;
					}
				}
			}
		}
		foreach ($arpackets as $id => $arPacket)
		{
			$arpackets[$id]["REAL_POSITIONS"] = $arPacket["POSITIONS"];
		}
		if ($arCurrentObjects && count($arCurrentObjects) > 0)
		{
			foreach ($arpackets as $id => $arPacket)
			{
				foreach ($arPacket["REAL_POSITIONS"] as $positionID => $arPosition)
				{
					foreach ($arCurrentObjects as $IblockObjects => $IblockObjectsCount)
					{
						if ($arPosition["VALUE"] > 0 && $IblockObjectsCount > 0 && $IblockObjects == $arPosition["ID"])
						{
							$newVal = $arpackets[$id]["REAL_POSITIONS"][$positionID]["VALUE"];
							$newVal -= 
							($IblockObjectsCount > $arpackets[$id]["REAL_POSITIONS"][$positionID]["VALUE"])?
							$arpackets[$id]["REAL_POSITIONS"][$positionID]["VALUE"] : $IblockObjectsCount;
							$arpackets[$id]["REAL_POSITIONS"][$positionID]["VALUE"] = $newVal;
							$arCurrentObjects[$IblockObjects] -= $arpackets[$id]["REAL_POSITIONS"][$positionID]["VALUE"] - $newVal;
							
						}
					}
				}
			}
		}
		unset($arPacket);
		return $arpackets;
	}
	
	public static function isUserRealtor($USER_ID)
	{
		return in_array(7, \CUser::GetUserGroup($USER_ID));
	}
}
?>