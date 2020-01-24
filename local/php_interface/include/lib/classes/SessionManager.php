<?

namespace Sessions;

use Bitrix\Main;
/*use Bitrix\Main\EventManager;
//use Bitrix\Security\SessionTable;

$eventManager = EventManager::getInstance();
$_REQUEST["EVENT_SETED"] = $eventManager->addEventHandler(
		"",
		"\Bitrix\Security\SessionTable::OnAfterAdd",
		array("SessionManager","OnSessionAdd")
);
$eventManager->addEventHandler(
		"",
		"Bitrix\Security\SessionTable::OnAfterUpdate",
		array("SessionManager","OnSessionUpdate")
);
$eventManager->addEventHandler(
		"",
		"Bitrix\Security\SessionTable::OnAfterDelete",
		array("SessionManager","OnSessionRemove")
);*/

/*$_REQUEST["EVENT_SETED"] = $eventManager->addEventHandler(
    "main",
    "OnEpilog",
    "TestEventMananger"
);*/

class SessionManager
{
	private function getTableName()
	{
		return "we_session_manager";
	}
	public function getSessionsByUser($USER_ID)
	{
		global $DB;
		$USER_ID = intval($USER_ID);
		if ($USER_ID <= 0)
		{
			return false;
		}
		$query = "SELECT * FROM ".self::getTableName()." WHERE USER_ID = '$USER_ID'";
		return $DB->Query($query,true);
	}
	public function addSessionByUser($SESSION_ID,$USER_ID)
	{
		global $DB;
		$USER_ID = intval($USER_ID);
		if (!$SESSION_ID || $USER_ID <= 0)
		{
			return false;
		}
		
		$query = "INSERT INTO ".self::getTableName()." (`SESSION_ID`,`USER_ID`) VALUES ('$SESSION_ID','$USER_ID')";
		return $DB->Query($query,true);
	}
	public function removeSessionsByUser ($USER_ID,$SESSION_ID)
	{
		global $DB;
		$USER_ID = intval($USER_ID);
		if ($USER_ID <= 0)
		{
			return false;
		}
		$query = "DELETE FROM ".self::getTableName()." WHERE USER_ID = '$USER_ID' ";
		if ($SESSION_ID)
		{
			$query.= " AND SESSION_ID = '$SESSION_ID'";
		}
		$DB->Query($query,true);
	}
	public function OnSessionRemove ()
	{
		AddMessage2Log("here");
	}
	public function OnSessionAdd (\Bitrix\Main\Event $event)
	{
		global $USER;
		$arParam = $event->getParameters();
		AddMessage2Log($arParam);
		AddMessage2Log("here");
	}
	public function OnSessionRemove (\Bitrix\Main\Event $event)
	{
		global $USER;
		$arParam = $event->getParameters();
		AddMessage2Log($arParam);
		AddMessage2Log("here");
	}
	public function OnSessionUpdate (\Bitrix\Main\Event $event)
	{
		global $USER;
		$arParam = $event->getParameters();
		AddMessage2Log($arParam);
		AddMessage2Log("here");
	}
	public function Test(\Bitrix\Main\Event $event)
	{
		$arParam = $event->getParameters();
		AddMessage2Log($arParam);
		AddMessage2Log("here");
	}

}



?>