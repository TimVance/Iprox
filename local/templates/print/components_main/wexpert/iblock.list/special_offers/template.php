<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>

<?
//view-list
$itemsCount = count($arResult['ITEMS']);
$IBLOCK_ID = $arParams['IBLOCK_ID'];
//$link_added_part = ($IBLOCK_ID == 19) ? '' : '/sell';
$link_added_part = "";
?>

<?if(count($arResult['ITEMS'])  > 0):?>
<div class="b-propos b-propos2">
	<div class="t-emploe">Специальные предложения</div>
	<? $APPLICATION->IncludeComponent('wexpert:includer', "sort_in_sell", array('OK' => 'no',"NOT_RELOAD_ON_CHANGE_TILE"=>"Y"), false); ?>

<div id="special-offers">
<? 
if ($_REQUEST["ajax"] == "Y")
{
	$APPLICATION->RestartBuffer();
}
?>
	<div class="list-agents list-agents2">
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
								<?// 	$photoLink = addWatermark($photo['VALUE']); 
								$photoLink = AddWaterMarkResized($photo['VALUE'], 322, 242 , CImg::M_CROP); 
								?>
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
					<?=$arProperties['city']['MODIFIED_VALUE']?>,
					<?=$arProperties['district']['MODIFIED_VALUE']?>,
					<?=$arProperties['microdistrict']['MODIFIED_VALUE']?>,
					<?=$arProperties['street']['VALUE']?>
				</div>
				<div class="info-agents">
					<div class="params-propos">
						<div class="list-prm">
							<ul>
								<li>Общая площадь: <strong><?=$arProperties['square']['VALUE']?></strong> м<sup>2</sup></li>
								<li>Этаж: <strong><?=$arProperties['floor']['VALUE']?></strong></li>
								<li>Срок сдачи: <strong>сдан</strong></li>
								<li>До моря: <strong>200 м</strong></li>
								<li>Отделка: <strong>с отделкой и мебелью</strong></li>
							</ul>
						</div><!--list-prm-->

						<div class="name-r">
							<?=$arProperties['apartment_complex']['MODIFIED_VALUE']?>
						</div>
					</div><!--params-propos-->
					
					<div class="func-propos">
						<div class="price-propos"><?
						 if ($arProperties['price']['VALUE'] && $arProperties['currency']['VALUE_XML_ID'])
						{
							?><p><span><?=$arProperties['price']['VALUE']?> <?=$arProperties['currency']['VALUE_XML_ID']?></span></p><?
						}?><? if ($arProperties['price_1m']['VALUE'] && $arProperties['currency']['VALUE_XML_ID'])
						{
							?><p><?=$arProperties['price_1m']['VALUE']?> <?=$arProperties['currency']['VALUE_XML_ID']?>/м<sup>2</sup></p><?
						}
							
						?></div>
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
</div>
<?=$arResult["NAV_STRING"]?>
<? 
if ($_REQUEST["ajax"] == "Y")
{
	die();
}
?>
</div>

<?endif;?>
</div>
