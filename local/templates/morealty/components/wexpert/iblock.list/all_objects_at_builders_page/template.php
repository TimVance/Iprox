<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>

<? ?>

<?if(count($arResult['ITEMS']) > 0):?>
<div class="all-propos">
	<div class="t-emploe">Все предложения застройщика <span>(<?=count($arResult['ITEMS'])?>)</span></div>

	<div class="table-history table-history2">
		<table id="tb1">
			<thead>
				<tr>
					<th><a href="javascript:void(0);">Предложение</a>	<i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
					<th><a href="javascript:void(0);">Площадь</a> <i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
					<th><a href="javascript:void(0);">Этаж/этажей</a> <i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
					<th><a href="javascript:void(0);">Цена</a> <i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
					<th><a href="javascript:void(0);">Цена м<sup>2</sup></a> <i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
					<th><a href="javascript:void(0);">Обновлено</a> <i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
					<th><a href="javascript:void(0);">Просмотров</a> <i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
				</tr>
			</thead>
			<tbody>
			<?foreach($arResult['ITEMS'] as $arItem):?>
				<?$arProperties = $arItem['PROPERTIES']?>
				<tr>
					<td><?=$arItem['NAME']?></td>
					<td><strong><?=$arProperties['square']['VALUE']?></strong></td>
					<td><?=$arProperties['floor']['VALUE']?></td>
					<td><strong><?= number_format($arProperties['price']['VALUE'], 0, '', ' ') ?> <?=$arProperties['currency']['VALUE_ENUM']?></strong></td>
					<td><?= number_format($arProperties['price_1m']['VALUE'], 0, '', ' ') ?> <?=$arProperties['currency']['VALUE_ENUM']?></td>
					<td><?=rus_date("j F Y", strtotime($arItem['TIMESTAMP_X']))?></td>
					<td><?=$arItem['SHOW_COUNTER']?></td>
				</tr>
			<?endforeach;?>
			</tbody>
		</table>
	</div>
</div>
<?endif;?>