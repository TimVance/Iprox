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
		<div class="item-agents item-agents-mini">
			<input type="hidden" id="item-id" value="<?=$currentItem['ID']?>">
			<div class="img-agent">
				<div class="gal-agent">
					<div class="nums-photo"><span><?=count($arProperties['photo_gallery'])?></span></div>
				</div>
			</div>
			<div class="desc-agents">
				
				<div class="t-agents">
					<a href="<?=$link_added_part . $currentItem['DETAIL_PAGE_URL']?>"><?=$currentItem['NAME']?></a>
					<?
					
					if ($arProperties['price']['VALUE'])
					{
						?>
							<div class="item_price"><?=$priceFrom?><?=MoneyOutPut($arProperties['price']['VALUE'])?> <?=$arProperties['currency']['VALUE_XML_ID']?></div>
						<?
					}
					?>
				</div>
				<div class="but-favor check-favor" data-element="<?=$currentItem['ID']?>"><a href="javascript:void(0)"></a></div>
			</div>
		</div>
		<? /*
		<? if((($counter + 1) == $itemsCount / 2) || $itemsCount == 1): ?>
			<div class="item-ads"><a href="#"><img src="<?=SITE_TEMPLATE_PATH?>/images_tmp/ban-partner.jpg" alt="" /></a></div>
		<? endif; ?>
        */ ?>
		<? $counter++; ?>
	<? endforeach; ?>
</div>





