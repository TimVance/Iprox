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


<div class="bx-filter catalog_smart_filter">
		<form name="<?echo $arParams["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get" class="smartfilter">
			<?foreach($arResult["HIDDEN"] as $arItem):?>
			<input type="hidden" name="<?echo $arItem["CONTROL_NAME"]?>" id="<?echo $arItem["CONTROL_ID"]?>" value="<?echo $arItem["HTML_VALUE"]?>" />
			<?endforeach;?>
			<div class="filter-line">
				<div class="f-visible">
					<div class="sub_wrap">
						<div class="float_wrapper">
							 <div class="secs">
								<?
								$checkedItems = 0;
				//not prices

				/**
				 * 
				 * Types = P B P 
				 * PROPERTY_TYPE  
				 * L - LIST
				 * E - Element
				 * N - Number
				 */
								foreach($arResult["ITEMS"] as $key=>$arItem)
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
										$CurMax = ($arItem["VALUES"]["MAX"]["HTML_VALUE"])? $arItem["VALUES"]["MAX"]["HTML_VALUE"]: $arItem["VALUES"]["MAX"]["VALUE"];
										$CurMin = ($arItem["VALUES"]["MIN"]["HTML_VALUE"])? $arItem["VALUES"]["MIN"]["HTML_VALUE"]: $arItem["VALUES"]["MIN"]["VALUE"];
										//$CurMax = $arItem["VALUES"]["MAX"]["HTML_VALUE"];
										//$CurMin = $arItem["VALUES"]["MIN"]["HTML_VALUE"];
										?>
										<div class="sec">
										  <div class="sec-head">
											<span><?=$arItem["NAME"]?></span>
												<strong><?
												if ($isHTMLVal)
												{
													$checkedItems++;
													if ($isItPrice)
													{
														?>
														от <?=number_format($arItem["VALUES"]["MIN"]["HTML_VALUE"],0," "," ")?>
														руб.
														
														до <?=number_format($arItem["VALUES"]["MAX"]["HTML_VALUE"],0," "," ")?>
														руб.
														<?
													}
													else if ($isItSquare)
													{
														?>
														от <?=$arItem["VALUES"]["MIN"]["HTML_VALUE"]?>
														м<sup>2</sup>
														
														до <?=$arItem["VALUES"]["MAX"]["HTML_VALUE"]?>
														м<sup>2</sup>
														<?
													}
													else if ($arItem["POSTFIX"])
													{
														?>от <?=$arItem["VALUES"]["MIN"]["HTML_VALUE"]?> <?=$arItem["POSTFIX"]?> до <?=$arItem["VALUES"]["MAX"]["HTML_VALUE"]?> <?=$arItem["POSTFIX"]?>
														<? 
													}
													else {
													?>от <?=$arItem["VALUES"]["MIN"]["HTML_VALUE"]?> до <?=$arItem["VALUES"]["MAX"]["HTML_VALUE"]?>
													<? 
													}
													
												}
												?>
												</strong>
											
										  </div>
										  <div class="form slide-pop">
											<?/* ?><span class="label"><?=$arItem["NAME"]?></span><?*/ ?>
											  <div class="field <?=$arItem["FIELD_CLASS"]?> <?if ($isItPrice)echo("pricefield")?> number-fields">
												<input type="number" value="<?=$CurMin?>" min="<?=$arItem["VALUES"]["MIN"]["VALUE"]?>"  name="<?=$arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>" placeholder="От">
												<input type="number" value="<?=$CurMax?>" min="<?=$arItem["VALUES"]["MIN"]["VALUE"]?>"  name="<?=$arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>" placeholder="До">
											  </div>
											  <?if ($isItSquare)
											  {
											  	?>
											  		<span class="postfix">м<sup>2</sup></span>
											  	<?
											  }
											  else if ($isItPrice)
											  {
											  	?>
											  		<span class="postfix">руб.</span>
											  	<?
											  }
											  else if ($arItem["POSTFIX"])
											  {
											  	?>
											  		<span class="postfix"><?=$arItem["POSTFIX"]?></span>
											  	<?
											  }
											  ?>
											  
												<a class="pick" href="javascript:void(0);">Подобрать</a>
											</div>
										</div>
										<?
										unset($isItPrice,$isItSquare,$CurMax,$CurMin,$isHTMLVal);
									}
									elseif ($arItem["DISPLAY_TYPE"] == "P")
									{
										
										$ControlName = false;
										$isCheched = false;
										$arChecked = array();
										if (count($arItem["VALUES"]) <= 1)
										{
											continue;
										}
										foreach ($arItem["VALUES"] as $arValue)
										{
											//my_print_r($arValue);
											if ($arValue["CONTROL_NAME_ALT"])
											{
												$ControlName = $arValue["CONTROL_NAME_ALT"];
												
											}
											if ($arValue["CHECKED"])
											{
												$arChecked[] = $arValue["VALUE"];
												$isCheched = true;
											}
										}
										
										if ($isCheched)
										{
											$checkedItems++;
										}
										if (!$ControlName) continue;
											
										?>
										
										<div class="sec">
											<div class="sec-head">
												<span><?=$arItem["NAME"]?></span>
												<?
												if ($isCheched)
												{
													?>
													<strong><?=implode(", ", $arChecked)?></strong>
													<?
												}
												?>
											</div>
											<div class="form slide-pop">
												<?/* ?><span class="label"><?=$arItem["NAME"]?></span><?*/?>
												<div class="field">
													<select id="<?=$arItem["CODE"]?>" name="<?=$ControlName?>">
													<option <?if (!$isCheched){echo("selected");} ?> value="">Все</option>
													<? foreach ($arItem["VALUES"] as $arValue)
													{
														
														?>
														
															<option <? if ($arValue["CHECKED"]){echo("selected");}?> value="<?=$arValue["HTML_VALUE_ALT"]?>"><?=$arValue["VALUE"]?></option>
														
														<?
													}?>
													</select>
												</div>
												<a class="pick" href="javascript:void(0);">Подобрать</a>
											</div>
										</div>
										
										<?
									}
									elseif ($arItem["DISPLAY_TYPE"] == "CS")
									{
										?>
										
										<div class="sec">
											<div class="sec-head">
												<span><?=$arItem["NAME"]?></span>
												<?
												if ($arItem["HTML_VALUE"])
												{
													$checkedItems++;
													?>
													<strong><?=$arItem["HTML_VALUE"]?></strong>
													<?
												}
												?>
											</div>
											<div class="form slide-pop">
												<?/* ?><span class="label"><?=$arItem["NAME"]?></span><?*/?>
												<div class="field">
													<input name="<?=$arItem["FIELD_NAME"]?>" id="<?=$arItem["FIELD_ID"]?>" type="text" value="<?=$arItem["HTML_VALUE"]?>">
												</div>
												<a class="pick" href="javascript:void(0);">Подобрать</a>
											</div>
										</div>
										
										<?
									}
								}
								?>
							</div>
							<?
							if ($checkedItems > 0)
							{
								?>
									<a href="javascript:void(0);" class="more filter-cancel">Сбросить все фильтры</a>
								<?
							}
							?>
						</div>
					</div>
				</div>
			</div>
			<div class="clb">
			</div>
			<div class="hidden">
				<input type="submit" id="del_filter" name="del_filter" value="y"/>
				<input type="submit" id="set_filter" name="set_filter" value="y"/>
			</div>
			
		</form>
</div>