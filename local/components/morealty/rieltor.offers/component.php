<? if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die(); ?>

<?
$RIELTOR = intval($arParams['RIELTOR']);

if ($RIELTOR <= 0)
{
	ShowError("Неверно указан риелтор");
	return false;
}
$SortTemplate = array(
		"time" => 	array("CODE"=>"created","NAME"=>"Дата обновления","NEW_DIRECTION"=>"ASC"),
		"name" => 	array("CODE"=>"NAME","NAME"=>"Название","NEW_DIRECTION"=>"ASC"),
		"price"=> 	array('CODE'=>"PROPERTY_price","NAME"=>"Цена","NEW_DIRECTION"=>"ASC"),
		"square"=> 	array("CODE"=>"PROPERTY_square","NAME"=>"Общая площадь","NEW_DIRECTION"=>"ASC"),
);
if ($arParams["SORT"])
{
	if (!$SortTemplate[$arParams["SORT"]])
	{
		$Sortby = $SortTemplate["time"]["CODE"];
		$SortTemplate["time"]["SELECTED"] = "Y";
		$SortTemplate["time"]["DIRECTION"] = "DESC";
		$SortTemplate["time"]["NEW_DIRECTION"] = "ASC";
	}
	else
	{
		$Sortby = $SortTemplate[$arParams["SORT"]]["CODE"];
		$SortTemplate[$arParams["SORT"]]["SELECTED"] = "Y";
		$SortTemplate[$arParams["SORT"]]["DIRECTION"] = ($arParams["ORDER"] == "ASC")? "ASC" : "DESC";
		$SortTemplate[$arParams["SORT"]]["NEW_DIRECTION"] = ($SortTemplate[$arParams["SORT"]]["DIRECTION"] == "ASC")? "DESC" : "ASC";
	}
}
else
{
	$SortTemplate["time"]["SELECTED"] = "Y";
}
//$SortOrder = ($arParams["ORDER"])? $arParams["ORDER"] : "DESC";
$ShowIB = $arParams["IB_TYPE"];
$PerPage = ($arParams['NPAGESIZE'])? $arParams['NPAGESIZE'] : 8;


if ($this->StartResultCache(false)) {
	
	$arResult["SORT_BLOCK"] = $SortTemplate;
	$res = CIBlockElement::GetList(array(),array("IBLOCK_ID"=>$GLOBALS['CATALOG_IBLOCKS_ARRAY'],"ACTIVE"=>"Y","!PROPERTY_IS_ACCEPTED"=>false,"PROPERTY_realtor" => $RIELTOR),array("IBLOCK_ID"));
	
	$arIblocks = array();
	while ($arIblock = $res->GetNext())
	{
		$arResult["IBLOCK_CNT"][$arIblock["IBLOCK_ID"]] = $arIblock["CNT"];
	}
	foreach ($arResult["IBLOCK_CNT"] as $key=> $Iblock)
	{
		$arResult["UsedIBLOCKS"][$key] = GetIbByID($key);
	}
	if ($ShowIB)
	{
		foreach ($arResult["UsedIBLOCKS"] as &$IBLOCK)
		{
			if ($IBLOCK["CODE"] == $ShowIB)
			{
				$SelectedIB = $IBLOCK["ID"];
				$IBLOCK["SELECTED"] = "Y";
			}
		}
		unset($IBLOCK);
	}
	$arResult["TOTAL_CNT"] = array_sum($arResult["IBLOCK_CNT"]);
	
	$EleRes = CIBlockElement::GetList($arParams["ORDER"],
			array(
					"ACTIVE"=>"Y",
					"!PROPERTY_IS_ACCEPTED"=>false,
					"IBLOCK_ID"=>($SelectedIB)? $SelectedIB : $GLOBALS['CATALOG_IBLOCKS_ARRAY'],
					"PROPERTY_realtor" => $RIELTOR
			),false,array("nPageSize"=>$PerPage));
	
	while ($objItem = $EleRes->GetNextElement())
	{
		$arItem = $objItem->GetFields();
		$arItem["PROPERTIES"] = $objItem->GetProperties();
		$arResult["ITEMS"][$arItem["ID"]] = $arItem;
	}
	$arResult["COUNT_OBJECTS"] = $EleRes->NavRecordCount;
	if ($arResult["ITEMS"] > 0)
	{
		/*$arPropsTemplate = array("city","district",'microdistrict','apartment_complex');
		$arPropsQuery = array();
		$arPropsResult = array();
		foreach ($arResult["ITEMS"] as $arItem)
		{
			foreach ($arPropsTemplate as $Prop)
			{
				if ($arItem["PROPERTIES"][$Prop]["VALUE"])
				{
					$arPropsQuery[] = $arItem["PROPERTIES"][$Prop]["VALUE"];
				}
			}
		}
		if (count($arPropsQuery) > 0)
		{
			$rsProps = CIBlockElement::GetList(array(),array("ACTIVE"=>"Y","ID"=>$arPropsQuery),false,false,array("ID","IBLOCK_ID","NAME"));
			while ($arProp = $rsProps->Fetch())
			{
				$arPropsResult[$arProp["ID"]] = $arProp["NAME"];
			}
		}*/
		unset($arItem);

		foreach ($arResult["ITEMS"] as &$arItem)
		{
			/*foreach ($arPropsTemplate as $Prop)
			{
				if ($arItem["PROPERTIES"][$Prop]["VALUE"])
				{
					$arItem["PROPERTIES"][$Prop]["MODIFIED_VALUE"] = $arPropsResult[$arItem["PROPERTIES"][$Prop]["VALUE"]];
				}
			}*/
				
			switch($arItem["PROPERTIES"]['currency']['VALUE_XML_ID']) {
				case 'rubles' : $arItem["PROPERTIES"]['currency']['VALUE_XML_ID'] = 'Руб.'; break;
				case 'dollars': $arItem["PROPERTIES"]['currency']['VALUE_XML_ID'] = '$'; break;
				case 'euro'   : $arItem["PROPERTIES"]['currency']['VALUE_XML_ID'] = 'Евро'; break;
				default: ; // ok
			}
		}
		unset($arItem);
		$arResult["NAV_RESULT"] = array(
				'PAGE_NOMER'					=> $EleRes->NavPageNomer,		// номер текущей страницы постранички
				'PAGES_COUNT'					=> $EleRes->NavPageCount,		// всего страниц постранички
				'RECORDS_COUNT'					=> $EleRes->NavRecordCount,	// размер выборки, всего строк
				'CURRENT_PAGE_RECORDS_COUNT'	=> count($arResult["ITEMS"])	// размер выборки текущей страницы
		);
		$arResult["NAV_STRING"] = $EleRes->GetPageNavStringEx(
				$navComponentObject,
				"",
				$arParams['NAV_TEMPLATE'],
				($arParams["NAV_SHOW_ALWAYS"] == 'Y')
		);
	}
	else
	{
		$this->AbortResultCache();
	}
	$this->SetResultCacheKeys(array(
			"NAV_RESULT","TOTAL_CNT","UsedIBLOCKS","IBLOCK_CNT", "COUNT_OBJECTS"
	));
	$this->IncludeComponentTemplate();
	//my_print_r($arResult["ITEMS"]);
}
?>