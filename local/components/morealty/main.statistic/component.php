<? if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die(); ?>

<?
$data = json_decode(file_get_contents("data.txt"),true);

if (is_null($data) || (time() -  $data["last_time"]) > 3600 * 24 || $_REQUEST["clear_cache"] == "Y")
{
	$arSelect = Array('ID', 'NAME');
	$arFilter = Array('TYPE'=> 'catalog', 'ACTIVE'=>'Y', 'GLOBAL_ACTIVE'=>'Y');
	$res = CIBlock::GetList(Array('SORT'=>'ASC'), $arFilter, true, $arSelect);
	while($item = $res->GetNext()):
	//укорачиваем название раздела, ибо рушится верстка
	if($item['ID'] == 9) {
		$item['NAME'] = 'Коттеджные поселки';
	}
	$ob[] = $item;

	$arFilter = Array("IBLOCK_ID"=>$item['ID'], "ACTIVE"=>"Y",/*"SECTION_ID"=>\MorealtySale\Offer::getSectionIdByType(\MorealtySale\Offer::$SellType,$item['ID']),*/"!PROPERTY_IS_ACCEPTED"=>false);
	$countRes = CIBlockElement::GetList(Array(), $arFilter, false, false, Array("ID"));
	$count = $countRes->SelectedRowsCount();
	$item['COUNT'] = $count;
	$arResult['SELL'][] = $item;
	endwhile;
	
	
	foreach($ob as $object) {
		
		if(!empty($object['NAME'])) {
				$arSelect = Array("ID");
				$arFilter = Array("IBLOCK_ID"=>$object['ID'], "SECTION_ID"=>\MorealtySale\Offer::getSectionIdByType(\MorealtySale\Offer::$AredtType,$object['ID']),"!PROPERTY_IS_ACCEPTED"=>false, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
				
				$countRes = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
				$count = $countRes->SelectedRowsCount();
				$object['COUNT'] = $count;
				$arResult['AREND'][] = $object;
		}
	}
	
	$arSelect = Array("ID", "NAME", "IBLOCK_ID", "PROPERTY_price_1m", "PROPERTY_newbuilding");
	$arFilter = Array("IBLOCK_ID" => 7, "ACTIVE"=>"Y", "PROPERTY_estate_type_VALUE" => "Вторичка");
	$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
	$summInNewbuilding = 0;
	$nbCounter = 0;
	$summInResells = 0;
	$resellCounter = 0;
	while($ob = $res->Fetch())
	{
		/*if($ob["PROPERTY_NEWBUILDING_VALUE"] != null && $ob["PROPERTY_NEWBUILDING_VALUE"] != '0') {
			$summInNewbuilding += $ob['PROPERTY_PRICE_1M_VALUE'];
			$nbCounter++;
		} else {
			$summInResells += $ob['PROPERTY_PRICE_1M_VALUE'];
			$resellCounter++;
		}*/
		$summInResells += $ob['PROPERTY_PRICE_1M_VALUE'];
		$resellCounter++;
		//estate_type
	}
	$arSelect = Array("ID", "NAME", "IBLOCK_ID", "PROPERTY_price_1m", "PROPERTY_newbuilding");
	$arFilter = Array("IBLOCK_ID" => 7, "ACTIVE"=>"Y", "!PROPERTY_newbuilding" => false);
	$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
	$summInNewbuilding = 0;
	$nbCounter = 0;
	while($ob = $res->Fetch())
	{
		 $summInNewbuilding += $ob['PROPERTY_PRICE_1M_VALUE'];
		 $nbCounter++;
		 
	}
	
	$arSelect = Array("ID", "NAME", "IBLOCK_ID", "PROPERTY_price", "PROPERTY_plot_dimension", "PROPERTY_square", "PROPERTY_sector_square", "PROPERTY_dimension");
	$arFilter = Array("IBLOCK_ID" => array(10, 8), "ACTIVE"=>"Y");
	$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
	$summInLand = 0;
	$landCounter = 0;
	while($ob = $res->Fetch())
	{
		
		if ($ob['PROPERTY_DIMENSION_VALUE'])
		{
			$ob['PROPERTY_PLOT_DIMENSION_VALUE'] = $ob['PROPERTY_DIMENSION_VALUE'];
		}
		if ($ob['PROPERTY_SECTOR_SQUARE_VALUE'])
		{
			$ob['PROPERTY_SQUARE_VALUE'] = $ob['PROPERTY_SECTOR_SQUARE_VALUE'];
		}
		if($ob['PROPERTY_PLOT_DIMENSION_VALUE'] == 'Сотка' && $ob['PROPERTY_SQUARE_VALUE']) {
			
			$summInLand += $ob['PROPERTY_PRICE_VALUE'] / ($ob['PROPERTY_SQUARE_VALUE']);
			$landCounter++;
		} else if($ob['PROPERTY_PLOT_DIMENSION_VALUE'] == 'Гектары' && $ob['PROPERTY_SQUARE_VALUE']) {
			$summInLand += $ob['PROPERTY_PRICE_VALUE'] / ($ob['PROPERTY_SQUARE_VALUE'] * 100);
			$landCounter++;
		}
	
	}
	
	$arResult["averageNewbuilding"] = intval($summInNewbuilding / $nbCounter);
	$arResult["averageResells"] =intval($summInResells / $resellCounter);
	$arResult["averageLand"] = intval($summInLand / $landCounter);

	
	$newData = array(
			"oldResult"=> $data["arResult"],
			"arResult" => $arResult,
			"last_time" => time(),
	);
	$arResult["OLD_DATA"] = $data["oldResult"];
	file_put_contents("data.txt", json_encode($newData));

}
else
{
	$arResult = $data["arResult"];
	$arResult["OLD_DATA"] = $data["oldResult"];
}

$arResult["averageNewbuildingInc"] = ($arResult["averageNewbuilding"] > 0)?  floatval(($arResult["averageNewbuilding"] - $arResult["OLD_DATA"]["averageNewbuilding"])/$arResult["averageNewbuilding"] * 100) : 0;
$arResult["averageResellsInc"] = ($arResult["averageResells"] > 0)? floatval(($arResult["averageResells"] - $arResult["OLD_DATA"]["averageResells"])/$arResult["averageResells"] * 100) : 0;;
$arResult["averageLandInc"] =  ($arResult["averageLand"] > 0)? floatval(($arResult["averageLand"] - $arResult["OLD_DATA"]["averageLand"])/$arResult["averageLand"] * 100) : 0;
	
	
	$this->includeComponentTemplate();
?>