<?
/**
 * Company developer: Intellectrix            
 * Developer: AXEL (DMITRIEV EVGENIY)                              
 * Site: http://intellectrix.ru/
 * Copyright (c) 2016 Intellectrix
 */

global $MESS;
$strPath2Lang = str_replace("\\", "/", __FILE__);
$strPath2Lang = substr($strPath2Lang, 0, strlen($strPath2Lang)-strlen("/install/index.php"));
include(GetLangFileName($strPath2Lang."/lang/", "/install/index.php"));

$is_not = CAdminNotify::GetList(array(), array('MODULE_ID'=>'intellectrix.facebook'))->Fetch();
if(COption::GetOptionString("intellectrix_facebook", "show_notice") === "Y") {
	if (!CModule::IncludeModule("intellectrix.facebook")) CModule::IncludeModule("intellectrix.facebook");
	$ot = COption::GetOptionString("intellectrix_facebook", "notice_end_token");
	$tt = CIntellectrixFacebook::GetTokenTime();
	if($tt <= $ot) {
		CAdminNotify::Add(array(          
		   'MESSAGE' => str_replace("#DAY#",$tt,GetMessage("INTELLECTRIX_FACEBOOK_NOTICE_END_TOKEN_TEXT")),
		   'TAG' => 'ix_fe_notice_end_token',          
		   'MODULE_ID' => 'intellectrix.facebook',          
		   'ENABLE_CLOSE' => 'Y'
		));
	} else {
		CAdminNotify::DeleteByTag('ix_fe_notice_end_token');
	}
} elseif(count($is_not)>0) {
	CAdminNotify::DeleteByModule('intellectrix.facebook');
}

Class intellectrix_facebook extends CModule {
	var $MODULE_ID = "intellectrix.facebook";
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $DR;

	function intellectrix_facebook() {
		$arModuleVersion = array();
		$path = str_replace("\\", "/", __FILE__);
		$path = substr($path, 0, strlen($path) - strlen("/index.php"));
		include($path."/version.php");

		if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
			$this->MODULE_VERSION = $arModuleVersion["VERSION"];
			$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		}
		$this->MODULE_NAME = GetMessage("INTELLECTRIX_FACEBOOK_INSTALL_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("INTELLECTRIX_FACEBOOK_INSTALL_DESCRIPTION");
		$this->PARTNER_NAME = "Intellectrix";
		$this->PARTNER_URI = "http://intellectrix.ru/";
		$this->DR = $_SERVER["DOCUMENT_ROOT"];
	}
	
	function DoInstall() {
		if (IsModuleInstalled($this->MODULE_ID)) {
			$this->DoUninstall();
			return;
		} else {
			global $APPLICATION, $step;
			$RIGHT = $APPLICATION->GetGroupRight($this->MODULE_ID);
			if ($RIGHT>="W") {
				$this->InstallFiles();
				$this->InstallDB();
				$this->InstallEvents();
				$APPLICATION->IncludeAdminFile(GetMessage("INTELLECTRIX_FACEBOOK_INSTALL_TITLE"), dirname(__FILE__)."/step.php");
			}
		}
	}
	
	function DoUninstall() {
		global $APPLICATION;
		$this->UnInstallDB();
		$this->UnInstallEvents();
		$this->UnInstallFiles();
		$APPLICATION->IncludeAdminFile(GetMessage("INTELLECTRIX_FACEBOOK_UNINSTALL_TITLE"), dirname(__FILE__)."/unstep.php");
	}
	
	function InstallDB() {
		RegisterModule($this->MODULE_ID);
		return true;
	}
	
	function UnInstallDB($arParams = array()) {
		global $APPLICATION, $step;
		
		$this->errors = false;
		COption::RemoveOption("intellectrix_facebook");
		UnRegisterModule($this->MODULE_ID);
		return true;
	}
	
	function InstallEvents() {
		RegisterModuleDependences("iblock",	"OnAfterIBlockElementUpdate",	$this->MODULE_ID,	"CIntellectrixFacebook",	"ElementUpdate");
		RegisterModuleDependences("iblock",	"OnAfterIBlockElementAdd",		$this->MODULE_ID, 	"CIntellectrixFacebook",	"ElementAdd");
	}

	function UnInstallEvents() {
         UnRegisterModuleDependences("iblock",	"OnAfterIBlockElementUpdate",	$this->MODULE_ID, "CIntellectrixFacebook", "ElementUpdate");
         UnRegisterModuleDependences("iblock",	"OnAfterIBlockElementAdd",		$this->MODULE_ID, "CIntellectrixFacebook", "ElementAdd");
	}

	function InstallFiles() {
		CopyDirFiles($this->DR."/bitrix/modules/".$this->MODULE_ID."/install/bitrix/tools/intellectrix.facebook", $this->DR."/bitrix/tools/intellectrix.facebook", true, true);
		return true;
	}

	function UnInstallFiles() {
        DeleteDirFilesEx("/bitrix/tools/intellectrix.facebook");
		return true;
	}
	
	function GetModuleRightList() {
		$arr = array(
			"reference_id" => array("D","R","W"),
			"reference" => array(
				"[D] ".GetMessage("REL_DENIED"),
				"[R] ".GetMessage("REL_VIEW"),
				"[W] ".GetMessage("REL_ADMIN"))
		);
		return $arr;
	}
	
}
?>