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

<div class="bx-filter map-filter">
		<form name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get" class="smartfilter">
			<?foreach($arResult["HIDDEN"] as $arItem):?>
			<input type="hidden" name="<?echo $arItem["CONTROL_NAME"]?>" id="<?echo $arItem["CONTROL_ID"]?>" value="<?echo $arItem["HTML_VALUE"]?>" />
			<?endforeach;?>
			<div class="filter-line">
				<div class="f-visible">
					<div class="sub_wrap">
						<div class="float_wrapper">
							 <div class="secs">
							 	<div class="sec">
								  <div class="search search-element">
								  </div>
								  	<input
										class="search"
										type="submit"
										id="set_filter"
										name="set_filter"
										value="<?=GetMessage("CT_BCSF_SET_FILTER")?>"/>
								</div>
								
								<? if (count($arParams["MORE_INFO"]) > 0)
								{
									?>
									<div class="sec">
										  <div class="sec-head">
											<span class="">Тип объекта</span>
										  </div>
										  <div class="form slide-pop">
											<span class="label">Типы объектов</span>
												<?$LinkTemplate = "/sell/map/#CODE#/";?>
												<div class="types-list">
													<ul>
														<? 
														foreach ($arParams["MORE_INFO"] as $Code=>$arInfo)
														{
															if (!$arInfo["TOP"])
															{
																$Link = str_replace("#CODE#", $Code, $LinkTemplate);
																if ($Link == $arParams["CUR_PAGE"]) $Link = "javascript:void(0);";
																?>
																<li>
																<a href="<?=$Link?>">
																<?=$arInfo["NAME"]?>
																</a>
																</li>
																
																<?
															}
															else 
															{
																$Link = "/sell/map/";
																if ($Link == $arParams["CUR_PAGE"]) $Link = "javascript:void(0);";
																?>
																<li>
																<a href="<?=$Link?>">
																<?=$arInfo["NAME"]?>
																</a>
																</li>
																
																<?
															}
								
														}
														?>
													</ul>
												</div>
										  </div>
									</div>
									<?
								}?>

				
								<?//not prices

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
									if ($arItem["DISPLAY_TYPE"] == "B")
									{
										$isHTMLVal = ($arItem["VALUES"]["MIN"]["HTML_VALUE"] && $arItem["VALUES"]["MAX"]["HTML_VALUE"]);
										$isItPrice = ($arItem["CODE"] == "price");
										$isItSquare = ($arItem["CODE"] == "square");
										$CurMax = ($arItem["VALUES"]["MAX"]["HTML_VALUE"])? $arItem["VALUES"]["MAX"]["HTML_VALUE"]: $arItem["VALUES"]["MAX"]["VALUE"];
										$CurMin = ($arItem["VALUES"]["MIN"]["HTML_VALUE"])? $arItem["VALUES"]["MIN"]["HTML_VALUE"]: $arItem["VALUES"]["MIN"]["VALUE"];
										?>
										<div class="sec">
										  <div class="sec-head">
											<span><?=$arItem["NAME"]?> 
												<strong><?
												if ($isHTMLVal)
												{
													if ($isItPrice)
													{
														?><br>
														от <?=number_format($arItem["VALUES"]["MIN"]["HTML_VALUE"],0," "," ")?>
														руб.
														<br>
														до <?=number_format($arItem["VALUES"]["MAX"]["HTML_VALUE"],0," "," ")?>
														руб.
														<?
													}
													else if ($isItSquare)
													{
														?><br>
														от <?=$arItem["VALUES"]["MIN"]["HTML_VALUE"]?>
														м<sup>2</sup>
														<br>
														до <?=$arItem["VALUES"]["MAX"]["HTML_VALUE"]?>
														м<sup>2</sup>
														<?
													}
													else {
													?><br>от <?=$arItem["VALUES"]["MIN"]["HTML_VALUE"]?> до <?=$arItem["VALUES"]["MAX"]["HTML_VALUE"]?>
													<? 
													}
													
												}
												?></strong>
											</span>
										  </div>
										  <div class="form slide-pop">
											<span class="label"><?=$arItem["NAME"]?></span>
											  <div class="field <?if ($isItPrice)echo("pricefield")?> number-fields">
												<input type="number" value="<?=$CurMin?>" min="<?=$arItem["VALUES"]["MIN"]["VALUE"]?>" max="<?=$arItem["VALUES"]["MAX"]["VALUE"]?>" name="<?=$arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>" placeholder="От">
												<input type="number" value="<?=$CurMax?>" min="<?=$arItem["VALUES"]["MIN"]["VALUE"]?>" max="<?=$arItem["VALUES"]["MAX"]["VALUE"]?>" name="<?=$arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>" placeholder="До">
											  </div>
											  <?if ($isItSquare)
											  {
											  	?>
											  		<span>м<sup>2</sup></span>
											  	<?
											  }
											  if ($isItPrice)
											  {
											  	?>
											  		<span>руб.</span>
											  	<?
											  }
											  ?>
											  
											 
										  </div>
										</div>
										<?
										unset($isItPrice,$isItSquare,$CurMax,$CurMin,$isHTMLVal);
									}
									else if ($arItem["DISPLAY_TYPE"] == "P")
									{
										
										$ControlName = false;
										$isCheched = false;
										foreach ($arItem["VALUES"] as $arValue)
										{
											//my_print_r($arValue);
											if ($arValue["CONTROL_NAME_ALT"])
											{
												$ControlName = $arValue["CONTROL_NAME_ALT"];
												
											}
											if ($arValue["CHECKED"])
											{
												$isCheched = true;
											}
										}
										if (!$ControlName) continue;
											
										?>
										
										<div class="sec">
											<div class="sec-head">
												<span><?=$arItem["NAME"]?></span>
											</div>
											<div class="form slide-pop">
												<span class="label"><?=$arItem["NAME"]?></span>
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
											</div>
										</div>
										
										<?
									}
								}
								?>
							</div>
						</div>
					</div>
				</div>
				 <a href="javascript:void(0);" class="more">Еще детали</a>
			</div>
			<div class="clb">
			</div>
		</form>
</div>