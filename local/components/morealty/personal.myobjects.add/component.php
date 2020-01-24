<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?
CModule::IncludeModule('iblock');
global $USER;
global $APPLICATION;

\MorealtySale\Packets::UpdateActivePockets();

/**
 * Обновляем данные пользователя о пакетах
 * 
 */




/*
 * сначала проверяем возможноость добавления объявлений в зависимости от группы пользователя
 * если юзер владелец, то разрешено только 1 объявление. выводим сообщение
 * если реалтор, то согласно разрешенному колву пакетов в массиве $GLOBALS['USER_ACCOUNT']['userAllowedCounts']
 * если же все хорошо, то выводим форму добавления объявления
 */
$IBLOCK_ID = intval($_REQUEST["IBLOCK_ID"]);
$PRODUCT_ID = intval($_REQUEST["PRODUCT_ID"]);
$APPLICATION->IncludeComponent("morealty:personal.myobjects.iblocks","",Array());
$USER_ADDED_OBJECTS = ($GLOBALS['objectsIDs'] === false)? 0 : count($GLOBALS['objectsIDs']);

$isFlat = $IBLOCK_ID == MorealtySettings::$FLAT_ID;

$keepSaving = true;

if ($IBLOCK_ID && $PRODUCT_ID)
{
	// Проверяем а это вообще его объект или он в параметры подсунул другой объект
	// Если не его объект, то пошел он куда подальше
	$CheckRes = CIBlockElement::GetList(array(),array("IBLOCK_ID"=>$IBLOCK_ID,"ID"=>$PRODUCT_ID,
			
			array(
				"LOGIC" => "OR",
				"PROPERTY_realtor"=>$USER->GetID(),
				"CREATED_BY" => $USER->GetID(),
				),
			
			),false,false,array("ID","CREATED_BY","IBLOCK_ID"));
	if ($CheckRes->AffectedRowsCount() <= 0){
		ShowError("Неверные параметры");
		$keepSaving = false;
		return false;
	}
}

if ($IBLOCK_ID != "7")
{
	$arResult["USE_MAP"] = "Y";
}

if( (IS_USER_OWNER_ONE_REALTY == true && $USER_ADDED_OBJECTS == 1 && $PRODUCT_ID == 0)) {
//if( 1 == 1 ) {
	
	$arResult['ERROR_MSG']['OWNER_END_OFFERS'] = 'y';
	$keepSaving = false;
	
} elseif (IS_USER_REALTOR == true && !empty($GLOBALS['USER_ACCOUNT']['userAllowedCounts']) && $GLOBALS['USER_ACCOUNT']['userAllowedCounts'][ $_REQUEST['IBLOCK_ID'] ] == "0") {
//} elseif ( 1 == 1) {
	$arResult['ERROR_MSG']['REALTOR_END_OFFERS'] = 'y';
	
	$keepSaving = false;
	
} else {
	
if (!$PRODUCT_ID)
{
	if (!\MorealtySale\User::canUserAddTo($IBLOCK_ID) 
			&& (IS_USER_OWNER_ONE_REALTY == true || $USER_ADDED_OBJECTS > 0)
			&& !$USER->IsAdmin()/* && !in_array("6",$USER->GetUserGroupArray())*/)
	{
		$arResult['ERROR_MSG']['REALTOR_END_OFFERS'] = 'y';
		$keepSaving = false;
	}
}
if ($keepSaving) {
	

	
	$tempArray = array('decoration', 'wc', 'currency', 'garage');

	if ($_REQUEST["currency"])
	{
		if ($IBLOCK_ID == 7)
		{
			switch ($_REQUEST["currency"])
			{
				case "Рубли":
					$_REQUEST["currency"] = "14";
					break;
				case "Доллары":
					$_REQUEST["currency"] = "15";
					break;
				case "Евро":
					$_REQUEST["currency"] = "16";
					break;
			}
		}
		else if ($IBLOCK_ID == 8)
		{
			switch ($_REQUEST["currency"])
			{
				case "Рубли":
					$_REQUEST["currency"] = "418";
					break;
				case "Доллары":
					$_REQUEST["currency"] = "419";
					break;
				case "Евро":
					$_REQUEST["currency"] = "420";
					break;
			}
		}
		else if ($IBLOCK_ID == 10)
		{
			switch ($_REQUEST["currency"])
			{
				case "Рубли":
					$_REQUEST["currency"] = "421";
					break;
				case "Доллары":
					$_REQUEST["currency"] = "422";
					break;
				case "Евро":
					$_REQUEST["currency"] = "423";
					break;
			}
		}
	}
	
	foreach ($_REQUEST as $key => $value) {
		if(substr_count($key, "___") > 0) {
			$arr = explode("___", $key);
			$property_enums = CIBlockPropertyEnum::GetList(Array("DEF" => "DESC", "SORT" => "ASC"), Array("IBLOCK_ID" => $_REQUEST['IBLOCK_ID'], "CODE" => $arr[1], "VALUE" => $value));
			if ($enum_fields = $property_enums->GetNext()) {
				$_REQUEST[$arr[1]] = Array('VALUE' => $enum_fields['ID']);
				unset($_REQUEST[$key]);
			}
		}
	}
	//fixme
	$el = new CIBlockElement;

	$properties = array();
	if ($arResult["USE_MAP"] == "Y")
	{
		if ($_REQUEST["map_point"])
		{
			$properties["yandex_map"] =  htmlspecialchars($_REQUEST["map_point"]);
		}
		else if ($_REQUEST["city"] && $_REQUEST['street'] && $USER->GetLogin() == "vadim")
		{
			$CityRes = CIBlockElement::GetList(array(),array('IBLOCK_ID'=>"5","ID"=>$_REQUEST["city"],"ACTIVE"=>"Y"),false,false,array("NAME","ID","IBLOCK_ID"));
			if ($arItem = $CityRes->GetNext())
			{
				//my_print_r($arItem["NAME"]);
				$properties["yandex_map"] =  YandexMap::Init()->GetPointByAddress(implode(", ", array($arItem["NAME"],$_REQUEST['street'])));
			}

		}
		
	}
	$currentUserID               = $USER->GetID();
	//$properties['lot']          = $_REQUEST['lot'];//регион
	$properties['city']          = $_REQUEST['city'];//регион
	$properties['district']      = $_REQUEST['district'];//регион
	$properties['microdistrict'] = $_REQUEST['microdistrict'];//регион
	$properties['street']        = $_REQUEST['street'];//регион

	$properties['newbuilding']          = $_REQUEST['newbuilding'];//новостройка -
	$properties['room_number']          = ($_REQUEST['room_number'] != "all")? $_REQUEST['room_number']: false;//тип
	$properties['hidden_city']          = $_REQUEST['hidden_city'];//
	$properties['hidden_district']      = $_REQUEST['hidden_district'];//
	$properties['hidden_microdistrict'] = $_REQUEST['hidden_microdistrict'];//
	$properties['hidden_street']        = $_REQUEST['hidden_street'];//
//flats
	$properties['floor']          = $_REQUEST['floor'];//этаж
	$properties['square_lived']   = $_REQUEST['square_lived'];//площадь жилая
	$properties['square']         = $_REQUEST['square'];//площадь общая
	$properties['kitchen']        = $_REQUEST['kitchen'];//кухня-
	$properties['decoration']     = $_REQUEST['decoration'];//
	$properties['stove']          = $_REQUEST['stove'];//
	$properties['wc']             = $_REQUEST['wc'];
	$properties['have_loggia']    = $_REQUEST['have_loggia'];//
	$properties['have_balcony']   = $_REQUEST['have_balcony'];//
	$properties['have_phone']     = $_REQUEST['have_phone'];//
	$properties['price']          = intval(str_replace(" ","",$_REQUEST['price']));
	$properties['currency']       = $_REQUEST['currency'];//
	$properties['have_furniture'] = $_REQUEST['have_furniture'];//
	if ($_REQUEST["with_furniture"])
	{
		$properties['have_furniture'] = $_REQUEST["with_furniture"];
	}
	$properties['can_mortgage']   = $_REQUEST['can_mortgage'];//
//newbuildings
	$properties['class']                     = $_REQUEST['class'];
	$properties['type']                      = $_REQUEST['type'];
	$properties['floors']                    = $_REQUEST['floors'];
	$properties['build_year']                = $_REQUEST['build_year'];
	$properties['lift']                      = $_REQUEST['lift'];
	$properties['distance_to_sea']           = $_REQUEST['distance_to_sea'];
	$properties['dimension_distance_to_sea'] = $_REQUEST['dimension_distance_to_sea'];
	$properties['deadline']                  = $_REQUEST['deadline'];
//houses
	$properties['summary_buildings_square'] = $_REQUEST['summary_buildings_square'];
	$properties['summary_apartment_square'] = $_REQUEST['summary_apartment_square'];
	$properties['number_of_storeys']        = $_REQUEST['number_of_storeys'];
	$properties['sector_square']            = $_REQUEST['sector_square'];
	$properties['dimension']                = $_REQUEST['dimension'];
	$properties['number_of_bedrooms']       = $_REQUEST['number_of_bedrooms'];
	$properties['decoration']               = $_REQUEST['decoration'];
	$properties['garage']                   = $_REQUEST['garage'];
	$properties['special_purpose']          = $_REQUEST['special_purpose'];
	$properties['build_year']               = $_REQUEST['build_year'];
//land
	$properties['sector_square']             = $_REQUEST['sector_square'];
	$properties['plot_dimension']            = $_REQUEST['plot_dimension'];
	$properties['rights_to_land']            = $_REQUEST['rights_to_land'];
	$properties['special_purpose']           = $_REQUEST['special_purpose'];
	$properties['distance_to_sea']           = $_REQUEST['distance_to_sea'];
	$properties['dimension_distance_to_sea'] = $_REQUEST['dimension_distance_to_sea'];
	
	$properties["STATUS"]					= $_REQUEST['status'];
	$properties["wall_type"]				= $_REQUEST["wall_type"];
	$properties["video_gallery"]			= $_REQUEST["video_gallery"];
	$properties["object_type"]				= $_REQUEST["object_type"];
	$properties["commerc_type"]				= $_REQUEST["commerc_type"];


	if ($_REQUEST['radio'] == 'radio_all_price') {
		$properties[88] = $_REQUEST['price'];//
	} else {
		$properties[90] = $_REQUEST['price_1m'];//
	}
	$DETAIL_TEXT = $_REQUEST['DETAIL_TEXT'];

	if ($IBLOCK_ID == 7)
	{
		if (true)
		{
			if ($isFlat)
			{
				$name = "";
				if ($properties['room_number'])
				{
					$name .= $properties['room_number']."-комн. квартира, ";
				}
				if ($properties['square'])
				{
					$name .= $properties['square']." м², ";
				}
				if ($properties['floor']  && $properties['floors'])
				{
					$name .= $properties['floor']."/".$properties['floors']." эт. ";
				}
				if ($properties['newbuilding'])
				{
					$objNewBuild = \MorealtyEntities\Newbuilding::getInstanceByID($properties['newbuilding']);
					if ($objNewBuild)
					{
						$name .= "в ".$objNewBuild->getName();
					}
					
				}
				else if ($properties['street'])
				{
					$name .= "по ".$properties['street'];
				}
				
			}
			else
			{
				$name = $_REQUEST['name'];
			}
		}
	}
	/*else if ($IBLOCK_ID == 8)
	{
		if (!$PRODUCT_ID)
		{
			$name = "";
			if ($properties["object_type"])
			{
				$name.= \Utilities\Iblock\PropertyEnum::getTextBuyEnumID($properties["object_type"], "object_type", $IBLOCK_ID)." ";
			}
			if ($properties["summary_buildings_square"])
			{
				$name.= 
			}
		}
	}*/


	

	$IBLOCK_ID = intval($_REQUEST['IBLOCK_ID']);
//SECTION_TYPE = $_REQUEST['type'];

	$arLoadProductArray = Array(
		"MODIFIED_BY"     => $currentUserID,
		//"IBLOCK_SECTION_ID" => false,
		"IBLOCK_ID"       => $IBLOCK_ID,
		"PROPERTY_VALUES" => $properties,
		"DETAIL_TEXT"     => $DETAIL_TEXT,
		"ACTIVE"          => "Y",
	);
	if ($name)
	{
		$arLoadProductArray["NAME"] = $name;
	}

	if ($_REQUEST['is_need_remove'] == 'y') {
		$remove = $el->Delete($_REQUEST['PRODUCT_ID']);
		if ($remove)
		{
				$APPLICATION->RestartBuffer();
				//$USER->SESS_ALL
				$_SESSION["REMOVE_OBJECT"] = "Y";
				LocalRedirect("/personal/myobjects/");
				return true;
		}
	} else if (empty( $_REQUEST['PRODUCT_ID'] ) && $_REQUEST['form_send'] == 'y') {
		
		if (isMeRedactor())
		{
			if ($IBLOCK_ID)
			{
				$res = CIBlockProperty::GetPropertyEnum("IS_ACCEPTED",array(),array("IBLOCK_ID"=>$IBLOCK_ID));
				if ($arTemp = $res->GetNext())
				{
					$arLoadProductArray["PROPERTY_VALUES"]["IS_ACCEPTED"] = $arTemp["ID"];
				}
			}
			$arLoadProductArray["IS_INFORMED"] = "Y";
		}
		/*if (count($arImages) > 0)
		{
			$arLoadProductArray["PROPERTY_VALUES"]["photo_gallery"] = $arImages;
		}
		if (count($PLAN_FILES) > 0)
		{
			$arLoadProductArray["PROPERTY_VALUES"]["layouts_gallery"] = $PLAN_FILES;
		}*/
		
		if ($IBLOCK_ID > 0)
		{
			if ($_REQUEST["type"] == "arend")
			{
				$arLoadProductArray["IBLOCK_SECTION_ID"] = GetArendSectionAtIB($IBLOCK_ID);
			}
			else if ($_REQUEST["type"] == "sell")
			{
				$arLoadProductArray["IBLOCK_SECTION_ID"] = GetSellSectionAtIB($IBLOCK_ID);
			}
		}

		if (\MorealtySale\User::canUserAddTo($IBLOCK_ID)
				|| (IS_USER_OWNER_ONE_REALTY != true || $USER_ADDED_OBJECTS == 0)
				|| $USER->IsAdmin()
				)
		{
			$arLoadProductArray["PROPERTY_VALUES"]["CREATED_BY"] = $USER->GetID();
			if (\MorealtySale\User::isUserRealtor($USER->GetID()))
			{
				$arLoadProductArray["PROPERTY_VALUES"]["realtor"] = $USER->GetID();
			}
			$PRODUCT_ID = $el->Add($arLoadProductArray);
			if (!$PRODUCT_ID)
			{
				$arResult["Errors"]["UPDATE"] = array("STATE"=>$PRODUCT_ID,"MSG"=>$el->LAST_ERROR);
				if ($_REQUEST["PHOTO_FILES"] && count($_REQUEST["PHOTO_FILES"]) > 0)
				{
					$Photo_files = CustomFileInput::convertFilesArrayToID($_REQUEST["PHOTO_FILES"]);
					$_REQUEST["PHOTO_FILES"] = $Photo_files;
				}
				
				if ($_REQUEST["PLAN_FILES"] && count($_REQUEST["PLAN_FILES"]) > 0)
				{
					$Plan_files = CustomFileInput::convertFilesArrayToID($_REQUEST["PLAN_FILES"]);
					$_REQUEST["PLAN_FILES"] = $Plan_files;
				}
				$arResult["INPUTED"] = $_REQUEST;
				if ($Photo_files)
				{
					$arResult["INPUTED"]["PHOTO_FILES"] = $Photo_files;
				}
				if ($Plan_files)
				{
					$arResult["INPUTED"]["PLAN_FILES"] = $Plan_files;
				}
			}
			else
			{
				$rs = CIBlockElement::GetList(array(), array("ID" => $PRODUCT_ID), false, false, array("ID", "IBLOCK_ID"));
				if ($objItem = $rs->GetNextElement())
				{
					$readyItem = $objItem->GetFields();
					$readyItem["PROPERTIES"] = $objItem->GetProperties();
					if ($readyItem["PROPERTIES"]["price"]["VALUE"])
					{
						$el->SetPropertyValuesEx($readyItem["ID"], $readyItem["IBLOCK_ID"], array(
								"price" => intval($readyItem["PROPERTIES"]["price"]["VALUE"]),
						));
					}
				}
				if ($_REQUEST["PHOTO_FILES"] && count($_REQUEST["PHOTO_FILES"]) > 0)
				{
					CustomFileInput::post_proccessFilesPostIblock(
						CustomFileInput::proccessFilesPost(array("post_name" => "PHOTO_FILES")),
						$PRODUCT_ID,
						$IBLOCK_ID,
						"photo_gallery"
					);
				}
				
				if ($_REQUEST["PLAN_FILES"] && count($_REQUEST["PLAN_FILES"]) > 0)
				{
					CustomFileInput::post_proccessFilesPostIblock(
							CustomFileInput::proccessFilesPost(array("post_name" => "PLAN_FILES")),
							$PRODUCT_ID,
							$IBLOCK_ID,
							"layouts_gallery"
					);
				}
				
				
				if (!isMeRedactor() && false)
					//Не не устраивает одно письма на каждый объект, хотят группировку.
				{
					$linkTemplate = "http://morealty.dev.mnwb.com/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=#IB_ID#&type=catalog&ID=#ELE_ID#&lang=ru&find_section_section=-1&WF=Y";
			
					$Link = str_replace("#ELE_ID#",$PRODUCT_ID,str_replace("#IB_ID#", $IBLOCK_ID, $linkTemplate));
					$arCurUser = $USER->GetByID($USER->GetID())->GetNext();
			
					CEvent::Send("ADDED_OBJECT", SITE_ID, array("LINK"=>$Link,
							"NAME"=>$arLoadProductArray["NAME"],
							"USER"=>implode(" ", array($arCurUser["NAME"],$arCurUser["LAST_NAME"])))
					);
				}
				$APPLICATION->RestartBuffer();
				//$USER->SESS_ALL
				$_SESSION["ADDED_OBJECT"] = $PRODUCT_ID;
				LocalRedirect("/personal/myobjects/");
				return true;
			}
		}
		else
		{
			ShowError("Для того чтобы добавить объект в данный раздел, вам следует купить пакет");
			return false;
		}

		$_REQUEST['PRODUCT_ID'] = $PRODUCT_ID;

		$arSelect = Array("ID", "NAME", "DETAIL_TEXT", "IBLOCK_ID", "PROPERTY_*");
		$arFilter = Array("IBLOCK_ID" => $IBLOCK_ID, 'ID' => $PRODUCT_ID, "ACTIVE" => "Y");
		$res      = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize" => 50), $arSelect);
		while ($ob = $res->GetNextElement()) {
			$arFields     = $ob->GetFields();
			$arProperties = $ob->GetProperties();
		}

		$arResult['ITEMS']      = $arFields;
		$arResult['PROPERTIES'] = $arProperties;

		//uf get
		$cUser    = new CUser;
		$sort_by  = "ID";
		$sort_ord = "ASC";
		$dbUsers  = $cUser->GetList($sort_by, $sort_ord, array('ID' => $USER->GetID()), array('SELECT' => array('UF_MYOBJECTS'), 'FIELDS' => array('ID')));

		$arUser = $dbUsers->Fetch();

		//uf set
		$fields = Array(
			"UF_MYOBJECTS" => $arUser['UF_MYOBJECTS'] . $IBLOCK_ID . '-' . $PRODUCT_ID . '|',
		);
		$cUser->Update($currentUserID, $fields);
	} else if ($_REQUEST['form_send'] == 'y') {
		$PRODUCT_ID = $_REQUEST['PRODUCT_ID'];

		
		
		
		$el = new CIBlockElement;
		
		//realtor
		
		$arMainData = $arLoadProductArray;
		unset($arMainData["PROPERTY_VALUES"]);
		$isUpdated = $el->Update($PRODUCT_ID, $arMainData);
		
		if ($arLoadProductArray["PROPERTY_VALUES"])
		{
			$el->SetPropertyValuesEx($PRODUCT_ID, $IBLOCK_ID, $arLoadProductArray["PROPERTY_VALUES"]);
		}
		if (!$isUpdated)
		{
			$arResult["Errors"]["UPDATE"] = array("STATE"=>$isUpdated,"MSG"=>$el->LAST_ERROR);
			if ($_REQUEST["PHOTO_FILES"] && count($_REQUEST["PHOTO_FILES"]) > 0)
			{
				$Photo_files = CustomFileInput::convertFilesArrayToID($_REQUEST["PHOTO_FILES"]);
				$_REQUEST["PHOTO_FILES"] = $Photo_files;
			}
			
			if ($_REQUEST["PLAN_FILES"] && count($_REQUEST["PLAN_FILES"]) > 0)
			{
				$Plan_files = CustomFileInput::convertFilesArrayToID($_REQUEST["PLAN_FILES"]);
				$_REQUEST["PLAN_FILES"] = $Plan_files;
			}
			$arResult["INPUTED"] = $_REQUEST;
			if ($Photo_files)
			{
				$arResult["INPUTED"]["PHOTO_FILES"] = $Photo_files;
			}
			if ($Plan_files)
			{
				$arResult["INPUTED"]["PLAN_FILES"] = $Plan_files;
			}
		}
		else
		{
			if ($PRODUCT_ID)
			{
				$rs = CIBlockElement::GetList(array(), array("ID" => $PRODUCT_ID), false, false, array("ID", "IBLOCK_ID"));
				if ($objItem = $rs->GetNextElement())
				{
					$readyItem = $objItem->GetFields();
					$readyItem["PROPERTIES"] = $objItem->GetProperties();
					if ($readyItem["PROPERTIES"]["price"]["VALUE"])
					{
						$el->SetPropertyValuesEx($readyItem["ID"], $readyItem["IBLOCK_ID"], array(
								"price" => intval($readyItem["PROPERTIES"]["price"]["VALUE"]),
						));
					}
				}
			}
			
			if ($_REQUEST["PHOTO_FILES"] && count($_REQUEST["PHOTO_FILES"]) > 0)
			{
				CustomFileInput::post_proccessFilesPostIblock(
						CustomFileInput::proccessFilesPost(array("post_name" => "PHOTO_FILES")),
						$PRODUCT_ID,
						$IBLOCK_ID,
						"photo_gallery"
						);
			}
			
			if ($_REQUEST["PLAN_FILES"] && count($_REQUEST["PLAN_FILES"]) > 0)
			{
				CustomFileInput::post_proccessFilesPostIblock(
						CustomFileInput::proccessFilesPost(array("post_name" => "PLAN_FILES")),
						$PRODUCT_ID,
						$IBLOCK_ID,
						"layouts_gallery"
						);
			}
			/*if(count($arImages) > 0)
			{
				
				
				CIBlockElement::SetPropertyValueCode($PRODUCT_ID,"photo_gallery",$arImages);
			}
			if (count($PLAN_FILES) > 0)
			{
				CIBlockElement::SetPropertyValueCode($PRODUCT_ID,"layouts_gallery",$PLAN_FILES);
			}*/
			$APPLICATION->RestartBuffer();
			$_SESSION["UPDATE_OBJECT"] = "Y";
			LocalRedirect("/personal/myobjects/");
			return true;
		}
		$arSelect = Array("ID", "NAME", "DETAIL_TEXT", "IBLOCK_ID", "PROPERTY_*");
		$arFilter = Array("IBLOCK_ID" => $IBLOCK_ID, 'ID' => $PRODUCT_ID);
		$res      = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize" => 50), $arSelect);
		while ($ob = $res->GetNextElement()) {
			$arFields     = $ob->GetFields();
			$arProperties = $ob->GetProperties();
		}


		$arResult['ITEMS']      = $arFields;
		$arResult['PROPERTIES'] = $arProperties;
	} else if (!($_REQUEST['form_send'] == 'y') && !empty($IBLOCK_ID) && $_REQUEST['PRODUCT_ID']) {
		$PRODUCT_ID = $_REQUEST['PRODUCT_ID'];

		$arSelect = Array("*");
		$arFilter = Array("IBLOCK_ID" => $IBLOCK_ID, 'ID' => $PRODUCT_ID,);
		$res      = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize" => 50), $arSelect);
		while ($ob = $res->GetNextElement()) {
			$arFields     = $ob->GetFields();
			$arProperties = $ob->GetProperties();
		}

		
		$arResult['ITEMS']      = $arFields;
		$arResult['PROPERTIES'] = $arProperties;
		$arResult["INPUTED"] = array("name"=>$arFields["NAME"],"DETAIL_TEXT"=>$arFields["DETAIL_TEXT"]);
		foreach ($arResult['PROPERTIES'] as $arProp)
		{
			
			if ($arProp["CODE"] && $arProp["VALUE"])
			{
				if (($IBLOCK_ID == 8 || $IBLOCK_ID == 10 || $IBLOCK_ID == 11) && $arProp["PROPERTY_TYPE"] == "L")
				{
					$arResult["INPUTED"][$arProp["CODE"]] = $arProp["VALUE_ENUM_ID"];
				}
				else if ($arProp["CODE"] == "decoration" || $arProp["CODE"] == "stove" || $arProp["CODE"] == "wc")
				{
					$arResult["INPUTED"][$arProp["CODE"]] = $arProp["VALUE_ENUM_ID"];
				}
				else
				{
					$arResult["INPUTED"][$arProp["CODE"]] = $arProp["VALUE"];
				}
				
			}
		}
		
	}
//else {
//    $PRODUCT_ID = $_REQUEST['PRODUCT_ID'];
//
//    $arSelect = Array("ID", "NAME", "DETAIL_TEXT", "IBLOCK_ID", "PROPERTY_*");
//    $arFilter = Array("IBLOCK_ID" => $IBLOCK_ID, 'ID' => $PRODUCT_ID, "ACTIVE" => "Y");
//    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize" => 50), $arSelect);
//    while ($ob = $res->GetNextElement()) {
//        $arFields = $ob->GetFields();
//        $arProperties = $ob->GetProperties();
//    }
//
//
//    $arResult['ITEMS'] = $arFields;
//    $arResult['PROPERTIES'] = $arProperties;
//}
	}
}
$this->IncludeComponentTemplate();
?>