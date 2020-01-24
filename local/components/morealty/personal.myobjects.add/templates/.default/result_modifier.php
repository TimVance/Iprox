<? if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule('iblock');

if ($arResult['ITEMS']['DETAIL_TEXT'])
{
	$arResult["INPUTED"]['DETAIL_TEXT'] = $arResult['ITEMS']['DETAIL_TEXT'] = HTMLToTxt($arResult['ITEMS']['DETAIL_TEXT'], "", array(), 0);
}


$res = CIBlockElement::GetList(Array(), Array("IBLOCK_ID" => 5, "ACTIVE"=>"Y"), false, Array("nPageSize"=>50), Array("ID", "NAME"));
while($ob = $res->Fetch()) {
    $arResult['PROPERTIES']['CITIES'][] = $ob;
}

//регионы
$res = CIBlockElement::GetList(Array(), Array("IBLOCK_ID" => 14, "PROPERTY_city" => $arResult['PROPERTIES']['CITIES'][0]['ID'], "ACTIVE"=>"Y"), false, Array("nPageSize"=>50), Array("ID", "NAME"));
while($ob = $res->Fetch()) {
    $arResult['PROPERTIES']['DISTRICTS'][] = $ob;
}


//микрорайоны
$res = CIBlockElement::GetList(Array(), Array("IBLOCK_ID" => 15, "PROPERTY_district" => $arResult['PROPERTIES']['DISTRICTS'][0]['ID'], "ACTIVE"=>"Y"), false, Array("nPageSize"=>50), Array("ID", "NAME"));
while($ob = $res->Fetch()) {
    $arResult['PROPERTIES']['MICRODISTRICTS'][] = $ob;
}

//новостройки
$res = CIBlockElement::GetList(Array(), Array("IBLOCK_ID" => 19, "ACTIVE"=>"Y"), false, false, Array("ID", "NAME"));
while($ob = $res->Fetch()) {
    $arResult['PROPERTIES']['newbuildings'][] = $ob;
}

if(empty($_REQUEST['PRODUCT_ID'])) {
    $arResult['PROPERTIES']['room_number_list'] = array(
        '1' => '1-комнатная',
        '2' => '2-комнатная',
        '3' => '3-комнатная',
        '4' => '4-комнатная'
    );

//отделка
    /*$arResult['PROPERTIES']['decoration_list'] = array(
        'no_repair' => 'Без ремонта',
        'decoration' => 'Чистовая отделка',
        'redecorating' => 'Косметический ремонт',
        'euro' => 'Евроремонт',
        'elite' => 'Элитный ремонт'
    );*/

    

//плита
    $arResult['PROPERTIES']['stove_list'] = array(
        'gas' => 'Газовая',
        'electricity' => 'Электрическая'
    );

//санузел
    $arResult['PROPERTIES']['wc_list'] = array(
        'combined' => 'Совмещенный',
        'separated' => 'Раздельный'
    );

    if ($_REQUEST["IBLOCK_ID"])
    {
    	$res = CIBlockPropertyEnum::GetList(array("sort"=>"ASC"),array("IBLOCK_ID"=>intval($_REQUEST["IBLOCK_ID"]),"CODE"=>"decoration"));
    	$arResult['PROPERTIES']['decoration_list'] = array();
    	while ($arItem = $res->GetNext())
    	{
    		$arResult['PROPERTIES']['decoration_list'][$arItem["ID"]] = $arItem["VALUE"];
    	}
    	$res = CIBlockPropertyEnum::GetList(array("sort"=>"ASC"),array("IBLOCK_ID"=>intval($_REQUEST["IBLOCK_ID"]),"CODE"=>"decoration"));
    	$arResult['PROPERTIES']['decoration_list'] = array();
    	while ($arItem = $res->GetNext())
    	{
    		$arResult['PROPERTIES']['decoration_list'][$arItem["ID"]] = $arItem["VALUE"];
    	}
    	$res = CIBlockPropertyEnum::GetList(array("sort"=>"ASC"),array("IBLOCK_ID"=>intval($_REQUEST["IBLOCK_ID"]),"CODE"=>"stove"));
    	$arResult['PROPERTIES']['stove_list'] = array();
    	while ($arItem = $res->GetNext())
    	{
    		$arResult['PROPERTIES']['stove_list'][$arItem["ID"]] = $arItem["VALUE"];
    	}
    	$res = CIBlockPropertyEnum::GetList(array("sort"=>"ASC"),array("IBLOCK_ID"=>intval($_REQUEST["IBLOCK_ID"]),"CODE"=>"wc"));
    	$arResult['PROPERTIES']['wc_list'] = array();
    	while ($arItem = $res->GetNext())
    	{
    		$arResult['PROPERTIES']['wc_list'][$arItem["ID"]] = $arItem["VALUE"];
    	}
    	
    	$res = CIBlockPropertyEnum::GetList(array("sort"=>"ASC"),array("IBLOCK_ID"=>intval($_REQUEST["IBLOCK_ID"]),"CODE"=>"STATUS"));
    	$arResult['PROPERTIES']['STATUS_ITEMS'] = array();
    	while ($arItem = $res->GetNext())
    	{
    		$arResult['PROPERTIES']['STATUS_ITEMS'][$arItem["ID"]] = $arItem["VALUE"];
    	}
    }
    
//санузел
    $arResult['PROPERTIES']['currency_list'] = array(
        'rubles' => 'Рубли',
        'dollars' => 'Доллары',
        'euro' => 'Евро'
    );
} else {

    //количество комнат
    $arResult['PROPERTIES']['room_number_list'] = array(
        '1' => '1-комнатная',
        '2' => '2-комнатная',
        '3' => '3-комнатная',
        '4' => '4-комнатная'
    );

//отделка
    /*$arResult['PROPERTIES']['decoration_list'] = array(
        'no_repair' => 'Без ремонта',
        'decoration' => 'Чистовая отделка',
        'redecorating' => 'Косметический ремонт',
        'euro' => 'Евроремонт',
        'elite' => 'Элитный ремонт'
    );*/
    if ($_REQUEST["IBLOCK_ID"])
    {
    	$res = CIBlockPropertyEnum::GetList(array("sort"=>"ASC"),array("IBLOCK_ID"=>intval($_REQUEST["IBLOCK_ID"]),"CODE"=>"decoration"));
    	$arResult['PROPERTIES']['decoration_list'] = array();
    	while ($arItem = $res->GetNext())
    	{
    		$arResult['PROPERTIES']['decoration_list'][$arItem["ID"]] = $arItem["VALUE"];
    	}
    	$res = CIBlockPropertyEnum::GetList(array("sort"=>"ASC"),array("IBLOCK_ID"=>intval($_REQUEST["IBLOCK_ID"]),"CODE"=>"stove"));
    	$arResult['PROPERTIES']['stove_list'] = array();
    	while ($arItem = $res->GetNext())
    	{
    		$arResult['PROPERTIES']['stove_list'][$arItem["ID"]] = $arItem["VALUE"];
    	}
    	$res = CIBlockPropertyEnum::GetList(array("sort"=>"ASC"),array("IBLOCK_ID"=>intval($_REQUEST["IBLOCK_ID"]),"CODE"=>"wc"));
    	$arResult['PROPERTIES']['wc_list'] = array();
    	while ($arItem = $res->GetNext())
    	{
    		$arResult['PROPERTIES']['wc_list'][$arItem["ID"]] = $arItem["VALUE"];
    	}
    	
    	$res = CIBlockPropertyEnum::GetList(array("sort"=>"ASC"),array("IBLOCK_ID"=>intval($_REQUEST["IBLOCK_ID"]),"CODE"=>"STATUS"));
    	$arResult['PROPERTIES']['STATUS_ITEMS'] = array();
    	while ($arItem = $res->GetNext())
    	{
    		$arResult['PROPERTIES']['STATUS_ITEMS'][$arItem["ID"]] = $arItem["VALUE"];
    	}
    }

//плита
    /*$arResult['PROPERTIES']['stove_list'] = array(
        'gas' => 'Газовая',
        'electricity' => 'Электрическая'
    );*/

//санузел
    /*$arResult['PROPERTIES']['wc_list'] = array(
        'combined' => 'Совмещенный',
        'separated' => 'Раздельный'
    );*/

//санузел
    $arResult['PROPERTIES']['currency_list'] = array(
        'rubles' => 'Рубли',
        'dollars' => 'Доллары',
        'euro' => 'Евро'
    );
    
	
}

$arResult["PROPERTIES"]["CURRENCY_VARIANTS"] = \Utilities\Iblock\PropertyEnum::getEnumVariants("currency", intval($_REQUEST["IBLOCK_ID"]));
foreach ($arResult["PROPERTIES"]["CURRENCY_VARIANTS"] as $key => $arVariant)
{
	$arResult["PROPERTIES"]["CURRENCY_VARIANTS"][$key]["VALUE"] = str_replace(array("RUB", "USD", "EUR"), array("Рубли", "Доллары", "Евро"), $arVariant["VALUE"]);
}

