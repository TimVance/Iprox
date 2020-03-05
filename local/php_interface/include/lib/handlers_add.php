<?
/**
 * Установка обработчиков и их описание.
 * Желательно описание (определение класса и метода) делать отдельно от данного файла
 *
 * Т.е. в данном файле пишем AddEventHandler
 * а сам обработчик в файле /include/lib/handlers/siteevents.php
 */

//


use Bitrix\Main\EventManager;


/**
 * Странно но события не вызываются ... хм, что то тут не так
 * $eventManager = EventManager::getInstance();
$eventManager->addEventHandler(
		"security",
		"Bitrix\Security\SessionTable::OnAfterAdd",
		array("Sessions\\SessionManager","OnSessionAdd")
);
$eventManager->addEventHandler(
		"security",
		"Bitrix\Security\SessionTable::OnAfterUpdate",
		array("Sessions\\SessionManager","OnSessionUpdate")
);
$eventManager->addEventHandler(
		"security",
		"Bitrix\Security\SessionTable::OnAfterDelete",
		array("Sessions\\SessionManager","OnSessionRemove")
);*/


use Bitrix\Main;
Main\EventManager::getInstance()->addEventHandler(
		'sale',
		'OnSaleOrderBeforeSaved',
		'OnSaleStatusUpdateSetLastDate'
);
//AddEventHandler("sale", "OnStatusUpdate", "OnSaleStatusUpdateSetLastDate");

AddEventHandler("iblock", "OnBeforeIBlockElementAdd", "CountPrice");
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", "CountPrice");
AddEventHandler('iblock', 'OnAfterIBlockElementUpdate', "CheckAddedToArendSection");

AddEventHandler("main", "OnBeforeProlog", "ChangeDescriptionField");
AddEventHandler("iblock", "OnBeforeIBlockElementAdd", "checkDescr");
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", "checkDescr");




MorealtyEvents::registerEvents();

function LogoutUserIfNeeded()
{
	global $USER;
	if ($USER->IsAuthorized())
	{
		$rsUser = $USER->GetList($by = "id",$order =  "asc",array("ID"=>$USER->GetID(),"!UF_LOGOUTUSER"=>false),array("SELECT"=>array("UF_LOGOUTUSER"),"FIELDS"=>array("ID")));
		if ($arUser = $rsUser->GetNext())
		{
			$USER->Update($USER->GetID(), array("UF_LOGOUTUSER"=>false));
			$USER->Logout();
		}
		
	}
	
}

AddEventHandler("main", "OnAdminTabControlBegin", "tryToRemakeBuildForm");
function tryToRemakeBuildForm(&$form)
{
	global $USER,$APPLICATION;
	if ($APPLICATION->GetCurPage() == "/bitrix/admin/iblock_element_edit.php" && $_REQUEST["IBLOCK_ID"] == 7 && $_REQUEST["simple_add"] == "Y")
	{
		$PropsToShow = array(
				"PROPERTY_34",
				"PROPERTY_41",
				"PROPERTY_54",
				"PROPERTY_61",
				"PROPERTY_69",
				"PROPERTY_74",
				//"PROPERTY_82",
				"PROPERTY_84",
				"PROPERTY_85",
				"PROPERTY_88",
				"PROPERTY_89",
				"PROPERTY_157",
				"PROPERTY_188",
				"PROPERTY_281"
				);
		//my_print_r($form);
		foreach ($form->tabs as &$arTab)
		{
			foreach ($arTab["FIELDS"] as $key=>$arField)
			if (strpos($arField["id"], "PROPERTY_") !== false && !$arField["required"])
			{
				if (!in_array($arField["id"],$PropsToShow))
				{
					unset($arTab["FIELDS"][$key]);
				}
			}
			//my_print_r($arTab);
		}
	}
}
AddEventHandler('main', 'OnAdminContextMenuShow', "tryToRemakeBuildButtons");
function tryToRemakeBuildButtons(&$items)
{
	global $USER,$APPLICATION;
	if ($_REQUEST["IBLOCK_ID"] == "7" && ($APPLICATION->GetCurPage() == "/bitrix/admin/iblock_section_admin.php" || $APPLICATION->GetCurPage() == "/bitrix/admin/iblock_element_admin.php"))
	{
		$searchedIndex = false;
		$addAr = false;
		foreach ($items as $key=>$arItem)
		{
			if (strpos($arItem["LINK"],"iblock_element_edit.php") !== false)
			{
				$addAr = $arItem;
				$searchedIndex = $key;
				break;
			}
		}
		if ($addAr)
		{
			$addAr["LINK"] = (strpos($addAr["LINK"], "?") !== false)? $addAr["LINK"]."&simple_add=Y" : $addAr["LINK"]."?simple_add=Y";
			$addAr["TEXT"] = "Быстрое добавление квартиры";
			array_splice($items, $searchedIndex+1,0,array($addAr));
		}

	}
}

//AddEventHandler('iblock', 'OnBeforeIBlockElementUpdate', "AddWatermark");
//function AddWatermark(&$arFields){
//	foreach($arFields['PROPERTY_VALUES'] as $key => $value) {
//		foreach($value as $key2 => $value2) {
//			if($value2['VALUE']["tmp_name"]){
//				$v = CFile::SaveFile(CFile::MakeFileArray($value2['VALUE']["tmp_name"]), "abc");
//
//				$name = $arFields['PROPERTY_VALUES'][$key][$key2]['VALUE']["name"];
//				$arFields['PROPERTY_VALUES'][$key][$key2] = CFile::MakeFileArray(CImg::Resize(CImg::Overlay($v, $_SERVER['DOCUMENT_ROOT']."/upload/medialibrary/952/morealty_watermark.png"), 1024, 768));
//				$arFields['PROPERTY_VALUES'][$key][$key2]["name"] = $name;
//			}
//		}
//	}
//}

function SetWatermark(&$arFields) {
	echo "<pre>";
	print_r($arFields);
	foreach($arFields['PROPERTY_VALUES'] as $key => $value) {
		if($key == 34) {
			foreach($value as $val) {
				if(!empty($val['VALUE']['tmp_name'])) {
					print_r($val);
					$v = CFile::SaveFile(CFile::MakeFileArray($val["tmp_name"]), "abc");
					print_r(CFile::GetPath($v));
					die();
				}
			}
		}
	}
	die();
}

function CheckAddedToArendSection(&$arFields) {
	global $USER;
	$ELEMENT_ID = $arFields['ID'];

	switch ($arFields['IBLOCK_ID']) {
		case 7: $IBLOCK_SECTION_ID = 1; break;
	}

	$db_old_groups = CIBlockElement::GetElementGroups($ELEMENT_ID, true, array('ID', 'IBLOCK_ID'));
	$ar_new_groups = array();
	while($ar_group = $db_old_groups->Fetch()) {
		$ar_new_groups[] = $ar_group["ID"];
	}

	if ($arFields["PROPERTY_VALUES"]["80"]["0"]["VALUE"] == 4) {
		$ar_new_groups[] = $IBLOCK_SECTION_ID;
	}
	else {
		if(in_array($IBLOCK_SECTION_ID, $ar_new_groups)) {
			foreach ($ar_new_groups as $key => $value) {
				if($value == $IBLOCK_SECTION_ID) {
					unset($ar_new_groups[$key]);
				}
			}
		}
	}

	CIBlockElement::SetElementSection($ELEMENT_ID, $ar_new_groups);
}

// вычисляем поле "Стоимость одного м2" путем деления "Стоимость объекта" на "Площадь квартиры"
function CountPrice(&$arFields) {

	switch($arFields['IBLOCK_ID']) {
		case 7:  $priceID = 88;	    $squareID = 157;	    $price_1m_ID = 90; break;
		case 19: $priceID = 214;	$squareID = 216;	$price_1m_ID = 217; break;
		default: break;
	}
	foreach($arFields['PROPERTY_VALUES'][$priceID] as $item) {
		$price = $item['VALUE'];
	}
	foreach($arFields['PROPERTY_VALUES'][$squareID] as $item) {
		$square = $item['VALUE'];
	}
	$price = (int)str_replace(' ', '', $price);
	$square = (int)str_replace(' ', '', $square);
	foreach($arFields['PROPERTY_VALUES'][$price_1m_ID] as $item) {
		//echo $item['VALUE'] . ' = val';
	}
	foreach($arFields['PROPERTY_VALUES'][$price_1m_ID] as $key => $value) {
		$arFields['PROPERTY_VALUES'][$price_1m_ID][$key]['VALUE'] = (int) ($price / $square);
	}

}

//TODO переделать. (падает сайт, если закомментировать весь event)
//каталог-элементы: дизейблим селекты "Квартал сдачи" и "Год сдачи", если проставлен чекбокс "Объект сдан"
AddEventHandler("iblock", "OnIBlockPropertyBuildList", array("CIBlockNewProperty", "GetUserTypeDescription"), function (&$arFields) {
	global $APPLICATION;
	if (defined("CRON_SCRIPT") || strpos($APPLICATION->GetCurPage(), "/bitrix") !== false ||
			
			CSite::InDir("/local/templates/morealty/ajax_php/") || $_REQUEST["ajax"] || $_REQUEST["AJAX_POST"])
		return;
	ob_start();
	?>
<!--	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>-->
<!--	<script>-->
<!--		$(document).ready( function() {-->
<!--			if(!$('#tr_PROPERTY_121 input[type=checkbox]').prop('checked')) {-->
<!--				$('#tr_PROPERTY_122 select').attr('disabled', 'disabled');-->
<!--				$('#tr_PROPERTY_123 select').attr('disabled', 'disabled');-->
<!--			}-->
<!--			$('#tr_PROPERTY_121 input[type=checkbox]').change(function() {-->
<!--				if(!$('#tr_PROPERTY_121 input[type=checkbox]').prop('checked')) {-->
<!--					$('#tr_PROPERTY_122 select').attr('disabled', 'disabled');-->
<!--					$('#tr_PROPERTY_123 select').attr('disabled', 'disabled');-->
<!--				}-->
<!--				else {-->
<!--					$('#tr_PROPERTY_122 select').removeAttr('disabled');-->
<!--					$('#tr_PROPERTY_123 select').removeAttr('disabled');-->
<!--				}-->
<!--			});-->
<!--		});-->
<!--	</script>-->
	<?
	$t = ob_get_clean();
	//echo $t;
});

// определяем пользователя в группу (владелец недвижимости \ клиент)
AddEventHandler("main", "OnBeforeUserRegister", function (&$arFields) {
	$arFields["GROUP_ID"] = '';
	if($arFields["WORK_PROFILE"] == '8') {
		$arFields["GROUP_ID"][] = 8;
	}
	else if($arFields["WORK_PROFILE"] == '7') {
		$arFields["GROUP_ID"][] = 7;
	}
	else {
		$arFields["GROUP_ID"][] = 2;
	}
});

//авторизация по email
AddEventHandler("main", "OnBeforeUserLogin", function (&$arFields) {
	if(empty($arFields["LOGIN"]) && trim($_REQUEST["USER_EMAIL"]) != '') {
		$filter = Array("EMAIL" => $_REQUEST["USER_EMAIL"]);
		$rsUsers = CUser::GetList(($by="LAST_NAME"), ($order="asc"), $filter);
		if($user = $rsUsers->GetNext())
			$arFields["LOGIN"] = $user["LOGIN"];
	}
});


// определяем глобальные константы, которые могут зависеть от $APPLICATION и $USER
AddEventHandler("main", "OnProlog", function () {

	global $APPLICATION, $USER;

	// включаем генератор ORM
	if ($APPLICATION->GetCurPage() == '/bitrix/admin/perfmon_tables.php' && $_GET['orm'] != 'y') {
		LocalRedirect( $APPLICATION->GetCurPageParam("orm=y") );
	}

	if (defined("ADMIN_SECTION")) {
		ob_start();
		?>
		<script type="text/javascript">
			BX.ready(function(){
				try {
					var mess = BX.findChild(BX('adm-workarea'), {'class' : 'adm-info-message'}, true);
					if (mess && mess.textContent && mess.textContent.search('пробная') != -1) {
						BX.remove( BX.findChild(BX('adm-workarea'), {'class' : 'adm-info-message-wrap'}, true) );
					}
				} catch (ignore) {
				}
			});
		</script>
		<? 
		if ($APPLICATION->GetCurPage() == '/bitrix/admin/iblock_element_admin.php') {
			CJSCore::Init("bx");
			?>
			<script>
				BX.ready(function(){
					setTimeout(function(){
						var Elements = document.getElementsByClassName("adm-select");
						
						if (Elements.length > 0)
						{
							for(var i = 0;i< Elements.length;i++)
							{
								CurElement = Elements[i];
								
								var Childs = BX.findChildren(CurElement,{tagName: "option",property : {"value" : "NOT_REF"}});
								if (Childs.length > 0)
								{
									for(var j = 0;j< Childs.length;j++)
									{
										Childs[j].text = "Нет";
									}
								}
							}
						}
					},400);

				});

			</script>
			<?
		}
		?>
		<?
		$APPLICATION->AddHeadString( ob_get_clean() );
	} else {
		include __DIR__ . '/constants.php';
	}
	?>

	<?

});

// проставляем id инфоблоков в административном меню
AddEventHandler("main", "OnBuildGlobalMenu", function (&$aGlobalMenu, &$aModuleMenu) {

	if (! $GLOBALS['USER']->IsAdmin() || !defined("ADMIN_SECTION")) {
		return;
	}
	foreach ($aModuleMenu as $k => $arMenu) {
		if ($arMenu['icon'] != 'iblock_menu_icon_types') {
			continue;
		}
		foreach ($arMenu['items'] as $i => $item) {
			$arEx = explode('/', $item['items_id']);
			$aModuleMenu[$k]['items'][$i]['text'] .= ' [' . $arEx[2] . ']';
		}
	}

});


// верхняя постраничка в админке в лентах
AddEventHandler("main", "OnAdminListDisplay", function ($this_al) {

	/* @var $this_al CAdminList */
	echo $this_al->sNavText;

});

AddEventHandler("main", "OnBeforeUserRegister", "OnBeforeUserRegisterMorealty");

function OnBeforeUserRegisterMorealty(&$arFields)
{
	global $APPLICATION;
	if ($arFields["PERSONAL_MOBILE"])
	{
		$res = CUser::GetList($by = "ID", $order = "ASC",array("PERSONAL_MOBILE"=>$arFields["PERSONAL_MOBILE"]));
		if ($res->AffectedRowsCount() > 0)
		{
			$APPLICATION->ThrowException("Пользователь с таким телефоном уже присутствует в системе");
			return false;
		}
	}
	//
	
}
//OnBeforeUserRegister

// очищаем настройки формы по-умолчанию для всех админов
// @see http://hipot.wexpert.ru/Codex/form_iblock_element_settings/
AddEventHandler('main', 'OnEndBufferContent', function (&$content) {
	if (count($_POST['p']) <= 0) {
		return;
	}

	global $APPLICATION, $DB, $CACHE_MANAGER;

	$pCfg 		= array_shift($_POST['p']);

	if ($APPLICATION->GetCurPage() != '/bitrix/admin/user_options.php'
		|| $pCfg['c'] != 'form' || $pCfg['d'] != 'Y'
		|| !preg_match('#^form_((section)|(element))_[0-9]+$#', $pCfg['n'])
	) {
		return;
	}

	$DB->Query("DELETE FROM b_user_option WHERE CATEGORY = 'form' AND NAME = '" . $pCfg['n'] . "' AND COMMON = 'N'");
	$CACHE_MANAGER->cleanDir("user_option");
});



/*

//в планировках
function ChangeDescriptionField() {
	?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<script>
		$(document).ready(function(){

			var inputs = $('#tr_PROPERTY_244 .adm-detail-content-cell-r table tbody tr td input[type=text]');
			var fullVal;
			for(var i = 0; i < 2;i++) {
				$(inputs[i]).val('1,2,3');
				fullVal = $(inputs[i]).val();
				fullVal = fullVal.split(',');
				console.log(fullVal);

				if(fullVal == '') {
					fullVal[0] = '';
					fullVal[1] = '';
					fullVal[2] = '';
				}
				$('' +
					'<label class="temp-label">Этаж: </label>' +
					'<input name="temp-stage" class="temp-input" value="'+ fullVal[0] +'" size="5" type="text">' +
					'<label class="temp-label">Номер квартиры: </label>' +
					'<input name="temp-flat-number" class="temp-input" value="'+ fullVal[1] +'" size="5" type="text">' +
					'<label class="temp-label">Количество комнат: </label>' +
					'<input name="temp-flat-rooms-count" class="temp-input" value="'+ fullVal[2] +'" size="5" type="text">'
				).insertAfter($(inputs[i]));
			}
			$('body').on('click', '.adm-detail-content-btns', function(e) {
				var rows = $('#tr_PROPERTY_244 .adm-detail-content-cell-r table tbody tr');
				var ar, val = 0, newVal = 0, curVal = 0;
				for(var j = 0; j < rows.length; j++) {
					ar = $(rows[j]).find('.temp-input');

					newVal = '';
					for(var i = 0; i <= 2;i++) {
						newVal += $(ar[i]).val();
						//curVal = $(ar[i]).parent('td').children('input:eq(0)').val();
						if(curVal != '') {
							$(ar[i]).parent('td').children('input:eq(0)').val(curVal + ',' + newVal);
						} else {
							$(ar[i]).parent('td').children('input:eq(0)').val(newVal);
						}
					}
				}
			});
		});
	</script>
	<style>
		tr#tr_PROPERTY_244 input[type=text] {display:none; width: 50px !important; margin-bottom: 8px;}
		tr#tr_PROPERTY_244 input.temp-input {display: inline-block !important;}
		tr#tr_PROPERTY_244 label {vertical-align: top; margin: 0 8px; }
		tr#tr_PROPERTY_244 td {width: 80px !important; margin-bottom: 8px;}
	</style><?
}
*/


//false

//$PocketRes = CIBlockElement::GetList(array(),array("ACTIVE"=>"Y","IBLOCK_ID"=>6,"PROPERTY_ONLY_REG"=>false, "ID"=>$arBasketItems[0]),false,false,array("IBLOCK_ID","NAME","ID","PROPERTY_long_month"));

/*AddEventHandler("main", "OnAfterUserLogin", "OnAfterUserLoginCheckPackets");
function OnAfterUserLoginCheckPackets($arParams)
{
	global $USER;
	if ($arParams["USER_ID"])
	{
		$rs = CUser::GetList($by = "id", $order = "asc",array("ID"=>$arParams["USER_ID"],"!UF_PACKETS_TO_ACCEPT"=>false),array("UF_PACKETS_TO_ACCEPT"));
		$Usr = new CUser;
		if ($arUser = $rs->GetNext())
		{
		if ($arUser["ID"] && CModule::IncludeModule("iblock") && CModule::IncludeModule("catalog") && CModule::IncludeModule("sale"))
		 {
		 	$Usr->Update($arUser["ID"], array("UF_PACKETS_TO_ACCEPT"=>array()));
			
			$PocketRes = CIBlockElement::GetList(array(),array("ACTIVE"=>"Y","IBLOCK_ID"=>6,"!PROPERTY_ONLY_REG"=>false,"ID"=>$arUser["UF_PACKETS_TO_ACCEPT"]),false,false,array("IBLOCK_ID","NAME","ID","PROPERTY_long_month"));
			if ($PocketRes->AffectedRowsCount() > 0)
			{
			while ($aritemPocket = $PocketRes->GetNext())
				{
				
				Add2BasketByProductID(intval($aritemPocket["ID"]),$Quant);
				
				$FUserID = \Bitrix\Sale\Fuser::getIdByUserId($arUser["ID"]);
				
				$basket = \Bitrix\Sale\Basket::loadItemsForFUser(
						$FUserID
						,
				
						\Bitrix\Main\Context::getCurrent()->getSite()
				);
				$basketItems = $basket->getBasketItems();
				foreach ($basketItems as $objBasketItem)
				{
				$arBasketItems[] = $objBasketItem->getProductId();
				}
				$order = Bitrix\Sale\Order::create(SITE_ID, $arUser["ID"]);
				$order->setPersonTypeId(1);
				$order->setBasket($basket);
				
				
				$shipmentCollection = $order->getShipmentCollection();
				$shipment = $shipmentCollection->createItem(
						Bitrix\Sale\Delivery\Services\Manager::getObjectById(2)
				);
				
				$shipmentItemCollection = $shipment->getShipmentItemCollection();
				
				foreach ($basket as $basketItem)
				{
				
				$item = $shipmentItemCollection->createItem($basketItem);
				$item->setQuantity($basketItem->getQuantity());
				}
				
				$paymentCollection = $order->getPaymentCollection();
				$payment = $paymentCollection->createItem(
						Bitrix\Sale\PaySystem\Manager::getObjectById(1)
				);
				$payment->setField("SUM", $order->getPrice());
				$payment->setField("CURRENCY", $order->getCurrency());
				
				
				
				
				$propertyCollection = $order->getPropertyCollection();
				
				$packetProperty = getPropertyByCode($propertyCollection, 'packet');
				$packetProperty->setValue($arBasketItems[0]);
				
				$timeProperty = getPropertyByCode($propertyCollection, 'time_end');
				$SetTimeTo = \MorealtySale\Packets::getCurTime($aritemPocket["PROPERTY_LONG_MONTH_VALUE"]);
				$timeProperty->setValue($SetTimeTo);
				
				
				
				
				$result = $order->save();
				
				
				$Return["ITEMS"] = $arBasketItems;
				if (!$result->isSuccess())
				{
					
				}
				else {
				$OrderId = $result->getId();
				CSaleUserAccount::UpdateAccount($USER->GetID(), "+".$order->getPrice(), $order->getCurrency());
				$return = CSaleOrder::PayOrder($OrderId,"Y",true,false);
					if (!$return)
					{
					CSaleOrder::Delete($OrderId);
					}
					else {
					\MorealtySale\Packets::UpdateActivePockets($arUser["ID"]);
					}
				}
				}
			}
			}
			CSaleBasket::DeleteAll(CSaleBasket::GetBasketUserID());
		}
	}
}
if (!function_exists("getPropertyByCode"))
{
	function getPropertyByCode($propertyCollection, $code)  {
		foreach ($propertyCollection as $property)
		{
			if($property->getField('CODE') == $code)
				return $property;
		}
	}
}
AddEventHandler("main", "OnAfterUserRegister", "OnAfterUserRegisterBuyPackets");
function OnAfterUserRegisterBuyPackets($arFields)
{
	if ($arFields["USER_ID"] && CModule::IncludeModule("iblock"))
	{
		$PocketRes = CIBlockElement::GetList(array(),array("ACTIVE"=>"Y","IBLOCK_ID"=>6,"!PROPERTY_ONLY_REG"=>false),false,false,array("IBLOCK_ID","NAME","ID","PROPERTY_long_month"));
		$PacketsToAdd = false;
		if ($PocketRes->AffectedRowsCount() > 0)
		{
			$PacketsToAdd = array();
			while ($arpacket = $PocketRes->GetNext())
			{
				$PacketsToAdd[] = $arpacket["ID"];
			}
		}
		if ($PacketsToAdd)
		{
			$usr = new CUser;
			$usr->Update($arFields["USER_ID"], array("UF_PACKETS_TO_ACCEPT"=>$PacketsToAdd));
		}
		
	}
}
*/

AddEventHandler("iblock", "OnAfterIBlockElementAdd", "SetApartLivingBlock");
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", "SetApartLivingBlock");
//OnStartIBlockElementUpdate
function SetApartLivingBlock($arFields)
{
	
	if ($arFields["RESULT"] || $arFields["ID"] > 0)
	{
		
		if ($arFields["IBLOCK_ID"] == "27")
		{
			
			$ID = $arFields["ID"];
			
			$SECTION_ID = $arFields["IBLOCK_SECTION_ID"];
			
			$ele = new CIBlockElement;
			$res = CIBlockElement::GetList(array(),array("IBLOCK_ID"=>27,"ID"=>$ID));
			if ($objItem = $res->GetNextElement())
			{
				$arItem = $objItem->GetFields();
				$arItem["PROPERTIES"] = $objItem->GetProperties();
				if (!$SECTION_ID)
				{
					$SECTION_ID = $arItem["IBLOCK_SECTION_ID"];
				}
				if ($arItem["PROPERTIES"]["APART_ELEMS"]["VALUE"])
				{
					$res2 = CIBlockElement::GetList(array(),array("ID"=>$arItem["PROPERTIES"]["APART_ELEMS"]["VALUE"]));
					while ($objSubItem = $res2->GetNextElement())
					{
						$arSubItem = $objSubItem->GetFields();
						$arSubItem["PROPERTIES"] = $objSubItem->GetProperties();
						
						$newLivingBlocks = ($arSubItem["PROPERTIES"]['living_block']["VALUE"])? array_merge($arSubItem["PROPERTIES"]['living_block']["VALUE"],array($SECTION_ID)) : array($SECTION_ID);
						
						if ($newLivingBlocks)
						{
							
							$ele->SetPropertyValuesEx($arSubItem["ID"], $arSubItem["IBLOCK_ID"], array("living_block"=>$newLivingBlocks));
						}
						
					}
				}
			}
		}
	}
}

\NewbuildingsAdmin\Menu::registerEvents();


AddEventHandler("main", "OnProlog", "OnProplogSetCatalogParams");
function OnProplogSetCatalogParams()
{
	if (CSite::InDir("/sell/"))
	{
		switch ($_REQUEST["catalog"])
		{
			case "projects" :
				$_REQUEST["catalog"] = "commercial";
				$_REQUEST["SECTION_ID"] = "40";
			break;
			case "markets" :
				$_REQUEST["catalog"] = "commercial";
				$_REQUEST["SECTION_ID"] = "39";
			break;
			case "complex" :
				$_REQUEST["catalog"] = "houses";
				$_REQUEST["SECTION_ID"] = "41";
			break;
		}
	}
}

AddEventHandler("iblock", "OnAfterIBlockElementAdd", "UpdatePricePacketInstant");
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", "UpdatePricePacketInstant");
function UpdatePricePacketInstant($arFields)
{
	if ($arFields["ID"] && $arFields["IBLOCK_ID"] == 7)
	{
		$res = CIBlockElement::GetList(array(), array("ID" => $arFields["ID"], "IBLOCK_ID" => $arFields["IBLOCK_ID"]), false, false, array("ID", "IBLOCK_ID" , "PROPERTY_newbuilding"));
		if ($arItem = $res->GetNext())
		{
			if ($arItem["PROPERTY_NEWBUILDING_VALUE"])
			{
				\Morealty\Catalog::updateNewbuildingProps($arItem["PROPERTY_NEWBUILDING_VALUE"]);
			}
		}
	}
	if ($arFields["ID"] && $arFields["IBLOCK_ID"] == "6" && CModule::IncludeModule("iblock") && CModule::IncludeModule("sale"))
	{
		$res = CIBlockElement::GetList(array(),array("IBLOCK_ID"=>"6","ID"=>$arFields["ID"]),false,false,array("ID","IBLOCK_ID","NAME","PROPERTY_price"));
		if ($arItem = $res->GetNext())
		{
			
			if ($arItem["PROPERTY_PRICE_VALUE"])
			{
				
				CPrice::SetBasePrice($arItem["ID"], $arItem["PROPERTY_PRICE_VALUE"], "RUB");
				
				AddEventHandler("catalog", "OnBeforePriceUpdate", function($ID,&$arFields) use ($arItem){
					if ($arFields["PRODUCT_ID"] == $arItem["ID"])
					{
						$arFields["PRICE"] = (float) $arItem["PROPERTY_PRICE_VALUE"];
					}
				});
				/*$arPrice = CPrice::GetBasePrice($arItem["ID"], false, false, false);
				var_dump($arPrice);
				if ($arPrice)
				{
					if (CPrice::Update($arPrice["ID"], array("PRICE"=>floatval($arItem["PROPERTY_PRICE_VALUE"])),true))
					{
						var_dump("Updated");
					}
					
				}*/
				//die();
				//var_dump($arPrice);
				//die();
				//CPrice::SetBasePrice($arItem["ID"], $arItem["PROPERTY_PRICE_VALUE"], "RUB");
				/*if (CPrice::SetBasePrice($arItem["ID"], $arItem["PROPERTY_PRICE_VALUE"], "RUB"))
				{
					var_dump("ok");
					var_dump($arItem);
					die();
				}*/
			}
			
		}
		
	}
}
AddEventHandler("iblock", "OnStartIBlockElementAdd", "SetNameByPropBeforeElement");
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", "SetNameByPropBeforeElementAdmin");

AddEventHandler("iblock", "OnAfterIBlockElementSetPropertyValuesEx", "SetNameByPropAfterElementUpdate");

function SetNameByPropBeforeElementAdmin(&$arFields)
{
	global $APPLICATION;
	if ($APPLICATION->GetCurPage() == "/bitrix/admin/iblock_element_edit.php")
	{
		SetNameByPropBeforeElement($arFields);
	}
}

function SetNameByPropAfterElementUpdate($element, $iblock_id, $props, $flags)
{
	if ($iblock_id == 8)
	{
/*		$res = CIBlockElement::GetByID($element);
		if ($objItem = $res->GetNextElement())
		{
			$arItem = $objItem->GetFields();
			$arItem["PROPERTIES"] = $objItem->GetProperties();
		}
		
		$PropsToId = array(
				"object_type",
				"summary_buildings_square",
				"sector_square",
				"dimension"
		);
		//$CodesToID = \Utilities\Iblock\Property::PropertiesCodeToID($PropsToId, 8);
		$type = $arItem["PROPERTIES"]["object_type"]["VALUE"];
		
		$sumSquare = $arItem["PROPERTIES"]["summary_buildings_square"]["VALUE"];
		$sumSquareMetr = "кв.м.";
		
		$uchastok = $arItem["PROPERTIES"]["sector_square"]["VALUE"];
		
		
		$uchastokMetr = $arItem["PROPERTIES"]["dimension"]["VALUE"];
		
		
		$name = "";
		if ($type)
			$name.= $type." ";
		if ($sumSquare)
			$name.=$sumSquare." ";
		if ($sumSquareMetr && $sumSquare)
			$name.=$sumSquareMetr." ";
		if ($uchastok)
			$name.="на участке ".$uchastok." ";
		if ($uchastokMetr && $uchastokMetr)
			$name.=$uchastokMetr;
					
			
		$newEle = new CIBlockElement();
		$newEle->Update($arItem["ID"], array("NAME" => $name, "CODE" => CUtil::translit($name, "ru")));*/
	}
	else if ($iblock_id == 11)
	{
		$res = CIBlockElement::GetByID($element);
		if ($objItem = $res->GetNextElement())
		{
			$arItem = $objItem->GetFields();
			$arItem["PROPERTIES"] = $objItem->GetProperties();
		}
		
		
		$PropsToId = array(
			"commerc_type",
			"square",
			"street"
		);
		
		$street = $arItem["PROPERTIES"]["street"]["VALUE"];
		$square = $arItem["PROPERTIES"]["square"]["VALUE"];

		$comecrType = \Utilities\Iblock\PropertyEnum::getTextBuyEnumID($arItem["PROPERTIES"]["commerc_type"]["VALUE_ENUM_ID"], "commerc_type", 11);
		
		$uchastokMetr = "кв.м.";
		
		$name = $comecrType." ".$square." ".$uchastokMetr." по ".$street;
		
		
		$newEle = new CIBlockElement();
		$newEle->Update($arItem["ID"], array("NAME" => $name, "CODE" => CUtil::translit($name, "ru")));
	}
}

function SetNameByPropBeforeElement(&$arFields)
{
	global $USER;
	if ($arFields["IBLOCK_ID"] == 7)
	{
		//188 - Новостройка
		//85  - Этаж
		//84  - Количество комнат
		//157 - Площадь
		//54  - Город
		//61   district
		//74 - Street
		//69 Микрорайон
		$microdistrict = 69;
		$Street = 74;
		$City  = 54;
		$district = 61;
		$newBuild = 188;
		$Floor = 85;
		$Rooms = 84;
		$Square = 157;
		if ($arFields["PROPERTY_VALUES"][$newBuild]["n0"]["VALUE"])
		{
			$res = CIBlockElement::GetByID($arFields["PROPERTY_VALUES"][$newBuild]["n0"]["VALUE"]);
			if ($objItem = $res->GetNextElement())
			{
				
				//floors
				$arNewB = $objItem->GetFields();
				$arNewBProp = $objItem->GetProperties();
				
				
				$arFields["PROPERTY_VALUES"][$City]["n0"]["VALUE"] = $arNewBProp["city"]['VALUE'];
				$arFields["PROPERTY_VALUES"][$Street]["n0"]["VALUE"] = $arNewBProp["street"]['VALUE'];
				$arFields["PROPERTY_VALUES"][$district]["n0"]["VALUE"] = $arNewBProp["district"]['VALUE'];
				$arFields["PROPERTY_VALUES"][$microdistrict]["n0"]["VALUE"] = $arNewBProp["microdistrict"]['VALUE'];
				
				$arFields["PROPERTY_VALUES"]["281"]["n0"]["VALUE"] = 417;
				
				
				$name = "";
				$name.= $arFields["PROPERTY_VALUES"][$Rooms]["n0"]["VALUE"]."-комн. квартира, ";
				$name.= $arFields["PROPERTY_VALUES"][$Square]["n0"]["VALUE"]." м², ";
				$name.= $arFields["PROPERTY_VALUES"][$Floor]["n0"]["VALUE"]."/".$arNewBProp["floors"]["VALUE"]." эт., ";
				$name.= $arNewB["NAME"];
				
				$arFields["NAME"] = $name;
				$arFields["CODE"] = CUtil::translit($name, "ru");
			}
		}
		
		//AddMessage2Log($arFields);
	}
	else if ($arFields["IBLOCK_ID"] == 8)
	{
		/*
		$PropsToId = array(
				"object_type",
				"summary_buildings_square",
				"sector_square",
				"dimension"
		);
		$CodesToID = \Utilities\Iblock\Property::PropertiesCodeToID($PropsToId, 8);
		$current = current($arFields["PROPERTY_VALUES"][$CodesToID["object_type"]]);
		$type = ($current["VALUE"])? 
		\Utilities\Iblock\PropertyEnum::getTextBuyEnumID($current["VALUE"], "object_type", 8) :
			\Utilities\Iblock\PropertyEnum::getTextBuyEnumID($arFields["PROPERTY_VALUES"]["object_type"], "object_type", 8);
		
			
		$current = current($arFields["PROPERTY_VALUES"][$CodesToID["summary_buildings_square"]]);
		$sumSquare = ($current["VALUE"])? $current["VALUE"] : $arFields["PROPERTY_VALUES"]["summary_buildings_square"];
		$sumSquareMetr = "кв.м.";
		
		$current = current($arFields["PROPERTY_VALUES"][$CodesToID["sector_square"]]);
		$uchastok = ($current["VALUE"])? $current["VALUE"] : $arFields["PROPERTY_VALUES"]["sector_square"];
		
		
		$current = current($arFields["PROPERTY_VALUES"][$CodesToID["dimension"]]);
		$uchastokMetr = ($current["VALUE"])?
		\Utilities\Iblock\PropertyEnum::getTextBuyEnumID($current["VALUE"], "dimension", 8) :
			\Utilities\Iblock\PropertyEnum::getTextBuyEnumID($arFields["PROPERTY_VALUES"]["dimension"], "dimension", 8);
		
			
		$name = "";
		if ($type)
			$name.= $type." ";
		if ($sumSquare)
			$name.=$sumSquare." ";
		if ($sumSquareMetr && $sumSquare)
			$name.=$sumSquareMetr." ";
		if ($uchastok)
			$name.="на участке ".$uchastok." ";
		if ($uchastokMetr && $uchastokMetr)
			$name.=$uchastokMetr;
		
		$arFields["NAME"] = $name;
		$arFields["CODE"] = CUtil::translit($name, "ru");
		*/
	}
	else if ($arFields["IBLOCK_ID"] == 10)
	{
		/*
		$PropsToId = array(
				"plot_dimension",
				"sector_square",
				"microdistrict"
		);
		$CodesToID = \Utilities\Iblock\Property::PropertiesCodeToID($PropsToId, 10);
		$start = "Земельный участок";
		$uchastokCurrent = current($arFields["PROPERTY_VALUES"][$CodesToID["sector_square"]]);
		$uchastok = ($uchastokCurrent["VALUE"])? $uchastokCurrent["VALUE"] : $uchastokCurrent;
		
		$currentUchastokMetr = current($arFields["PROPERTY_VALUES"][$CodesToID["plot_dimension"]]);
		$uchastokMetr = ($currentUchastokMetr["VALUE"])?
		\Utilities\Iblock\PropertyEnum::getTextBuyEnumID($currentUchastokMetr["VALUE"], "plot_dimension", 10) :
		\Utilities\Iblock\PropertyEnum::getTextBuyEnumID($currentUchastokMetr, "plot_dimension", 10);
		
		$currentMictroDistrict = current($arFields["PROPERTY_VALUES"][$CodesToID["microdistrict"]]);
		$microdistrict = ($currentMictroDistrict["VALUE"])? $currentMictroDistrict["VALUE"] : $currentMictroDistrict;
		if ($microdistrict)
		{
			$Qur = \Utilities\Iblock\PropertyElement::valuesIDsToName(array($microdistrict));
			$microdistrict = $Qur[$microdistrict];
		}
		
		$name = "";
		if ($start)
			$name.= $start." ";
		if ($uchastok)
			$name.=$uchastok." ";
		if ($uchastok && $uchastokMetr)
			$name.=$uchastokMetr." ";
		if ($microdistrict)
			$name.=", ".$microdistrict;
		

		$arFields["NAME"] = $name;
		$arFields["CODE"] = CUtil::translit($name, "ru");
		*/
	}
	else if ($arFields["IBLOCK_ID"] == 11)
	{/*
		$PropsToId = array(
			"commerc_type",
			"square",
			"street"
		);
		$CodesToID = \Utilities\Iblock\Property::PropertiesCodeToID($PropsToId, 11);
		
		$currentStreet = current($arFields["PROPERTY_VALUES"][$CodesToID["street"]]);
		
		$street = ($currentStreet["VALUE"])? $currentStreet["VALUE"] : $currentStreet;
		$currentSquare = current($arFields["PROPERTY_VALUES"][$CodesToID["square"]]);
		$square = ($currentSquare["VALUE"])?$currentSquare["VALUE"] : $currentSquare;
		
		
		$currentCommerc = current($arFields["PROPERTY_VALUES"][$CodesToID["commerc_type"]]);
		$comecrType = ($currentCommerc["VALUE"])?
		\Utilities\Iblock\PropertyEnum::getTextBuyEnumID($currentCommerc["VALUE"], "commerc_type", 11) :
		\Utilities\Iblock\PropertyEnum::getTextBuyEnumID($currentCommerc, "commerc_type", 11);
		
		$uchastokMetr = "кв.м.";
		
		$name = $comecrType." ".$square." ".$uchastokMetr." по ".$street;
		
		
		$arFields["NAME"] = $name;
		$arFields["CODE"] = CUtil::translit($name, "ru");
        */
	}
}