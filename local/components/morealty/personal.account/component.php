<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/* @var $this CBitrixComponent */
use Bitrix\Main\Localization\Loc;
$arResult['BILL_FIELDS_FILLED'] = \UserAccount::isBillFiledsFilled( $USER->GetID() );

CModule::IncludeModule('iblock');
CModule::IncludeModule('sale');
\MorealtySale\Packets::UpdateActivePockets();
$res = CIBlock::GetList(array('SORT'=>'ASC'), array('TYPE'=> 'catalog', 'ACTIVE'=>'Y', 'GLOBAL_ACTIVE'=>'Y'), false, false, array('ID', 'NAME'));
while ($ob = $res->GetNext()) {
	$arResult['CATALOGS'][ $ob['ID'] ] = $ob;
}



$accountList = CSaleUserAccount::GetList(
		array("CURRENCY" => "ASC"),
		array("USER_ID" => (int)($USER->GetID())),
		false,
		false,
		array("ID", "CURRENT_BUDGET", "CURRENCY", "TIMESTAMP_X")
);

$currencyList = array();
$currencyIterator = Bitrix\Currency\CurrencyTable::getList(array(
		'select' => array('CURRENCY', 'FULL_NAME' => 'CURRENT_LANG_FORMAT.FULL_NAME', 'SORT'),
		'order' => array('SORT' => 'ASC', 'CURRENCY' => 'ASC')
));
while ($currency = $currencyIterator->fetch())
{
	$currencyList[$currency['CURRENCY']] = (string)$currency['FULL_NAME'];
}

$baseCurrencyCode = Bitrix\Sale\Internals\SiteCurrencyTable::getSiteCurrency(SITE_ID);
$this->arResult['BASE_CURRENCY'] =  array(
		"CODE" => $baseCurrencyCode,
		"TEXT" => $currencyList[$baseCurrencyCode]
);
	
while ($account = $accountList->Fetch())
{
	$resultTemplate["CURRENCY"] = $account["CURRENCY"];
	$resultTemplate["ACCOUNT_LIST"] = $account;
	$resultTemplate["INFO"] = Loc::getMessage(
			"SPA_IN_CUR",
			array(
					"#CURRENCY#" => $resultTemplate["CURRENCY"],
					"#SUM#" => SaleFormatCurrency($account["CURRENT_BUDGET"], $account["CURRENCY"]),
			));
	$resultTemplate["CURRENCY_FULL_NAME"] = $currencyList[$account['CURRENCY']];
	$resultTemplate["SUM"] =  SaleFormatCurrency($account["CURRENT_BUDGET"], $account["CURRENCY"]);
	$arResult["ACCOUNT_LIST"][] = $resultTemplate;
}
if (empty($this->arResult["ACCOUNT_LIST"]))
{
	$arResult["ACCOUNT_LIST"][] = array(
			'SUM' => SaleFormatCurrency(0, $this->arResult['BASE_CURRENCY']['CODE']),
			'CURRENCY' => $this->arResult['BASE_CURRENCY']['CODE'],
			'CURRENCY_FULL_NAME' => $this->arResult['BASE_CURRENCY']['TEXT']
	);
}

/*$rsUserGroups = CUser::GetUserGroupList($USER->GetID());
$arGroupsDuration = array();
while ($arGroup = $rsUserGroups->Fetch()){

	$UserGroups[] = $arGroup["GROUP_ID"];
	if ($arGroup["DATE_ACTIVE_TO"] && $arGroup["DATE_ACTIVE_FROM"])
	{
		$arGroupsDuration[$arGroup["GROUP_ID"]] = array("DATE_ACTIVE_FROM"=>$arGroup["DATE_ACTIVE_FROM"],"DATE_ACTIVE_TO"=>$arGroup["DATE_ACTIVE_TO"]);
	}

}
$arUserpackets = array();
if (count($UserGroups) > 0)
{
	$UserGroups = implode("|", $UserGroups);
	$ThisGroups = CGroup::GetList($by = "c_sort", $order = "asc",array("ID"=>$UserGroups,"NAME"=>"%Пакет%"));
	while ($arGroup = $ThisGroups->GetNext())
	{
		if ($arGroupsDuration[$arGroup["ID"]])
		{
			$arGroup["DATE_ACTIVE_TO"] = $arGroupsDuration[$arGroup["ID"]]["DATE_ACTIVE_TO"];
			$arGroup["DATE_ACTIVE_FROM"] = $arGroupsDuration[$arGroup["ID"]]["DATE_ACTIVE_FROM"];
		}
		
		$arUserpackets[] = $arGroup;
	}
}
*/
/*$arResult["PACKETS"] = \MorealtySale\User::getActivePackets();
unset($arUserpackets,$arGroup,$ThisGroups,$UserGroups);
*/

$arResult["ACCOUNT"] = array(
		"ACTIVE_TO" => \CustomUsers::getUserActiveTime($USER->GetID())
);

//$arResult["PACKETS"] = \Morealty\User::getPacketsArray();
$arResult['PACKETS'] = \MorealtySale\User::getActivePackets();
$arResult["TRANSACTIONS"] = array();

$sectionID = false;

$rsSections = CIBlockSection::GetList(
		array(),
		array(
				"IBLOCK_ID" => 23,
				"NAME" => $USER->GetID(),
				'ACTIVE' => "Y"
		),
		false,
		array("ID" , 'NAME')
		
	);
if ($arSection = $rsSections->GetNext())
{
	$sectionID = $arSection['ID'];
}
if ($sectionID)
{
	$rsIblockTransactions = CIBlockElement::GetList(
			array(),
			array(
					"IBLOCK_ID" => 23,
					"SECTION_ID" => $sectionID,
					"ACTIVE" => "Y"
			)
		);
	
	while ($objTransaction = $rsIblockTransactions->GetNextElement())
	{
		$arItem = $objTransaction->GetFields();
		$arItem["PROPERTIES"] = $objTransaction->GetProperties();
		$arResult["TRANSACTIONS"][] = array(
				"ID" => $arItem["ID"],
				"TRANSACT_DATE" => $arItem["DATE_CREATE"],
				"DATE_TIMESTAMP" => MakeTimeStamp($arItem["DATE_CREATE"]),
				"SUM"			=> $arItem['PROPERTIES']["SUMM"]["VALUE"],
				"CUR"			=> "RUB",
				"LINK"			=> "/personal/?DOC=".$arItem["ID"],
				"PLUS"			=> ($arItem["PROPERTIES"]["STATUS"]["VALUE_ENUM_ID"] == 387)? "Y" : "Ожидается оплата"
		);
	}

}



$rsTransactions = CSaleUserTransact::GetList(array("TRANSACT_DATE"=>"DESC"),array("USER_ID" => $USER->GetID()));
while ($arTransaction = $rsTransactions->GetNext())
{
	$arResult["TRANSACTIONS"][] = array("ID"=>$arTransaction["ID"],"TRANSACT_DATE"=>$arTransaction["TRANSACT_DATE"], "DATE_TIMESTAMP" => MakeTimeStamp($arTransaction["TRANSACT_DATE"]),"SUM"=>$arTransaction["AMOUNT"],"CUR"=>$arTransaction["CURRENCY"],"PLUS"=>$arTransaction["DEBIT"],"BUY"=> ($arTransaction["DESCRIPTION"] == "ORDER_PAY")? "Y" : "N");
}
if (!function_exists("sorting_by_column_key_component"))
{
	function sorting_by_column_key_component($a, $b)
	{
		return strcmp($b["DATE_TIMESTAMP"], $a["DATE_TIMESTAMP"]);
	}
}
usort($arResult["TRANSACTIONS"], "sorting_by_column_key_component");
//my_print_r($arResult["TRANSACTIONS"]);


$this->includeComponentTemplate();

	


?>