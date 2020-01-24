<? if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();?>
<?
//считаем количество предложений
/*$arSelect = Array('ID', 'NAME');
$arFilter = Array('TYPE'=> 'catalog', 'ACTIVE'=>'Y', 'GLOBAL_ACTIVE'=>'Y');
$res = CIBlock::GetList(Array('SORT'=>'ASC'), $arFilter, true, $arSelect);
while($item = $res->GetNext()) {
	$arID[]   = $item['ID'];
	$arNAME[]   = $item['NAME'];
}

$arSECTION = array();

for($i = 0; $i < count($arID) ; $i++) {
	$arSECTION[$arID[$i]] = $arNAME[$i];
}
$arSelect = Array('PROPERTY_REALTOR', 'IBLOCK_ID', 'ID');
$arFilter = Array("IBLOCK_ID"=>$arID, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
$currentID = $arResult['ID'];
$arRealtorOffers = '';
$arCurrentRealtorOffersID = array();
while($ob = $res->GetNextElement()) {
	$arFields = $ob->GetFields();
	if($arFields['PROPERTY_REALTOR_VALUE'] == $currentID) {
		$arCurrentRealtorOffersID[] = $arFields['ID'];
		if(!empty($arRealtorOffers[$arFields['IBLOCK_ID']])) {
			$arRealtorOffers[$arFields['IBLOCK_ID']]++;
		}
		else {
			$arRealtorOffers[$arFields['IBLOCK_ID']] = 1;
		}
	}
}
*/

$totalCount = intval($arResult["UF_REALTOR_OBJECTS"]);
/*foreach($arRealtorOffers as $offer) {
	$totalCount += $offer;
}*/
?>