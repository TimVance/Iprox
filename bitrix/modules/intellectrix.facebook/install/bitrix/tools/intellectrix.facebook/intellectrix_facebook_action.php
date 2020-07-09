<?
/**
 * Company developer: Intellectrix            
 * Developer: AXEL (DMITRIEV EVGENIY)                              
 * Site: http://intellectrix.ru/
 * Copyright (c) 2016 Intellectrix
 */
 
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$MOD_ID = "intellectrix.facebook";
if(CModule::IncludeModule($MOD_ID)) {
	$MODULE_ID = "intellectrix_facebook";
	if($USER->IsAdmin()) {
		$app_id 	= COption::GetOptionString($MODULE_ID, "app_id");
		$app_secret = COption::GetOptionString($MODULE_ID, "app_secret");
		$redirect_uri = 'http://'.$_SERVER["HTTP_HOST"].'/bitrix/tools/'.$MOD_ID.'/intellectrix_facebook_action.php';
		
		if(isset($_REQUEST["code"]) && isset($_REQUEST["state"])) {
			$url_token = "https://graph.facebook.com/oauth/access_token?client_id=".$app_id."&redirect_uri=".urlencode($redirect_uri)."&client_secret=".$app_secret."&code=".$_REQUEST["code"];
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url_token);
			curl_setopt($ch, CURLOPT_POST, 0);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FAILONERROR, 1);
			$text = curl_exec($ch);
			if(!empty($text)) {
				parse_str($text, $parsed_query);
				if(isset($parsed_query["access_token"])) {
					COption::SetOptionString($MODULE_ID,"access_token",$parsed_query["access_token"]);
					if(isset($parsed_query["expires"])) {
						COption::SetOptionString($MODULE_ID,"expires_token",$parsed_query["expires"]);
					}
					COption::SetOptionString($MODULE_ID,"token_get_time",time());
				}
			}
			LocalRedirect('/bitrix/admin/settings.php?lang=ru&mid='.$MOD_ID.'&mid_menu=1');
		}
		if(isset($_REQUEST["action"])) {
			$error = array();
			switch ($_REQUEST["action"]) {
				case "hand_get_token":
					COption::SetOptionString($MODULE_ID,"access_token",0);
					COption::SetOptionString($MODULE_ID,"expires_token",0);
					COption::SetOptionString($MODULE_ID,"token_get_time",0);
					
					if(empty($app_id)) 		$error[] = "NO_app_id";
					if(empty($app_secret))	$error[] = "NO_app_secret";
					if(count($error)) {
						echo json_encode(array('error' => $error));
					} else {
						require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$MOD_ID."/classes/general/Facebook/autoload.php");
						$fb = new Facebook\Facebook(array(
							'app_id'  => $app_id,
							'app_secret' => $app_secret,
							'default_graph_version' => 'v2.5',
						));
					
						$helper = $fb->getRedirectLoginHelper();
						$permissions = array('publish_actions','manage_pages','publish_pages');
						$url = $helper->getLoginUrl($redirect_uri, $permissions);
						echo json_encode(array('url' => $url));
					}
				break;
			}
		}
	}
}