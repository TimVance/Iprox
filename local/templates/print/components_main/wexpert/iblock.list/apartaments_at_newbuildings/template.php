<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? if ($arParams["AJAX"] !== "Y")
{
	if (count($arResult["ITEMS"]) <= 0)
	{
		?><div class="hide-me"></div><?
	}
	?>
	<div class="table-history table-history2" id="<?=$arParams["BLOCK_ID"]?>">
		<?
}
?>
	<table id="tb1">
		<thead>
		<tr>
			<th><span>Фото</span></th>
			<th><a href="javascript:void(0);">Предложение</a>	<i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
			<th><a href="javascript:void(0);">Площадь</a> <i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
			<th><a href="javascript:void(0);">Этаж</a> <i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
			<th><a href="javascript:void(0);">Цена</a> <i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
			<th><a href="javascript:void(0);">Цена м<sup>2</sup></a> <i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
			<th><a href="javascript:void(0);">Обновлено</a> <i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
			<th><a href="javascript:void(0);">Просмотров</a> <i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
		</tr>
		</thead>		
		<tbody class="hr">

		<?foreach($arResult["ITEMS"] as $item):?>
			<? 
			$image = ($item["PROPERTIES"]["photo_gallery"][0]["VALUE"]) ? weImg::Resize($item["PROPERTIES"]["photo_gallery"][0]["VALUE"],100 , 100,weImg::M_CROP) : false;
			if (!$image && $item["PROPERTIES"]["layouts_gallery"][0]["VALUE"])
			{
				$image = weImg::Resize($item["PROPERTIES"]["layouts_gallery"][0]["VALUE"],100 , 100,weImg::M_CROP);
			}
			?>
			<tr>
				<td class="imgrow">
					<?if ($image)
					{
						?><img src="<?=$image?>"><?
					}?>
				</td>
				<td><a href="<?=$item["DETAIL_PAGE_URL"]?>"><?=$item['PROPERTIES']['room_number']['VALUE']?>-комнатная</a></td>
				<td><strong><?=$item['PROPERTIES']['square']['VALUE']?></strong></td>
				<td><?=$item['PROPERTIES']['floor']['VALUE']?></td>
				<td class="sort-by-data no-wrapp-this-field" data-sort="<?=$item['PROPERTIES']['price']['VALUE']?>"><strong><?=MoneyOutPut($item['PROPERTIES']['price']['VALUE'])?></strong></td>
				<td class="sort-by-data no-wrapp-this-field" data-sort="<?=$item['PROPERTIES']['price_1m']['VALUE']?>"><?=MoneyOutPut($item['PROPERTIES']['price_1m']['VALUE'])?></td>
				<? $day = rus_date("j F Y", strtotime($item['TIMESTAMP_X'])); ?>
				<td class="sort-by-data" data-sort="<?=MakeTimeStamp($item['TIMESTAMP_X'])?>"><?=$day?></td>
				<td><?=$item['SHOW_COUNTER']?></td>
			</tr>
		<?endforeach;?>
		</tbody>

	</table>
<? if ($arParams["AJAX"] !== "Y")
{?>
</div>
<?
}


