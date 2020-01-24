<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/* @var $this CBitrixComponentTemplate */

if (count($arResult['ITEMS']) <= 0) {
	return;
}

//my_print_r($arResult['ITEMS']);
?>
<p>Так же можно купить отдельно объекты</p>
<div class="list-pakets">
	<ul>
		<?
		$iblockForms = array(
			7 => 'квартира|квартиры|квартир',
			11 => 'коммерческая недвижимость|коммерческие недвижимости|коммерческой недвижимости',
			8 => 'дом|дома|домов',
			9 => 'поселок|поселка|поселков',
			10 => 'участок|участка|участков',
			12 => 'торговый цент|торговых центра|торговых центов',
			13 => 'инвестиционный проект|инвестиционных проекта|инвестиционных проектов'
		);

		foreach ($arResult['ITEMS'] as $item) {
			$cntIblocks = array();
			foreach ($item['PROPERTIES'] as $k => $v) {
				if (strpos($k, 'count_') === false || trim($v['VALUE']) == '') {
					continue;
				}
				$iblock = substr($k, strlen('count_'));
				$cntIblocks[$iblock] = (int) $v['VALUE'];
			}
			?>
			<li>
				<div class="tit-pakets"><p><?=$item['NAME']?></p></div>
				<div class="num-pakets">
				</div>
				<div class="price-pakets">
					<?=$item["PRICES"]["base"]["PRINT_VALUE"];?> за шт.
				</div>
				<div class="but-paket"><a class="inline" href="#pop<?=$item['ID']?>">Купить</a></div>
			</li>

			<?$this->SetViewTarget('PACKAGES_DETAIL_POP');?>
				<div id="pop<?=$item['ID']?>" class="pop buy_objects pop3">
					<div class="close"></div>
					<div class="t-pop t-pop2">«<?=$item['NAME']?>»</div>
					<div class="property-pop">
						<label>Количество объектов</label>
						<input class="objects_val" data-ele="<?=$arItem["ID"]?>" value="1" type="number">
					</div>
					
					<div class="big-price"><?=$item["PRICES"]["base"]["PRINT_VALUE"];?></div>
					<div class="but-pop"><button type="submit" data-package="<?=$item['ID']?>" class="buy_objects">Купить</button></div>
				</div><!--pop-->
			<?$this->EndViewTarget();?>

		<?}?>
	</ul>
</div><!--list-pakets-->

<?=$arResult['NAV_STRING']?>