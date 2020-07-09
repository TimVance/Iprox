<?
IncludeModuleLangFile(__FILE__);
Class oo_payonline extends CModule
{
    //cc
	const MODULE_ID = 'oo.payonline';
	var $MODULE_ID = 'oo.payonline';
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $strError = '';

	function __construct()
	{
		$arModuleVersion = array();
		include(dirname(__FILE__)."/version.php");
		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_NAME = GetMessage("OO_PAYONLINE_PLATEJNYY_MODULQ");
		$this->MODULE_DESCRIPTION = "http://payonline.ru";

		$this->PARTNER_NAME = GetMessage("OO_PAYONLINE_OLEG_ORESTOV");
		$this->PARTNER_URI = "http://olegorestov.ru";
	}

	function InstallDB($arParams = array())
	{
		return true;
	}

	function UnInstallDB($arParams = array())
	{
		return true;
	}

	function InstallEvents()
	{
		return true;
	}

	function UnInstallEvents()
	{
		return true;
	}

	function InstallFiles($arParams = array())
	{

		# /bitrix/php_interface/include/sale_payment
		# /bitrix/tools


        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/oo.payonline/install/payment/",
            $_SERVER["DOCUMENT_ROOT"]."/bitrix/php_interface/include/sale_payment/oo.payonline/");
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/oo.payonline/install/tools/",
            $_SERVER["DOCUMENT_ROOT"]."/bitrix/tools/oo.payonline/");

		return true;
	}

	function UnInstallFiles()
	{
        DeleteDirFilesEx("/bitrix/php_interface/include/sale_payment/oo.payonline");
        DeleteDirFilesEx("/bitrix/tools/oo.payonline/");
		return true;
	}

	function DoInstall()
	{
		global $APPLICATION;
		$this->InstallFiles();
		$this->InstallDB();
		RegisterModule(self::MODULE_ID);
	}

	function DoUninstall()
	{
		global $APPLICATION;
		UnRegisterModule(self::MODULE_ID);
		$this->UnInstallDB();
		$this->UnInstallFiles();
	}
}
?>
