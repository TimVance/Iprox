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

$arMainItems = array_filter($arResult["ITEMS"], function($a){
	return $a["ADDITIONAL"] !== "Y";
});
$arAdditinalItems = array_filter($arResult["ITEMS"], function($a){
	return $a["ADDITIONAL"] === "Y";
});

if (!function_exists("main_page_filter_generateProps"))
{
	function main_page_filter_generateProps($arProps, $arValute)
	{
		foreach ($arProps as $arItem)
		{
			if ($arItem['FILTER_HINT'])
			{
				$arItem["NAME"] = $arItem["FILTER_HINT"];
			}
			if ($arItem["DISPLAY_TYPE"] == "P")
			{
				?>
					<div class="radio-rooms">
					<p><?=$arItem["NAME"]?></p>
						<ul>
							<?
							foreach ($arItem["VALUES"] as $arValue)
							{
								?>
								<li class="defaultP">
									<input value="<?=$arValue["HTML_VALUE_ALT"]?>" id="<?=$arValue["CONTROL_ID"]?>" name="<?=$arValue["CONTROL_NAME_ALT"]?>" type="radio" />
									<label for="<?=$arValue["CONTROL_ID"]?>"><?=$arValue["VALUE"]?></label>
								</li>
								<?
							}
							?>
							
						</ul>
					</div>
				<?
			}
			else if ($arItem["DISPLAY_TYPE"] == "B")
			{
				$isHTMLVal = (isset($arItem["VALUES"]["MIN"]["HTML_VALUE"]) && isset($arItem["VALUES"]["MAX"]["HTML_VALUE"])) &&
				(isset($arItem["VALUES"]["MIN"]["HTML_VALUE"]) && $arItem["VALUES"]["MIN"]["HTML_VALUE"] != $arItem["VALUES"]["MIN"]["VALUE"]) || (isset($arItem["VALUES"]["MAX"]["HTML_VALUE"]) && $arItem["VALUES"]["MAX"]["HTML_VALUE"] != $arItem["VALUES"]["MAX"]["VALUE"]);
				$isItPrice = ($arItem["CODE"] == "price");
				$isItSquare = ($arItem["CODE"] == "square");
				$CurMax = ($arItem["VALUES"]["MAX"]["HTML_VALUE"])? $arItem["VALUES"]["MAX"]["HTML_VALUE"]: $arItem["VALUES"]["MAX"]["VALUE"];
				$CurMin = ($arItem["VALUES"]["MIN"]["HTML_VALUE"])? $arItem["VALUES"]["MIN"]["HTML_VALUE"]: $arItem["VALUES"]["MIN"]["VALUE"];
				?>
				
				<div class="choice-top-form choice-top-form2">
					<label><?=$arItem["NAME"]?></label>
					<div class="fields-pr">		
						<div class="field-pr">
							<input type="number" value="<?=$arItem["VALUES"]["MIN"]["HTML_VALUE"]?>" <?/* ?>min="<?=$arItem["VALUES"]["MIN"]["VALUE"]?>" max="<?=$arItem["VALUES"]["MAX"]["VALUE"]?>"<?*/?> name="<?=$arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>" placeholder="От">
						</div>
						<div class="field-pr">
							<input type="number" value="<?=$arItem["VALUES"]["MAX"]["HTML_VALUE"]?>" <?/* ?>min="<?=$arItem["VALUES"]["MIN"]["VALUE"]?>" max="<?=$arItem["VALUES"]["MAX"]["VALUE"]?>"<?*/?> name="<?=$arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>" placeholder="До">
						</div>
					</div>
					<?
					if ($isItPrice && $arValute)
					{
						$controlName = false;
						foreach ($arValute["VALUES"] as $arValuteItem)
						{
							$controlName = $arValuteItem["CONTROL_NAME_ALT"];
							break;
						}
						if ($controlName)
						{
							?>
							<div class="sel-pr">
								<select name="<?=$controlName?>">
									<?
									foreach ($arValute["VALUES"] as $arValuteItem)
									{
										?>
											<option value="<?=$arValuteItem["HTML_VALUE_ALT"]?>"><?=$arValuteItem['VALUE']?></option>
										<?
									}
									?>
								</select>
							</div>
							<?
						}
					}
					?>
				</div>
				
				<?
			}
			?>
			
			<?
		}
	}
}

$isAdditionalProps = (bool) $arAdditinalItems && count($arAdditinalItems) > 0;
?>
<form name="<?echo $arParams["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get" class="main_page_filter">
	<?foreach($arResult["HIDDEN"] as $arItem):?>
	<input type="hidden" name="<?echo $arItem["CONTROL_NAME"]?>" id="<?echo $arItem["CONTROL_ID"]?>" value="<?echo $arItem["HTML_VALUE"]?>" />
	<?endforeach;?>
	
	
	<?
	if ($arMainItems && count($arMainItems) > 0)
	{
		?>
			<div class="cont-rooms">
				<div class="tab-room">
					<?
					main_page_filter_generateProps($arMainItems, $arResult['VALUTE']);
					?>
				</div>
			</div>
		<?
	}
	
	if ($arAdditinalItems && count($arAdditinalItems) > 0)
	{
		?>
			<div class="add-options-forms">
				<?main_page_filter_generateProps($arAdditinalItems, $arResult['VALUTE']);?>
			</div>
		<?
	}
	?>

	<div class="bot-forms">
		<div class="but-choice"><button type="submit" name="set_filter" value="Y">Подобрать</button></div>
		<div class="view-map send_map_element"><a href="javascript:void(0);" data-link="<?=str_replace(array("#IBLOCK_CODE#"), array($arResult["IBLOCK"]['CODE']), $arParams["MAP_LINK"])?>" >Показать на карте</a></div>
		<?
		if ($isAdditionalProps)
		{
			?>
				<div class="more-params"><a href="javascript:void(0);">Дополнительные параметры</a></div>
			<?
		}
		?>
		
	</div>
</form>