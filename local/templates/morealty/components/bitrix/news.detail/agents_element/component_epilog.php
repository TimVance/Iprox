<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
global $APPLICATION;
$bAjax = (strtolower($_REQUEST["ajax"]) === "y") && (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
if ($arResult["USERS"] && count($arResult["USERS"]) > 0)
{
	?>
	<div class="b-propos agent_objects_workarea">
		<div class="t-emploe">Предложения <?=$arResult["NAME"]?> <span id="agents_element_count"><?$APPLICATION->ShowViewContent("agent_page_count_objects")?></span></div>
		<div class="line-view">
			<p>Показать</p>
			<?
			$groupFilter = array("PROPERTY_REALTOR" => $arResult["USERS"], "!PROPERTY_IS_ACCEPTED"=>false);
			$arGroups = array();
			$rsItems = CIBlockElement::GetList(array(), $groupFilter, array("IBLOCK_ID"));
			while ($arGroup = $rsItems->Fetch())
			{
				if ($arGroup["IBLOCK_ID"])
				{
					$IblockName = \Morealty\Catalog::getIblockDataField($arGroup["IBLOCK_ID"], "NAME");
					$arGroups[] = array(
							"ID" => $arGroup["IBLOCK_ID"],
							"NAME"		=> $IblockName,
							"CNT"		=> $arGroup['CNT']
					);
				}
				
			}
			?>
			<div class="sel-view select_build-type">
				<select>
					<option value="" <?=($_REQUEST["TYPE_BUILD"] == "all") ? "selected" : ""?>>Все объекты <?$APPLICATION->ShowViewContent("agent_page_count_objects")?></option>
					<? foreach($arGroups as $arValue): ?>
						<option value="<?=$arValue["ID"]?>" <?=($_REQUEST["TYPE_BUILD"] == $arValue["ID"]) ? "selected" : ""?>><?=strtolower($arValue["NAME"]) . '(' . $arValue["CNT"] . ')'?></option>
					<? endforeach; ?>
				</select>
			</div>
		</div>
	
	<div class="sort_wrapper">
	<?
	$APPLICATION->IncludeComponent('wexpert:includer', "sort_in_sell",
			array("CUSTOM_EVENT" => "Y", "NOT_RELOAD_ON_CHANGE_TILE" => "Y", "CHANGE_VIEW_CLASS" => "im_myself", "REVERSE" => "Y"),
			$this->__component
	);
	?>
	</div>
	<?
	if ($arResult["USERS"] && count($arResult["USERS"]) > 0)
	{
		$Filter = array("PROPERTY_REALTOR" => $arResult["USERS"], "!PROPERTY_IS_ACCEPTED"=>false);
	}
	
	if ( in_array(intval($_REQUEST["TYPE_BUILD"]),$GLOBALS['CATALOG_IBLOCKS_ARRAY'] ))
	{
		$Filter["IBLOCK_ID"] = intval($_REQUEST["TYPE_BUILD"]);
	}
	?>
	<div class="agents_objects_prewrapper">
	<?
	if ($bAjax)
	{
		$APPLICATION->RestartBuffer();
	}
	?>
		
		<div class="agents_objects_wrapper">
			<?
			$return = $APPLICATION->IncludeComponent("wexpert:iblock.list",$arParams["OFFERS_TEMPLATE_ID"],Array(
					"ORDER"                             => array($_GET["SORT_BY"] => $_GET["SORT_ORDER"]),
					"FILTER"                            => $Filter,
					"IBLOCK_ID"							=> $arID,
					"PAGESIZE"						    => $arParams["AGENT_OBJECTS_PAGESIZE"],
					"GET_PROPERTY"						=> "Y",
					"CACHE_TIME"    					=> $arParams["CACHE_TIME"],
					"CACHE_TYPE"						=>	$arParams["CACHE_TYPE"],
					"OPTIONS"							=> $_REQUEST,
			), $this->__component);
			$nums = ($return["NAV_RESULT"]["RECORDS_COUNT"]) ? $return["NAV_RESULT"]["RECORDS_COUNT"] : 0;
			if (isset($nums))
			{
				?>
				<script>
				$(function(){
					$(".total_counts_wrapper").text("<?=$nums?> <?=Suffix($nums, array("Объект", "Объекта", "Объектов"))?>");
				})
				</script>
				<?
			}
			?>
		</div>
		<?
		if ($bAjax)
		{
			die();
		}
		?>
	</div>
	</div>
	<?
}
/*
echo preg_replace_callback(
         "/#sell-items#/is".BX_UTF_PCRE_MODIFIER,
			function ($matches) use ($arResult,$arParams)
			{
				
				ob_start();
				
				?>
					<div class="line-view">
						<p>Показать</p>
				
						<div class="sel-view select_build-type">
							<select>
								<option data-value="all" <?=($_REQUEST["TYPE_BUILD"] == "all") ? "selected" : ""?>>Все объекты (<?=$arResult["TOTAL_COUNT"]?>)</option>
								<? foreach($arResult["OFFERS_TYPES"] as $key => $value): ?>
									<option data-value="<?=$key?>" <?=($_REQUEST["TYPE_BUILD"] == $key) ? "selected" : ""?>><?=strtolower($arResult["arSECTIONS"][$key]) . '(' . $value . ')'?></option>
								<? endforeach; ?>
							</select>
						</div>
					</div>
				
				<?
				$GLOBALS["APPLICATION"]->IncludeComponent('wexpert:includer', "sort_in_sell",
						array(),
						false
				);
				if ($templateData && count($templateData) > 0)
				{
					$Filter = array("PROPERTY_REALTOR" => $templateData);
				}
				
				if ( in_array(intval($_REQUEST["TYPE_BUILD"]),$GLOBALS['CATALOG_IBLOCKS_ARRAY'] ))
				{
					$Filter["IBLOCK_ID"] = intval($_REQUEST["TYPE_BUILD"]);
				}
				
				$GLOBALS["APPLICATION"]->IncludeComponent("wexpert:iblock.list",$arParams["OFFERS_TEMPLATE_ID"],Array(
						"ORDER"                             => array($_GET["SORT_BY"] => $_GET["SORT_ORDER"]),
						"FILTER"                            => $Filter,
						"IBLOCK_ID"							=> $arID,
						"PAGESIZE"						    => 6,
						"GET_PROPERTY"						=> "Y",
						"CACHE_TIME"    => 3600 * 24 * 10,
						"CACHE_TYPE"	=>	"Y",
						"OPTIONS"=>$_REQUEST,
				));
				
				$retrunStr = @ob_get_contents();
				ob_get_clean();
				
				return $retrunStr;
			},
         $arResult["CACHED_TPL"]);
         
         */
?>