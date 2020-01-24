<?


use \Bitrix\Main\Loader;
use \Utilities\Sale\Order as CUOrder;
/**
 *
 * Файл для описания обработчиков событий сайта
 * @copyright WebExpert
 *
 */

/**
 * Класс с обработчиками событий сайта
 */

class MorealtyEvents
{

	protected static $__temp_realtors = array();
	
	public static function registerEvents()
	{
		$EventManager = \Bitrix\Main\EventManager::getInstance();
		$EventManager->addEventHandler("main", "OnBeforeUserLogin", array(__CLASS__, "onBeforeUserLogin"));
		$EventManager->addEventHandler("main", "OnAfterUserAdd", array(__CLASS__, "onAfterUserAdd"));
		$EventManager->addEventHandler("main", "OnAfterUserUpdate", array(__CLASS__, "OnAfterUserUpdate"));
		$EventManager->addEventHandler("main", "OnBeforeUserUpdate", array(__CLASS__, "OnBeforeUserUpdate"));
		$EventManager->addEventHandler("main", "OnBeforeUserAdd", array(__CLASS__, "OnBeforeUserUpdate"));
		
		$EventManager->addEventHandler("sale", "OnSaleOrderPaid", array(__CLASS__, "OnSaleOrderPaidUpdateUserDate"));
	
		
		$EventManager->addEventHandler("main", "OnAfterUserAuthorize", array(__CLASS__, "OnAfterUserAuthorizeSetSessionData"));
		$EventManager->addEventHandler("main", "OnBeforeProlog", array(__CLASS__, "OnBeforeProlog"));
		
		
		$EventManager->addEventHandler("iblock", "OnBeforeIBlockElementUpdate", array(__CLASS__, "OnBeforeIBlockElementUpdate"));
		
		$EventManager->addEventHandler("iblock", "OnAfterIBlockElementAdd", array(__CLASS__, "OnAfterIBlockElementAdd"));
		$EventManager->addEventHandler("iblock", "OnAfterIBlockElementUpdate", array(__CLASS__, "OnAfterIBlockElementAdd"));
		$EventManager->addEventHandler("iblock", "OnBeforeIBlockElementDelete", array(__CLASS__, "OnBeforeIBlockElementDelete"));
	}
	
	private static function getRealtorID($element)
	{
		if (!\Bitrix\Main\Loader::includeModule("iblock"))
			return false;
		
		$rs = \CIBlockElement::GetList(array(), array("ID" => intval($element), "!PROPERTY_realtor"), false, false, array("ID", "IBLOCK_ID" , "PROPERTY_realtor"));
		if ($arItem = $rs->Fetch())
		{
			return $arItem["PROPERTY_REALTOR_VALUE"];
		}
		return false;
	}
	
	public static function OnBeforeIBlockElementUpdate($arFields)
	{
		if (in_array($arFields["IBLOCK_ID"], \Morealty\Catalog::getCatalogIblockIds()) && $arFields["ID"])
		{
			$realtor = static::getRealtorID($arFields["ID"]);
			if ($realtor)
			{
				static::$__temp_realtors[$arFields["ID"]] = $realtor;
			}
		}
	}
	
	public static function OnAfterIBlockElementAdd($arFields)
	{

		if (in_array($arFields["IBLOCK_ID"], \Morealty\Catalog::getCatalogIblockIds()) && $arFields["ID"])
		{
			$realtor = static::getRealtorID($arFields["ID"]);
			if ($realtor)
			{
				\Morealty\Realtor::recalcRealtorsObjects($realtor);
			}
			else if (static::$__temp_realtors[$arFields["ID"]])
			{
				\Morealty\Realtor::recalcRealtorsObjects(static::$__temp_realtors[$arFields["ID"]]);
			}
		}
	}
	
	public static function OnBeforeIBlockElementDelete($arFields)
	{
		if (in_array($arFields["IBLOCK_ID"], \Morealty\Catalog::getCatalogIblockIds()) && $arFields["ID"])
		{
			$realtor = static::getRealtorID($arFields["ID"]);
			if ($realtor)
			{
				\Morealty\Realtor::minusRealtorObjects($realtor, 1);
			}
		}
	}
	
	public static function onBeforeUserLogin($arFields)
	{
		if ($arFields["LOGIN"])
		{
			$ID = CustomUsers::getUserIdByLogin($arFields["LOGIN"]);
			if ($ID)
			{
				CustomUsers::addUserRegisterGroup($ID);
			}
		}
	}
	
	public static function OnAfterUserUpdate($arFields)
	{
		if(intval($arFields["ID"]) > 0)
		{
			CustomUsers::checkUserActive($arFields["ID"]);
		}
	}
	
	public static function OnBeforeUserUpdate(&$arFields)
	{
		if (!$arFields["PERSONAL_MOBILE"] && $arFields["ID"])
		{
			$rs = CUser::GetList($by = "id", $order = "asc", array("ID" => $arFields["ID"]));
			if ($arUser = $rs->Fetch())
			{
				if ($arUser["PERSONAL_MOBILE"])
				{
					$arFields["PERSONAL_MOBILE"] = $arUser["PERSONAL_MOBILE"];
				}
			}
		}
		if ($arFields["PERSONAL_MOBILE"])
		{
			$matches = array();
			if (preg_match_all("/\d+/", $arFields["PERSONAL_MOBILE"], $matches))
			{
				if ($matches[0] && count($matches[0]) > 0)
				{
					$newNumber = implode("", $matches[0]);
					if ($newNumber)
					{
						$arFields["UF_PHONE_READY"] = $newNumber;
					}
				}
				
			}
			$now = new \Bitrix\Main\Type\DateTime();
			$arFields["UF_LAST_UPDATE"] = $now->toString();
		}
	}
	
	public static function onAfterUserAdd($arFields)
	{
		if(intval($arFields["ID"]) > 0)
		{
			CustomUsers::addUserRegisterGroup($arFields["ID"]);
			CustomUsers::checkUserActive($arFields["ID"]);
		}
	}
	
	public static function OnSaleOrderPaidUpdateUserDate(\Bitrix\Main\Event $entity)
	{
		if (Loader::includeModule("sale") && Loader::includeModule("iblock"))
		{
			/**
			 * 
			 * 
			 * @var \Bitrix\Sale\Order $order
			 */
			$order = $entity->getParameter("ENTITY");
			if ($order)
			{
				$bPayd = $order->isPaid();
				$PacketProperty = CUOrder::getPropertyByCode($order->getPropertyCollection(), "packet");
				$PacketID = ($PacketProperty) ? $PacketProperty->getValue() : false;
				
				if ($PacketID)
				{
					$Time = \MorealtyEntities\Packet::getPacketLifeTime($PacketID);
					
					if ($Time)
					{
						if (!$bPayd)
						{
							$Time = -1 * abs($Time);
						}
						CustomUsers::addUserActiveTime($Time, $order->getUserId());
					}
				}
			}
		}
	}
	
	
	public static function OnAfterUserAuthorizeSetSessionData($user_fields)
	{
		global $USER;
		
		$id = $USER->GetID();
		$time = \CustomUsers::getUserActiveTime($id);
		if ($time)
		{
			$_SESSION["USER_ACTIVE_TO"] = $time;
		}
	}
	
	public static function OnBeforeProlog()
	{
		global $USER, $APPLICATION;
		if (!defined("ADMIN_SECTION") && strpos($APPLICATION->GetCurPage(), "/bitrix") === false && !$_REQUEST["ajax"])
		{
			$id = $USER->GetID();
			$time = \CustomUsers::getUserActiveTime($id);
			
			$objTime = \Bitrix\Main\Type\DateTime::createFromUserTime($time);
			$objCurrentTime = new \Bitrix\Main\Type\DateTime();
			if ($time && ( $_SESSION["USER_ACTIVE_TO"] != $time || $objTime->getTimestamp() <  $objCurrentTime->getTimestamp() ))
			{
				return false;
				\CustomUsers::checkUserActive($id);
				$USER->Logout();
				$USER->Authorize($id);
			}
		}
		
		
	}
}
?>