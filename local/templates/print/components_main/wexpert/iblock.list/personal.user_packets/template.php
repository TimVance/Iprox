<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/* @var $this CBitrixComponentTemplate */

if (count($arResult['ITEMS']) <= 0) {
	return;
}

$iblockForms = array(
	7 => 'квартира|квартиры|квартир',
	11 => 'коммерческая недвижимость|коммерческие недвижимости|коммерческой недвижимости',
	8 => 'дом|дома|домов',
	9 => 'поселок|поселка|поселков',
	10 => 'участок|участка|участков',
	12 => 'торговый цент|торговых центра|торговых центов',
	13 => 'инвестиционный проект|инвестиционных проекта|инвестиционных проектов'
);

//my_print_r($arResult['ITEMS']);
?>
<div class="list-pakets">
	<ul>
		<?foreach ($arResult['ITEMS'] as $item) {
			$dt     = new \DateTime('now');
			$dtTo   = new \DateTime('@' . MakeTimeStamp($item['DATE_ACTIVE_TO']));
			$cntDays = $dtTo->diff($dt)->format('%a');
			if ($cntDays <= 0) {
				$cntDays = 0;
			}

			$cntIblocks = array();
			foreach ($item as $k => $v) {
				if (!preg_match('#^PROPERTY_PACKAGE_PROPERTY_COUNT_([0-9]+)_VALUE$#', $k, $m) || trim($v) == '') {
					continue;
				}

				$iblock = $m[1];
				$cntIblocks[$iblock] = (int)$v;
			}
			?>
			<li <?if ($cntDays <= 0) {?>class="no-active"<?}?>>
				<div class="tit-pakets"><p>Комбо 100</p></div>
				<div class="num-pakets">
					<?
					$k = 0;
					foreach ($cntIblocks as $iblock => $cnt) {?>
						<strong><?=$cnt?></strong> <?=Suffix($cnt, $iblockForms[$iblock])?><?if (++$k < count($cntIblocks)) {?>,<?}?>
						<?
					}?>
				</div>
				<div class="price-pakets">
					<?=number_format($item['PROPERTY_PACKAGE_PROPERTY_PRICE_VALUE'], 0, '.', ' ')?> руб.
				</div>
				<div class="condit-tarif">
					<div class="shelf">Активен с <?=FormatDate('d.m.Y', MakeTimeStamp($item['DATE_ACTIVE_FROM']))?> по
						<?=FormatDate('d.m.Y', MakeTimeStamp($item['DATE_ACTIVE_TO']))?>
					</div>
					<div class="days-off">Осталось <?=$cntDays?> <?=Suffix($cntDays, 'день|дня|дней')?></div>
				</div>
				<?if ($cntDays <= 0) {?>
					<div class="archive">в архиве</div>
				<?}?>
			</li>
		<?}?>
	</ul>
</div><!--list-pakets-->

<?=$arResult['NAV_STRING']?>