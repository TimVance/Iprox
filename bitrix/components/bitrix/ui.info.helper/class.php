<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Main\Error;

Loc::loadMessages(__FILE__);

class CIntranetNotifySidepanelComponent extends \CBitrixComponent
{
	public function executeComponent()
	{
		global $USER;

		$isBitrix24Cloud = \Bitrix\Main\ModuleManager::isModuleInstalled("bitrix24");

		$notifyUrl = "https://helpdesk.bitrix24.com/widget2/show/code/";
		$this->arResult["NOTIFY_URL"] = CHTTP::urlAddParams($notifyUrl, array(
			   "is_admin" => Loader::includeModule("bitrix24") && \CBitrix24::IsPortalAdmin($USER->GetID()) || !$isBitrix24Cloud && $USER->IsAdmin() ? 1 : 0,
			   "tariff" => COption::GetOptionString("main", "~controller_group_name", ""),
			   "is_cloud" => $isBitrix24Cloud ? "1" : "0",
			   "host"  => $isBitrix24Cloud && defined("BX24_HOST_NAME") ? BX24_HOST_NAME : \CIntranetUtils::getHostName(),
			   "languageId" => LANGUAGE_ID
		   )
		);

		$this->includeComponentTemplate();
	}
}
?>