<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
$this->setFrameMode(true);
if ($arParams["STRUCTURE"] && count($arParams["STRUCTURE"]) > 0)
{
	
	?>
	<div class="cont-func cont-tb fint_estate_block">
		<div class="tab-f tab-tb">
			<div class="tabs-room">
				<div class="nav-rooms stabs_navs">
					<ul>
						<?
						$bFirst = true;
						foreach ($arParams["STRUCTURE"] as $IblockID => $arStructure)
						{
							?>
								<li class="stabs_nav <?=($bFirst)? 'active' : ""?>"><a href="javascript:void(0);"><?=$arStructure['NAME']?></a></li>
							<?
							$bFirst = false;
						}
						?>
						
					</ul>
				</div>
				<div class="stabs_contents">
					<?
					$bFirst = true;
					foreach ($arParams["STRUCTURE"] as $IblockID => $IblockSettings)
					{
						?>
							<div class="stabs_content <?=($bFirst)? 'active' : ""?>">
						<?
						$bFirst = false;
						$APPLICATION->IncludeComponent(
							"bitrix:catalog.smart.filter",
							"main_filter",
							array(
									"COMPONENT_TEMPLATE"    => ".default",
									"IBLOCK_TYPE"           => "catalog",
									"IBLOCK_ID"             => $IblockID,
									"SECTION_ID"            => "",
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
									"MAIN_PROPS"			=> $IblockSettings['PROPS'],
									"ADDITIONAL_PROPS"		=> $IblockSettings["ADDITIONAL_PROPS"],
									"MAP_LINK"				=> $IblockSettings["MAP"]
							),
							$component
						);
						?>
						</div>
						<?
					}
					?>
				</div>
			</div>
		</div>
	</div>
	
	<?
	
}
