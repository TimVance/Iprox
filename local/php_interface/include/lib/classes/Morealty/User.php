<?
namespace Morealty;
use \Morealty\Settings as CSettings;
use MorealtySale;

class User
{
	public static function getClearedPhone($phone)
	{
		$matches = array();
		if (preg_match_all("/\d+/", $phone, $matches))
		{
			if ($matches[0] && count($matches[0]) > 0)
			{
				return implode("", $matches[0]);
			}
			
		}
		return null;
	}
	
	public static function getMaskedPhone($phone)
	{
		
	}
	
	public static function getUserIdByPhone($phone)
	{
		$res = \CUser::GetList(
				$by = "id",
				$order = "asc",
				array(
						"PERSONAL_MOBILE" => $phone,
						"!PERSONAL_MOBILE" => false
						
				), array(
					"FIELDS" => array(
							"ID", "PERSONAL_MOBILE", "ACTIVE"
					)
				)
		);
		if ($arUser = $res->Fetch())
		{
			return $arUser["ID"];
		}
		return false;
	}
	
	private static function generateCodeForUser($id)
	{
		global $APPLICATION;
		$code = randString(4, "0123456789");
		$res = \CUser::GetList(
				$by = "id",
				$order = "asc",
				array(
						"ID" => $id,
						
				), array(
						"FIELDS" => array(
								"ID", "ACTIVE"
						),
						'SELECT' => array("UF_TEMP_PASS_TIME")
				)
		);
		if ($arUser = $res->Fetch())
		{
			
			if ($arUser["UF_TEMP_PASS_TIME"])
			{
				$lastTime = \Bitrix\Main\Type\DateTime::createFromUserTime($arUser["UF_TEMP_PASS_TIME"]);
				if (time() - $lastTime->getTimestamp() < CSettings::$USER_AUTH_DELAY_SEC)
				{
					$APPLICATION->ThrowException("Ошибка отправки смс. Подождите немного. Задержка между отправкой смс с кодом ".CSettings::$USER_AUTH_DELAY_SEC." ".Suffix(CSettings::$USER_AUTH_DELAY_SEC, array("Секунда", "Секунды", "Секунд")));
					return false;
				}
			}
			
		}
		$user = new \CUser();
		$now = new \Bitrix\Main\Type\DateTime();
		$user->Update($id, array(
				"UF_TEMP_PASS" => $code,
				"UF_TEMP_PASS_TIME" => $now
		));
		return $code;
	}
	
	public static function clearUserCode($id)
	{
		$user = new \CUser();
		$user->Update($id, array(
				"UF_TEMP_PASS" => false,
				"UF_TEMP_PASS_TIME" => false
		));
	}
	
	public static function startCodeAuth($phone)
	{
		global $APPLICATION;
		$id = static::getUserIdByPhone($phone);
		
		if (!$id)
		{
			$APPLICATION->ThrowException("Номер не зарегистрирован на сайте!<br> Проверьте номер или зарегистрируйтесь");
			return false;
		}
			
		
		$code = static::generateCodeForUser($id);
		if ($code === false)
			return false;
		$sended = static::sendUserCode($phone, $code);
		if (!$sended)
		{
			$APPLICATION->ThrowException("Ошибка при отправке смс");
		}
		return $sended;
	}
	
	public static function authByCode($phone, $code)
	{
		global $USER;
		$res = \CUser::GetList(
				$by = "id",
				$order = "asc",
				array(
						"PERSONAL_MOBILE" => $phone,
						"!PERSONAL_MOBILE" => false,
						"UF_TEMP_PASS" => $code,
						"!UF_TEMP_PASS" => false
						
				), array(
						"FIELDS" => array(
								"ID", "PERSONAL_MOBILE", "ACTIVE"
						)
				)
				);
		if ($arUser = $res->Fetch())
		{
			$USER->Authorize($arUser["ID"]);
			static::clearUserCode($arUser["ID"]);
			return $arUser["ID"];
		}
		return false;
	}
	
	private static function sendUserCode($phone, $code)
	{
		$smser = SmsSender::getInstance();
		
		return $smser->sendMessage($phone, static::getAuthMessage($code))[$phone];
	}
	
	private static function getAuthMessage($code)
	{
		return str_replace("#CODE#", $code, CSettings::$USER_AUTH_SMS_MESSAGE);
	}
	
	public static function getPacketsArray($USER_ID = false)
	{
		global $USER;
		if (!\Bitrix\Main\Loader::includeModule("sale"))
			return false;
		
		if (!$USER_ID)
			$USER_ID = $USER->GetID();
		$USER_ID = intval($USER_ID);
		return \MorealtySale\PacketsReader::getUserPackets($USER_ID);
	}
	
	public static function updateUsersObjects()
	{
		if (!\Bitrix\Main\Loader::includeModule("iblock"))
			die();
			
			
		$newUser = new \CUser();
		$arUsers = array();
		
		$rs = \CIBlockElement::GetList(array(), array("IBLOCK_TYPE" => "catalog","!PROPERTY_IS_ACCEPTED"=>false , "ACTIVE" => "Y"), array("PROPERTY_realtor"));
		while ($arRes = $rs->Fetch())
		{
			if ($arRes["PROPERTY_REALTOR_VALUE"] && $arRes["CNT"])
			{
				if ($arUsers[$arRes["PROPERTY_REALTOR_VALUE"]])
					$arUsers[$arRes["PROPERTY_REALTOR_VALUE"]]+=$arRes["CNT"];
				else
					$arUsers[$arRes["PROPERTY_REALTOR_VALUE"]]=$arRes["CNT"];
			}
		}
		$filter = array("!UF_REALTOR_OBJECTS" => false);
		$ids = array_keys($arUsers);
		$rsOthers = \CUser::GetList($by = "id", $order = "asc", $filter);
		while ($arCurrentUser = $rsOthers->Fetch())
		{
			if (!in_array($arCurrentUser["ID"], $ids))
				$newUser->Update($arCurrentUser["ID"], array("UF_REALTOR_OBJECTS" => 0));
		}
		foreach ($arUsers as $userID => $arUser)
		{
			$rs = \CUser::GetList($by = "id", $order = "asc", array("ID" => $userID), array("SELECT" => array("UF_REALTOR_OBJECTS"), "FIELDS" => array('ID')));
			if ($arCurrentUser = $rs->Fetch())
			{
				if ($arCurrentUser["UF_REALTOR_OBJECTS"] != $arUser)
				{
					$newUser->Update($userID, array("UF_REALTOR_OBJECTS" => $arUser));
				}
			}
		}
		
	}

}