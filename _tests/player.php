<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");
?><br>
 <br>
 <br>
 <br>
 <br>
<?$APPLICATION->IncludeComponent(
	"bitrix:player",
	"",
	Array(
		"ADDITIONAL_FLASHVARS" => "",
		"ADDITIONAL_WMVVARS" => "",
		"ADVANCED_MODE_SETTINGS" => "N",
		"ALLOW_SWF" => "N",
		"AUTOSTART" => "N",
		"BUFFER_LENGTH" => "",
		"CONTROLBAR" => "",
		"CONTROLS_BGCOLOR" => "",
		"CONTROLS_COLOR" => "",
		"CONTROLS_OVER_COLOR" => "",
		"DOWNLOAD_LINK" => "",
		"DOWNLOAD_LINK_TARGET" => "",
		"FILE_AUTHOR" => "",
		"FILE_DATE" => "",
		"FILE_DESCRIPTION" => "",
		"FILE_DURATION" => "",
		"FILE_TITLE" => "",
		"HEIGHT" => "300",
		"LOGO" => "",
		"LOGO_LINK" => "",
		"LOGO_POSITION" => "",
		"MUTE" => "N",
		"PATH" => "https://www.youtube.com/watch?v=6DllK2-L-Zw",
		"PLAYER_ID" => "",
		"PLAYER_TYPE" => "",
		"PLUGINS" => array(""),
		"PREVIEW" => "",
		"PROVIDER" => "",
		"REPEAT" => "none",
		"SCREEN_COLOR" => "",
		"SHOW_CONTROLS" => "N",
		"SHOW_DIGITS" => "N",
		"SKIN" => "",
		"SKIN_PATH" => "",
		"STREAMER" => "",
		"USE_PLAYLIST" => "N",
		"VOLUME" => "90",
		"WIDTH" => "400",
		"WMODE" => "",
		"WMODE_WMV" => ""
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>