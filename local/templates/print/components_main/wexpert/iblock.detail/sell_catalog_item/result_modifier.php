<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
<?
$ELEMENT_ID = $arResult["ID"];
$IBLOCK_ID = $arResult['IBLOCK_ID'];


//get meta
$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($IBLOCK_ID, $ELEMENT_ID);
$arResult["I_PROPS"] = $ipropValues->getValues();




//TO DO: IN RESULT_MODIFIER
$arProperties = $arResult['PROPERTIES'];
$res          = CIBlockElement::GetByID($arProperties['city']['VALUE']);
//city
if ($ar_res = $res->GetNext()) {
	$arProperties['city']['MODIFIED_VALUE'] = $ar_res['NAME'];
}
$res = CIBlockElement::GetByID($arProperties['district']['VALUE']);
//district
if ($ar_res = $res->GetNext()) {
	$arProperties['district']['MODIFIED_VALUE'] = $ar_res['NAME'];
}
$res = CIBlockElement::GetByID($arProperties['microdistrict']['VALUE']);
//microdistrict
if ($ar_res = $res->GetNext()) {
	$arProperties['microdistrict']['MODIFIED_VALUE'] = $ar_res['NAME'];
}
//apartment_complex
$res = CIBlockElement::GetByID($arProperties['apartment_complex']['VALUE']);
if ($ar_res = $res->GetNext()) {
	$arProperties['apartment_complex']['MODIFIED_VALUE'] = $ar_res['NAME'];
}
//currency
switch ($arProperties['currency']['VALUE_XML_ID']) {
	case 'rubles' :
		$arProperties['currency']['VALUE_XML_ID'] = 'Руб.';
		break;
	case 'dollars':
		$arProperties['currency']['VALUE_XML_ID'] = '$';
		break;
	case 'euro'   :
		$arProperties['currency']['VALUE_XML_ID'] = 'Евро';
		break;
	default:
		; // ok
}
//price view
$price    = (string)$arProperties['price']['VALUE'];
$priceLen = strlen($price);
if ($priceLen >= 5) {
	switch ($priceLen) {
		case 5:
			$price = substr($price, 0, 2) . ' ' . substr($price, 2, 3);
			break;
		case 6:
			$price = substr($price, 0, 3) . ' ' . substr($price, 3, 3);
			break;
		case 7:
			$price = substr($price, 0, 1) . ' ' . substr($price, 1, 3) . ' ' . substr($price, 4, 3);
			break; //
		case 8:
			$price = substr($price, 0, 2) . ' ' . substr($price, 2, 3) . ' ' . substr($price, 5, 3);
			break;
		case 9:
			$price = substr($price, 0, 3) . ' ' . substr($price, 3, 3) . ' ' . substr($price, 6, 3);
			break;
		case 10:
			$price = substr($price, 0, 1) . ' ' . substr($price, 1, 3) . ' ' . substr($price, 4, 3) . ' ' . substr($price, 7, 3);
			break;
		default:
			break;
	}
	$arProperties['price']['VALUE'] = $price;
}
//price1m view
$price1m  = (string)$arProperties['price_1m']['VALUE'];
$priceLen = strlen($price1m);
if ($priceLen >= 4) {
	switch ($priceLen) {
		case 4:
			$price1m = substr($price1m, 0, 1) . ' ' . substr($price1m, 1, 3);
			break;
		case 5:
			$price1m = substr($price1m, 0, 2) . ' ' . substr($price1m, 2, 3);
			break;
		case 6:
			$price1m = substr($price1m, 0, 3) . ' ' . substr($price1m, 3, 3);
			break;
		default:
			break;
	}
	$arProperties['price_1m']['VALUE'] = $price1m;
}

$arResult['PROPERTIES'] = $arProperties;

//if ($arResult[])
	
/*
 * 
 * 
 * 
 * 
$arSelect = array("ID","NAME","IBLOCK_ID","PROPERTY_floors")
$arFilter = array("IBLOCK_ID"=>8,"ACTIVE"=>"Y","ID");
 */
$this->__component->SetResultCacheKeys(array("I_PROPS"));
?>