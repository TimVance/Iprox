<? 
class CustomUsers {
	
	/**
	 * 
	 * 
	 * Получаем группы к которым принадлежат пользователи
	 * 
	 * @param int|array $UsersIds
	 * @return array
	 */
	public static function GetUsersGourps ($UsersIds = array())
	{
		
		if (!is_array($UsersIds))
		{
		
			if (intval($UsersIds) > 0)
			{
				$UsersIds = array(intval($UsersIds));
			}
			else
			{
				return false;
			}
			
		}
		else if (count($UsersIds) <= 0)
		{
			return false;
		}
		$Filterar = array();
		foreach ($UsersIds as $UserId)
		{
			
			$Filterar[] = " UG.USER_ID = ".intval($UserId)." ";
		}
		$FilterString = implode(" OR ", $Filterar);
	
		global $DB;
		
		$strSql = "
		SELECT
		UG.USER_ID,
		UG.GROUP_ID,
		".$DB->DateToCharFunction("UG.DATE_ACTIVE_FROM", "FULL")." as DATE_ACTIVE_FROM,
		".$DB->DateToCharFunction("UG.DATE_ACTIVE_TO", "FULL")." as DATE_ACTIVE_TO
		FROM
		b_user_group UG
		WHERE
		".$FilterString;
		
		$res = $DB->Query($strSql, false, "FILE: ".__FILE__."<br> LINE: ".__LINE__);
		
		$arUsers = array();
		while ($QueryEle = $res->GetNext())
		{
			$arUsers[$QueryEle["USER_ID"]][] = $QueryEle["GROUP_ID"];
			
		}
		return $arUsers;
		
	}
	
	/**
	 *
	 *
	 * Получаем ID пользователей принадлежащих к указанным группам
	 *
	 * @param array $arGroups
	 * @return array
	 */
	public function GetUsersByGroups($arGroups)
	{
		
		if (count($arGroups) <= 0)
			return array();
		
		$arReturn = array();

		$rsUsers = CUser::GetList($by = "ID", $order = "asc",array("GROUPS_ID"=>$arGroups),array("FIELDS"=>array("ID")));
		while ($arUser = $rsUsers->GetNext()) {
			$arReturn[] = $arUser["ID"];
		}
		if (count($arReturn) <= 0)
		{
			$arReturn = array(false);
		}
		return  $arReturn;
	}
	/**
	 * Получаем ID застройщиков
	 * 
	 * @return array
	 */
	public function GetBuildersUsers()
	{
		return self::GetUsersByGroups(array(GROUP_ZASTROY));
	}
	
	public static function isUserActive($user_id = false)
	{
		global $USER;
		$target = $user_id;
		if (!$user_id)
			$target = $USER->GetID();
		
		$arGrourps = CUser::GetUserGroup($target);
		
		return (bool) $arGrourps && (in_array(1, $arGrourps) || in_array(16, $arGrourps));
	}
	
	public static function __checkUsersActive()
	{
		$resUsers = CUser::GetList($by = "id", $order = "asc", array(), array("FIELDS" => array("ID")));
		while ($arUser = $resUsers->GetNext())
		{
			static::checkUserActive($arUser["ID"]);
		}
	}
	
	public static function getFullUser($id)
	{
		$arUser = CUser::GetList(
				$by = "id",
				$order = "asc",
				array(
						"ID" => $id,
						
				),
				array(
						"SELECT" => array("UF_*"),
						"FIELDS" => array("*")
				))->GetNext();
		
		return ($arUser) ? $arUser : false;
	}
	
	public static function getUserActiveTime($user_id)
	{
		$arUser = CUser::GetList(
				$by = "id",
				$order = "asc",
				array(
						"ID" => $user_id,
						
				),
				array(
						"SELECT" => array(
								"UF_ACTIVE_TO"
						),
						"FIELDS" => array(
								"ID", "ACTIVE"
						)
				)
		)->GetNext();
		return ($arUser["UF_ACTIVE_TO"])? $arUser["UF_ACTIVE_TO"] : false;
	}
	
	public static function checkUserActive($userID)
	{
		global $DB;
		$objTime = \Bitrix\Main\Type\DateTime::createFromTimestamp(time());
		$timeTO = $objTime->toString();
		//$timeTO = date($DB->DateFormatToPHP("DD-MM-YYYY HH:MM:SS"), time());
		$arUser = CUser::GetList(
				$by = "id",
				$order = "asc",
				array(
						"ID" => $userID,
						"ACTIVE" => "Y",
						">=UF_ACTIVE_TO" => $timeTO
						
				),
				array(
						"SELECT" => array(
								"UF_ACTIVE_TO"
						),
						"FIELDS" => array(
								"ID", "ACTIVE"
						)
				)
		)->GetNext();
		$bFind = (bool) $arUser;
		if (!$bFind)
		{
			
			static::removeUserGroups($userID, array(16));
		}
		else
		{
			static::addUserGroups($userID, array(16));
		}
	}
	
	public static function removeUserGroups($user_id, $arGroups)
	{
		$realGroups = CUser::GetUserGroup($user_id);
		$realGroups = (array) array_diff($realGroups, $arGroups);
		CUser::SetUserGroup($user_id, $realGroups);
	}
	
	public static function addUserRegisterGroup($id)
	{
		static::addUserGroups($id, array(17));
	}
	
	public static function addUserGroups($user_id, $arGroups)
	{
		$realGroups = CUser::GetUserGroup($user_id);
		$realGroups = array_merge($realGroups, $arGroups);
		CUser::SetUserGroup($user_id, $realGroups);
	}
	
	public static function isUserInGroup($user_id, $group)
	{
		return in_array($group, (array) CUser::GetUserGroup($user_id));
	}
	
	public static function getUserIdByLogin($login)
	{
		$arUser = CUser::GetList(
				$by = "id",
				$order = "asc",
				array(
						"LOGIN" => $login,
						"ACTIVE" => "Y",
						
				),
				array(
						"FIELDS" => array(
								"ID", "ACTIVE"
						)
				)
		)->GetNext();
		if ($arUser)
			return $arUser["ID"];
		return false;
	}
	
	public static function addUserActiveTime($seconds, $user_id = false)
	{
		global $USER;
		if (!$user_id)
			$user_id = $USER->GetID();
		
		$arUser = static::getFullUser($user_id);
		$current = time();
		$timeFrom = ($arUser['UF_ACTIVE_TO'])? MakeTimeStamp($arUser['UF_ACTIVE_TO']) : $current;
		$timeFrom +=$seconds;
		
		
		if ($timeFrom < $current)
			$timeFrom = $current;
		$objTime = \Bitrix\Main\Type\DateTime::createFromTimestamp($timeFrom);
		
		$newUser = new CUser();
		$newUser->Update($user_id, array("UF_ACTIVE_TO" => $objTime->toString()));
		
		
	}
}