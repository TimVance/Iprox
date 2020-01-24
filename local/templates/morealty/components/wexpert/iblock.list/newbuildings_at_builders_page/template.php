<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<?
?>

<?if(count($arResult['ITEMS']) > 0):?>
<div class="object-sale">
	<div class="tit-spec">Объекты в продаже <span>(<?=count($arResult['ITEMS'])?>)</span></div>

	<div class="line-propos">
		<?foreach($arResult['ITEMS'] as $arItem):?>
			<?
			$arProperties = $arItem['PROPERTIES'];
			$res = CIBlockElement::GetByID($arProperties['city']['VALUE']);
			//city
			if($ar_res = $res->GetNext()){
				$arProperties['city']['MODIFIED_VALUE'] = $ar_res['NAME'];
			}
			$res = CIBlockElement::GetByID($arProperties['district']['VALUE']);
			//district
			if($ar_res = $res->GetNext()){
				$arProperties['district']['MODIFIED_VALUE'] = $ar_res['NAME'];
			}
			$res = CIBlockElement::GetByID($arProperties['microdistrict']['VALUE']);
			//microdistrict
			if($ar_res = $res->GetNext()){
				$arProperties['microdistrict']['MODIFIED_VALUE'] = $ar_res['NAME'];
			}
			//apartment_complex
			$res = CIBlockElement::GetByID($arProperties['apartment_complex']['VALUE']);
			if($ar_res = $res->GetNext()){
				$arProperties['apartment_complex']['MODIFIED_VALUE'] = $ar_res['NAME'];
			}
			//currency
			switch($arProperties['currency']['VALUE_XML_ID']) {
				case 'rubles' : $arProperties['currency']['VALUE_XML_ID'] = 'Руб.'; break;
				case 'dollars': $arProperties['currency']['VALUE_XML_ID'] = '$'; break;
				case 'euro'   : $arProperties['currency']['VALUE_XML_ID'] = 'Евро'; break;
				default: ; // ok
			}
			//price view
			$price = (string) $arProperties['price']['VALUE'];
			$priceLen = strlen($price);
			if($priceLen >= 5) {
				switch($priceLen) {
					case 5: $price = substr($price, 0, 2) . ' ' . substr($price, 2, 3); break;
					case 6: $price = substr($price, 0, 3) . ' ' . substr($price, 3, 3); break;
					case 7: $price = substr($price, 0, 1) . ' ' . substr($price, 1, 3) . ' ' . substr($price, 4, 3); break; //
					case 8: $price = substr($price, 0, 2) . ' ' . substr($price, 2, 3) . ' ' . substr($price, 5, 3); break;
					case 9: $price = substr($price, 0, 3) . ' ' . substr($price, 3, 3) . ' ' . substr($price, 6, 3); break;
					case 10: $price = substr($price, 0, 1) . ' ' . substr($price, 1, 3) . ' ' . substr($price, 4, 3) . ' ' . substr($price, 7, 3); break;
					default: break;
				}
				$arProperties['price']['VALUE'] = $price;
			}
			//price1m view
			$price1m = (string) $arProperties['price_1m']['VALUE'];
			$priceLen = strlen($price1m);
			if($priceLen >= 4) {
				switch($priceLen) {
					case 4: $price1m = substr($price1m, 0, 1) . ' ' . substr($price1m, 1, 3); break;
					case 5: $price1m = substr($price1m, 0, 2) . ' ' . substr($price1m, 2, 3); break;
					case 6: $price1m = substr($price1m, 0, 3) . ' ' . substr($price1m, 3, 3); break;
					default: break;
				}
				$arProperties['price_1m']['VALUE'] = $price1m;
			}
			//ID
			$currentItemID = $currentItem['ID'];
			//favorites IDs
			$arSort = array('SORT' => 'ASC');
			$arFilter = array('USER_ID' => $USER->GetID());
			$rsItems = CFavorites::GetList($arSort, $arFilter);
			$arID = array();
			while ($Res = $rsItems->Fetch()) {
				$arID[] = $Res['URL'];
			}
			?>

			<div class="spec-propos">
				<div class="bl-spec">
					<div class="head-spec">
						<a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a>
						<span class="adress-agent">
							<?=$arProperties['city']['MODIFIED_VALUE']?>,
							<?=$arProperties['district']['MODIFIED_VALUE']?>,
							<?=$arProperties['microdistrict']['MODIFIED_VALUE']?>,
							<?=$arProperties['street']['VALUE']?>
						</span>
					</div>

					<div class="body-spec">
						<div class="img-body-spec">
							<?if(count($arProperties['photo_gallery']) > 0):?>
								<? 	$photoLink = addWatermark($arProperties['photo_gallery'][0]['VALUE']); ?>
								<div><img src="<?=$photoLink?>" alt="" /></div>
							<?else:?>
								<div class="slide img-grey"><div class="img-grey"></div></div>
							<?endif;?>
						</div>
						<div class="desc-body-spec">
							<ul>
								<?foreach($arParams['SHOW_PROPERTY'] as $keyCode=>$code):?>
									<?
									if (is_array($code))
									{
										if ($arProperties[$keyCode])
										{
											$property = $arProperties[$keyCode];
										}
										else
										{
											$property  = array("NAME" => $code["NAME"], "VALUE"	=> $code["VALUE"]);
										}
									}
									else 
									{
										$property = $arProperties[$code];
									}
									$square_dimension = "м<sup>2</sup>";
									$metres = 'м';
									?>

									<? //если список ?>
									<?if(!empty($property['VALUE_ENUM'])):?>
										<li><span><?=$property['NAME']?></span> <strong><?=$property['VALUE_ENUM']?></strong></li>

										<? //если HTML поле ?>
									<?elseif($property['VALUE']['TYPE'] == 'HTML'):?>
										<li><span><?=$property['NAME']?></span> <strong><?=$property['VALUE']['TEXT']?></strong></li>

										<? //если текст, с исключениями(возможно + размерность) ?>
									<?elseif(!empty($property['VALUE'])):?>

										<?
										if(is_numeric($property['VALUE'])) {
											$property['VALUE'] = number_format($property['VALUE'], 0, '', ' ');
										}

										if($code == 'distance_to_sea') {
											$property['VALUE'] .= ' ' . $arProperties['dimension_distance_to_sea']['VALUE_ENUM'];
										} elseif($code == 'square') {
											$property['VALUE'] .= ' ' . $square_dimension;
										} elseif($code == 'square_do' || $code == 'price_m2_do') {
											continue;
										} elseif($code == 'square_ot') {
											$property['NAME'] = str_replace("от", '', $property['NAME']);
											$property['VALUE'] = $property['VALUE'] .  ' ' . $square_dimension . ' - ' . $arProperties['square_do']['VALUE'] . ' ' . $square_dimension;
										} elseif($code == 'price_m2_ot') {
											$property['NAME'] = str_replace("от", '', $property['NAME']);
											$property['VALUE'] = 'от ' . $property['VALUE']." ".$arProperties['currency']['VALUE_XML_ID'];
										} elseif($code == 'price_flat_min') {
											$property['VALUE'] .= ' ' . $arProperties['currency']['VALUE_ENUM'];
										} elseif($code == 'ceilings_height') {
											$property['VALUE'] .= ' ' . $metres;
										}

										?>
									<li class="line-table"><span class="line-table-element"><?=$property['NAME']?></span> <strong class="line-table-element"><?=$property['VALUE']?></strong></li>

										<? //если множественный чекбокс ?>
									<?elseif(count($property) > 1 && !empty($property[0]['NAME'])):?>
										<li class="line-table">
											<span class="line-table-element"><?=$property[0]['NAME']?></span>
											<?$val = "";?>
											<?foreach($property as $prop):?>
												<?$val .= $prop['VALUE_ENUM'] . ', '?>
											<?endforeach;?>
											<strong class="line-table-element"><?=substr($val, 0, -2)?></strong>
										</li>

										<? //если чекбокс не проставлен ?>
									<?else:?>
										<?if($property['PROPERTY_TYPE'] == "L"):?>
											<li class="line-table"><span class="line-table-element"><?=$property['NAME']?></span> <strong class="line-table-element">нет</strong></li>
										<?else:?>
											<li class="line-table"><span class="line-table-element"><?=$property['NAME']?></span> <strong class="line-table-element">не указано</strong></li>
										<?endif;?>
									<?endif;?>
								<?endforeach;?>
							</ul>
						</div>
					</div>
				</div>
			</div>
		<?endforeach;?>
	</div>
</div>
<?endif;?>