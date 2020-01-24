<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);?>


<div class="bottom-filter-bb">
	<div class="left-bot-filter-bb">
		<div class="object-bb catalog_counts_wrapper"><?$APPLICATION->ShowViewContent("all_count");?></div>
		
		<?
		if ($arParams["PROPERTY_FILTER"])
		{
			$All = (bool) !$_REQUEST[$arParams["PROPERTY_FILTER"]] || $_REQUEST[$arParams["PROPERTY_FILTER"]] == "all";
			$bProp = $_REQUEST[$arParams["PROPERTY_FILTER"]] == "Y";
			?>
			<div style="display:none" class="menu-obj-bb custom_prop_filter">
				<ul>
					<li <?=($All)? 'class="active"' : ""?> ><a href="javascript:void(0);" data-prop="<?=$arParams["PROPERTY_FILTER"]?>" data-val="all">Все</a></li>
					<li <?=(!$All && $bProp)? 'class="active"' : ""?>><a href="javascript:void(0);" data-prop="<?=$arParams["PROPERTY_FILTER"]?>" data-val="Y">Риэлтор</a></li>
					<li <?=(!$All && !$bProp)? 'class="active"' : ""?>><a href="javascript:void(0);" data-prop="<?=$arParams["PROPERTY_FILTER"]?>" data-val="N">Собственник</a></li>
				</ul>
			</div>
			<?
		}
		?>
		
	</div>
	
	<div class="right-bot-filter-bb">
		<?
		$isMap = $arParams["ISMAP"] === "Y";
		$types = \Morealty\Settings::$CATALOG_VIEW_TYPES;
		$current = \Morealty\Catalog::getCatalogViewType();
		if ($types)
		{
			?>
			<div class="menu-quare-bb type-selector">
				<ul>
					<?
					foreach ($types as $type)
					{
						$arIblock = \Morealty\Catalog::getIblockDataByID($arParams["IBLOCK_ID"]);
						$link = ($isMap)? $arIblock["LIST_PAGE_URL"] : "javascript:void(0);";
						?>
							<li class="<?=$type["class"]?> <?=($current == $type['id'] && !$isMap)? 'active' : ""?>"><a href="<?=$link?>" data-val="<?=$type["id"]?>" data-code="<?=\Morealty\Settings::CATALOG_VIEW_TYPE_PARAM?>"></a></li>
						<?
					}
					?>
					
					<?
					if ($arParams["HIDE_MAP"] !== "Y")
					{
						$arIblock = \Morealty\Catalog::getIblockDataByID($arParams["IBLOCK_ID"]);
						if ($arIblock["MAP_PAGE_URL"])
						{
							?>
								<li class="item4 catalog_go_to_map <?=($isMap)? "active" : ""?>"><a href="<?=$arIblock["MAP_PAGE_URL"]?>"></a></li>
							<?
						}
					}
					
					?>
					
				</ul>
			</div>
			<?
		}
		
		
		$arSorts = \Morealty\Settings::$CATALOG_SORT_PARAMS;
		if ($arSorts)
		{
			?>
			<div class="select-bb">
				<select name="sort" id="sort">
					<?
					foreach ($arSorts as $arSort)
					{
						$bCurrent = \Morealty\Catalog::getCurrentSort() == $arSort["SORT"];
						?>
							<option value="<?=$arSort["ID"]?>" <?=($bCurrent)? 'selected' : ""?>><?=$arSort["NAME"]?></option>
						<?
					}
					?>
					
				</select>
			</div>
			<?
		}
		?>
		
	</div>
</div>