<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/* @var $this CBitrixComponentTemplate */

if (count($arResult['ITEMS']) <= 0) {
	return;
}

//my_print_r($arResult['ITEMS']);
?>
<div class="list-pakets">
	<ul>
		<?
		/*$iblockForms = array(
			7 => 'квартира|квартиры|квартир',
			11 => 'коммерческая недвижимость|коммерческие недвижимости|коммерческой недвижимости',
			8 => 'дом|дома|домов',
			9 => 'поселок|поселка|поселков',
			10 => 'участок|участка|участков',
			12 => 'торговый цент|торговых центра|торговых центов',
			13 => 'инвестиционный проект|инвестиционных проекта|инвестиционных проектов'
		);*/

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
				<div class="price-pakets">
					<?=number_format($item['PROPERTIES']['price']['VALUE'], 0, '.', ' ')?> руб.
				</div>
				<div class="condit-tarif">
					<div class="shelf">Действует
						<?=$item['PROPERTIES']['long_days']['VALUE']?> <?=Suffix($item['PROPERTIES']['long_days']['VALUE'], 'день|дня|дней')?>
						с момента покупки
					</div>
				</div>
				<div class="but-paket"><a class="inline" href="#pop<?=$item['ID']?>">Купить</a></div>
			</li>

			<?$this->SetViewTarget('PACKAGES_DETAIL_POP');?>
				<div id="pop<?=$item['ID']?>" class="pop pop3">
					<div class="close"></div>
					<div class="t-pop t-pop2">Пакет «<?=$item['NAME']?>»</div>
					<div class="property-pop">
						<ul>
							<?
							$k = 0;
							foreach ($cntIblocks as $iblock => $cnt) {?>
								<li><span><?=$cnt?></span> <?=Suffix($cnt, $iblockForms[$iblock])?></li>
								<?
							}?>
						</ul>
					</div>
					<div class="big-price"><?=number_format($item['PROPERTIES']['price']['VALUE'], 0, '.', ' ')?> руб.</div>
					<div class="acts">Действует
						<?=$item['PROPERTIES']['long_days']['VALUE']?> <?=Suffix($item['PROPERTIES']['long_days']['VALUE'], 'день|дня|дней')?>
						с момента покупки</div>
					<div class="but-pop"><button type="submit" data-package="<?=$item['ID']?>" class="goodbuy">Купить</button></div>
				</div><!--pop-->
			<?$this->EndViewTarget();?>

		<?}?>
	</ul>
</div><!--list-pakets-->

<?=$arResult['NAV_STRING']?>


