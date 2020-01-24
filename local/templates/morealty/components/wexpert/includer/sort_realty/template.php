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


<div class="bottom-filter-bb realtors_sort_block">

	<div class="left-bot-filter-bb">
	<div class="object-bb catalog_counts_wrapper"><?$APPLICATION->ShowViewContent("all_count");?></div>
	<p>Сортировка:</p>
		<?
		$arSorts = \Morealty\Settings::$REALTOR_SORT_PARAMS;
		if ($arSorts)
		{
			?>
			<div class="select-bb">
				<select name="sort" id="sort">
					<?
					foreach ($arSorts as $arSort)
					{
						$bCurrent = \Morealty\Realtor::getCurrentSort() == $arSort["SORT"];
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