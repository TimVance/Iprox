<? if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die(); ?>

<?
if ($this->StartResultCache(false)) {

	$IBLOCKS = array('7', '8', '11', '10', '12');
	foreach($IBLOCKS as $IBLOCK_ID) {
		$arSelect = Array("ID",  'IBLOCK_ID', 'DETAIL_PAGE_URL', 'NAME', 'PROPERTY_city', 'PROPERTY_district', 'PROPERTY_microdistrict', 'PROPERTY_street', 'PROPERTY_apartment_complex', 'PROPERTY_square', 'PROPERTY_floor', 'PROPERTY_currency',"PROPERTY_summary_buildings_square","PROPERTY_number_of_storeys");
		$arFilter = Array("IBLOCK_ID" => $IBLOCK_ID, "ACTIVE"=>"Y","!PROPERTY_IS_ACCEPTED"=>false,"PROPERTY_living_block"=>false);
		$mainRes = CIBlockElement::GetList(Array("timestamp_x"=>"DESC"), $arFilter, false, Array("nPageSize" => 5), $arSelect);
		while($objEle = $mainRes->GetNextElement()) {
			$ob = $objEle->GetFields();
			$ob["PROPERTIES"] = $objEle->GetProperties();
			if ($ob["PROPERTIES"]["price"]["VALUE"])
			{
				$ob["PROPERTY_PRICE_VALUE"] = $ob["PROPERTIES"]["price"]["VALUE"];
			}
			$res = CIBlockElement::GetByID($ob['PROPERTY_CITY_VALUE']);
			if($ar_res = $res->GetNext()){
				$ob['PROPERTY_CITY_VALUE'] = $ar_res['NAME'];
			}
			$res = CIBlockElement::GetByID($ob['PROPERTY_DISTRICT_VALUE']);
			if($ar_res = $res->GetNext()){
				$ob['PROPERTY_DISTRICT_VALUE'] = $ar_res['NAME'];
			}
			$res = CIBlockElement::GetByID($ob['PROPERTY_MICRODISTRICT_VALUE']);
			if($ar_res = $res->GetNext()){
				$ob['PROPERTY_MICRODISTRICT_VALUE'] = $ar_res['NAME'];
			}
			$res = CIBlockElement::GetByID($ob['PROPERTY_APARTMENT_COMPLEX_VALUE']);
			if($ar_res = $res->GetNext()){
				$ob['PROPERTY_APARTMENT_COMPLEX_VALUE'] = $ar_res['NAME'];
			}
			switch($ob['PROPERTY_CURRENCY_VALUE']) {
				case 'Рубли' : $ob['PROPERTY_CURRENCY_VALUE'] = 'Руб.'; break;
				case 'Доллары': $ob['PROPERTY_CURRENCY_VALUE'] = '$'; break;
				case 'Евро'   : $ob['PROPERTY_CURRENCY_VALUE'] = 'Евро'; break;
				default: ; // ok
			}
			if ($ob["PROPERTIES"]["photo_gallery"]["VALUE"][0])
			{
				$ob['PHOTO_LINK'] = 'http://' . $_SERVER['HTTP_HOST'] . CFile::GetPath($ob["PROPERTIES"]["photo_gallery"]["VALUE"][0]);
			}
			

			$price = (string) $ob['PROPERTY_PRICE_VALUE'];
			$priceLen = strlen($price);
			if($priceLen >= 5) {
				switch($priceLen) {
					case 5: $price = substr($price, 0, 2) . ' ' . substr($price, 2, 3); break;
					case 6: $price = substr($price, 0, 3) . ' ' . substr($price, 3, 3); break;
					case 7: $price = substr($price, 0, 1) . ' ' . substr($price, 1, 3) . ' ' . substr($price, 4, 3); break; //
					case 8: $price = substr($price, 0, 2) . ' ' . substr($price, 2, 3) . ' ' . substr($price, 5, 3); break;
					case 9: $price = substr($price, 0, 3) . ' ' . substr($price, 3, 3) . ' ' . substr($price, 6, 3); break;
					case 10: $price = substr($price, 0, 1) . ' ' . substr($price, 1, 3) . ' ' . substr($price, 4, 3) . ' ' . substr($price, 7, 3); break;
					default: break;
				}
				$ob['PROPERTY_PRICE_VALUE'] = $price;
			}
			$arProperties[$IBLOCK_ID][] = $ob;
		}

	}

	$arResult['IBLOCKS'] = $IBLOCKS;
	$arResult['arProperties'] = $arProperties;


	$this->includeComponentTemplate();
}

?>