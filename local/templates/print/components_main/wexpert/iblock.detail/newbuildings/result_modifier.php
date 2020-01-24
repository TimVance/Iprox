<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
<?
$IBLOCK_ID = 19;
$ELEMENT_ID = $_REQUEST['ID'];

$arProperties = $arResult['PROPERTIES'];
//city
$res          = CIBlockElement::GetByID($arProperties['city']['VALUE']);
if ($ar_res = $res->GetNext()) {
	$arProperties['city']['MODIFIED_VALUE'] = $ar_res['NAME'];
}
//district
$res = CIBlockElement::GetByID($arProperties['district']['VALUE']);
if ($ar_res = $res->GetNext()) {
	$arProperties['district']['MODIFIED_VALUE'] = $ar_res['NAME'];
}
//microdistrict
$res = CIBlockElement::GetByID($arProperties['microdistrict']['VALUE']);
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

$arSelect = Array("ID", "NAME", 'IBLOCK_ID', 'PROPERTY_CONTACT_PERSON');
$arFilter = Array("IBLOCK_ID" => 4, "ID" => $arProperties['builder']['VALUE'], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while($ob = $res->Fetch()) {
	$arProperties['BUILDER'] = $ob;
}

$arProperties['CONTACT_PERSON_ID'] = $arProperties['BUILDER']['PROPERTY_CONTACT_PERSON_VALUE'];


/*$rsUser                    = CUser::GetByID($arProperties['CONTACT_PERSON_ID']);
$arUser                    = $rsUser->Fetch();
$arResult['arUser'] = $arUser;
$fullName                  = $arUser['NAME'] . ' ' . $arUser['SECOND_NAME'] . ' ' . $arUser['LAST_NAME'];
$realtor_email             = $arUser['EMAIL'];*/
$GLOBALS['REALTOR_EMAILS'] = $realtor_email;


//определение имени агенства
$res = CIBlockElement::GetByID($arUser['UF_AGENT_NAME']);
if ($ar_res = $res->GetNext()) {
	$arUser['UF_AGENT_VALUE'] = $arUser['UF_AGENT_NAME'];
	$arUser['UF_AGENT_NAME']  = $ar_res['NAME'];
}

//favorites IDs

if ($USER->IsAuthorized()) {
	$arSort   = array('SORT' => 'ASC');
	$arFilter = array('USER_ID' => $USER->GetID());
	$rsItems  = CFavorites::GetList($arSort, $arFilter);
	$arID     = array();
	while ($Res = $rsItems->Fetch()) {
		$arID[] = $Res['URL'];
	}
}


//считаем количество предложений
$arSelect = Array('ID', 'NAME');
$arFilter = Array('TYPE' => 'catalog', 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y');
$res      = CIBlock::GetList(Array('SORT' => 'ASC'), $arFilter, true, $arSelect);
while ($item = $res->GetNext()) {
	$arID2[]  = $item['ID'];
	$arNAME[] = $item['NAME'];
}

$arSECTION = array();

for ($i = 0; $i < count($arID2); $i++) {
	$arSECTION[$arID2[$i]] = $arNAME[$i];
}
$arSelect                 = Array(
	'PROPERTY_REALTOR', 'IBLOCK_ID', 'ID', 'NAME', 'PROPERTY_PRICE', 'PROPERTY_apartment_complex', 'PROPERTY_street',
	'PROPERTY_district', 'PROPERTY_microdistrict', 'PROPERTY_city', 'PROPERTY_floor', 'PROPERTY_currency'
);
$arFilter                 = Array("IBLOCK_ID" => $arID2, "ACTIVE_DATE" => "Y", "ACTIVE" => "Y");
$res                      = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize" => 50), $arSelect);
$currentID                = $arProperties['realtor']['VALUE'];
$arRealtorOffers          = '';
$arCurrentRealtorOffersID = array();
while ($ob = $res->GetNextElement()) {
	$arFields = $ob->GetFields();
	if ($arFields['PROPERTY_REALTOR_VALUE'] == $currentID) {
		$arCurrentRealtorOffersID[] = $arFields;
		if (!empty( $arRealtorOffers[$arFields['IBLOCK_ID']] )) {
			$arRealtorOffers[$arFields['IBLOCK_ID']] = intval($arRealtorOffers[$arFields['IBLOCK_ID']]) + 1;
		} else {
			$arRealtorOffers[$arFields['IBLOCK_ID']] = 1;
		}
	}
}


$arProperties['deadline']['VALUE'] = ($arProperties['deadline_quart']['VALUE_ENUM'] && $arProperties['deadline_year']['VALUE_ENUM'])? $arProperties['deadline_quart']['VALUE_ENUM']." ".$arProperties['deadline_year']['VALUE_ENUM']: false; 


$arResult['PROPERTIES'] = $arProperties;



//$arItems - квартиры текущей новостройки
$arSelect = array('ID', 'NAME', 'IBLOCK_ID', 'PROPERTY_*', 'SHOW_COUNTER', 'TIMESTAMP_X');
$arFilter = Array("IBLOCK_ID" => 7, "ACTIVE" => "Y", "PROPERTY_newbuilding" => $ELEMENT_ID,"!PROPERTY_IS_ACCEPTED"=>false);
$res = CIBlockElement::GetList(Array('sort' => 'desc'), $arFilter, false, false, $arSelect);
$arItems = array();
$minPrice = false;
$flatsCount = $res->SelectedRowsCount();
while($ob = $res->GetNextElement()) {
	$item = $ob->GetFields();
	$item['PROPERTIES'] = $ob->GetProperties();
	
	if (!$minPrice) 
	{
		$minPrice = intval($item['PROPERTIES']['price']['VALUE']);
	}
	else if ($minPrice > intval($item['PROPERTIES']['price']['VALUE']))
	{
		$minPrice = intval($item['PROPERTIES']['price']['VALUE']);
	}
	
	$arResult['arItems'][] = $item;
}
$arResult["flatsCount"] = $flatsCount;
$arResult["BuildingMinPrice"] = $minPrice;



$arSelect = array('ID', 'NAME', 'IBLOCK_ID', 'PROPERTY_price');
$arFilter = Array("IBLOCK_ID" => 7, "ACTIVE" => "Y", "PROPERTY_newbuilding" => $ELEMENT_ID,"!PROPERTY_IS_ACCEPTED"=>false);
$res = CIBlockElement::GetList(Array('PROPERTY_price' => 'asc'), $arFilter, false, array("nTopCount"=>"1"), $arSelect);
if ($arItem = $res->GetNext())
{
	$arResult["BuildingMinPrice"] = $arItem["PROPERTY_PRICE_VALUE"];
}

/*
$arFilter = Array("IBLOCK_ID" => 7, "ACTIVE" => "Y", "PROPERTY_newbuilding" => $ELEMENT_ID,"!PROPERTY_IS_ACCEPTED"=>false);
$res = CIBlockElement::GetList(Array('SORT' => 'asc'), $arFilter, array("ACTIVE"), false);
if ($arItem = $res->GetNext())
{
	$arResult['ItemsCount'] = $arItem["CNT"];
}
*/
?>