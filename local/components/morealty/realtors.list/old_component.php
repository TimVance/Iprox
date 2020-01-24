<? if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die(); ?>

<?
/**
 * @var CBitrixComponent $this
 * 
 */

if ($this->StartResultCache(false)) {
	$SORT_BY  = $arParams['SORT_BY'];
	$SORT_ORDER = $arParams['SORT_ORDER'];
	$SEARCH_BY = $arParams['SEARCH_BY'];
	//TIMESTAMP_X

	$arFilter = Array(
		"GROUPS_ID" => '7',
	);
	$itemsPerPage = (isset($arParams["ITEMS_PER_PAGE"]))? $arParams["ITEMS_PER_PAGE"] : 7;
	/*$itemsPerPage = (isset($arParams["ITEMS_PER_PAGE"]))? $arParams["ITEMS_PER_PAGE"] : 7;

	
	$rsUsers     = CUser::GetList(( $by = $SORT_BY ), ( $order = $SORT_ORDER ), $arFilter, array("SELECT" => array("UF_*"))); // выбираем пользователей
	$arrTemp["ITEMS"] = array();
	if ($itemsPerPage)
	{
		$rsUsers->NavStart($itemsPerPage, false);
	}
	while ($arUser = $rsUsers->GetNext())
	{
		$arrTemp["ITEMS"][] = $arUser;
	}
	
	foreach($arrTemp["ITEMS"] as $arItem) {

		CModule::IncludeModule("iblock");
		$Res = CIBlockElement::GetByID($arItem["UF_AGENT_NAME"]);
		if ($ar_props = $Res->GetNext()) {
			$arItem["UF_AGENT_NAME"] = $ar_props["NAME"];
			$arItem["UF_AGENT_ID"] = $ar_props["ID"];
		}
		if(strpos($arItem['NAME'], $SEARCH_BY) !== false  || strpos($arItem['LAST_NAME'], $SEARCH_BY) !== false  || strpos($arItem["UF_AGENT_NAME"], $SEARCH_BY) !== false ) {
			$arrID[] = $arItem["ID"];
		}
	}


	$string = '';
	foreach($arrID as $id) {
		$string .= $id . ' || ';
	}
	$string = substr($string, 0, count($string) - 5);
	*/
	$arFilter2 = Array(
		"GROUPS_ID" => '7',
	);
	if ($arParams["FILTER"])
	{
		$arFilter2 = array_merge($arFilter2, (array) $arParams["FILTER"]);
	}
	if ($SEARCH_BY)
	{
		//$tempLogic = $GLOBALS["FILTER_logic"];
		//$GLOBALS["FILTER_logic"] = "or";
		$additionalFilter = array("NAME" => "%".$SEARCH_BY."%", "PERSONAL_MOBILE" => "%".$SEARCH_BY."%");
		if (\Bitrix\Main\Loader::includeModule("iblock"))
		{
			$arAgents = array();
			$rsAgents = CIBlockElement::GetList(array(), array("IBLOCK_ID" => 3, "ACTIVE" => "Y", "?NAME" => "%".$SEARCH_BY."%"), false, false, array("ID"));
			while ($arAgent = $rsAgents->Fetch())
			{
				$arAgents[] = $arAgent["ID"];
			}
			if ($arAgents)
			{
				$additionalFilter["UF_AGENT_NAME"] = $arAgents;
			}
		}
		$arFilter2[] = $additionalFilter;
		
	}
	
	$rsUsers     = CUser::GetList(( $by = $SORT_BY ), ( $order = $SORT_ORDER ), $arFilter2, array("SELECT" => array("UF_*"))); // выбираем пользователей
	if ($SEARCH_BY && $tempLogic)
	{
		//$GLOBALS["FILTER_logic"] = $tempLogic;
	}
	if ($itemsPerPage)
	{
		$rsUsers->NavStart($itemsPerPage, false);
	}
	if ($USER->GetLogin() == "vadim")
	{
		$nav = new \Bitrix\Main\UI\PageNavigation("");
		$nav->setPageSize($itemsPerPage)->initFromUri();
		$arFilter2 = Array(
				"Bitrix\Main\UserGroupTable:USER.GROUP_ID" => '7',
				);
		$additionalFilter = array("?NAME" => $SEARCH_BY, "?PERSONAL_MOBILE" => $SEARCH_BY);
		if (\Bitrix\Main\Loader::includeModule("iblock"))
		{
			$arAgents = array();
			$rsAgents = CIBlockElement::GetList(array(), array("IBLOCK_ID" => 3, "ACTIVE" => "Y", "?NAME" => "%".$SEARCH_BY."%"), false, false, array("ID"));
			while ($arAgent = $rsAgents->Fetch())
			{
				$arAgents[] = $arAgent["ID"];
			}
			if ($arAgents)
			{
				$additionalFilter["UF_AGENT_NAME"] = $arAgents;
			}
		}
		my_print_r($additionalFilter);
		$arFilter2[] = array_merge(array("LOGIC" => "OR"), $additionalFilter);
		$rsUsersd = \Bitrix\Main\UserTable::getList(array(
				"data_doubling" => false,
				"select" => array("*","UF_*"),
				"filter" => $arFilter2,
				"limit" => $nav->getLimit(),
				"offset" => $nav->getOffset(),
				"count_total" => true,
		));
		
		$nav->setRecordCount($rsUsersd->getCount());
		my_print_r($nav);
		my_print_r($rsUsersd->getCount());
		$APPLICATION->IncludeComponent(
				"bitrix:main.pagenavigation",
				"",
				array(
						"NAV_OBJECT" => $nav,
						"SEF_MODE" => "N",
				),
				false
		);
		
	}
	
	$arResult["ITEMS"] = array();
	$temp = array();
	while ($arUser = $rsUsers->GetNext())
	{
		$temp[] = $arUser;
	}
	
	foreach($temp as $tempItem) {
		if($tempItem['NAME'] != '') {
			array_push($arResult["ITEMS"], $tempItem);
		}
	}
	$arResult["NAV_RESULT"] = array(
		'PAGE_NOMER'					=> $rsUsers->NavPageNomer,		// номер текущей страницы постранички
		'PAGES_COUNT'					=> $rsUsers->NavPageCount,		// всего страниц постранички
		'RECORDS_COUNT'					=> $rsUsers->NavRecordCount,	// размер выборки, всего строк
		'CURRENT_PAGE_RECORDS_COUNT'	=> count($arResult["ITEMS"])	// размер выборки текущей страницы
	);
	$this->SetResultCacheKeys(array(
			"NAV_RESULT",
			'ITEMS'
	));
	$arResult["NAV_STRING"] = $rsUsers->GetPageNavStringEx(
		$navComponentObject,
		"",
		$arParams['NAV_TEMPLATE'],
		($arParams["NAV_SHOW_ALWAYS"] == 'Y')
	);
	$this->IncludeComponentTemplate();
}

return array_column($arResult["ITEMS"], "ID");
?>

<?
/*

$counter = 0;
$count = count($arResult['ITEMS']);

foreach($arResult['ITEMS'] as $arItem) {
CModule::IncludeModule("iblock");
$Res = CIBlockElement::GetByID($arItem["UF_AGENT_NAME"]);
if ($ar_props = $Res->GetNext()) {
$arResult["UF_AGENT_NAME"] = $ar_props["NAME"];
}
if(!( $arItem['NAME'] == $SEARCH_BY || $arItem['LAST_NAME'] == 'Длинных')) {
unset($arResult['ITEMS'][$counter]);
}
$counter++;
}
$rsUsers->arResult = $arResult['ITEMS'];
CConsole::log($count);
CConsole::log(count($arResult['ITEMS']));
$rsUsers->NavPageCount = count($arResult['ITEMS']) / 1;
$rsUsers->NavRecordCount = count($arResult['ITEMS']) / 1;
$rsUsers->nEndPage = count($arResult['ITEMS']) / 1;
$rsUsers->NavPageNomer = $rsUsers->PAGEN;
$rsUsers->NavNum = $rsUsers->PAGEN;
CConsole::log($rsUsers);
$arResult["NAV_STRING"] = $rsUsers->GetPageNavStringEx(
$navComponentObject,
"",
$arParams['NAV_TEMPLATE'],
($arParams["NAV_SHOW_ALWAYS"] == 'Y')
); */
?>