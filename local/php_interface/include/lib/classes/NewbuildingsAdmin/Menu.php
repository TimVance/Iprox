<? 

namespace NewbuildingsAdmin;



class Menu
{
	
	
	private static $groupList = false;
	
	private static $newbuildings = false;
	
	private static $livingblocks = false;
	
	private static $aparts = false;
	
	private static $levels = false;
	
	private static $menu = false;
	
	
	public function registerEvents()
	{
		if (defined("ADMIN_SECTION") && ADMIN_SECTION === true)
		{
			AddEventHandler("main", "OnBuildGlobalMenu", array("\NewbuildingsAdmin\Menu","_RegisterAdminEvents"));
			
			AddEventHandler("main", "OnAdminTabControlBegin",  array("\NewbuildingsAdmin\AdminEvents","onApartAddPage"));
			
			AddEventHandler("main", "OnProlog", array("\NewbuildingsAdmin\AdminEvents","onApartAddPageSetProps"));
			
			AddEventHandler("main", "OnProlog", array("\NewbuildingsAdmin\AdminEvents","onNewBuildChangeAction"));
			
			AddEventHandler("main", "OnBeforeLocalRedirect", array("\NewbuildingsAdmin\AdminEvents","onRedirectCheck"));
		}
			
	}
	
	private function debug($what)
	{
		global $USER;
		if ($USER->GetLogin() == "vadim")
		{
			?><pre><? print_r($what)?></pre><?
		}
	}
	
	
	public function _RegisterAdminEvents(&$aGlobalMenu, &$aModuleMenu)
	{
		global $USER;
		if (true)
		{

			$aGlobalMenu ["global_newbuilding_menu"] = array(
					"menu_id" => "newbuidings",
					"text" => "Новостройки",
					"title" => "Новостройки",
					//"url" => "javascript:void(0);",
					"sort" => 200,
					"items_id" => "global_newbuilding_menu",
					"help_section" => "desktop",
					"items" => array(),
					"dynamic" => 1,
			
			);
			//my_print_r($aGlobalMenu);
			
			
			$aModuleMenu[] = array(
					"parent_menu" => "global_newbuilding_menu",
					"text" => "Новостройки",
					//"url" => "javascript:void(0);",
					"title" => "Test Title",
					"sort" => 1000,
					"icon" => "iblock_menu_icon_types",
					"page_icon" => "iblock_page_icon_types",
					"module_id" => "iblock",
					//"dynamic" => 1,
					"items_id" => "newbuildings_level_one",
					"items"=> self::buildMenu(),
					"dynamic" => 1,
			);
			$aModuleMenu[] = array(
					"parent_menu" => "global_menu_services",
					"text" => "Выдача пакетов пользователям",
					"url" => "morealty_add_userpackets.php",
					"title" => "Выдача пакетов пользователям",
					"sort" => 10,
					"icon" => "user_page_icon",
					"page_icon" => "user_page_icon",
					"module_id" => "sale",
			);
			$aModuleMenu[] = array(
					"parent_menu" => "global_menu_services",
					"text" => "Отключение пакетов у пользователей",
					"url" => "morealty_remove_userpackets.php",
					"title" => "Отключение пакетов у пользователей",
					"sort" => 20,
					"icon" => "user_page_icon",
					"page_icon" => "user_page_icon",
					"module_id" => "sale",
			);
			
			//my_print_r($aModuleMenu);
		}
	}
	
	
	public function buildMenu()
	{
		if (!\CModule::IncludeModule("iblock"))
			return false;

		self::loadData();
		
		if (!self::$menu)
			self::$menu = self::buildMenuChilds();
		
		/*self::debug(self::$groupList);
		self::debug(self::$newbuildings);
		self::debug(self::$livingblocks);
		self::debug(self::$aparts);*/
		//self::debug(self::$aparts);
		return self::$menu;
	}
	
	
	
	private function loadData()
	{
		if (!self::$groupList)
			self::$groupList = self::queryGroupNewbildingsAndLivingBlocks();
		if (!self::$newbuildings)
			self::$newbuildings = self::queryNewbuildings(/*self::$groupList["NEWBUILDINGS"]*/);
		if (!self::$livingblocks)
			self::$livingblocks = self::queryLivingBlocks(self::$groupList["LIVING_BLOCKS"]);
		if (!self::$aparts)
			self::$aparts = self::queryAparts(self::$groupList["NEWBUILDINGS"]);
		if (!self::$levels)
			self::$levels = self::queryLivingBlockLevels();
		
	}
	private function buildMenuChilds()
	{
		global $USER;
		$menu = array();
		foreach (self::$newbuildings as $newBuildingID=>$newBuilding)
		{
			$arNewBuilding = self::$groupList["ITEMS"][$newBuildingID];
			/*if (!$newBuilding)
				continue;*/
			
			$arItem = array(
							"text" => $newBuilding["NAME"],
							"url" => $newBuilding["LINK"],
							"title" => $newBuilding["NAME"],
							"icon" => "iblock_menu_icon_types",
							"page_icon" => "iblock_page_icon_types",
							"dynamic" => 1,
							"items_id" => "newbuildings_level_t".$newBuilding["ID"],
							//"dynamic" => 1,
					);
			$arItems = array();
			$arItems[] = array(
					"text" => Settings::$MESS_add_appart,
					"url" => self::buildCreateAppartLink($newBuildingID, 0),
					"title" => Settings::$MESS_add_appart,
			);
			foreach ($arNewBuilding["BLOCKS"] as $LivingBlockID)
			{
				$arLivingBlock = self::$livingblocks[$LivingBlockID];
				if ($arLivingBlock)
				{
					$arSubItem = array(
							"text" => $arLivingBlock["NAME"],
							"url" => $arLivingBlock["LINK"],
							"title" => $arLivingBlock["NAME"],
							"page_icon" => "iblock_page_icon_iblocks",
							"icon" => "iblock_menu_icon_iblocks",
							"dynamic" => 1,
							"items_id" => "newbuildings_level_th".$LivingBlockID,
							);
					$arSubSubItems = array();
					
					$arSubSubItems[] = array(
							"text" => Settings::$MESS_add_appart,
							"url" => self::buildCreateAppartLink($newBuildingID, $LivingBlockID),
							"title" => Settings::$MESS_add_appart,
					);
					if (self::$levels[$LivingBlockID])
					{
						foreach (self::$levels[$LivingBlockID] as $item)
						{
							$subItem = array(
									"text" => $item["NAME"],
									"title" => $item["NAME"],
									"dynamic" => 1,
									"items_id" => "newbuildings_level_levels".$newBuildingID.$item["LEVEL"],
							);
							
							$arSubSubSubItems = array();
							$arSubSubSubItems[] = array(
									"text" => Settings::$MESS_add_appart,
									"url" => self::buildCreateAppartLink($newBuildingID, $LivingBlockID,$item["LEVEL"]),
									"title" => Settings::$MESS_add_appart,
							);
							foreach (self::$aparts as $arAppart)
							{
								if (in_array($item["LEVEL"], $arAppart["PROPERTY_FLOORS"]) && $arAppart["PROPERTY_LIVING_BLOCK"] && in_array($arLivingBlock["ID"], $arAppart["PROPERTY_LIVING_BLOCK"]))
								{
									$arSubSubSubItems[] = array(
											"text" => $arAppart["NAME"],
											"url" => $arAppart["LINK"],
											"title" => $arAppart["NAME"],
									);
								}
							}
							if (count($arSubSubSubItems) > 0)
								$subItem["items"] = $arSubSubSubItems;
							
							$arSubSubItems[] = $subItem;
						}
					}
					if (count($arSubSubItems) > 0)
						$arSubItem["items"] = $arSubSubItems;
					$arItems[] = $arSubItem;
				}
			}
			foreach (self::$aparts as $arAppart)
			{
				
				if (!$arAppart["PROPERTY_LIVING_BLOCK"] && $arAppart["PROPERTY_NEWBUILDING"] == $newBuildingID)
				{
					$arItems[] = array(
							"text" => $arAppart["NAME"],
							"url" => $arAppart["LINK"],
							"title" => $arAppart["NAME"],
					);
				}
			}
			
			if (count($arItems) > 0)
				$arItem["items"] = $arItems;
			$menu[] = $arItem;
		}
		//my_print_r(self::$aparts);
		return $menu;
	}
	
	private function queryAparts($newBuildingIds = false)
	{
		global $USER;
		$filter = array("IBLOCK_ID"=>Settings::$Iblock_apparts);
		if ($newBuildingIds)
			$filter["PROPERTY_newbuilding"] = $newBuildingIds;
		$res = \CIBlockElement::GetList(
				array("NAME"=>"ASC"),
				$filter,
				false,
				false,
				array("ID","NAME","IBLOCK_ID","IBLOCK_TYPE","IBLOCK_SECTION_ID")
		);
		$return = array();
		while ($objItem = $res->GetNextElement()) {
			$arItem = $objItem->GetFields();
			$props = $objItem->GetProperties();
			if ($props["living_block"]["VALUE"])
			{
				$arItem["PROPERTY_LIVING_BLOCK"] = $props["living_block"]["VALUE"];
			}
			if ($props["newbuilding"]["VALUE"])
			{
				$arItem["PROPERTY_NEWBUILDING"] = $props["newbuilding"]["VALUE"];
			}
			if (!$arItem["IBLOCK_SECTION_ID"])
			{
				$arItem["IBLOCK_SECTION_ID"] = 0;
			}
			if ($props["floor"]["VALUE"])
			{
				if (strpos($props["floor"]["VALUE"],"-") !== false)
				{
					
					$arFloors = preg_split("/[\s-]+/", $props["floor"]["VALUE"]);
					if (!is_null($arFloors[0]) && !is_null($arFloors[1]))
						$arItem["PROPERTY_FLOORS"] = range($arFloors[0], $arFloors[1]);
					else 
						continue;
				}
				else if (strpos($props["floor"]["VALUE"],"/") !== false)
				{
					$arFloors = preg_split("/[\s\/]+/", $props["floor"]["VALUE"]);
					if (!is_null($arFloors[0]) && !is_null($arFloors[1]))
						$arItem["PROPERTY_FLOORS"] = range($arFloors[0], $arFloors[1]);
					else
						continue;
					
				}
				else
				{
					$arItem["PROPERTY_FLOORS"] = array_filter(preg_split("/[\s,|\/|-]+/", $props["floor"]["VALUE"]));
				}
				
			}
			$arItem["LINK"] = self::buildAdminLink(true, $arItem);
			$return[$arItem["ID"]] = $arItem;
		}
		return $return;
	}
	
	/**
	 * Получаем текущие новостройки
	 * @return array
	 */
	private function queryNewbuildings($ID = false)
	{
		$filter = array("IBLOCK_ID"=>Settings::$Iblock_newbuildings);
		if ($ID)
			$filter["ID"] = $ID;
		$res = \CIBlockElement::GetList(
				array("NAME"=>"ASC"),
				$filter,
				false,
				false,
				array("ID","NAME","IBLOCK_ID","IBLOCK_TYPE","IBLOCK_SECTION_ID")
				);
		$return = array();
		while ($arItem = $res->GetNext()) {
			if (!$arItem["IBLOCK_SECTION_ID"])
			{
				$arItem["IBLOCK_SECTION_ID"] = 0;
			}
			$arItem["LINK"] = self::buildAdminLink(true, $arItem);
			$return[$arItem["ID"]] = $arItem;
		}
		return $return;
	}
	
	/**
	 * Получаем текущие новостройки
	 * @return array
	 */
	/*private function queryLivingBlocks()
	{
		$res = \CIBlockSection::GetList(
				array("NAME"=>"ASC"),
				array("IBLOCK_ID"=>Settings::$Iblock_plans,"ACTIVE"=>"Y"),
				false,
				array("ID","NAME","IBLOCK_ID"),
				false
		);
		$return = array();
		while ($arItem = $res->GetNext()) {
			$return[$arItem["ID"]] = $arItem;
		}
		return $return;
	}*/
	
	
	/**
	 * Получаем группировку по Новостройкам и блокам
	 * @return array
	 */
	private function queryGroupNewbildingsAndLivingBlocks()
	{
		$filter = array("IBLOCK_ID"=>Settings::$Iblock_apparts,"ACTIVE"=>"Y",/*"!PROPERTY_living_block"=>false,*/"!PROPERTY_newbuilding"=>false);
		$res = \CIBlockElement::GetList(
				array(),
				$filter,
				array("PROPERTY_living_block","PROPERTY_newbuilding"),
				false
		);
		$return = array();
		while ($arItem = $res->GetNext()) {
			if ($arItem["PROPERTY_LIVING_BLOCK_VALUE"])
			{
				$return["LIVING_BLOCKS"][] = $arItem["PROPERTY_LIVING_BLOCK_VALUE"];
			}
			
			if ($arItem["PROPERTY_NEWBUILDING_VALUE"])
			{
				$return["NEWBUILDINGS"][] = $arItem["PROPERTY_NEWBUILDING_VALUE"];
				if (!$return["ITEMS"][$arItem["PROPERTY_NEWBUILDING_VALUE"]])
					$return["ITEMS"][$arItem["PROPERTY_NEWBUILDING_VALUE"]]["BLOCKS"] = array();
				
				if ($arItem["PROPERTY_LIVING_BLOCK_VALUE"])
				{
					$return["ITEMS"][$arItem["PROPERTY_NEWBUILDING_VALUE"]]["BLOCKS"][] = $arItem["PROPERTY_LIVING_BLOCK_VALUE"];
				}
			}
			
			//$return["ITEMS"][] = $arItem;
		}
		return $return;
	}
	
	
	/**
	 * Получаем текущие новостройки
	 * @return array
	 */
	private function queryLivingBlocks($ID = false)
	{
		$filter = array("IBLOCK_ID"=>Settings::$Iblock_plans,"ACTIVE"=>"Y");
		if ($ID)
		{
			$filter["ID"] = $ID;
		}
		$res = \CIBlockSection::GetList(
				array("NAME"=>"ASC"),
				$filter,
				false,
				array("ID","NAME","IBLOCK_ID","IBLOCK_TYPE","IBLOCK_SECTION_ID"),
				false
		);
		$return = array();
		while ($arItem = $res->GetNext()) {
			if (!$arItem["IBLOCK_SECTION_ID"])
			{
				$arItem["IBLOCK_SECTION_ID"] = 0;
			}
			$arItem["LINK"] = self::buildAdminLink(false, $arItem);
			$return[$arItem["ID"]] = $arItem;
		}
		return $return;
	}
	
	private function queryLivingBlockLevels()
	{
		$return = array();
		$res = \CIBlockElement::GetList(
				array(),
				array("IBLOCK_ID"=>Settings::$Iblock_plans,"!IBLOCK_SECTION_ID"=>false,"!PROPERTY_APART_PLAN_FROM"=>false),
				false,
				false,
				array("ID","IBLOCK_ID","NAME","IBLOCK_SECTION_ID","PROPERTY_APART_PLAN_FROM","PROPERTY_APART_PLAN_TO")
				);
		while ($arItem = $res->GetNext()) {
			if ($arItem["PROPERTY_APART_PLAN_FROM_VALUE"])
			{

				if (!$arItem["PROPERTY_APART_PLAN_TO_VALUE"])
				{
					$return[$arItem["IBLOCK_SECTION_ID"]][] = array(
								"NAME" => $arItem["PROPERTY_APART_PLAN_FROM_VALUE"]." ".Settings::$MESS_level_text,
								"LEVEL" => $arItem["PROPERTY_APART_PLAN_FROM_VALUE"],
							);
				}
				else if ($arItem["PROPERTY_APART_PLAN_FROM_VALUE"] < $arItem["PROPERTY_APART_PLAN_TO_VALUE"])
				{
					/*if ($arItem["ID"] == "474")
						self::debug($arItem);*/
					foreach (range($arItem["PROPERTY_APART_PLAN_FROM_VALUE"], $arItem["PROPERTY_APART_PLAN_TO_VALUE"]) as $Level)
					{
						$return[$arItem["IBLOCK_SECTION_ID"]][] = array(
								"NAME" => $Level." ".Settings::$MESS_level_text,
								"LEVEL" => $Level,
						);
					}
				}
			}
		}
		return $return;
	}
	private function makeElementsLink($arSearch,$arReplace)
	{
		$arLinks = array();
		foreach (Settings::$arLinkTemplateElement as $Link)
		{
			$arLinks[] = str_replace($arSearch, $arReplace, $Link);
		}
		return $arLinks;
	}
	
	private function buildAdminLink($isElement, $arItem)
	{
		$arSearch = array("#IBLOCK_ID#","#IBLOCK_TYPE#","#ELEMENT_ID#","#SECTION_ID#");
		$arReplace = array($arItem["IBLOCK_ID"],Settings::getIblockType($arItem["IBLOCK_ID"]),$arItem["ID"],($isElement) ?$arItem["IBLOCK_SECTION_ID"] : $arItem["ID"]);
		$target = ($isElement) ? Settings::$LinkTemplateElement : Settings::$LinkTemplateSection;
		return str_replace($arSearch, $arReplace, $target);
		//return self::makeElementsLink($arSearch, $arReplace);
	}
	
	private function buildCreateAppartLink($newbuilding,$living_block,$level = false)
	{
		return str_replace(array("#newbuidling_id#","#living_block_id#","#apart_level#"), array($newbuilding,$living_block,($level)? $level : ""), Settings::$LinkTemplateAddApart);
	}
}