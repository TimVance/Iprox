<?
use \Morealty\Settings as CSettings;
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
$isFilterSetted = $_REQUEST["set_filter"] = "Y";
?>


<div class="block-filter-bb">
	<form name="<?echo $arParams["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get" class="smartfilter">
		<div class="main-nav-filter">
			<?
			$current = false;
			if ($arResult["IBLOCKS"])
			{
				?>
				<div class="menu-f-bb iblocks_catalog">
					<ul>
						<?
						foreach ($arResult["IBLOCKS"] as $arIblock)
						{
							$name = ($arIblock["FILTER_NAME"])? $arIblock["FILTER_NAME"] : $arIblock["NAME"];
							$linkKey = ($arParams["ISMAP"] === "Y")? "MAP_PAGE_URL" : "LIST_PAGE_URL";
							
							$url = $arIblock["CURRENT"] == "Y"? "javascript:void(0);" : $arIblock[$linkKey];
							if ($arIblock["CURRENT"] == "Y")
							{
								$current = $arIblock;
							}
							?>
								<li <?=($arIblock["CURRENT"] == "Y")? 'class="active"' : "";?>><a data-map="<?=$arIblock["MAP_PAGE_URL"]?>" data-def="<?=$arIblock["LIST_PAGE_URL"]?>" href="<?=$url?>"><?=$name?></a></li>
							<?
						}
						?>
						
					</ul>
				</div>
				<?
			}
			?>
			
			<?
			if ($current && $arParams["ISMAP"] !== "Y" && $arParams["HIDE_MAP"] !== "Y")
			{
				?>
					<div class="view-map-bb"><a href="<?=$current["MAP_PAGE_URL"]?>">Показать на карте</a></div>
				<?
			}
			?>
			
		</div>
		
		<div class="line-fiter-bb">
		
			<?
			foreach ($arResult["ITEMS"] as $arItem)
			{
				if ($arItem['FILTER_HINT'])
				{
					$arItem["NAME"] = $arItem["FILTER_HINT"];
				}
				if ($arItem["DISPLAY_TYPE"] == "B")
				{
					$isHTMLVal = (isset($arItem["VALUES"]["MIN"]["HTML_VALUE"]) && isset($arItem["VALUES"]["MAX"]["HTML_VALUE"])) &&
					(isset($arItem["VALUES"]["MIN"]["HTML_VALUE"]) && $arItem["VALUES"]["MIN"]["HTML_VALUE"] != $arItem["VALUES"]["MIN"]["VALUE"]) || (isset($arItem["VALUES"]["MAX"]["HTML_VALUE"]) && $arItem["VALUES"]["MAX"]["HTML_VALUE"] != $arItem["VALUES"]["MAX"]["VALUE"]);
					
					$isHTMLVal = $isHTMLVal && strlen($arItem["VALUES"]["MIN"]["HTML_VALUE"]) > 0 && strlen($arItem["VALUES"]["MAX"]["HTML_VALUE"]) > 0;
					$isItPrice = ($arItem["CODE"] == "price");
					$isItSquare = ($arItem["CODE"] == "square");
					$CurMax = ($arItem["VALUES"]["MAX"]["HTML_VALUE"]);
					$CurMin = ($arItem["VALUES"]["MIN"]["HTML_VALUE"]);
					$placeHolderMin = $arItem["NAME"]." от";
					$placeHolderMax = "до";
					if ($isItPrice)
					{
						$placeHolderMin = "Цена от";
						$placeHolderMax = "до, руб.";
					}
					else if ($isItSquare)
					{
						$placeHolderMin = "Площадь от";
						$placeHolderMax = "до м²";
					}
					//$CurMax = $arItem["VALUES"]["MAX"]["HTML_VALUE"];
					//$CurMin = $arItem["VALUES"]["MIN"]["HTML_VALUE"];
					?>
					<div class="fields-price-bb <?=$arItem["FIELD_CLASS"]?> <?if ($isItPrice)echo("pricefield")?> number-fields">
						<div class="inp-bb"><input type="text" value="<?=$CurMin?>" name="<?=$arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>" placeholder="<?=$placeHolderMin?>">
						<?
						if ($isItPrice)
						{
							?>
								<div class="die-ff"><ul></ul></div>
							<?
						}
						?>
						</div>
						<div class="inp-bb"><input type="text" value="<?=$CurMax?>" name="<?=$arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>" placeholder="<?=$placeHolderMax?>">
						<?
						if ($isItPrice)
						{
							?>
								<div class="die-ff"><ul></ul></div>
							<?
						}
						?>
						</div>
					</div>
					<?unset($isItPrice,$isItSquare,$CurMax,$CurMin,$isHTMLVal);
				}
				elseif ($arItem["DISPLAY_TYPE"] == "P")
				{
					
					if (count($arItem["VALUES"]) <= 0)
					{
						continue;
					}
					
					
					?>
					<div class="select-check-bb">
						<span><?=$arItem["NAME"]?>:</span>
						
						<div class="panel-check-bb">
							<div class="in-check-bb customP">
								<?
								if (count($arItem["VALUES"]) >= 1)
								{
								?>
								<ul>
									<?
									$prevIsChild = false;
									foreach ($arItem["VALUES"] as $key =>  $arValue)
									{
										$next = $arItem["VALUES"][($key + 1)];
										$isNextChild = $next["CHILD"] == "Y";
										$isChild = $arValue["CHILD"] == "Y";
										if ($prevIsChild && !$isChild)
										{
											$prevIsChild = false;
											?>
											</ul>
										</li>
											<?
										}
										?>
											<li  class="item_root_checkbox">
											<?
											if (!$isChild)
											{
												?>
												<p>
												<?										
											}
											?>
												<input id="<?=$arValue["CONTROL_ID"]?>" name="<?=$arValue["CONTROL_NAME"]?>" type="checkbox" <?=($arValue["CHECKED"])? 'checked="checked"' : ""?> value="<?=$arValue["HTML_VALUE"]?>"/>
												<label for="<?=$arValue["CONTROL_ID"]?>"><?=$arValue["VALUE"]?></label>
											<?
											if (!$isChild)
											{
												?>
												</p>
												<?										
											}
										
										if (!$isChild && $isNextChild)
										{
											?>
											<ul>
											<?
											continue;
										}
										?>
											</li>
										<?
										if ($isChild)
										{
											$prevIsChild = true;
										}
									}
									if ($prevIsChild)
									{
										?>
										</ul>
										</li>
										<?
									}
									?>
								</ul>
								<?
								}
								else 
								{
									?>
										<p>Нет значений</p>
									<?
								}
								?>
							</div>
						</div>
					</div>
					<?
				}
			}
			?>
		</div>
		<div class="hidden">
			<input type="submit" id="del_filter" name="del_filter" value="Y"/>
			<input type="submit" id="set_filter" name="set_filter" value="Y"/>
		</div>
	</form>
</div>
		
		