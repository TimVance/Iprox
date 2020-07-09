<?
/**
 * Company developer: Intellectrix            
 * Developer: AXEL (DMITRIEV EVGENIY)                              
 * Site: http://intellectrix.ru/
 * Copyright (c) 2016 Intellectrix
 */

IncludeModuleLangFile(__FILE__);

$arClassesList = array(
	"CIntellectrixFacebook" => "classes/general/intellectrix_facebook.php",
);

if (method_exists(CModule, "AddAutoloadClasses")) {
	CModule::AddAutoloadClasses("intellectrix.facebook",$arClassesList);
} else {
	foreach ($arClassesList as $sClassName => $sClassFile) {
		require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/intellectrix.facebook/".$sClassFile);
	}
}
?>