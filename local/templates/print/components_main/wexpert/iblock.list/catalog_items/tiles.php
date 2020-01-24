<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>

<?
//view-tiles
$itemsCount = count($arResult['ITEMS']);
$IBLOCK_ID = $arParams['IBLOCK_ID'];
$link_added_part = ($IBLOCK_ID == 19) ? '' : '/sell';

?>

<div class="list-square tiles_new_style">
	<? $counter = 0; ?>
	<? foreach($arResult['ITEMS'] as $currentItem): ?>
	<?
	$arProperties = $currentItem['PROPERTIES'];
	$priceFrom = "";
	if (!$arProperties["price"]["VALUE"] && $arProperties["system_price_from"]["VALUE"])
	{
		$arProperties['price']['VALUE'] = $arProperties["system_price_from"]["VALUE"];
		$priceFrom = "От ";
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
	<div class="item-propos">
		<input type="hidden" id="item-id" value="<?=$currentItem['ID']?>">

		<div class="top-propos">
			<?/* ?><div class="img-propos">
				
					<?if(count($arProperties['photo_gallery']) > 0):?>
						<? 	$photoLink = 'http://' . $_SERVER['HTTP_HOST'] . '/' . AddWaterMarkResized($arProperties['photo_gallery'][0]['VALUE'],235,161,CImg::M_PROPORTIONAL); ?>
						<a href="<?=$currentItem['DETAIL_PAGE_URL']?>"><img src="<?=$photoLink?>" alt="" /></a>
					<?else:?>
						<a href="<?=$currentItem['DETAIL_PAGE_URL']?>"><span class="img-grey"></span></a>
					<?endif;?>
				
			</div><?*/?>
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
			<div class="t-propos"><p><a href="<?=$currentItem['DETAIL_PAGE_URL']?>"><?=$currentItem['NAME']?></a></p></div>
		</div><!--top-propos-->

		<div class="bot-propos">
			<div class="adress-p">
			<?
			$arProperties["district"]["VALUE"] = false;
			?>
				<?=\SiteTemplates\Object::buildAddress($arProperties);?>
			</div>
			
			<?
			if ($arProperties['price']['VALUE'])
			{
				?>
				<div class="price-p"><?=$priceFrom?>
				<?=$arProperties['price']['VALUE']?> <?=$arProperties['currency']['VALUE_XML_ID']?>
					<? 
					if ($arProperties['price_1m']['VALUE'])
					{
						?><div class="price-small"><?=$arProperties['price_1m']['VALUE']?> <?=$arProperties['currency']['VALUE_XML_ID']?> за м²</div><?
					}
					?>
				</div>
				<?
			}
			?>
			
			<?
			/*if ($currentItem["IBLOCK_ID"] == 7 && false)
			{
				?>
					<?=\SiteTemplates\Object::FlatTilesGenerateProps($arProperties);?>
				<?
			}
			else
			{
				?>
					<?=\SiteTemplates\Object::tilesGenerateProps($arProperties);?>
				<?
			}*/
			?>
			
			<?/* ?><div class="params-p">
				<ul>
					<li>Общая площадь: <strong><?=$arProperties['square']['VALUE']?></strong> м<sup>2</sup></li>
					<li>Этаж: <strong><?=$arProperties['floor']['VALUE']?></strong></li>
				</ul>
			</div><? */?>

			<div class="compl-prop"><?=$arProperties['apartment_complex']['MODIFIED_VALUE']?></div>
		</div><!--bot-propos-->
	</div><!--item-propos-->
	<? $counter++; ?>
	<? endforeach; ?>
</div>

