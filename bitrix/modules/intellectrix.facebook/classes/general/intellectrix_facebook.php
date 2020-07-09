<?
/**
 * Company developer: Intellectrix            
 * Developer: AXEL (DMITRIEV EVGENIY)                              
 * Site: http://intellectrix.ru/
 * Copyright (c) 2016 Intellectrix
 */

IncludeModuleLangFile(__FILE__);

class CIntellectrixFacebook {
	function lang() {
		global $MESS;
		return $MESS;
	}
	
	function GetToken() {
		$access_token = COption::GetOptionString("intellectrix_facebook", "access_token");
		if($access_token && !empty($access_token)) return $access_token;
		return false;
	}
	
	function GetTokenTime() {
		$expires_token = COption::GetOptionString("intellectrix_facebook", "expires_token");
		$token_get_time = COption::GetOptionString("intellectrix_facebook", "token_get_time");
		if($expires_token && $token_get_time) {
			return round(($token_get_time+$expires_token-time())/3600/24);
		}
		return false;
	}
	
	function GetIbGroupFB () {
		$result = array();
		$a = COption::GetOptionString("intellectrix_facebook","ib_and_group_fb");
		if(!empty($a)) {
			$a = explode(";",$a);
			if(isset($a[0]) && !empty($a[0])) {
				foreach($a as $b) {
					$b = explode("|",$b);
					if(count($b)==2) {
						$result[$b[0]] = $b[1];
					}
				}
			}
		}
		return $result;
	}
	
	function _replace ($text) {
		$text = str_ireplace(array('<br />','<br>','<br/>'),"\n",$text);
		$text = html_entity_decode(strip_tags($text));
		return $text;
	}
	
	function ElementUpdate(&$arFields) {
		CIntellectrixFacebook::_postFB($arFields,'update');
	}
	
	function ElementAdd(&$arFields) {
		CIntellectrixFacebook::_postFB($arFields);
	}
	
	function _init_fb() {
		require_once __DIR__.'/Facebook/autoload.php';
		$fb = new Facebook\Facebook(array(
			'app_id'  		=> COption::GetOptionString("intellectrix_facebook", "app_id"),
			'app_secret' 	=> COption::GetOptionString("intellectrix_facebook", "app_secret"),
			'default_graph_version' => 'v2.5',
		));
		return $fb;
	}
	
	function add_post ($params, $group_id) {
		try {
			$response = CIntellectrixFacebook::_init_fb()->post("/{$group_id}/feed", $params, CIntellectrixFacebook::GetToken());
			$graphNode = $response->getGraphNode();
			return $graphNode['id'];
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			return false;
		}
	}
	
	function update_post ($params, $post_id) {
		try {
			$request = CIntellectrixFacebook::_init_fb()->sendRequest("POST","/{$post_id}", $params, CIntellectrixFacebook::GetToken());
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			return false;
		}
	}
	
	function _postFB($arFields,$action = 'add') {
		$arrIbGroup = CIntellectrixFacebook::GetIbGroupFB();
		if(isset($arrIbGroup[$arFields["IBLOCK_ID"]])) {
			$group_id = $arrIbGroup[$arFields["IBLOCK_ID"]];
			$ibp = new CIBlockProperty;
			$flash_prop = $ibp->GetList(array(), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$arFields["IBLOCK_ID"], "CODE" => "_INX_FB_ID_POST"))->Fetch();
			if(!$flash_prop) {
				$ibp->Add(array(
					"NAME" 			=> GetMessage('INTELLECTRIX_FACEBOOK_NAME_PROPERTY_FB_ID_POST'),
					"ACTIVE" 		=> "Y",
					"CODE" 			=> "_INX_FB_ID_POST",
					"PROPERTY_TYPE" => "S",
					"IBLOCK_ID"		=> $arFields["IBLOCK_ID"],
				));
			}
			$ID_POST_FB = CIBlockElement::GetProperty($arFields["IBLOCK_ID"],$arFields["ID"],array(),array("CODE"=>"_INX_FB_ID_POST"))->Fetch();
			$ID_POST_FB = $ID_POST_FB["VALUE"];
	
			$PHOTO_FOR_FB = false;
			$SITE_URL = (($_SERVER["SERVER_PORT"] == 80)?'http':'https').'://'.$_SERVER["HTTP_HOST"];
			$arr = CIBlockElement::GetByID($arFields["ID"])->GetNext();
			if(!$PHOTO_FOR_FB) {
				if(is_numeric($arr["PREVIEW_PICTURE"])) {
					$path = CFile::GetPath($arr["PREVIEW_PICTURE"]);
					if($path) $PHOTO_FOR_FB = $SITE_URL.$path;
				} elseif(is_numeric($arr["DETAIL_PICTURE"])) {
					$path = CFile::GetPath($arr["DETAIL_PICTURE"]);
					if($path) $PHOTO_FOR_FB = $SITE_URL.$path;
				}
			}
			$LINK = $SITE_URL.$arr["DETAIL_PAGE_URL"];
			
			$message = CIntellectrixFacebook::_replace((!empty($arr["PREVIEW_TEXT"]))?$arr["PREVIEW_TEXT"]:$arr["DETAIL_TEXT"]);
			$name = (strtoupper(SITE_CHARSET) == 'UTF-8')?$arr["NAME"]:iconv('windows-1251','UTF-8',$arr["NAME"]);
			$message = (strtoupper(SITE_CHARSET) == 'UTF-8')?$message:iconv('windows-1251','UTF-8',$message);
			
			$params = array(
				'link' 	=> $LINK,
				'name' 	=> $name,
				'message' => $message,
				'picture' => $PHOTO_FOR_FB,
			);
			
			if(empty($ID_POST_FB)) {
				$id_post = CIntellectrixFacebook::add_post($params,$group_id);
				if($id_post) CIBlockElement::SetPropertyValues($arFields["ID"], $arFields["IBLOCK_ID"], $id_post, "_INX_FB_ID_POST");
			} else {
				CIntellectrixFacebook::update_post($params, $ID_POST_FB);
			}
		}
	}
}






