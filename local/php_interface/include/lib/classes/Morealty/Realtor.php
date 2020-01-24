<?
namespace Morealty;
use \Morealty\Settings as CSettings;

class Realtor
{
	
	
	public static function getCurrentSort()
	{
		$params = CSettings::$REALTOR_SORT_PARAMS;
		$defaultSort = false;
		foreach ($params as $PossibleSort)
		{
			if (!$PossibleSort["ID"] && !$defaultSort)
			{
				$defaultSort = $PossibleSort;
				continue;
			}
			if ($_REQUEST["sort"] == $PossibleSort["ID"])
			{
				return $PossibleSort["SORT"];
			}
		}
		return $defaultSort? $defaultSort["SORT"] : array();
	}
	
	public static function getCurrentSortBy()
	{
		return static::getCurrentSort()["BY"];
	}
	
	public static function getCurrentSortOrder()
	{
		return static::getCurrentSort()["ORDER"];
	}
	
	public static function minusRealtorObjects($realtorID, $cnt)
	{
		$newUser = new \CUser();
		$rs = \CUser::GetList($by = "id", $order = "asc", array("ID" => $realtorID), array("SELECT" => array("UF_REALTOR_OBJECTS"), "FIELDS" => array('ID')));
		if ($arUser = $rs->Fetch())
		{
			if ($arUser["UF_REALTOR_OBJECTS"])
			{
				$number = intval($arUser["UF_REALTOR_OBJECTS"] - $cnt);
				if ($number < 0)
					$number = 0;
				$newUser->Update($realtorID, array("UF_REALTOR_OBJECTS" => $number));
			}
		}
	}
	
	public static function recalcRealtorsObjects($realtorsID)
	{
		if (!\Bitrix\Main\Loader::includeModule("iblock"))
			return;
			
		
		$newUser = new \CUser();
		$arUsers = array();
		
		$rs = \CIBlockElement::GetList(array(), array("IBLOCK_TYPE" => "catalog","PROPERTY_realtor" => $realtorsID, "!PROPERTY_IS_ACCEPTED"=>false , "ACTIVE" => "Y"), array("PROPERTY_realtor"));
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
	
	public static function getRealtorsByPhone($phone)
	{
		$return = array();
		if (!$phone || strlen(trim($phone)) <= 0)
			return $return;
		$arFilter = Array(
			"ACTIVE" => "Y",
			"Bitrix\Main\UserGroupTable:USER.GROUP_ID" => '7',
			array(
				"LOGIC" => "OR",
				"?PERSONAL_MOBILE" => $phone,
				"?UF_PHONE_READY" => \Morealty\User::getClearedPhone($phone)
			)
		);
		$rs = \Bitrix\Main\UserTable::getList(array(
			"select" => array("ID"),
			"filter" => $arFilter,
			"limit"	 => 10
		));
		while ($arUser = $rs->fetch())
		{
			$return[] = $arUser["ID"];
		}
		return $return;
	}
}