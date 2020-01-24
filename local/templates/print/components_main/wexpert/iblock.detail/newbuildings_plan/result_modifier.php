<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
<?
$IBLOCK_ID = 19;
$ELEMENT_ID = $_REQUEST['ID'];

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

$arSelect = Array("ID", "NAME", 'IBLOCK_ID', 'PROPERTY_CONTACT_PERSON', 'PROPERTY_ADDRESS', 'PROPERTY_PHONE_NUMBER');
$arFilter = Array("IBLOCK_ID" => 4, "ID" => $arProperties['builder']['VALUE'], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while($ob = $res->Fetch()) {
	$arProperties['BUILDER'] = $ob;
}

//
/*$rsUser                    = CUser::GetByID($arProperties['CONTACT_PERSON_ID']);
$arUser                    = $rsUser->Fetch();
$fullName                  = $arUser['NAME'] . ' ' . $arUser['SECOND_NAME'] . ' ' . $arUser['LAST_NAME'];
$realtor_email             = $arUser['EMAIL'];
$GLOBALS['REALTOR_EMAILS'] = $realtor_email;


$res = CIBlockElement::GetByID($arUser['UF_AGENT_NAME']);
if ($ar_res = $res->GetNext()) {
	$arUser['UF_AGENT_VALUE'] = $arUser['UF_AGENT_NAME'];
	$arUser['UF_AGENT_NAME']  = $ar_res['NAME'];
}
$arResult['arUser'] = $arUser;
*/
//



$arProperties['CONTACT_PERSON_ID'] = $arProperties['BUILDER']['PROPERTY_CONTACT_PERSON_VALUE'];


$arResult['PROPERTIES'] = $arProperties;


?>