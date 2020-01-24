<?
die();
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

//CustomUsers::__checkUsersActive();
$rsUsers = \Bitrix\Main\UserTable::getList(array(
		"filter" => array("!PERSONAL_MOBILE" => false),
		"select" => array("*", "UF_*")
));
$newUser = new CUser();
while ($arUser = $rsUsers->Fetch())
{
	$matches = array();
	if (preg_match_all("/\d+/", $arUser["PERSONAL_MOBILE"], $matches))
	{
		if ($matches[0] && count($matches[0]) > 0)
		{
			
			$newNumber = implode("", $matches[0]);
			if ($newNumber)
			{
				//$newUser->Update($arUser["ID"], array("UF_PHONE_READY" => $newNumber));
			}
			//my_print_r($newNumber);
		}
		
	}
	my_print_r($arUser);
}