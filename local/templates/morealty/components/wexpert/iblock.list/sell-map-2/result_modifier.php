<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
<? 
if ($arParams["GET_ELEMENTS_FROM"] && count($arParams['GET_ELEMENTS_FROM']) > 0)
{
	$arNewBuildings = array();
	$arValueKey = array();
	foreach ($arResult["ITEMS"] as $key=>$arItem)
	{
		$arNewBuildings[] = $arItem["ID"];
		$arValueKey[$arItem["ID"]] = $key;
	}
	
	
	
	/**
	 * 
	 * 
	 * Получаем квартиры привязанные к текущим новостройкам
	 */
	if (count($arNewBuildings) > 0)
	{
		$arSort = ($arParams["APART_SORT"])? $arParams["APART_SORT"] :  array();
		$arFilter = array(
				"IBLOCK_ID"=>$arParams["GET_ELEMENTS_FROM"],
				"ACTIVE"=>"Y",
				"PROPERTY_newbuilding" => $arNewBuildings,
		);
		$arSelect = array("ID","IBLOCK_ID","NAME","DETAIL_PAGE_URL",
				"PROPERTY_newbuilding","PROPERTY_NEWBUILDING.NAME","PROPERTY_price","PROPERTY_currency"/*,"PROPERTY_room_number","PROPERTY_photo_gallery","PROPERTY_street"*/
		);
		$resBuildings = CIBlockElement::GetList($arSort,$arFilter,false,false,$arSelect);
		$arTemp = array();
		while ($TisObject = $resBuildings->GetNextElement())
		{
			$arObject = $TisObject->GetFields();
			$arObject["PROPERTIES"] = $TisObject->GetProperties();
			if (is_array($arResult["ITEMS"][$arValueKey[$arObject["PROPERTY_NEWBUILDING_VALUE"]]]))
			{
				$arResult["ITEMS"][$arValueKey[$arObject["PROPERTY_NEWBUILDING_VALUE"]]]["OBJ"][] = $arObject;
				if ($arObject["PROPERTY_PRICE_VALUE"]) 
				{
					if (($arResult["ITEMS"][$arValueKey[$arObject["PROPERTY_NEWBUILDING_VALUE"]]]["MIN_PRICE"] == false) 
							|| 
							($arResult["ITEMS"][$arValueKey[$arObject["PROPERTY_NEWBUILDING_VALUE"]]]["MIN_PRICE"] > $arObject["PROPERTY_PRICE_VALUE"]))
					{
						$arResult["ITEMS"][$arValueKey[$arObject["PROPERTY_NEWBUILDING_VALUE"]]]["MIN_PRICE"] = $arObject["PROPERTY_PRICE_VALUE"];
					}
					if (($arResult["ITEMS"][$arValueKey[$arObject["PROPERTY_NEWBUILDING_VALUE"]]]["MAX_PRICE"] == false)
							||
							($arResult["ITEMS"][$arValueKey[$arObject["PROPERTY_NEWBUILDING_VALUE"]]]["MAX_PRICE"] < $arObject["PROPERTY_PRICE_VALUE"]))
					{
						$arResult["ITEMS"][$arValueKey[$arObject["PROPERTY_NEWBUILDING_VALUE"]]]["MAX_PRICE"] = $arObject["PROPERTY_PRICE_VALUE"];
					}
				}
				

			}
			
			$arTemp[] = $arObject;
		}
		$arResult["OBJECTS"] = $arTemp;
		//my_print_r($arResult["ITEMS"],true,false);
		unset($arTemp);
		

	}

}

?>