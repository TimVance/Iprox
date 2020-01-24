<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

Class Catalog_Flat_map_component extends CBitrixComponent
{
	
	protected $IblockID;
	protected $filter = array();
	protected $names = array();
	protected $arNeedNames = array();
	
	public function executeComponent()
	{
		global $USER;
		$this->IblockID = intval($this->arParams["IBLOCK_ID"]);
		if (!\Bitrix\Main\Loader::includeModule("iblock"))
			return false;
		if ($this->startResultCache(false, array($USER->GetGroups(), $this->IblockID, $this->arParams["FILTER"])))
		{
			$this->loadGroup();
			$this->loadSelfs();
			$this->buildObjects();
			$this->proccessObjects();
			$this->setResultCacheKeys(array("PLACEMARKS", "CNT"));
			
			
			$this->includeComponentTemplate(); 
		}
		
		
	}
	
	protected function buildObjects()
	{
		$this->arResult["OBJECTS"] = array();
		foreach ($this->arResult["NEWBUILDINGS"] as $arNewbuilding)
		{
			if (count($arNewbuilding["ITEMS"]) > 0)
			{
				$arNewbuilding["ID"] = $arNewbuilding["VALUE"];
				$arNewbuilding["PROPERTY_CITY_NAME"] = $this->getLoadedName($arNewbuilding["PROPERTY_CITY_VALUE"]);
				$arNewbuilding["PROPERTY_DISTRICT_NAME"] = $this->getLoadedName($arNewbuilding["PROPERTY_DISTRICT_VALUE"]);
				$arNewbuilding["PROPERTY_MICRODISTRICT_NAME"] = $this->getLoadedName($arNewbuilding["PROPERTY_MICRODISTRICT_VALUE"]);
				$arNewbuilding["MIN_PRICE"] = min(array_column($arNewbuilding["ITEMS"], "PROPERTY_PRICE_VALUE"));
				$arNewbuilding["MAX_PRICE"] = max(array_column($arNewbuilding["ITEMS"], "PROPERTY_PRICE_VALUE"));
				$this->arResult["OBJECTS"][] = $arNewbuilding;
			}
		}
		//$this->arResult["OBJECTS"] = array_merge($this->arResult["OBJECTS"], $this->arResult["SELFS"]);
		foreach ($this->arResult["SELFS"] as $arSelf)
		{
			$arSelf["CNT"] = 1;
			$arSelf["MAX_PRICE"] = $arSelf["MIN_PRICE"] = $arSelf["PROPERTY_PRICE_VALUE"];
			$arSelf["PROPERTY_PHOTO_GALLERY_VALUE"] = $arSelf["PROPERTY_photo_gallery"];
			$this->arResult["OBJECTS"][] = $arSelf;
		}
		unset($this->arResult["SELFS"], $this->arResult["NEWBUILDINGS"]);
	}
	
	protected function proccessObjects()
	{
		$iterator = 0;
		$this->arResult["PLACEMARKS"] = array();
		foreach ($this->arResult["OBJECTS"] as $arItem)
		{
			$iterator++;
			$arPosition = preg_split("/[\s,]+/", $arItem["PROPERTY_YANDEX_MAP_VALUE"]);
			if (!$arItem["PROPERTY_YANDEX_MAP_VALUE"])
				continue;
			$arPointData = array(
				"ID" => $arItem["ID"],
				"LAT" => $arPosition[0],
				"LON" => $arPosition[1],
				"NAME" => $arItem["CNT"],
			);
			if ($arItem["MIN_PRICE"])
			{
				
				$arPointData["TITLE"] = number_format($arItem["MIN_PRICE"],0,' ',' ');
				
				if (($arItem["MAX_PRICE"]) &&  $arItem["MAX_PRICE"] != $arItem["MIN_PRICE"])
				{
					$arPointData["TITLE"].= " - ".number_format($arItem["MAX_PRICE"],0,' ',' ');
				}
				$arPointData["TITLE"].= " руб.";
			}
			else
			{
				$arPointData["TITLE"] = $arItem["NAME"];
			}
			ob_start();
			?>
			<div class="info_pop" style="display: block;">
				<div class="close"></div>
				<div class="ip_top">
				<?
				//my_print_r($arItem['PROPERTIES']["photo_gallery"]);
				?>
					<?if ($arItem["PROPERTY_PHOTO_GALLERY_VALUE"])
					{
						$img = weImg::Resize($arItem["PROPERTY_PHOTO_GALLERY_VALUE"],90,60,weImg::M_CROP);
						if ($img)
						{
							?>
								<div class="photo"><img src="<?=$img?>" alt="<?=$arItem["NAME"]?>"></div>
							<?
						}

					}?>
					<?$adressArray = array($arItem["PROPERTY_CITY_NAME"],$arItem["PROPERTY_DISTRICT_NAME"],$arItem["PROPERTY_MICRODISTRICT_NAME"],$arItem["PROPERTY_STREET_VALUE"])?>
					<? 
					$adressArray = array_filter($adressArray, function($value) { return $value !== ''; })
					?>
					<div class="txt">
						<div class="m_title"><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a></div>
						<span class="m_address"><?=implode(", ", $adressArray)?></span>
					</div>
				</div>
				<div class="table-wrapper">
					<table class="ip_info_t">
						<tbody>
						<? foreach ($arItem["ITEMS"] as $arObject)
						{
							?>
								<tr>
									<td class="info_t_type"><a href="<?=$arObject["DETAIL_PAGE_URL"]?>"><? 
									if ($arObject["PROPERTY_ROOM_NUMBER_VALUE"])
									{
										?><?=$arObject["PROPERTY_ROOM_NUMBER_VALUE"]?>-комнатная<?
									}?></a></td>
									<td class="info_t_square"><?if ($arObject["PROPERTY_SQUARE_VALUE"]) ?><?=$arObject["PROPERTY_SQUARE_VALUE"]?> м<sup>2</sup></td>
									<td class="info_t_price"><? if ($arObject["PROPERTY_PRICE_VALUE"]){echo(number_format($arObject["PROPERTY_PRICE_VALUE"],0,' ',' '));}?> руб.</td>
								</tr>
							<?
						}?>
	
						</tbody>
					</table>
				</div>
			</div>
			<?
			$arPointData["INNER_DATA"] = ob_get_clean();
			
			$this->arResult["PLACEMARKS"][] = $arPointData;
		
		}
		$this->arResult["CNT"] = $iterator;
	}
	
	protected function loadSelfs()
	{
		$this->arResult["SELFS"] = array();
		$selfsFilter = array_merge(
			array(
				"IBLOCK_ID" => $this->IblockID,
				"PROPERTY_newbuilding" => array(false, 0),
				"!PROPERTY_yandex_map" => false
			),
			$this->getDefaultFilter(),
			(array) $this->arParams["FILTER"]
		);
		$rsSelfs = CIBlockElement::GetList(
			array(),
			$selfsFilter,
			false,
			false,
			array(
				"ID",
				"NAME",
				"IBLOCK_ID",
				"DETAIL_PAGE_URL",
				//"PROPERTY_photo_gallery",
				"PROPERTY_price",
				"PROPERTY_currency",
				"PROPERTY_square",
				"PROPERTY_room_number",
				"PROPERTY_yandex_map",
				"PROPERTY_city.NAME",
				"PROPERTY_district.NAME",
				"PROPERTY_microdistrict.NAME"
			)
		);
		while ($objElement = $rsSelfs->GetNextElement())
		{
			$arItem = $objElement->GetFields();
			$arProps = $objElement->GetProperties(array(), array("CODE" => "photo_gallery"));
			$arItem["PROPERTY_photo_gallery"] = $arProps["photo_gallery"]["VALUE"][0];
			$this->arResult["SELFS"][] = $arItem;
		}
	}
	
	protected function loadGroup()
	{
		$groupFilter = array_merge(
			array(
				"IBLOCK_ID" => $this->IblockID,
				"!PROPERTY_newbuilding" => array(false, 0),
				"!PROPERTY_newbuilding.PROPERTY_yandex_map" => false
			),
			$this->getDefaultFilter(),
			(array) $this->arParams["FILTER"]
		);
		$rsGroups = CIBlockElement::GetList(
			array(),
			$groupFilter,
			$this->getGroupArray()
		);
		$arIds = array();
		while ($arGroup = $rsGroups->Fetch())
		{
			$result = array();
			foreach ($arGroup as $FieldName => $arValue)
			{
				$result[str_replace("PROPERTY_NEWBUILDING_", "", $FieldName)] = $arValue;
			}
			if ($result["PROPERTY_CITY_VALUE"])
			{
				$this->addNameToLoad($result["PROPERTY_CITY_VALUE"]);
			}
			if ($result["PROPERTY_DISTRICT_VALUE"])
			{
				$this->addNameToLoad($result["PROPERTY_DISTRICT_VALUE"]);
			}
			if ($result["PROPERTY_MICRODISTRICT_VALUE"])
			{
				$this->addNameToLoad($result["PROPERTY_MICRODISTRICT_VALUE"]);
			}
			$result["ITEMS"] = array();
			$arIds[] = $result["VALUE"];
			$this->arResult["NEWBUILDINGS"][$result["VALUE"]] = $result;
		}
		$this->loadNames();
		if ($arIds && count($arIds) > 0)
		{
			$rsChildrens = CIBlockElement::GetList(
				array("PROPERTY_room_number" => "ASC"),
				array(
					"IBLOCK_ID" => $this->IblockID,
					"PROPERTY_newbuilding" => $arIds
					
				),
				false,
				false,
				array(
					"IBLOCK_ID",
					"ID",
					"DETAIL_PAGE_URL",
					"PROPERTY_price",
					"PROPERTY_newbuilding",
					"PROPERTY_currency",
					"PROPERTY_square",
					"PROPERTY_room_number"
					
				)
			);
			while ($arSubItem = $rsChildrens->GetNext())
			{
				if ($arSubItem["PROPERTY_NEWBUILDING_VALUE"])
				{
					if ($this->arResult["NEWBUILDINGS"][$arSubItem["PROPERTY_NEWBUILDING_VALUE"]])
					{
						$this->arResult["NEWBUILDINGS"][$arSubItem["PROPERTY_NEWBUILDING_VALUE"]]["ITEMS"][] = $arSubItem;
					}
				}
			}
		}
		
	}
	
	
	protected function addNameToLoad($ID)
	{
		if ($ID && !$this->arNeedNames[$ID])
		{
			$this->arNeedNames[$ID] = $ID;
		}
	}
	
	protected function getGroupArray()
	{
		return array(
			"PROPERTY_newbuilding",
			"PROPERTY_newbuilding.DETAIL_PAGE_URL",
			"PROPERTY_newbuilding.NAME",
			"PROPERTY_newbuilding.PROPERTY_city",
			"PROPERTY_newbuilding.PROPERTY_district",
			"PROPERTY_newbuilding.PROPERTY_microdistrict",
			"PROPERTY_newbuilding.PROPERTY_street",
			"PROPERTY_newbuilding.PROPERTY_photo_gallery",
			"PROPERTY_newbuilding.PROPERTY_yandex_map"
		);
	}
	
	protected function loadNames()
	{
		if (!$this->arNeedNames || count($this->arNeedNames) <= 0)
			return false;
		
		$rs = CIBlockElement::GetList(array(), array("ID" => $this->arNeedNames), false, false, array("NAME", "ID"));
		while ($arElement = $rs->Fetch())
		{
			$this->names[$arElement["ID"]] = $arElement["NAME"];
		}
	}
	
	
	protected function getLoadedName($id)
	{
		return $this->names[$id];
	}
	
	protected function getDefaultFilter()
	{
		return array("!PROPERTY_IS_ACCEPTED"=>false, "ACTIVE" => "Y");
	}
	
}


/**

$rsElements = CIBlockElement::GetList(
		array(),
		array_merge(
			array(
				"IBLOCK_ID" => 7,
				"!PROPERTY_newbuilding" => array(false, 0),
				
			), $arrFilter),
		array(
			"PROPERTY_newbuilding",
			"PROPERTY_newbuilding.NAME",
			"PROPERTY_newbuilding.PROPERTY_city",
			"PROPERTY_newbuilding.PROPERTY_district",
			"PROPERTY_newbuilding.PROPERTY_microdistrict",
			"PROPERTY_newbuilding.PROPERTY_street",
			"PROPERTY_newbuilding.PROPERTY_photo_gallery"
		)
	);
	while ($arGroup = $rsElements->Fetch())
	{
		my_print_r($arGroup);
	}
 * 
 * 
 * 
 */