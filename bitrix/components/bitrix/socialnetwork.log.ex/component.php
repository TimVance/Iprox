<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/components/bitrix/socialnetwork.log.ex/include.php");

CPageOption::SetOptionString("main", "nav_page_in_session", "N");

if (!CModule::IncludeModule("socialnetwork"))
{
	ShowError(GetMessage("SONET_MODULE_NOT_INSTALL"));
	return;
}

$pathToUser = COption::GetOptionString("main", "TOOLTIP_PATH_TO_USER", false, SITE_ID);
$pathToUser = ($pathToUser ? $pathToUser : SITE_DIR."company/personal/user/#user_id#/");

$folderUsers = COption::GetOptionString("socialnetwork", "user_page", false, SITE_ID);
$folderUsers = ($folderUsers ? $folderUsers : SITE_DIR."company/personal/");

$folderWorkgroups = COption::GetOptionString("socialnetwork", "workgroups_page", false, SITE_ID);
$folderWorkgroups = ($folderWorkgroups ? $folderWorkgroups : SITE_DIR."workgroups/");

$pathToUserBlogPost = COption::GetOptionString("socialnetwork", "userblogpost_page", false, SITE_ID);
$pathToUserBlogPost = ($pathToUserBlogPost ? $pathToUserBlogPost : SITE_DIR."company/personal/user/#user_id#/blog/#post_id#/");

$pathToLogEntry = COption::GetOptionString("socialnetwork", "log_entry_page", false, SITE_ID);
$pathToLogEntry = ($pathToLogEntry ? $pathToLogEntry : SITE_DIR."company/personal/log/#log_id#/");

$pathToSmile = COption::GetOptionString("socialnetwork", "smile_page", false, SITE_ID);
$pathToSmile = ($pathToSmile ? $pathToSmile : "/bitrix/images/socialnetwork/smile/");

$pathToMessagesChat = COption::GetOptionString("main", "TOOLTIP_PATH_TO_MESSAGES_CHAT", false, SITE_ID);
$pathToMessagesChat = ($pathToMessagesChat ? $pathToMessagesChat : SITE_DIR."company/personal/messages/chat/#user_id#/");

$pathToVideoCall = COption::GetOptionString("main", "TOOLTIP_PATH_TO_VIDEO_CALL", false, SITE_ID);
$pathToVideoCall = ($pathToVideoCall ? $pathToVideoCall : SITE_DIR."company/personal/video/#user_id#/");

if (
	!array_key_exists("CHECK_PERMISSIONS_DEST", $arParams) 
	|| strLen($arParams["CHECK_PERMISSIONS_DEST"]) <= 0
)
{
	$arParams["CHECK_PERMISSIONS_DEST"] = "N";
}

if (
	!array_key_exists("USE_FOLLOW", $arParams) 
	|| strLen($arParams["USE_FOLLOW"]) <= 0
)
{
	$arParams["USE_FOLLOW"] = "Y";
}

if(defined("DisableSonetLogFollow") && DisableSonetLogFollow === true)
	$arParams["USE_FOLLOW"] = "N";

if(!$USER->IsAuthorized())
{
	$arParams["USE_FOLLOW"] = "N";
}

if(isset($arParams["DISPLAY"]))
{
	$arParams["USE_FOLLOW"] = "N";
}

if(!IsModuleInstalled("crm"))
{
	$arParams["IS_CRM"] = "N";
}

$arResult["CAN_DELETE"] = CSocNetUser::IsCurrentUserModuleAdmin(SITE_ID, false);
$arResult["ENTITIES_CORRESPONDENCE"] = array();

// activation rating
CRatingsComponentsMain::GetShowRating($arParams);
if (
	!array_key_exists("RATING_TYPE", $arParams) 
	|| strlen($arParams["RATING_TYPE"]) <= 0
)
{
	$arParams["RATING_TYPE"] = COption::GetOptionString("main", "rating_vote_template", COption::GetOptionString("main", "rating_vote_type", "standart") == "like"? "like": "standart");
	if ($arParams["RATING_TYPE"] == "like_graphic")
		$arParams["RATING_TYPE"] = "like";
	else if ($arParams["RATING_TYPE"] == "standart")
		$arParams["RATING_TYPE"] = "standart_text";
}
else
{
	if ($arParams["RATING_TYPE"] == "like_graphic")
		$arParams["RATING_TYPE"] = "like";
	else if ($arParams["RATING_TYPE"] == "standart")
		$arParams["RATING_TYPE"] = "standart_text";
}

if (
	!array_key_exists("USER_VAR", $arParams)
	|| strLen($arParams["USER_VAR"]) <= 0
)
{
	$arParams["USER_VAR"] = "user_id";
}

if (
	!array_key_exists("GROUP_VAR", $arParams)
	|| strLen($arParams["GROUP_VAR"]) <= 0
)
{
	$arParams["GROUP_VAR"] = "group_id";
}

if (
	!array_key_exists("PAGE_VAR", $arParams)
	|| strLen($arParams["PAGE_VAR"]) <= 0
)
{
	$arParams["PAGE_VAR"] = "page";
}

$arParams["PATH_TO_USER"] = (array_key_exists("PATH_TO_USER", $arParams) && strlen(trim($arParams["PATH_TO_USER"])) > 0 ? trim($arParams["PATH_TO_USER"]) : $pathToUser);
$arParams["PATH_TO_USER_MICROBLOG"] = (array_key_exists("PATH_TO_USER_MICROBLOG", $arParams) && strlen(trim($arParams["PATH_TO_USER_MICROBLOG"])) > 0 ? trim($arParams["PATH_TO_USER_MICROBLOG"]) : $folderUsers."user/#user_id#/blog/");
$arParams["PATH_TO_USER_BLOG_POST"] = $arParams["PATH_TO_USER_MICROBLOG_POST"] = (array_key_exists("PATH_TO_USER_BLOG_POST", $arParams) && strlen(trim($arParams["PATH_TO_USER_BLOG_POST"])) > 0 ? trim($arParams["PATH_TO_USER_BLOG_POST"]) : $pathToUserBlogPost);
$arParams["PATH_TO_USER_BLOG_POST_EDIT"] = (array_key_exists("PATH_TO_USER_BLOG_POST_EDIT", $arParams) && strlen(trim($arParams["PATH_TO_USER_BLOG_POST_EDIT"])) > 0 ? trim($arParams["PATH_TO_USER_BLOG_POST_EDIT"]) : $folderUsers."user/#user_id#/blog/edit/#post_id#/");
$arParams["PATH_TO_USER_BLOG_POST_IMPORTANT"] = (array_key_exists("PATH_TO_USER_BLOG_POST_IMPORTANT", $arParams) && strlen(trim($arParams["PATH_TO_USER_BLOG_POST_IMPORTANT"])) > 0 ? trim($arParams["PATH_TO_USER_BLOG_POST_IMPORTANT"]) : $folderUsers."user/#user_id#/blog/important/");

$arParams["PATH_TO_GROUP"] = (array_key_exists("PATH_TO_GROUP", $arParams) && strlen(trim($arParams["PATH_TO_GROUP"])) > 0 ? trim($arParams["PATH_TO_GROUP"]) : $folderWorkgroups."group/#group_id#/");
$arParams["PATH_TO_GROUP_MICROBLOG"] = (array_key_exists("PATH_TO_GROUP_MICROBLOG", $arParams) && strlen(trim($arParams["PATH_TO_GROUP_MICROBLOG"])) > 0 ? trim($arParams["PATH_TO_GROUP_MICROBLOG"]) : $folderWorkgroups."group/#group_id#/blog/");
$arParams["PATH_TO_GROUP_BLOG_POST"] = $arParams["PATH_TO_GROUP_MICROBLOG_POST"] = (array_key_exists("PATH_TO_GROUP_BLOG_POST", $arParams) && strlen(trim($arParams["PATH_TO_GROUP_BLOG_POST"])) > 0 ? trim($arParams["PATH_TO_GROUP_BLOG_POST"]) : $folderWorkgroups."group/#group_id#/blog/#post_id#/");

$arParams["PATH_TO_LOG_ENTRY"] = (array_key_exists("PATH_TO_LOG_ENTRY", $arParams) && strlen(trim($arParams["PATH_TO_LOG_ENTRY"])) > 0 ? trim($arParams["PATH_TO_LOG_ENTRY"]) : $pathToLogEntry);
$arParams["PATH_TO_MESSAGES_CHAT"] = (array_key_exists("PATH_TO_MESSAGES_CHAT", $arParams) && strlen(trim($arParams["PATH_TO_MESSAGES_CHAT"])) > 0 ? trim($arParams["PATH_TO_MESSAGES_CHAT"]) : $pathToMessagesChat);
$arParams["PATH_TO_VIDEO_CALL"] = (array_key_exists("PATH_TO_VIDEO_CALL", $arParams) && strlen(trim($arParams["PATH_TO_VIDEO_CALL"])) > 0 ? trim($arParams["PATH_TO_VIDEO_CALL"]) : $pathToVideoCall);
$arParams["PATH_TO_SMILE"] = (array_key_exists("PATH_TO_SMILE", $arParams) && strlen(trim($arParams["PATH_TO_SMILE"])) > 0 ? trim($arParams["PATH_TO_SMILE"]) : $pathToSmile);

$arParams["LOG_ID"] = IntVal($arParams["LOG_ID"]);
if ($arParams["LOG_ID"] > 0)
{
	$arParams["HIDE_EDIT_FORM"] = "Y";
	$arParams["SHOW_EVENT_ID_FILTER"] = "N";
}

$arParams["GROUP_ID"] = IntVal($arParams["GROUP_ID"]);
if ($arParams["GROUP_ID"] <= 0)
{
	$arParams["GROUP_ID"] = IntVal($_REQUEST["flt_group_id"]);
}
	
if ($arParams["GROUP_ID"] > 0)
{
	$arParams["ENTITY_TYPE"] = SONET_ENTITY_GROUP;
}
else
{
	$arParams["TO_USER_ID"] = IntVal($_REQUEST["flt_to_user_id"]);
}

$arParams["USER_ID"] = IntVal($arParams["USER_ID"]);
if ($arParams["USER_ID"] <= 0)
{
	$arParams["USER_ID"] = IntVal($_REQUEST["flt_user_id"]);
}

if (is_array($_REQUEST["flt_created_by_id"]))
	$_REQUEST["flt_created_by_id"] = $_REQUEST["flt_created_by_id"][0];

preg_match('/^(\d+)$/', $_REQUEST["flt_created_by_id"], $matches);
if (count($matches) > 0)
	$arParams["CREATED_BY_ID"] = $_REQUEST["flt_created_by_id"];
else
{
	$arFoundUsers = CSocNetUser::SearchUser($_REQUEST["flt_created_by_id"], false);
	if (is_array($arFoundUsers) && count($arFoundUsers) > 0)
		$arParams["CREATED_BY_ID"] = key($arFoundUsers);
}

$arParams["NAME_TEMPLATE"] = $arParams["NAME_TEMPLATE"] ? $arParams["NAME_TEMPLATE"] : CSite::GetNameFormat();
$arParams["NAME_TEMPLATE_WO_NOBR"] = str_replace(
	array("#NOBR#", "#/NOBR#"),
	array("", ""),
	$arParams["NAME_TEMPLATE"]
);
$bUseLogin = $arParams['SHOW_LOGIN'] != "N" ? true : false;

if (StrLen($arParams["ENTITY_TYPE"]) <= 0)
	$arParams["ENTITY_TYPE"] = Trim($_REQUEST["flt_entity_type"]);

$arParams["AVATAR_SIZE_COMMON"] = (isset($arParams["AVATAR_SIZE_COMMON"]) && intval($arParams["AVATAR_SIZE_COMMON"]) > 0) ? intval($arParams["AVATAR_SIZE_COMMON"]) : 58;
$arParams["AVATAR_SIZE"] = (isset($arParams["AVATAR_SIZE"]) && intval($arParams["AVATAR_SIZE"]) > 0) ? intval($arParams["AVATAR_SIZE"]) : 50;
$arParams["AVATAR_SIZE_COMMENT"] = (isset($arParams["AVATAR_SIZE_COMMENT"]) && intval($arParams["AVATAR_SIZE_COMMENT"]) > 0) ? intval($arParams["AVATAR_SIZE_COMMENT"]) : 39;

$arParams["USE_COMMENTS"] = (isset($arParams["USE_COMMENTS"]) ? $arParams["USE_COMMENTS"] : "N");
$arParams["COMMENTS_IN_EVENT"] = (isset($arParams["COMMENTS_IN_EVENT"]) && intval($arParams["COMMENTS_IN_EVENT"]) > 0 ? $arParams["COMMENTS_IN_EVENT"] : "3");
$arParams["DESTINATION_LIMIT"] = (isset($arParams["DESTINATION_LIMIT"]) ? intval($arParams["DESTINATION_LIMIT"]) : 100);
$arParams["DESTINATION_LIMIT_SHOW"] = (isset($arParams["DESTINATION_LIMIT_SHOW"]) ? intval($arParams["DESTINATION_LIMIT_SHOW"]) : 3);

CSocNetLogComponent::processDateTimeFormatParams($arParams);

$arResult["AJAX_CALL"] = array_key_exists("logajax", $_REQUEST);
$arResult["bReload"] = ($arResult["AJAX_CALL"] && $_REQUEST["RELOAD"] == "Y");

$arParams["SET_LOG_COUNTER"] = (
	$USER->IsAuthorized()
	&& $arParams["LOG_ID"] <= 0
	&& (
		!$arResult["AJAX_CALL"] 
		|| $arResult["bReload"]
	) 
		? "Y" 
		: "N"
);
$arParams["SET_LOG_PAGE_CACHE"] = ($arParams["LOG_ID"] <= 0 ? "Y" : "N");

$arParams["COMMENT_PROPERTY"] = array("UF_SONET_COM_FILE");
if (IsModuleInstalled("webdav") || IsModuleInstalled("disk"))
	$arParams["COMMENT_PROPERTY"][] = "UF_SONET_COM_DOC";

$arParams["COMMENT_PROPERTY"][] = "UF_SONET_COM_URL_PRV";

$arResult["SHOW_UNREAD"] = $arParams["SHOW_UNREAD"] = ($arParams["SET_LOG_COUNTER"] == "Y" ? "Y" : "N");

$arPresetFilters = $arResultPresetFilters = false;
$user_id = IntVal($USER->GetID());
if (
	$USER->IsAuthorized()
	&& $arParams["SHOW_EVENT_ID_FILTER"] != "N"
)
{
	$arPresetFilters = CUserOptions::GetOption("socialnetwork", "~log_filter_".SITE_ID, $user_id);
	if (!is_array($arPresetFilters))
		$arPresetFilters = CUserOptions::GetOption("socialnetwork", "~log_filter", $user_id);
}

$bGetComments = (
	array_key_exists("log_filter_submit", $_REQUEST) 
	&& array_key_exists("flt_comments", $_REQUEST) 
	&& $_REQUEST["flt_comments"] == "Y"
);

if ($_REQUEST["preset_filter_top_id"] == "clearall")
{
	$preset_filter_top_id = false;
}
elseif(array_key_exists("preset_filter_top_id", $_REQUEST) && strlen($_REQUEST["preset_filter_top_id"]) > 0)
{
	$preset_filter_top_id = $_REQUEST["preset_filter_top_id"];
}

if ($_REQUEST["preset_filter_id"] == "clearall")
{
	$preset_filter_id = false;
}
elseif(array_key_exists("preset_filter_id", $_REQUEST) && strlen($_REQUEST["preset_filter_id"]) > 0)
{
	$preset_filter_id = $_REQUEST["preset_filter_id"];
}

if (
	is_array($arPresetFilters)
	&& $arParams["SHOW_EVENT_ID_FILTER"] != "N"
	&& $arParams["IS_CRM"] != "Y"
)
{
	if(array_key_exists("preset_filter_id", $_REQUEST))
		CUserOptions::DeleteOption("socialnetwork", "~log_".$arParams["ENTITY_TYPE"]."_".($arParams["ENTITY_TYPE"] == SONET_ENTITY_GROUP ? $arParams["GROUP_ID"] : $arParams["USER_ID"]));

	$arResultPresetFilters = CSocNetLogComponent::ConvertPresetToFilters($arPresetFilters, $arParams);

	// to filter component
	$oLFC = new CSocNetLogComponent(array(
		"arItems" => $arResultPresetFilters
	));
	AddEventHandler("socialnetwork", "OnBeforeSonetLogFilterFill", Array($oLFC, "OnBeforeSonetLogFilterFill"));	
}

if (!is_array($arResultPresetFiltersTop))
{
	$arResultPresetFiltersTop = array();
}
if (!is_array($arResultPresetFilters))
{
	$arResultPresetFilters = array();
}

$db_events = GetModuleEvents("socialnetwork", "OnSonetLogFilterProcess");
while ($arEvent = $db_events->Fetch())
{
	$arEventResult = ExecuteModuleEventEx($arEvent, array($preset_filter_top_id, $preset_filter_id, $arResultPresetFiltersTop, $arResultPresetFilters));
	if (is_array($arEventResult))
	{
		if (array_key_exists("GET_COMMENTS", $arEventResult))
		{
			$bGetComments = $arEventResult["GET_COMMENTS"];
		}
		if (array_key_exists("PARAMS", $arEventResult) && is_array($arEventResult["PARAMS"]))
		{
			foreach($arEventResult["PARAMS"] as $key => $value)
			{
				$arParams[$key] = $value;
			}
		}
	}
}

if ($arParams["SHOW_EVENT_ID_FILTER"] != "N")
{
	$arEventResult = CSocNetLogComponent::OnSonetLogFilterProcess($preset_filter_top_id, $preset_filter_id, $arResultPresetFiltersTop, $arResultPresetFilters);
	if (is_array($arEventResult))
	{
		if (array_key_exists("GET_COMMENTS", $arEventResult))
		{
			$bGetComments = $arEventResult["GET_COMMENTS"];
		}
		if (array_key_exists("PARAMS", $arEventResult) && is_array($arEventResult["PARAMS"]))
		{
			foreach($arEventResult["PARAMS"] as $key => $value)
			{
				$arParams[$key] = $value;
			}
		}
	}
}

if (
	array_key_exists("flt_date_datesel", $_REQUEST)
	&& strlen($_REQUEST["flt_date_datesel"]) > 0
)
{
	switch($_REQUEST["flt_date_datesel"])
	{
		case "today":
			$arParams["LOG_DATE_FROM"] = $arParams["LOG_DATE_TO"] = ConvertTimeStamp();
			break;
		case "yesterday":
			$arParams["LOG_DATE_FROM"] = $arParams["LOG_DATE_TO"] = ConvertTimeStamp(time()-86400);
			break;
		case "week":
			$day = date("w");
			if($day == 0)
				$day = 7;
			$arParams["LOG_DATE_FROM"] = ConvertTimeStamp(time()-($day-1)*86400);
			$arParams["LOG_DATE_TO"] = ConvertTimeStamp(time()+(7-$day)*86400);
			break;
		case "week_ago":
			$day = date("w");
			if($day == 0)
				$day = 7;
			$arParams["LOG_DATE_FROM"] = ConvertTimeStamp(time()-($day-1+7)*86400);
			$arParams["LOG_DATE_TO"] = ConvertTimeStamp(time()-($day)*86400);
			break;
		case "month":
			$arParams["LOG_DATE_FROM"] = ConvertTimeStamp(mktime(0, 0, 0, date("n"), 1));
			$arParams["LOG_DATE_TO"] = ConvertTimeStamp(mktime(0, 0, 0, date("n")+1, 0));
			break;
		case "month_ago":
			$arParams["LOG_DATE_FROM"] = ConvertTimeStamp(mktime(0, 0, 0, date("n")-1, 1));
			$arParams["LOG_DATE_TO"] = ConvertTimeStamp(mktime(0, 0, 0, date("n"), 0));
			break;
		case "days":
			$arParams["LOG_DATE_FROM"] = ConvertTimeStamp(time() - intval($_REQUEST["flt_date_days"])*86400);
			$arParams["LOG_DATE_TO"] = "";
			break;
		case "exact":
			$arParams["LOG_DATE_FROM"] = $arParams["LOG_DATE_TO"] = $_REQUEST["flt_date_from"];
			break;
		case "after":
			$arParams["LOG_DATE_FROM"] = $_REQUEST["flt_date_from"];
			$arParams["LOG_DATE_TO"] = "";
			break;
		case "before":
			$arParams["LOG_DATE_FROM"] = "";
			$arParams["LOG_DATE_TO"] = $_REQUEST["flt_date_to"];
			break;
		case "interval":
			$arParams["LOG_DATE_FROM"] = $_REQUEST["flt_date_from"];
			$arParams["LOG_DATE_TO"] = $_REQUEST["flt_date_to"];
			break;
	}
}
elseif (array_key_exists("flt_date_datesel", $_REQUEST))
{
	$arParams["LOG_DATE_FROM"] = "";
	$arParams["LOG_DATE_TO"] = "";
}
else
{
	if (array_key_exists("flt_date_from", $_REQUEST))
		$arParams["LOG_DATE_FROM"] = trim($_REQUEST["flt_date_from"]);

	if (array_key_exists("flt_date_to", $_REQUEST))
		$arParams["LOG_DATE_TO"] = trim($_REQUEST["flt_date_to"]);
}

$arParams["LOG_CNT"] = (array_key_exists("LOG_CNT", $arParams) && intval($arParams["LOG_CNT"]) > 0 ? $arParams["LOG_CNT"] : 0);
$arParams["AUTH"] = ((StrToUpper($arParams["AUTH"]) == "Y") ? "Y" : "N");

$arPrevPageLogID = false;
if (array_key_exists("pplogid", $_REQUEST))
{
	$arPrevPageLogID = explode("|", trim($_REQUEST["pplogid"]));
	if (is_array($arPrevPageLogID))
	{
		foreach($arPrevPageLogID as $key => $val)
		{
			preg_match('/^(\d+)$/', $val, $matches);
			if (count($matches) <= 0)
				unset($arPrevPageLogID[$key]);
		}
		$arPrevPageLogID = array_unique($arPrevPageLogID);
	}
}

$arParams["PAGE_SIZE"] = intval($arParams["PAGE_SIZE"]);
if($arParams["PAGE_SIZE"] <= 0)
{
	$arParams["PAGE_SIZE"] = 20;
}

$arParams["PAGER_TITLE"] = trim($arParams["PAGER_TITLE"]);

$parent = $this->GetParent();
if (is_object($parent) && strlen($parent->__name) > 0)
{
	if(strlen($arParams["BLOG_IMAGE_MAX_WIDTH"]) <= 0)
		$arParams["BLOG_IMAGE_MAX_WIDTH"] = $parent->arParams["BLOG_IMAGE_MAX_WIDTH"];
	if(strlen($arParams["BLOG_IMAGE_MAX_HEIGHT"]) <= 0)
		$arParams["BLOG_IMAGE_MAX_HEIGHT"] = $parent->arParams["BLOG_IMAGE_MAX_HEIGHT"];
	if(strlen($arParams["BLOG_COMMENT_ALLOW_IMAGE_UPLOAD"]) <= 0)
		$arParams["BLOG_COMMENT_ALLOW_IMAGE_UPLOAD"] = $parent->arParams["BLOG_COMMENT_ALLOW_IMAGE_UPLOAD"];
	if(strlen($arParams["BLOG_ALLOW_POST_CODE"]) <= 0)
		$arParams["BLOG_ALLOW_POST_CODE"] = $parent->arParams["BLOG_ALLOW_POST_CODE"];
	if(strlen($arParams["BLOG_COMMENT_ALLOW_VIDEO"]) <= 0)
		$arParams["BLOG_COMMENT_ALLOW_VIDEO"] = $parent->arParams["BLOG_COMMENT_ALLOW_VIDEO"];
	$arParams["BLOG_GROUP_ID"] = $parent->arParams["BLOG_GROUP_ID"];
	if(isset($parent->arParams["BLOG_USE_CUT"]))
		$arParams["BLOG_USE_CUT"] = $parent->arParams["BLOG_USE_CUT"];

	$arParams["PHOTO_USER_IBLOCK_TYPE"] = $parent->arParams["PHOTO_USER_IBLOCK_TYPE"];
	$arParams["PHOTO_USER_IBLOCK_ID"] = $parent->arParams["PHOTO_USER_IBLOCK_ID"];
	$arParams["PHOTO_GROUP_IBLOCK_TYPE"] = $parent->arParams["PHOTO_GROUP_IBLOCK_TYPE"];
	$arParams["PHOTO_GROUP_IBLOCK_ID"] = $parent->arParams["PHOTO_GROUP_IBLOCK_ID"];
	$arParams["PHOTO_MAX_VOTE"] = $parent->arParams["PHOTO_MAX_VOTE"];
	$arParams["PHOTO_USE_COMMENTS"] = $parent->arParams["PHOTO_USE_COMMENTS"];
	$arParams["PHOTO_COMMENTS_TYPE"] = $parent->arParams["PHOTO_COMMENTS_TYPE"];
	$arParams["PHOTO_FORUM_ID"] = $parent->arParams["PHOTO_FORUM_ID"];
	$arParams["PHOTO_BLOG_URL"] = $parent->arParams["PHOTO_BLOG_URL"];
	$arParams["PHOTO_USE_CAPTCHA"] = $parent->arParams["PHOTO_USE_CAPTCHA"];

	if (
		(
			strlen($arParams["PHOTO_GROUP_IBLOCK_TYPE"]) <= 0
			|| intval($arParams["PHOTO_GROUP_IBLOCK_ID"]) <= 0
		)
		&& CModule::IncludeModule("iblock"))
	{
		$ttl = 60*60*24;
		$cache_id = 'sonet_group_photo_iblock_'.SITE_ID;
		$cache_dir = '/bitrix/sonet_group_photo_iblock';
		$obCache = new CPHPCache;

		if($obCache->InitCache($ttl, $cache_id, $cache_dir))
		{
			$cacheData = $obCache->GetVars();
			$arParams["PHOTO_GROUP_IBLOCK_TYPE"] = $cacheData["PHOTO_GROUP_IBLOCK_TYPE"];
			$arParams["PHOTO_GROUP_IBLOCK_ID"] = $cacheData["PHOTO_GROUP_IBLOCK_ID"];
			unset($cacheData);
		}
		else
		{
			$rsIBlockType = CIBlockType::GetByID("photos");
			if ($arIBlockType = $rsIBlockType->Fetch())
			{
				$rsIBlock = CIBlock::GetList(
					array("SORT" => "ASC"),
					array(
						"IBLOCK_TYPE" => $arIBlockType["ID"],
						"CODE" => array("group_photogallery", "group_photogallery_".SITE_ID),
						"ACTIVE" => "Y",
						"SITE_ID" => SITE_ID
					)
				);
				if ($arIBlock = $rsIBlock->Fetch())
				{
					$arParams["PHOTO_GROUP_IBLOCK_TYPE"] = $arIBlock["IBLOCK_TYPE_ID"];
					$arParams["PHOTO_GROUP_IBLOCK_ID"] = $arIBlock["ID"];
				}
			}

			if ($obCache->StartDataCache())
			{
				$obCache->EndDataCache(array(
					"PHOTO_GROUP_IBLOCK_TYPE" => $arParams["PHOTO_GROUP_IBLOCK_TYPE"],
					"PHOTO_GROUP_IBLOCK_ID" => $arParams["PHOTO_GROUP_IBLOCK_ID"]
				));
			}
		}
		unset($obCache);
	}

	$arParams["PHOTO_COUNT"] = $parent->arParams["LOG_PHOTO_COUNT"];
	$arParams["PHOTO_THUMBNAIL_SIZE"] = $parent->arParams["LOG_PHOTO_THUMBNAIL_SIZE"];

	$arParams["FORUM_ID"] = $parent->arParams["FORUM_ID"];

	// parent of 2nd level
	$parent = $parent->GetParent();
	if (is_object($parent) && strlen($parent->__name) > 0)
	{
		if(strlen($arParams["BLOG_IMAGE_MAX_WIDTH"]) <= 0)
			$arParams["BLOG_IMAGE_MAX_WIDTH"] = $parent->arParams["BLOG_IMAGE_MAX_WIDTH"];
		if(strlen($arParams["BLOG_IMAGE_MAX_HEIGHT"]) <= 0)
			$arParams["BLOG_IMAGE_MAX_HEIGHT"] = $parent->arParams["BLOG_IMAGE_MAX_HEIGHT"];
		if(strlen($arParams["BLOG_COMMENT_ALLOW_IMAGE_UPLOAD"]) <= 0)
			$arParams["BLOG_COMMENT_ALLOW_IMAGE_UPLOAD"] = $parent->arParams["BLOG_COMMENT_ALLOW_IMAGE_UPLOAD"];
		if(strlen($arParams["BLOG_ALLOW_POST_CODE"]) <= 0)
			$arParams["BLOG_ALLOW_POST_CODE"] = $parent->arParams["BLOG_ALLOW_POST_CODE"];
		if(strlen($arParams["BLOG_COMMENT_ALLOW_VIDEO"]) <= 0)
			$arParams["BLOG_COMMENT_ALLOW_VIDEO"] = $parent->arParams["BLOG_COMMENT_ALLOW_VIDEO"];
		if(intval($arParams["BLOG_GROUP_ID"]) <= 0)
			$arParams["BLOG_GROUP_ID"] = $parent->arParams["BLOG_GROUP_ID"];
		if(isset($parent->arParams["BLOG_USE_CUT"]))
			$arParams["BLOG_USE_CUT"] = $parent->arParams["BLOG_USE_CUT"];

		if(strlen($arParams["PHOTO_USER_IBLOCK_TYPE"]) <= 0)
			$arParams["PHOTO_USER_IBLOCK_TYPE"] = $parent->arParams["PHOTO_USER_IBLOCK_TYPE"];
		if(intval($arParams["PHOTO_USER_IBLOCK_ID"]) <= 0)
			$arParams["PHOTO_USER_IBLOCK_ID"] = $parent->arParams["PHOTO_USER_IBLOCK_ID"];
		if(strlen($arParams["PHOTO_GROUP_IBLOCK_TYPE"]) <= 0)
			$arParams["PHOTO_GROUP_IBLOCK_TYPE"] = $parent->arParams["PHOTO_GROUP_IBLOCK_TYPE"];
		if(intval($arParams["PHOTO_GROUP_IBLOCK_ID"]) <= 0)
			$arParams["PHOTO_GROUP_IBLOCK_ID"] = $parent->arParams["PHOTO_GROUP_IBLOCK_ID"];
		if(intval($arParams["PHOTO_MAX_VOTE"]) <= 0)
			$arParams["PHOTO_MAX_VOTE"] = $parent->arParams["PHOTO_MAX_VOTE"];
		if(strlen($arParams["PHOTO_USE_COMMENTS"]) <= 0)
			$arParams["PHOTO_USE_COMMENTS"] = $parent->arParams["PHOTO_USE_COMMENTS"];
		if(strlen($arParams["PHOTO_COMMENTS_TYPE"]) <= 0)
			$arParams["PHOTO_COMMENTS_TYPE"] = $parent->arParams["PHOTO_COMMENTS_TYPE"];
		if(intval($arParams["PHOTO_FORUM_ID"]) <= 0)
			$arParams["PHOTO_FORUM_ID"] = $parent->arParams["PHOTO_FORUM_ID"];
		if(strlen($arParams["PHOTO_BLOG_URL"]) <= 0)
			$arParams["PHOTO_BLOG_URL"] = $parent->arParams["PHOTO_BLOG_URL"];
		if(strlen($arParams["PHOTO_USE_CAPTCHA"]) <= 0)
			$arParams["PHOTO_USE_CAPTCHA"] = $parent->arParams["PHOTO_USE_CAPTCHA"];

		if(intval($arParams["PHOTO_COUNT"]) <= 0)
			$arParams["PHOTO_COUNT"] = $parent->arParams["LOG_PHOTO_COUNT"];
		if(intval($arParams["PHOTO_THUMBNAIL_SIZE"]) <= 0)
			$arParams["PHOTO_THUMBNAIL_SIZE"] = $parent->arParams["LOG_PHOTO_THUMBNAIL_SIZE"];

		if(intval($arParams["FORUM_ID"]) <= 0)
			$arParams["FORUM_ID"] = $parent->arParams["FORUM_ID"];
	}
}

if (intval($arParams["PHOTO_COUNT"]) <= 0)
	$arParams["PHOTO_COUNT"] = 6;
if (intval($arParams["PHOTO_THUMBNAIL_SIZE"]) <= 0)
	$arParams["PHOTO_THUMBNAIL_SIZE"] = 48;

if(
	$user_id > 0
	&& (
		(
			$arParams["ENTITY_TYPE"] != SONET_ENTITY_GROUP 
		) 
		|| 
		(
			(
				CSocNetFeaturesPerms::CanPerformOperation($user_id, SONET_ENTITY_GROUP, $arParams["GROUP_ID"], "blog", "full_post", CSocNetUser::IsCurrentUserModuleAdmin()) 
				|| $APPLICATION->GetGroupRight("blog") >= "W"
			)
			|| CSocNetFeaturesPerms::CanPerformOperation($user_id, SONET_ENTITY_GROUP, $arParams["GROUP_ID"], "blog", "write_post")
			|| CSocNetFeaturesPerms::CanPerformOperation($user_id, SONET_ENTITY_GROUP, $arParams["GROUP_ID"], "blog", "moderate_post")
			|| CSocNetFeaturesPerms::CanPerformOperation($user_id, SONET_ENTITY_GROUP, $arParams["GROUP_ID"], "blog", "premoderate_post")
		)
	)
)
{
	$arResult["MICROBLOG_USER_ID"] = $user_id;
}

if (IsModuleInstalled("photogallery"))
{
	if (strlen($arParams["PHOTO_USER_IBLOCK_TYPE"]) <= 0)
	{
		$arParams["PHOTO_USER_IBLOCK_TYPE"] = "photos";
	}

	if (
		intval($arParams["PHOTO_USER_IBLOCK_ID"]) <= 0
		&& CModule::IncludeModule("iblock")
	)
	{
		$dbRes = CIBlock::GetList(array(), array("SITE_ID" => SITE_ID, "CODE" => "user_photogallery"));
		if ($arRes = $dbRes->Fetch())
		{
			$arParams["PHOTO_USER_IBLOCK_ID"] = $arRes["ID"];
		}
	}

	if (
		intval($arParams["PHOTO_FORUM_ID"]) <= 0
		&& CModule::IncludeModule("forum")
	)
	{
		$dbRes = CForumNew::GetListEx(array(), array("SITE_ID" => SITE_ID, "XML_ID" => "PHOTOGALLERY_COMMENTS"));
		if ($arRes = $dbRes->Fetch())
		{
			$arParams["PHOTO_FORUM_ID"] = $arRes["ID"];
		}
	}

	$arParams["PATH_TO_USER_PHOTO"] = (strlen($arParams["PATH_TO_USER_PHOTO"]) > 0 ? $arParams["PATH_TO_USER_PHOTO"] : $folderUsers."user/#user_id#/photo/");
	$arParams["PATH_TO_GROUP_PHOTO"] = (strlen($arParams["PATH_TO_GROUP_PHOTO"]) > 0 ? $arParams["PATH_TO_GROUP_PHOTO"] : $folderWorkgroups."group/#group_id#/photo/");
	$arParams["PATH_TO_USER_PHOTO_SECTION"] = (strlen($arParams["PATH_TO_USER_PHOTO_SECTION"]) > 0 ? $arParams["PATH_TO_USER_PHOTO_SECTION"] : $folderUsers."user/#user_id#/photo/album/#section_id#/");
	$arParams["PATH_TO_GROUP_PHOTO_SECTION"] = (strlen($arParams["PATH_TO_GROUP_PHOTO_SECTION"]) > 0 ? $arParams["PATH_TO_GROUP_PHOTO_SECTION"] : $folderWorkgroups."group/#group_id#/photo/album/#section_id#/");
	$arParams["PATH_TO_USER_PHOTO_ELEMENT"] = (strlen($arParams["PATH_TO_USER_PHOTO_ELEMENT"]) > 0 ? $arParams["PATH_TO_USER_PHOTO_ELEMENT"] : $folderUsers."user/#user_id#/photo/photo/#section_id#/#element_id#/");
	$arParams["PATH_TO_GROUP_PHOTO_ELEMENT"] = (strlen($arParams["PATH_TO_GROUP_PHOTO_ELEMENT"]) > 0 ? $arParams["PATH_TO_GROUP_PHOTO_ELEMENT"] : $folderWorkgroups."group/#group_id#/photo/#section_id#/#element_id#/");
}

$bCurrentUserIsAdmin = CSocNetUser::IsCurrentUserModuleAdmin();

$arResult["TZ_OFFSET"] = CTimeZone::GetOffset();

CSocNetTools::InitGlobalExtranetArrays();

if (
	$USER->IsAuthorized()
	|| $arParams["AUTH"] == "Y" 
)
{
	$arResult["IS_FILTERED"] = false;

	if (
		$arParams["SET_TITLE"] == "Y" 
		|| $arParams["SET_NAV_CHAIN"] != "N"
		|| $arParams["GROUP_ID"] > 0
	)
	{
		if ($arParams["ENTITY_TYPE"] == SONET_ENTITY_USER)
		{
			$rsUser = CUser::GetByID($arParams["USER_ID"]);
			if ($arResult["User"] = $rsUser->Fetch())
			{
				$strTitleFormatted = CUser::FormatName($arParams['NAME_TEMPLATE'], $arResult["User"], $bUseLogin);
			}
		}
		elseif ($arParams["ENTITY_TYPE"] == SONET_ENTITY_GROUP)
		{
			$arResult["Group"] = CSocNetGroup::GetByID($arParams["GROUP_ID"]);
		}
	}

	if ($arParams["SET_TITLE"] == "Y")
	{
		$APPLICATION->SetTitle(GetMessage("SONET_C73_PAGE_TITLE"));
	}

	if ($arParams["SET_NAV_CHAIN"] != "N")
	{
		$APPLICATION->AddChainItem(GetMessage("SONET_C73_PAGE_TITLE"));
	}

	$arResult["Events"] = false;
	$arFilter = array();

	if ($arParams["LOG_ID"] > 0)
	{
		$arFilter["ID"] = $arParams["LOG_ID"];
	}

	if(isset($arParams["DISPLAY"]))
	{
		$arResult["SHOW_UNREAD"] = $arParams["SHOW_UNREAD"] = $arParams["SHOW_REFRESH"] = "N";

		$arParams["SHOW_EVENT_ID_FILTER"] = "N";
		if($arParams["DISPLAY"] === "forme")
		{
			$arAccessCodes = $USER->GetAccessCodes();
			foreach($arAccessCodes as $i => $code)
				if(!preg_match("/^(U|D|DR)/", $code)) //Users and Departments
					unset($arAccessCodes[$i]);
			$arFilter["LOG_RIGHTS"] = $arAccessCodes;
			$arFilter["!USER_ID"] = $USER->GetID();
			$arResult["IS_FILTERED"] = true;
			$arParams["SET_LOG_COUNTER"] = $arParams["SET_LOG_PAGE_CACHE"] = "N";
			$arParams["USE_FOLLOW"] = "N";
		}
		elseif($arParams["DISPLAY"] === "mine")
		{
			$arFilter["USER_ID"] = $USER->GetID();
			$arResult["IS_FILTERED"] = true;
			$arParams["SET_LOG_COUNTER"] = $arParams["SET_LOG_PAGE_CACHE"] = "N";
			$arParams["USE_FOLLOW"] = "N";
		}
		elseif($arParams["DISPLAY"] === "my")
		{
			$arAccessCodes = $USER->GetAccessCodes();
			foreach($arAccessCodes as $i => $code)
				if(!preg_match("/^(U|D|DR)/", $code)) //Users and Departments
					unset($arAccessCodes[$i]);
			$arFilter["LOG_RIGHTS"] = $arAccessCodes;
			$arParams["SET_LOG_PAGE_CACHE"] = "N";
			$arParams["USE_FOLLOW"] = "N";
		}
		elseif($arParams["DISPLAY"] > 0)
		{
			$arFilter["USER_ID"] = intval($arParams["DISPLAY"]);
			$arResult["IS_FILTERED"] = true;
			$arParams["SET_LOG_COUNTER"] = $arParams["SET_LOG_PAGE_CACHE"] = "N";
			$arParams["USE_FOLLOW"] = "N";
		}
	}

	if ($arParams["DESTINATION"] > 0)
	{
		$arFilter["LOG_RIGHTS"] = $arParams["DESTINATION"];
	}
	elseif ($arParams["GROUP_ID"] > 0)
	{
		$ENTITY_TYPE = SONET_ENTITY_GROUP;
		$ENTITY_ID = $arParams["GROUP_ID"];

		$arFilter["LOG_RIGHTS"] = "SG".intval($arParams["GROUP_ID"]);
		$arParams["SET_LOG_PAGE_CACHE"] = "Y";
		$arParams["USE_FOLLOW"] = "N";
	}
	elseif ($arParams["TO_USER_ID"] > 0)
	{
		$arFilter["LOG_RIGHTS"] = "U".$arParams["TO_USER_ID"];
		$arFilter["!USER_ID"] = $arParams["TO_USER_ID"];
		$arParams["SET_LOG_PAGE_CACHE"] = "N";
		$arParams["USE_FOLLOW"] = "N";

		$rsUsers = CUser::GetList(
			($by="ID"),
			($order="asc"),
			array(
				"ID" => $arParams["TO_USER_ID"]
			),
			array("FIELDS" => array("ID", "NAME", "LAST_NAME", "SECOND_NAME", "LOGIN"))
		);
		while($arUser = $rsUsers->Fetch())
		{
			$arResult["ToUser"] = array(
				"ID" => $arUser["ID"],
				"NAME" => CUser::FormatName($arParams['NAME_TEMPLATE'], $arUser, $bUseLogin)
			);
		}
	}
	elseif ($arParams["USER_ID"] > 0)
	{
		$ENTITY_TYPE = $arFilter["ENTITY_TYPE"] = SONET_ENTITY_USER;
		$ENTITY_ID = $arFilter["ENTITY_ID"] = $arParams["USER_ID"];
	}
	elseif (StrLen($arParams["ENTITY_TYPE"]) > 0)
	{
		$ENTITY_TYPE = $arFilter["ENTITY_TYPE"] = $arParams["ENTITY_TYPE"];
		$ENTITY_ID = 0;
	}
	else
	{
		$ENTITY_TYPE = "";
		$ENTITY_ID = 0;
	}

	if (isset($arParams["!EXACT_EVENT_ID"]))
	{
		$arFilter["!EVENT_ID"] = $arParams["!EXACT_EVENT_ID"];
		$arResult["IS_FILTERED"] = true;
		$arParams["SET_LOG_COUNTER"] = $arParams["SET_LOG_PAGE_CACHE"] = "N";
		$arParams["USE_FOLLOW"] = "N";
	}
	if (isset($arParams["EXACT_EVENT_ID"]))
	{
		$arFilter["EVENT_ID"] = array($arParams["EXACT_EVENT_ID"]);
		$arResult["IS_FILTERED"] = true;
		$arParams["SET_LOG_COUNTER"] = $arParams["SET_LOG_PAGE_CACHE"] = "N";
		$arParams["USE_FOLLOW"] = "N";
	}
	elseif (is_array($arParams["EVENT_ID"]))
	{
		if (!in_array("all", $arParams["EVENT_ID"]))
		{
			$event_id_fullset_tmp = array();
			foreach($arParams["EVENT_ID"] as $event_id_tmp)
				$event_id_fullset_tmp = array_merge($event_id_fullset_tmp, CSocNetLogTools::FindFullSetByEventID($event_id_tmp));
			$arFilter["EVENT_ID"] = array_unique($event_id_fullset_tmp);

			$arResult["IS_FILTERED"] = true;
			$arParams["SET_LOG_COUNTER"] = $arParams["SET_LOG_PAGE_CACHE"] = "N";
			$arParams["USE_FOLLOW"] = "N";
		}
	}
	elseif ($arParams["EVENT_ID"])
	{
		$arFilter["EVENT_ID"] = CSocNetLogTools::FindFullSetByEventID($arParams["EVENT_ID"]);
		$arResult["IS_FILTERED"] = true;
		$arParams["SET_LOG_COUNTER"] = $arParams["SET_LOG_PAGE_CACHE"] = "N";
		$arParams["USE_FOLLOW"] = "N";
	}
	elseif ($preset_filter_id == "extranet")
	{
		$arResult["IS_FILTERED"] = true;
		$arParams["SET_LOG_COUNTER"] = $arParams["SET_LOG_PAGE_CACHE"] = "N";
		$arParams["USE_FOLLOW"] = "N";
	}

	if (IntVal($arParams["CREATED_BY_ID"]) > 0)
	{
		if ($bGetComments)
		{
			$arFilter["USER_ID|COMMENT_USER_ID"] = $arParams["CREATED_BY_ID"];
		}
		else
		{
			$arFilter["USER_ID"] = $arParams["CREATED_BY_ID"];
		}

		$arResult["IS_FILTERED"] = true;
		$arParams["SET_LOG_COUNTER"] = $arParams["SET_LOG_PAGE_CACHE"] = "N";
		$arParams["USE_FOLLOW"] = "N";
		unset($arFilter["!USER_ID"]);
	}

	if (IntVal($arParams["GROUP_ID"]) > 0)
		$arResult["IS_FILTERED"] = true;

	if ($arParams["FLT_ALL"] == "Y")
		$arFilter["ALL"] = "Y";

	if (
		$ENTITY_TYPE != "" 
		&& $ENTITY_ID > 0
		&& !array_key_exists("EVENT_ID", $arFilter)
	)
	{
		$arFilter["EVENT_ID"] = array();
		$arSocNetLogEvents = CSocNetAllowed::GetAllowedLogEvents();

		foreach($arSocNetLogEvents as $event_id_tmp => $arEventTmp)
		{
			if (
				array_key_exists("HIDDEN", $arEventTmp)
				&& $arEventTmp["HIDDEN"]
			)
				continue;

			$arFilter["EVENT_ID"][] = $event_id_tmp;
		}

		$arFeatures = CSocNetFeatures::GetActiveFeatures($ENTITY_TYPE, $ENTITY_ID);
		$arSocNetFeaturesSettings = CSocNetAllowed::GetAllowedFeatures();

		foreach($arFeatures as $feature_id)
		{
			if(
				array_key_exists($feature_id, $arSocNetFeaturesSettings)
				&& array_key_exists("subscribe_events", $arSocNetFeaturesSettings[$feature_id])
			)
			{
				foreach ($arSocNetFeaturesSettings[$feature_id]["subscribe_events"] as $event_id_tmp => $arEventTmp)
				{
					$arFilter["EVENT_ID"][] = $event_id_tmp;
				}
			}
		}
	}

	if (
		!$arFilter["EVENT_ID"]
		|| (is_array($arFilter["EVENT_ID"]) && count($arFilter["EVENT_ID"]) <= 0)
	)
	{
		unset($arFilter["EVENT_ID"]);
	}

	if (isset($arParams["FILTER_SITE_ID"]))
	{
		$arFilter["SITE_ID"] = $arParams["FILTER_SITE_ID"];
	}
	else
	{
		$arFilter["SITE_ID"] = (
			CModule::IncludeModule('extranet')
			&& CExtranet::IsExtranetSite()
				? SITE_ID
				: array(SITE_ID, false)
		);
	}

	if (
		array_key_exists("LOG_DATE_FROM", $arParams)
		&& strlen(trim($arParams["LOG_DATE_FROM"])) > 0
		&& MakeTimeStamp($arParams["LOG_DATE_FROM"], CSite::GetDateFormat("SHORT")) < time()+$arResult["TZ_OFFSET"]
	)
	{
		$arFilter[">=LOG_DATE"] = $arParams["LOG_DATE_FROM"];
		$arParams["SET_LOG_COUNTER"] = $arParams["SET_LOG_PAGE_CACHE"] = "N";
		$arParams["USE_FOLLOW"] = "N";
		$arResult["IS_FILTERED"] = true;
	}
	else
	{
		unset($_REQUEST["flt_date_from"]);
	}

	if (
		array_key_exists("LOG_DATE_TO", $arParams)
		&& strlen(trim($arParams["LOG_DATE_TO"])) > 0
		&& MakeTimeStamp($arParams["LOG_DATE_TO"], CSite::GetDateFormat("SHORT")) < time()+$arResult["TZ_OFFSET"]
	)
	{
		$arFilter["<=LOG_DATE"] = ConvertTimeStamp(MakeTimeStamp($arParams["LOG_DATE_TO"], CSite::GetDateFormat("SHORT"))+86399, "FULL");
		$arParams["SET_LOG_COUNTER"] = $arParams["SET_LOG_PAGE_CACHE"] = "N";
		$arParams["USE_FOLLOW"] = "N";
		$arResult["IS_FILTERED"] = true;
	}
	else
	{
		$arFilter["<=LOG_DATE"] = "NOW";
		unset($_REQUEST["flt_date_to"]);
	}

	if ($arParams["IS_CRM"] == "Y")
	{
		$arParams["CRM_ENTITY_TYPE"] = trim($arParams["CRM_ENTITY_TYPE"]);
		$arParams["CRM_ENTITY_ID"] = intval($arParams["CRM_ENTITY_ID"]);

		if (strlen($arParams["CRM_ENTITY_TYPE"]) > 0)
		{
			$arParams["SET_LOG_COUNTER"] = $arParams["SET_LOG_PAGE_CACHE"] = "N";
		}
		elseif($preset_filter_top_id)
		{
			$arParams["SET_LOG_COUNTER"] = "N";
		}
		$arParams["CRM_EXTENDED_MODE"] = ($arParams["CRM_EXTENDED_MODE"] == "Y" ? "Y" : "N");
	}

	if (intval($arParams["LOG_CNT"]) > 0)
	{
		$arNavStartParams = array("nTopCount" => $arParams["LOG_CNT"]);
		$arResult["PAGE_NUMBER"] = 1;
		$bFirstPage = true;
		$arParams["SHOW_NAV_STRING"] = "N";
		$arParams["SHOW_REFRESH"] = "N";
	}
	elseif (
		!$arResult["AJAX_CALL"]
		|| $arResult["bReload"]
	)
	{
		$arNavStartParams = array("nTopCount" => $arParams["PAGE_SIZE"]);
		$arResult["PAGE_NUMBER"] = 1;
		$bFirstPage = true;
	}
	else
	{
		if (intval($_REQUEST["PAGEN_".($GLOBALS["NavNum"] + 1)]) > 0)
		{
			$arResult["PAGE_NUMBER"] = intval($_REQUEST["PAGEN_".($GLOBALS["NavNum"] + 1)]);
		}

		$arNavStartParams = array(
			"nPageSize" => (intval($_REQUEST["pagesize"]) > 0 ? intval($_REQUEST["pagesize"]) : $arParams["PAGE_SIZE"]),
			"bShowAll" => false,
			"iNavAddRecords" => 1,
			"bSkipPageReset" => true,
			"nRecordCount" => 1000000
		);
	}

	if ($bGetComments)
	{
		$arOrder = array("LOG_UPDATE" => "DESC");
	}
	elseif ($arParams["USE_FOLLOW"] == "Y")
	{
		$arOrder = array("DATE_FOLLOW" => "DESC");
	}
	elseif ($arParams["USE_COMMENTS"] == "Y")
	{
		$arOrder = array("LOG_UPDATE" => "DESC");
	}
	else
	{
		$arOrder = array("LOG_DATE"	=> "DESC");
	}

	$events = GetModuleEvents("socialnetwork", "OnBuildSocNetLogOrder");
	while ($arEvent = $events->Fetch())
	{
		ExecuteModuleEventEx($arEvent, array(&$arOrder, $arParams));
	}

	if (
		(
			!isset($arParams["USE_FAVORITES"])
			|| $arParams["USE_FAVORITES"] != "N"
		)
		&& $arParams["FAVORITES"] == "Y"
	)
	{
		$arFilter[">FAVORITES_USER_ID"] = 0;
		$arParams["SET_LOG_COUNTER"] = $arParams["SET_LOG_PAGE_CACHE"] = "N";
	}

	$arParams["NAME_TEMPLATE"] = $arParams["NAME_TEMPLATE_WO_NOBR"];

	if (intval($arParams["GROUP_ID"]) > 0)
	{
		$arResult["COUNTER_TYPE"] = "SG".intval($arParams["GROUP_ID"]);
	}
	elseif(
		$arParams["IS_CRM"] == "Y"
		&& (
			$arParams["SET_LOG_COUNTER"] != "N" 
			|| $arParams["SET_LOG_PAGE_CACHE"] != "N"
		)
	)
	{
		$arResult["COUNTER_TYPE"] = (
			is_set($arParams["CUSTOM_DATA"])
			&& is_set($arParams["CUSTOM_DATA"]["CRM_PRESET_TOP_ID"])
			&& $arParams["CUSTOM_DATA"]["CRM_PRESET_TOP_ID"] == "all"
				? "CRM_**_ALL"
				: "CRM_**"
		);
	}
	elseif($arParams["EXACT_EVENT_ID"] == "blog_post")
	{
		$arResult["COUNTER_TYPE"] = "blog_post";
	}
	else
	{
		$arResult["COUNTER_TYPE"] = "**";
	}

	if (
		$arParams["LOG_ID"] <= 0 
		&& (
			!$arResult["AJAX_CALL"] 
			|| $arResult["bReload"]
		)
	)
	{
		$arResult["LAST_LOG_TS"] = CUserCounter::GetLastDate($user_id, $arResult["COUNTER_TYPE"]);

		if($arResult["LAST_LOG_TS"] == 0)
		{
			$arResult["LAST_LOG_TS"] = 1;
		}
		else
		{
			//We substruct TimeZone offset in order to get server time
			//because of template compatibility
			$arResult["LAST_LOG_TS"] -= $arResult["TZ_OFFSET"];
		}
	}
	else
	{
		$arResult["LAST_LOG_TS"] = intval($_REQUEST["ts"]);
	}

	if ($arParams["IS_CRM"] == "Y")
	{
		$arListParams = array(
			"IS_CRM" => "Y",
			"CHECK_CRM_RIGHTS" => "Y"
		);

		$filterParams = array(
			"ENTITY_TYPE" => $arParams["CRM_ENTITY_TYPE"],
			"ENTITY_ID" => $arParams["CRM_ENTITY_ID"],
			"AFFECTED_TYPES" => array(),
			"OPTIONS" => array(
				"CUSTOM_DATA" => (
					isset($arParams["CUSTOM_DATA"])
					&& is_array($arParams["CUSTOM_DATA"])
						? $arParams["CUSTOM_DATA"]
						: array()
				)
			)
		);

		$events = GetModuleEvents("socialnetwork", "OnBuildSocNetLogFilter");
		while ($arEvent = $events->Fetch())
		{
			ExecuteModuleEventEx($arEvent, array(&$arFilter, &$filterParams, &$arParams));
		}

		$arListParams['CUSTOM_FILTER_PARAMS'] = $filterParams;
	}
	else
	{
		if (IsModuleInstalled('crm'))
		{
			$arFilter["!MODULE_ID"] = (
				COption::GetOptionString("crm", "enable_livefeed_merge", "N") == "Y"
					? array('crm')
					: array('crm', 'crm_shared')
			);
		}

		$arListParams = array(
			"CHECK_RIGHTS" => "Y",
			"CHECK_VIEW" => "Y"
		);
	}

	if (
		$arParams["USE_FOLLOW"] != "N"
		&& !IsModuleInstalled("intranet")
		&& isset($USER) 
		&& is_object($USER) 
		&& $USER->IsAuthorized()
	) // BSM
	{
		$arResult["USE_SMART_FILTER"] = "Y";
		$arListParams["MY_GROUPS_ONLY"] = (
			CSocNetLogSmartFilter::GetDefaultValue($user_id) == "Y"
				? "Y"
				: "N"
		);
	}

	if (
		CModule::IncludeModule('extranet')
		&& (
			CExtranet::IsExtranetSite()
			|| $preset_filter_id == 'extranet'
		)
	)
	{
		$arListParams["MY_GROUPS_ONLY"] = "Y";
	}

	$arResult["MY_GROUPS_ONLY"] = (
		isset($arListParams["MY_GROUPS_ONLY"])
			? $arListParams["MY_GROUPS_ONLY"]
			: false
	);

	if (intval($_REQUEST["pagesize"]) > 0)
	{
		$arParams["SET_LOG_PAGE_CACHE"] = "N";
	}

	if ($arParams["SET_LOG_PAGE_CACHE"] == "Y")
	{
		$groupCode = (strlen($arResult["COUNTER_TYPE"]) > 0 ? $arResult["COUNTER_TYPE"] : "**");
		$rsLogPages = \Bitrix\Socialnetwork\LogPageTable::getList(array(
			'order' => array(),
			'filter' => array(
				"USER_ID" => $user_id,
				"=SITE_ID" => SITE_ID,
				"=GROUP_CODE" => $groupCode,
				"PAGE_SIZE" => $arParams["PAGE_SIZE"],
				"PAGE_NUM" => $arResult["PAGE_NUMBER"]
			),
			'select' => array('PAGE_LAST_DATE', 'TRAFFIC_AVG', 'TRAFFIC_CNT', 'TRAFFIC_LAST_DATE')
		));

		if ($arLogPages = $rsLogPages->Fetch())
		{
			$dateLastPageStart = $arLogPages["PAGE_LAST_DATE"];
			$dateLastPageStartTS = MakeTimeStamp($arLogPages["PAGE_LAST_DATE"], CSite::GetDateFormat("FULL"));
			$arLastPageStart = array(
				'TRAFFIC_LAST_DATE_TS' => ($arLogPages["TRAFFIC_LAST_DATE"] ? MakeTimeStamp($arLogPages["TRAFFIC_LAST_DATE"], CSite::GetDateFormat("FULL")) : 0),
				'TRAFFIC_AVG' => intval($arLogPages['TRAFFIC_AVG']),
				'TRAFFIC_CNT' => intval($arLogPages['TRAFFIC_CNT'])
			);

			$arFilter[">=LOG_UPDATE"] = ConvertTimeStamp($dateLastPageStartTS - 60*60*24*4, "FULL");
		}
		elseif (
			$groupCode != '**'
			|| $arResult["MY_GROUPS_ONLY"] != 'Y'
		)
		{
			$rsLogPages = \Bitrix\Socialnetwork\LogPageTable::getList(array(
				'order' => array(
					'PAGE_LAST_DATE' => 'DESC'
				),
				'filter' => array(
					"=SITE_ID" => SITE_ID,
					"=GROUP_CODE" => $groupCode,
					"PAGE_SIZE" => $arParams["PAGE_SIZE"],
					"PAGE_NUM" => $arResult["PAGE_NUMBER"]
				),
				'select' => array('PAGE_LAST_DATE')
			));

			if ($arLogPages = $rsLogPages->Fetch())
			{
				$dateLastPageStart = $arLogPages["PAGE_LAST_DATE"];
				$arFilter[">=LOG_UPDATE"] = ConvertTimeStamp(MakeTimeStamp($arLogPages["PAGE_LAST_DATE"], CSite::GetDateFormat("FULL")) - 60*60*24*4, "FULL");
				$bNeedSetLogPage = true;
			}
		}
	}

	if ($bCurrentUserIsAdmin)
	{
		$arListParams["USER_ID"] = "A";
	}

	if ($arParams["USE_FOLLOW"] == "Y")
	{
		$arListParams["USE_FOLLOW"] = "Y";
	}
	else
	{
		$arListParams["USE_FOLLOW"] = "N";
		$arListParams["USE_SUBSCRIBE"] = "N";
	}

	$arSelectFields = array(
		"ID", "TMP_ID", "MODULE_ID",
		"LOG_DATE", "LOG_UPDATE", "DATE_FOLLOW", 
		"ENTITY_TYPE", "ENTITY_ID", "EVENT_ID", "SOURCE_ID", "USER_ID", "FOLLOW"
	);
	
	if (
		!isset($arParams["USE_FAVORITES"])
		|| $arParams["USE_FAVORITES"] != "N"
	)
	{
		$arSelectFields[] = "FAVORITES_USER_ID";
	}
	else
	{
		$arListParams["USE_FAVORITES"] = "N";
	}

	if ($GLOBALS["DB"]->type == "MYSQL")
	{
		$arSelectFields[] = "LOG_DATE_TS";
	}
/*
	if (
		!$arResult["IS_FILTERED"]
		&& (
			!isset($arListParams["IS_CRM"])
			|| $arListParams["IS_CRM"] != "Y"
		)
	)
	{
		$arFilter["__INNER_FILTER"] = array(
			"LOGIC" => "OR",
			"!COMMENTS_COUNT" => 0,
			"!EVENT_ID" => "tasks"
		);
	}
*/

	$arTmpEventsNew = array();
	$arResult["arLogTmpID"] = array();
	$arActivity2Log = array();

	$arDiskUFEntity = array(
		"BLOG_POST" => array(),
		"SONET_LOG" => array()
	);

	__SLLogGetIds(
		$arOrder, $arFilter, $arNavStartParams, $arSelectFields, $arListParams, $bFirstPage,
		$arResult, $arActivity2Log, $arDiskUFEntity, $arTmpEventsNew
	);

	if (
		count($arResult["arLogTmpID"]) <= 0
		&& $bNeedSetLogPage // no log pages for user
	)
	{
		unset($dateLastPageStart);
		unset($arFilter[">=LOG_UPDATE"]);

		__SLLogGetIds(
			$arOrder, $arFilter, $arNavStartParams, $arSelectFields, $arListParams, $bFirstPage,
			$arResult, $arActivity2Log, $arDiskUFEntity, $arTmpEventsNew
		);
	}

	$cnt = count($arResult["arLogTmpID"]);

	if (
		isset($arDiskUFEntity)
		&& (
			!empty($arDiskUFEntity["SONET_LOG"])
			|| !empty($arDiskUFEntity["BLOG_POST"])
		)
	)
	{
		$events = GetModuleEvents("socialnetwork", "OnAfterFetchDiskUfEntity");
		while ($arEvent = $events->Fetch())
		{
			ExecuteModuleEventEx($arEvent, array($arDiskUFEntity));
		}
	}

	if (
		!empty($arActivity2Log)
		&& CModule::IncludeModule('crm')
		&& CModule::IncludeModule('tasks')
	)
	{
		$rsActivity = CCrmActivity::GetList(
			array(),
			array(
				"@ID" => array_keys($arActivity2Log),
				"TYPE_ID" => CCrmActivityType::Task,
				"CHECK_PERMISSIONS" => "N"
			),
			false, 
			false, 
			array("ID", "ASSOCIATED_ENTITY_ID")
		);
		while(
			($arActivity = $rsActivity->Fetch())
			&& (intval($arActivity["ASSOCIATED_ENTITY_ID"]) > 0)
		)
		{
			$taskItem = new CTaskItem(intval($arActivity["ASSOCIATED_ENTITY_ID"]), $USER->GetId());
			if (!$taskItem->CheckCanRead())
			{
				unset($arActivity2Log[$arActivity["ID"]]);
			}
		}
	}

	if ($bFirstPage)
	{
		$last_date = $arTmpEventsNew[count($arTmpEventsNew)-1][($arParams["USE_FOLLOW"] == "Y" ? "DATE_FOLLOW" : "LOG_UPDATE")];
	}
	elseif (
		$dbEventsID
		&& $dbEventsID->NavContinue() 
		&& $arEvents = $dbEventsID->GetNext()
	)
	{
		$next_page_date = ($arParams["USE_FOLLOW"] == "Y" ? $arEvents["DATE_FOLLOW"] : $arEvents["LOG_UPDATE"]);
		if (
			$USER->IsAuthorized()
			&& ($arResult["LAST_LOG_TS"] < MakeTimeStamp($next_page_date))
		)
		{
			$next_page_date = $arResult["LAST_LOG_TS"];
		}
	}

	if (
		$cnt == 0
		&& isset($dateLastPageStart)
		&& $USER->IsAuthorized()
		&& $arParams["SET_LOG_PAGE_CACHE"] == "Y"
	)
	{
		CSocNetLogPages::DeleteEx($user_id, SITE_ID, $arParams["PAGE_SIZE"], (strlen($arResult["COUNTER_TYPE"]) > 0 ? $arResult["COUNTER_TYPE"] : "**"));
	}

	if (
		$cnt < $arParams["PAGE_SIZE"]
		&& 	isset($arFilter[">=LOG_UPDATE"])
	)
	{
		$arResult["NEXT_PAGE_SIZE"] = $cnt;
	}
	elseif (intval($_REQUEST["pagesize"]) > 0)
	{
		$arResult["NEXT_PAGE_SIZE"] = intval($_REQUEST["pagesize"]);
	}

	$bArActivity2LogEmpty = empty($arActivity2Log);
	$tasksNum = 0;
	foreach ($arTmpEventsNew as $key => $arTmpEvent)
	{
		if (
			$arTmpEvent["EVENT_ID"] == "crm_activity_add"
			&& !$bArActivity2LogEmpty
			&& !in_array($arTmpEvent["ID"], $arActivity2Log)
		)
		{
			unset($arTmpEventsNew[$key]);
		}
		elseif (
			!is_array($arPrevPageLogID)
			|| !in_array($arTmpEvent["ID"], $arPrevPageLogID)
		)
		{
			$arTmpEventsNew[$key]["EVENT_ID_FULLSET"] = CSocNetLogTools::FindFullSetEventIDByEventID($arTmpEvent["EVENT_ID"]);
			if ($arTmpEvent["EVENT_ID"] == 'tasks')
			{
				$tasksNum++;
			}

			if ($key == 0)
			{
				if ($arTmpEvent["DATE_FOLLOW"])
				{
					$dateFirstPageTS = MakeTimeStamp($arTmpEvent["DATE_FOLLOW"], CSite::GetDateFormat("FULL"));
				}
				elseif (
					$arParams["USE_FOLLOW"] == "N"
					&& $arTmpEvent["LOG_UPDATE"]
				)
				{
					$dateFirstPageTS = MakeTimeStamp($arTmpEvent["LOG_UPDATE"], CSite::GetDateFormat("FULL"));
				}
			}
		}
		else
		{
			unset($arTmpEventsNew[$key]);
		}
	}

	$arResult["Events"] = $arTmpEventsNew;

	if (
		$arParams["LOG_ID"] > 0
		&& count($arResult["Events"]) <= 0
	)
	{
		$arResult["FatalError"] = GetMessage("SONET_73_ENTRY_NOT_FOUND");
	}


	if ($arTmpEvent["DATE_FOLLOW"])
	{
		$arResult["dateLastPageTS"] = MakeTimeStamp($arTmpEvent["DATE_FOLLOW"], CSite::GetDateFormat("FULL"));
		$dateLastPage = ConvertTimeStamp($arResult["dateLastPageTS"], "FULL");
	}
	elseif (
		$arParams["USE_FOLLOW"] == "N"
		&& $arTmpEvent["LOG_UPDATE"]
	)
	{
		$arResult["dateLastPageTS"] = MakeTimeStamp($arTmpEvent["LOG_UPDATE"], CSite::GetDateFormat("FULL"));
		$dateLastPage = ConvertTimeStamp($arResult["dateLastPageTS"], "FULL");
	}

	$arResult["WORKGROUPS_PAGE"] = $folderWorkgroups;

	if (
		$USER->IsAuthorized()
		&& $arParams["SET_LOG_COUNTER"] == "Y"
	)
	{
		$arCounters = CUserCounter::GetValues($user_id, SITE_ID);
		if (isset($arCounters[$arResult["COUNTER_TYPE"]]))
		{
			$arResult["LOG_COUNTER"] = intval($arCounters[$arResult["COUNTER_TYPE"]]);
		}
		else
		{
			$bEmptyCounter = true;
			$arResult["LOG_COUNTER"] = 0;
		}
	}
	else
	{
		$arParams["SHOW_UNREAD"] = "N";
	}

	if (
		$USER->IsAuthorized()
		&& IsModuleInstalled('tasks')
	)
	{
		$arResult["EXPERT_MODE"] = "N";

		$rs = \Bitrix\Socialnetwork\LogViewTable::getList(array(
			'order' => array(),
			'filter' => array(
				"USER_ID" => $USER->GetID(),
				"EVENT_ID" => 'tasks'
			),
			'select' => array('TYPE')
		));
		if ($ar = $rs->Fetch())
		{
			$arResult["EXPERT_MODE"] = ($ar['TYPE'] == "N" ? "Y" : "N");
		}
	}

	if (
		$USER->IsAuthorized()
		&& $arParams["SET_LOG_PAGE_CACHE"] == "Y"
		&& $dateLastPage
		&& (
			!$dateLastPageStart
			|| $dateLastPageStart != $dateLastPage
			|| $bNeedSetLogPage
		)
	)
	{
		$groupCode = (strlen($arResult["COUNTER_TYPE"]) > 0 ? $arResult["COUNTER_TYPE"] : "**");

		$bNeedSetTraffic = CSocNetLogComponent::isSetTrafficNeeded(array(
			"PAGE_NUMBER" => $arResult["PAGE_NUMBER"],
			"GROUP_CODE" => $groupCode,
			"TRAFFIC_LAST_DATE_TS" => $arLastPageStart['TRAFFIC_LAST_DATE_TS']
		));

		CSocNetLogPages::Set(
			$user_id, 
			$dateLastPage,
			$arParams["PAGE_SIZE"],
			$arResult["PAGE_NUMBER"],
			SITE_ID,
			$groupCode,
			(
				$bNeedSetTraffic
					? ($arLastPageStart['TRAFFIC_AVG'] + $dateFirstPageTS - $arResult["dateLastPageTS"]) / ($arLastPageStart['TRAFFIC_CNT'] + 1)
					: false
			),
			(
				$bNeedSetTraffic
					? ($arLastPageStart['TRAFFIC_CNT'] + 1)
					: false
			)
		);

		if (
			$arResult["PAGE_NUMBER"] == 1
			&& IsModuleInstalled('tasks')
			&& $arResult["EXPERT_MODE"] != "Y"
		)
		{
			$arResult["EXPERT_MODE_SET"] = \Bitrix\Socialnetwork\LogViewTable::checkExpertModeAuto($user_id, $tasksNum, $arParams["PAGE_SIZE"]);
			if ($arResult["EXPERT_MODE_SET"])
			{
				$arParams["SET_LOG_COUNTER"] = "N";
			}
		}
	}

	if (
		$USER->IsAuthorized()
		&& $arParams["SET_LOG_COUNTER"] == "Y"
		&& (
			intval($arResult["LOG_COUNTER"]) > 0
			|| $bEmptyCounter
		)
	)
	{
		CUserCounter::ClearByUser(
			$user_id,
			array(SITE_ID, "**"),
			$arResult["COUNTER_TYPE"],
			true
		);
		CUserCounter::ClearByUser(
			$user_id,
			SITE_ID,
			"BLOG_POST_IMPORTANT"
		);
	}
}
else
{
	$arResult["NEED_AUTH"] = "Y";
}

if (
	!isset($arResult["FatalError"])
	&& $USER->IsAuthorized()
	&& !$arResult["AJAX_CALL"]
)
{
	$cache = new CPHPCache;
	$cache_id = "log_form_comments".serialize($arParams["COMMENT_PROPERTY"]);
	$cache_path = "/sonet/log_form/comments";

	if(defined("BX_COMP_MANAGED_CACHE"))
		$ttl = 2592000;
	else
		$ttl = 600;

	if ($cache->InitCache($ttl, $cache_id, $cache_path))
	{
		$Vars = $cache->GetVars();
		$arResult["COMMENT_PROPERTIES"] = $Vars["comment_props"];
		$cache->Output();
	}
	else
	{
		$cache->StartDataCache($ttl, $cache_id, $cache_path);

		$arResult["COMMENT_PROPERTIES"] = array("SHOW" => "N");
		if (!empty($arParams["COMMENT_PROPERTY"]))
		{
			$arPostFields = $GLOBALS["USER_FIELD_MANAGER"]->GetUserFields("SONET_COMMENT", 0, LANGUAGE_ID);

			if (count($arParams["COMMENT_PROPERTY"]) > 0)
			{
				foreach ($arPostFields as $FIELD_NAME => $arPostField)
				{
					if (!in_array($FIELD_NAME, $arParams["COMMENT_PROPERTY"]))
						continue;
					$arPostField["EDIT_FORM_LABEL"] = strLen($arPostField["EDIT_FORM_LABEL"]) > 0 ? $arPostField["EDIT_FORM_LABEL"] : $arPostField["FIELD_NAME"];
					$arPostField["~EDIT_FORM_LABEL"] = $arPostField["EDIT_FORM_LABEL"];
					$arPostField["EDIT_FORM_LABEL"] = htmlspecialcharsEx($arPostField["EDIT_FORM_LABEL"]);
					$arResult["COMMENT_PROPERTIES"]["DATA"][$FIELD_NAME] = $arPostField;
				}
			}
			if (!empty($arResult["COMMENT_PROPERTIES"]["DATA"]))
				$arResult["COMMENT_PROPERTIES"]["SHOW"] = "Y";
		}

		$cache->EndDataCache(array("comment_props" => $arResult["COMMENT_PROPERTIES"]));
	}
}

$arResult["bGetComments"] = $bGetComments;
$arResult["GET_COMMENTS"] = ($bGetComments ? "Y" : "N");

if (!isset($arResult["FatalError"]))
{
	if (CModule::IncludeModule("forum"))
	{
		$arResult["Smiles"] = COption::GetOptionInt("forum", "smile_gallery_id", 0);
	}
	else
	{
		if($GLOBALS["CACHE_MANAGER"]->Read(604800, "b_sonet_smile_".LANGUAGE_ID))
		{
			$arResult["Smiles"] = $GLOBALS["CACHE_MANAGER"]->Get("b_sonet_smile_".LANGUAGE_ID);
		}
		else
		{
			$arResult["Smiles"] = array();

			$rsSmile = CSocNetSmile::GetList(
				array("SORT" => "ASC"),
				array("SMILE_TYPE" => "S", "LANG_LID" => LANGUAGE_ID),
				false,
				false,
				array("ID", "IMAGE", "DESCRIPTION", "TYPING", "SMILE_TYPE", "SORT", "LANG_NAME")
			);
			while ($arSmile = $rsSmile->Fetch())
			{
				list($type) = explode(" ", $arSmile["TYPING"]);
				$arSmile["TYPE"] = str_replace("'", "\'", $type);
				$arSmile["TYPE"] = str_replace("\\", "\\\\", $arSmile["TYPE"]);
				$arSmile["NAME"] = $arSmile["LANG_NAME"];
				$arSmile["IMAGE"] = "/bitrix/images/socialnetwork/smile/".$arSmile["IMAGE"];

				$arResult["Smiles"][] = $arSmile;
			}

			$GLOBALS["CACHE_MANAGER"]->Set("b_sonet_smile_".LANGUAGE_ID, $arResult["Smiles"]);
		}
	}
}

$this->IncludeComponentTemplate();
?>