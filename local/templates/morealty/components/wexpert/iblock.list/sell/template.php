<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>

<?
//view-list
$itemsCount = count($arResult['ITEMS']);
$IBLOCK_ID = $arParams['IBLOCK_ID'];
//$link_added_part = ($IBLOCK_ID == 19) ? '' : '/sell';
$link_added_part = "";
$dopPadding = ($arResult['NAV_RESULT']['PAGES_COUNT'] == 1)? true: false;
?>
<div class="list-agents list-agents2 <?if($dopPadding)echo("dop-padding-lent")?>">
	<? $counter = 0; ?>
	<? foreach($arResult['ITEMS'] as $currentItem): ?>
		<?
		$arProperties = $currentItem['PROPERTIES'];
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
		<div class="item-agents">
			<input type="hidden" id="item-id" value="<?=$currentItem['ID']?>">
			<div class="img-agent">
				<div class="gal-agent">
					<div class="slider-agent">
						<?if(count($arProperties['photo_gallery']) > 0):?>
							<? foreach($arProperties['photo_gallery'] as $photo): ?>
								<? 	$photoLink = AddWaterMarkResized($photo["VALUE"],322,242,CImg::M_PROPORTIONAL); ?>
								<div class="slide"><img src="<?=$photoLink?>" alt="" /></div><!--slide-->
							<? endforeach; ?>
						<?else:?>
							<div class="slide img-grey"><div class="img-grey"></div></div><!--slide-->
						<?endif;?>
					</div><!--slider-agent-->
					<div class="nums-photo"><span><?=count($arProperties['photo_gallery'])?></span></div>
				</div>
			</div>
			<div class="desc-agents">
				<div class="but-favor check-favor" data-element="<?=$currentItem['ID']?>"><a href="javascript:void(0)"></a></div>
				<div class="t-agents"><a href="<?=$link_added_part . $currentItem['DETAIL_PAGE_URL']?>"><?=$currentItem['NAME']?></a></div>
				<div class="adress-agent">
					<?=\SiteTemplates\Object::buildAddress($arProperties);?>
				</div>
				<div class="info-agents">
					<div class="params-propos">
						<?/* ?>
						<div class="list-prm">
							<ul>

								<? 
								$PropsTemplate = array(
										"square"	=> array("POST_FIX"=>" м<sup>2</sup>","NAME"=>"Общая площадь"),
										"floor"		=> array("NAME"=>"Этаж"),
										"summary_buildings_square" => array("POST_FIX"=>" м<sup>2</sup>","NAME"=>"Общая площадь строений"),
										"number_of_storeys" => array('NAME'=>"Этажность"),
										"sector_square"		=> array("NAME"=>"Площадь участка","OTHER_OPTION"=>array("NAME"=>"Размерность площади участка","CODE"=>"dimension","OUTPUT"=>"VALUE_XML_ID")),
										"number_of_bedrooms"=> array("NAME"=>"Количество спален"),
										"garage"	=> array("NAME"=>"Гараж","BOOL"=>true),
										"decoration"=> array("NAME"=>"Отделка","OUTPUT"=>"VALUE_XML_ID"),
										"wc"		=> array("NAME"=>"Санузел","OUTPUT"=>"VALUE_XML_ID"),
										"have_loggia"=> array("NAME"=>"Лоджия","BOOL"=>true),
										"have_balcony"=> array("NAME"=>"Балкон","BOOL"=>true),
										"have_phone"=> array("NAME"=>"Телефон","BOOL"=>true),
										"have_furniture"=> array("NAME"=>"Продается с мебелью","BOOL"=>true),
										"can_mortgage"=> array("NAME"=>"Возможна ипотека","BOOL"=>true),
										
										);
								?>
								<? 
								foreach ($PropsTemplate as $PropCode => $PropOptions)
								{
									if (!$arProperties[$PropCode]["VALUE"])
										continue;
									
									
									$StringVal = "";
									
									if ($PropOptions["BOOL"])
									{
											$StringVal = "Да";
										
									}
									else if ($PropOptions["OUTPUT"] == "VALUE_XML_ID")
									{
										$StringVal = $arProperties[$PropCode]["VALUE_ENUM"];
									}
									else
									{
										$StringVal = $arProperties[$PropCode]["VALUE"];
									}
									if ($PropOptions["OTHER_OPTION"])
									{
										if ($PropOptions["BOOL"])
										{
											$StringVal .= " Да";
												
										}
										else if ($PropOptions["OUTPUT"] == "VALUE_XML_ID")
										{
											my_print_r();
											$StringVal .= " " . $arProperties[$PropOptions["OTHER_OPTION"]["CODE"]]["VALUE_ENUM"];
										}
										else
										{
											$StringVal .= " " .$arProperties[$PropOptions["OTHER_OPTION"]["CODE"]]["VALUE"];
										}
									}
									?>
									<li><?=$PropOptions["NAME"]?> : <strong><?=$StringVal?> <?=$PropOptions["POST_FIX"]?></strong></li>
									<?
								}
								?>
							</ul>
						</div><? */?>
						<?
						if ($currentItem["IBLOCK_ID"] == 7)
						{
							?>
							<?=\SiteTemplates\Object::buildFlatPropsShort($arProperties);?>
							<?
						}
						else if ($currentItem["IBLOCK_ID"] == 10)
						{
							?>
							<?=\SiteTemplates\Object::buildLandPropsShort($arProperties);?>
							<?
						}
						else 
						{
							?>
								<?=\SiteTemplates\Object::buildProps($arProperties);?>
							<?
						}
						?>
						
						
						

						<div class="name-r">
							<?=$arProperties['apartment_complex']['MODIFIED_VALUE']?>
						</div>
					</div><!--params-propos-->  
					<div class="func-propos">
						<div class="price-propos">
						<? 
						if ($arProperties['price']['VALUE'])
						{
							?>
							<p><span><?=$arProperties['price']['VALUE']?> <?=$arProperties['currency']['VALUE_XML_ID']?></span></p>
							<?
						}
						?>
							
							<? if ($arProperties['price_1m']['VALUE'])
							{
								?>
								<p><?=$arProperties['price_1m']['VALUE']?> <?=$arProperties['currency']['VALUE_XML_ID']?>/м<sup>2</sup></p>
								<?
							}
						
							/*else if ($arParams["IBLOCK_ID"] == "11")
							{
								if($arProperties["summary_apartment_square"]['VALUE'])
								{
									?>
									<?= MoneyOutPut(intval(str_replace(" ","",$arProperties['price']['VALUE']))/intval($arProperties["summary_apartment_square"]['VALUE'])) ?> <?= $arProperties['currency']['VALUE_XML_ID'] ?>
									за м²
									<?
								}
								else if($arProperties["square"]['VALUE'])
								{
									?>
									<?= MoneyOutPut(intval(str_replace(" ","",$arProperties['price']['VALUE']))/intval($arProperties["square"]['VALUE'])) ?> <?= $arProperties['currency']['VALUE_XML_ID'] ?>
									за м²
									<?
								}
								
							}*/

							?>
							
						</div>
						<div class="more-but"><a href="<?=$link_added_part .  $currentItem['DETAIL_PAGE_URL']?>">Подробнее об объекте</a></div>
					</div><!--func-propos-->
				</div><!--info-agents-->
			</div><!--desc-agents-->
		</div><!--item-agents-->
		<? /*
		<? if((($counter + 1) == $itemsCount / 2) || $itemsCount == 1): ?>
			<div class="item-ads"><a href="#"><img src="<?=SITE_TEMPLATE_PATH?>/images_tmp/ban-partner.jpg" alt="" /></a></div>
		<? endif; ?>
        */ ?>
		<? $counter++; ?>
	<? endforeach; ?>
</div><!--list-agents-->

<?=$arResult["NAV_STRING"]?>




