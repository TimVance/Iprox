<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Избранное");
CModule::IncludeModule("iblock");
?>

<?

$isCatalog = false;

$bAjax = ( $_REQUEST["ajax"] === "Y" ) && ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' );
//определяем шаблон отображения

//определяем каталог ли /sell/#CATALOG#/
if (!empty($_REQUEST['catalog'])) {
	$isCatalog   = true;
	$catalogType = $_REQUEST['catalog'];
}
$arSort = array('SORT' => 'ASC');
$arFilter = array('USER_ID' => $USER->GetID());
$rsItems = CFavorites::GetList($arSort, $arFilter);
$arID = array();
while ($Res = $rsItems->Fetch()) {
	$res = CIBlockElement::GetByID($Res['URL']);
	if($ar_res = $res->GetNext()) {
	} else {
		CFavorites::Delete($Res['ID']);
	}

	$arID[] = $Res['URL'];
}


if (!empty($arID))
{
	$items = array();
	
	$arFilter = Array("ID" => $arID, "IBLOCK_ID" => \Morealty\Catalog::getCatalogIblockIds());
	$res = CIBlockElement::GetList(array('sort'=>'asc'), $arFilter, false, false, array("ID"));
	while($ob = $res->Fetch()) {
		$items[] = $ob["ID"];
	}
	$arFilter = array("ID" => $items,
			/*array(
					"LOGIC" => "OR",
					"IBLOCK_ID" => 19,
					"!PROPERTY_IS_ACCEPTED" => false
			)*/
	);
	$rsGroup = CIBlockElement::GetList(array(), $arFilter, array("IBLOCK_ID", "IBLOCK_CODE"));
	$arIblocksIds = $arIblocksCodes =  array();
	while ($arGroupIblocks = $rsGroup->Fetch())
	{
		$arIblocksCodes[] = $arGroupIblocks["IBLOCK_CODE"];
		$arIblocksIds[] = $arGroupIblocks["IBLOCK_ID"];
	}
	if (!$catalogType)
	{
		$catalogType = current($arIblocksCodes);
	}
?>

	<!-- <div class="filter-line">
		<div class="f-visible">
			<div class="sub_wrap">
				<div class="float_wrapper">
					<div class="secs">
						<div class="sec">
							<div class="search"></div>
						</div>
						<div class="sec">
							<div class="sec-head">
								<span>Цена <strong>&nbsp;от 4 200 000 до 12 000 000</strong></span>
							</div>
							<div class="form slide-pop">
								<span class="label">Площадь</span>
								<div class="field">
									<input type="number" min="10" name="from" placeholder="От">
									<input type="number" min="10" name="to" placeholder="До">
								</div>
								<span>м<sup>2</sup></span>
							</div>
						</div>
						<div class="sec">
							<div class="sec-head">
								<span>Тип квартиры</span>
							</div>
							<div class="form slide-pop">
								<span class="label">Площадь</span>
								<div class="field">
									<input type="number" min="10" name="from" placeholder="От">
									<input type="number" min="10" name="to" placeholder="До">
								</div>
								<span>м<sup>2</sup></span>
							</div>
						</div>
						<div class="sec">
							<div class="sec-head">
								<span>Площадь</span>
							</div>
							<div class="form slide-pop">
								<span class="label">Площадь</span>
								<div class="field">
									<input type="number" min="10" name="from" placeholder="От">
									<input type="number" min="10" name="to" placeholder="До">
								</div>
								<span>м<sup>2</sup></span>
							</div>
						</div>
						<div class="sec">
							<div class="sec-head">
								<span>Расположение</span>
							</div>
							<div class="form slide-pop">
								<span class="label">Площадь</span>
								<div class="field">
									<input type="number" min="10" name="from" placeholder="От">
									<input type="number" min="10" name="to" placeholder="До">
								</div>
								<span>м<sup>2</sup></span>
							</div>
						</div>
					</div>
					<a href="#" class="more">Еще детали</a>
				</div>
			</div>
		</div>
		<div class="f-toggle slide-pop">
			<div class="sub_wrap"></div>
		</div>
	</div> -->

<?

/*$APPLICATION->IncludeComponent('wexpert:includer', "filter_under_header", array('OK' => 'no'), false);
$APPLICATION->IncludeComponent('wexpert:includer', "sort_in_sell", array(), false);


if(!empty($arID)) {
	$APPLICATION->IncludeComponent("wexpert:iblock.list", $templateByView, Array(
		"ORDER"                             => array($_GET["SORT_BY"] => $_GET["SORT_ORDER"]),
		//"ORDER"                             => array('PROPERTY_price' => 'asc'),
		"FILTER"                            => array('ID' => $arID, $GLOBALS['FILTER_PROPERTY'] => $GLOBALS['FILTER_VALUES']),
		"IBLOCK_ID"							=> CATALOG_IBLOCKS,
		"PAGESIZE"						    => 4,
		"GET_PROPERTY"						=> "Y",
		"CACHE_TIME"    => 3600 * 24 * 10
	));*/


		$APPLICATION->IncludeComponent(
		"bitrix:catalog.smart.filter",
		"catalog_newstyle_filter",
			array(
				"COMPONENT_TEMPLATE"    => ".default",
				"IBLOCK_TYPE"           => "catalog",
				"IBLOCK_ID"             => \Morealty\Catalog::getIblockByCode($catalogType),
				"IBLOCK_IDS"			=> $arIblocksIds,
				"IBLOCK_URL_TEMPLATE"	=> "/personal/favorites/#IBLOCK_CODE#/",
				"HIDE_MAP"				=> "Y",
				"SECTION_ID"            => $SECTION_ID,
				"SECTION_CODE"          => "",
				"FILTER_NAME"           => "arrFilter",
				"HIDE_NOT_AVAILABLE"    => "N",
				"TEMPLATE_THEME"        => "blue",
				"FILTER_VIEW_MODE"      => "horizontal",
				"DISPLAY_ELEMENT_COUNT" => "Y",
				"SEF_MODE"              => "Y",
				"CACHE_TYPE"            => "N",
				"CACHE_TIME"            => "36000000",
				"CACHE_GROUPS"          => "Y",
				"SAVE_IN_SESSION"       => "N",
				"INSTANT_RELOAD"        => "Y",
				"PAGER_PARAMS_NAME"     => "arrPager",
				"PRICE_CODE"            => array(
				0 => "BASE",
				),
				"CONVERT_CURRENCY"      => "Y",
				"XML_EXPORT"            => "N",
				"SECTION_TITLE"         => "-",
				"SECTION_DESCRIPTION"   => "-",
				"POPUP_POSITION"        => "left",
				"SEF_RULE"              => "/sell/map/filter/#SMART_FILTER_PATH#/apply/",
				"SECTION_CODE_PATH"     => "",
				"SMART_FILTER_PATH"     => $_REQUEST["SMART_FILTER_PATH"],
				"CURRENCY_ID"           => "RUB",
				"CUR_PAGE"              => $APPLICATION->GetCurPage(false),
				"USE_NEWBUILDING"		=> in_array(19, $arIblocksIds)? "Y" : "N"
				),
			false
			);
		?>
	<?
	$APPLICATION->IncludeComponent("wexpert:includer", "sort_catalog", array(
		"IBLOCK_ID"             => \Morealty\Catalog::getIblockByCode($catalogType),
		"FILTER_NAME"           => "arrFilter",
		"PROPERTY_FILTER"		=> "realtor",
		"HIDE_MAP"				=> "Y",
	));
	$arFilter = array("ID" => $items, "!PROPERTY_IS_ACCEPTED" => false);
	$arFilter = array_merge($arFilter, (array) $GLOBALS["arrFilter"]);
	
	if ($bAjax) {
		$APPLICATION->RestartBuffer();
	}
	?>
	<div class="sell-section-ajaxed">
	
	<?$APPLICATION->IncludeComponent("wexpert:iblock.list", "catalog_items", Array(
			"ORDER"        => \Morealty\Catalog::getCurrentSort(),
			"FILTER"       => $arFilter,
			"IBLOCK_ID"    => \Morealty\Catalog::getIblockByCode($catalogType),
			"PAGESIZE"     => 20,
			"SELECT"       => "DATE_CREATE",
			"GET_PROPERTY" => "Y",
			//"NAV_TEMPLATE"						=> "morealty",
			"CACHE_TIME"   => 3600,
			"TEMPLATE_THEME" => \Morealty\Catalog::getCatalogViewType(),
			"ALWAYS_INCLUDE_TEMPLATE" => "Y"
		));
	?>
	</div>
	<?
		if ($bAjax) {
			die();
		}



} else { ?>
	<div>Избранные объекты отсутствуют.</div>
<? }

?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>