<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>

<?
//view-tiles
$itemsCount = count($arResult['ITEMS']);
$IBLOCK_ID = $arParams['IBLOCK_ID'];
$link_added_part = ($IBLOCK_ID == 19) ? '' : '/sell';
?>

<div class="list-square newbuilding_squa">
	<?	if ($arResult["ITEMS"] && count($arResult["ITEMS"]) > 0)	{
		?>
			<? $counter = 0; ?>
			<? foreach($arResult['ITEMS'] as $currentItem): ?>
				<?
				$arProperties = $currentItem['PROPERTIES'];
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
				$arProperties['price']['VALUE'] = priceDigit($price);
				//price1m view
				$price1m = (string) $arProperties['price_1m']['VALUE'];
				$arProperties['price_1m']['VALUE'] = priceDigit($price1m);
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
			<div class="item-propos">
				<input type="hidden" id="item-id" value="<?=$currentItem['ID']?>">
		
				<div class="top-propos">
					<div class="img-propos last-objectsblock">
					<?if(count($arProperties['photo_gallery']) > 0):?>
						<div class="slider-agent2">
							
							<? foreach($arProperties['photo_gallery'] as $photo): ?>
									
								<? 	$photoLink = AddWaterMarkResized($photo["VALUE"],235,161,CImg::M_PROPORTIONAL); ?>
								<div class="slide"><a href="<?=$currentItem['DETAIL_PAGE_URL']?>"><img src="<?=$photoLink?>" alt="" /></a></div><!--slide-->
							<? endforeach; ?>
							
						</div><!--slider-agent-->
					<?else:?>
						<div class="slide img-grey"><a href="<?=$currentItem['DETAIL_PAGE_URL']?>"><div class="img-grey"></div></a></div><!--slide-->
					<?endif;?>
						<div class="nums-photo"><span><?=count($arProperties['photo_gallery'])?></span></div>
					</div>
					<div class="t-propos"><p class="fix-props"><a href="<?=$link_added_part . $currentItem['DETAIL_PAGE_URL']?>"><?=$currentItem['NAME']?></a></p></div>
				</div><!--top-propos-->
		
				<div class="bot-propos">
					<div class="adress-p"><?=$arProperties['city']['MODIFIED_VALUE']?>, <?=$arProperties['district']['MODIFIED_VALUE']?>, <?=$arProperties['microdistrict']['MODIFIED_VALUE']?>, <?=$arProperties['street']['VALUE']?>
					</div>
		
					<div class="price-p">
		
						от <?= number_format($arProperties['price_flat_min']['VALUE'], 0, '', ' ') ?> <?=$arProperties['currency']['VALUE_XML_ID']?>
						<? if ($arProperties['price_m2_ot']['VALUE'])
						{
							?>
							<div class="price-small">от <?=number_format($arProperties['price_m2_ot']['VALUE'], 0, '', ' ')?> <?=$arProperties['currency']['VALUE_XML_ID']?>/м<sup>2</sup></div>
							<?
						}?>
					</div>
		
					<div class="compl-prop"><?=$arProperties['apartment_complex']['MODIFIED_VALUE']?></div>
				</div><!--bot-propos-->
			</div><!--item-propos-->
			<? $counter++; ?>
			<? endforeach; ?>
		<?
	}
	else 
	{
		ShowError("Ничего не найдено");
	}
	?>

</div>

<?=$arResult["NAV_STRING"]?>
