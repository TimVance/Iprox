<? if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();?>
<?
foreach ($arResult["ITEMS"] as &$arItem)
{
	$arProperties = $arItem['PROPERTIES'];
	if ($arProperties['city']['VALUE'])
	{
		$res = CIBlockElement::GetByID($arProperties['city']['VALUE']);
		if ($ar_res = $res->GetNext()) {
			$arProperties['city']['MODIFIED_VALUE'] = $ar_res['NAME'];
		}
	}

	//city
	if ($arProperties['district']['VALUE'])
	{
		$res = CIBlockElement::GetByID($arProperties['district']['VALUE']);
		//district
		if ($ar_res = $res->GetNext()) {
			$arProperties['district']['MODIFIED_VALUE'] = $ar_res['NAME'];
		}
	}
	if ($arProperties['microdistrict']['VALUE'])
	{
		$res = CIBlockElement::GetByID($arProperties['microdistrict']['VALUE']);
		//microdistrict
		if ($ar_res = $res->GetNext()) {
			$arProperties['microdistrict']['MODIFIED_VALUE'] = $ar_res['NAME'];
		}
	}
	if ($arProperties["newbuilding"]["VALUE"])
	{
		$res = CIBlockElement::GetList(array(),array("ID"=>$arProperties["newbuilding"]["VALUE"]),false,false,array("ID","IBLOCK_ID","NAME","PROPERTY_floors"));
		if ($newBuilding = $res->GetNext())
		{
			$arProperties["newbuilding"]["ELEM"] = $newBuilding;
		}
	}
	$arItem["PROPERTIES"] = $arProperties;
	unset($arProperties);
}



?>