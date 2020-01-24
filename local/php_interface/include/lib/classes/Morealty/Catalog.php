<?
namespace Morealty;
use \Morealty\Settings as CSettings;
use Bitrix\Main\Web\Cookie;
use Bitrix\Main\Application;

class Catalog
{
	
	public static function getCatalogIblockIds()
	{
		return array_column(static::getIblocksData(), "ID");
	}
	
	public static function getIblockByCode($code)
	{
		return static::getIblocksData()[$code]["ID"];
	}
	
	public static function getIblockDataField($ID, $fieldCode)
	{
		return static::getIblockDataByID($ID)[$fieldCode];
	}
	
	public static function getIblockDataByID($ID)
	{
		$datas = static::getIblocksData();
		foreach ($datas as $Iblock)
		{
			if ($Iblock["ID"] == $ID)
				return $Iblock;
		}
		
		return false;
	}
	
	public static function getIblocksData()
	{
		$cache = \Bitrix\Main\Data\Cache::createInstance();
		$result = false;
		if ($cache->initCache(CSettings::CATALOG_IBLOCK_CACHE_TIME, "catalog_iblock_data_info"))
		{
			$vars =  $cache->getVars();
			$result =  $vars["iblocks"];
		}
		else if ($cache->startDataCache())
		{
			$result = static::loadIblockData();
			$cache->endDataCache(array(
					"iblocks" => $result
			));
		}
		return $result;
	}
	
	
	private static function loadIblockData()
	{
		if (!\Bitrix\Main\Loader::includeModule("iblock"))
			return false;
		
		$result = false;
		$res = \CIBlock::GetList(
					array("sort" => "asc"),
					array("TYPE" => "catalog", "!CODE" => false)
				);
		while ($arIblock = $res->Fetch())
		{
			$arIblock["LIST_PAGE_URL"] = static::proccessIblockUrl($arIblock["LIST_PAGE_URL"], $arIblock);
			$arIblock["MAP_PAGE_URL"] = static::proccessIblockUrl(($arIblock["ID"] == CSettings::CATALOG_FLAT)? CSettings::CATALOG_DEFAULT_MAP_PAGE : CSettings::CATALOG_CUSTOM_MAP_PAGE, $arIblock);
			$result[$arIblock["CODE"]] = $arIblock;
		}
		
		return $result;
	}
	
	
	
	public static function getNewbuildingAdditionFilterFields()
	{
		return array(
				array(
						"FIELD_TYPE" => "P",
						"TYPE" => "N",
						"MIN" => "system_price_from",
						"MAX" => "system_price_to",
						"NAME" => "Цена",
						"SORT" => 10,
						"FIELD_CLASS" => "pricefield",
						"POSTFIX" => "руб."
				),
				array(
						"FIELD_TYPE" => "P",
						"TYPE" => "N",
						"MIN" => "square_ot",
						"MAX" => "square_do",
						"NAME" => "Площадь",
						"SORT" => 20,
						"POSTFIX" => "м<sup>2</sup>"
				),
				array(
						"FIELD_TYPE" => "F",
						"TYPE" => "S",
						"NAME" => "Название новостройки",
						"FIELD_CODE" => "NAME",
						"SORT" => 500
				)
		);
	}
	
	public static function updateNewbuildingProps($ID)
	{
		if (!\Bitrix\Main\Loader::includeModule("iblock"))
			return false;
		
		$prices = \Utilities\Iblock\Element::getElementsMinAndMax(7, "price", array("PROPERTY_newbuilding" => $ID));
		$squares = \Utilities\Iblock\Element::getElementsMinAndMax(7, "square", array("PROPERTY_newbuilding" => $ID));
		$prrice_perm2 = \Utilities\Iblock\Element::getElementsMinAndMax(7, "price_1m", array("PROPERTY_newbuilding" => $ID));
		$newEle = new \CIBlockElement();
		
		if ($prices["MIN"] == 0 && $prices["MAX"] == 0)
		{
			$newbuildRes = \CIBlockElement::GetList(array(), array("ID" => $ID, "IBLOCK_ID" => 19), false, false, array('ID', "IBLOCK_ID" , "PROPERTY_price_flat_min"));
			if ($arNewbuilding = $newbuildRes->Fetch())
			{
				if ($arNewbuilding["PROPERTY_PRICE_FLAT_MIN_VALUE"] && intval($arNewbuilding["PROPERTY_PRICE_FLAT_MIN_VALUE"]) > 0)
				{
					$prices["MAX"] = $prices["MIN"] = intval($arNewbuilding["PROPERTY_PRICE_FLAT_MIN_VALUE"]);
				}
			}
			
		}
		
		$toUpdate = array(
				"system_price_from" => intval($prices["MIN"]),
				"system_price_to" => intval($prices["MAX"]),
				"square_ot" => intval($squares["MIN"]),
				"square_do" => intval($squares["MAX"]),
				"price_m2_ot" => intval($prrice_perm2["MIN"]),
				"price_m2_do" => intval($prrice_perm2["MAX"])
		);
		if (intval($prices["MIN"]) > 0)
		{
			$toUpdate["price_flat_min"] = intval($prices["MIN"]);
		}
		$newEle->SetPropertyValuesEx($ID, 19, $toUpdate);
	}
	
	
	public static function getCatalogAdditionalPropsFromMain()
	{
		return array(
				"commerc_type",
				"currency"
		);
	}
	
	
	public static function getCatalogIblocksInfo($useNewbuilding = false)
	{
		$arData = CSettings::$CATALOG_IBLOCKS_INFO;
		if ($useNewbuilding)
		{
			$arData = array_merge($arData, array(CSettings::$CATALOG_IBLOCK_NEWBUILDING));
		}
		$cachedIblocks = static::getIblocksData();
		$temp = array();
		foreach ($cachedIblocks as $IblockInfo)
		{
			$temp[$IblockInfo["ID"]] = $IblockInfo;
		}
		foreach ($arData as &$Iblock)
		{
			if ($temp[$Iblock["ID"]])
			{
				$Iblock = array_merge($temp[$Iblock["ID"]], $Iblock);
			}
		}
		unset($Iblock);
		return $arData;
	}
	
	public static function proccessIblockUrl($url, $arIblock)
	{
		return str_replace(
			array(
				"#IBLOCK_CODE#",
				"#IBLOCK_ID#"
			),
			array(
				$arIblock["CODE"],
				$arIblock["ID"]
			),
			$url
		);
	}
	
	
	public static function getCurrentSort()
	{
		$params = CSettings::$CATALOG_SORT_PARAMS;
		$defaultSort = false;
		foreach ($params as $PossibleSort)
		{
			if (!$PossibleSort["ID"] && !$defaultSort)
			{
				$defaultSort = $PossibleSort;
				continue;
			}
			if ($_REQUEST["sort"] == $PossibleSort["ID"])
			{
				return $PossibleSort["SORT"];
			}
		}
		return $defaultSort? $defaultSort["SORT"] : array();
	}
	
	
	public static function getCatalogViewType()
	{
		global $APPLICATION;
		$request = \Bitrix\Main\Context::getCurrent()->getRequest();
		if ($_REQUEST[CSettings::CATALOG_VIEW_TYPE_PARAM])
		{
			$APPLICATION->set_cookie(CSettings::CATALOG_VIEW_TYPE_COOKIE_NAME, htmlspecialchars($_REQUEST[CSettings::CATALOG_VIEW_TYPE_PARAM]));
			$type = htmlspecialchars($_REQUEST[CSettings::CATALOG_VIEW_TYPE_PARAM]);
		}
		else 
		{
			$type = $request->getCookie(CSettings::CATALOG_VIEW_TYPE_COOKIE_NAME);
		}
		if (!in_array(strtolower($type), array_column(CSettings::$CATALOG_VIEW_TYPES, "id")))
		{
			
			$types = CSettings::$CATALOG_VIEW_TYPES;
			reset($types);
			$type = current($types)["id"];
		}
		return $type;
	}
}