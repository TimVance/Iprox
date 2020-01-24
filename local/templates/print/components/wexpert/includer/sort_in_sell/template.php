<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="sort-line catalog-sort">
	<div class="sub_wrap">
		<div class="float_wrapper">
			<div class="items_wrap">
				<span class="count">3 589 объектов</span>
				<div class="items">
					<form action="" name="SORT_FORM" method="GET">
						<?foreach($_GET as $key => $value):?>
							<input type="hidden" name="<?=$key?>" value="<?=$value?>">
						<?endforeach;?>

						<input type="hidden" name="SORT_BY" value="<?=(!empty($_GET["SORT_BY"]) ? $_GET["SORT_BY"] : 'SORT')?>">
						<input type="hidden" name="SORT_ORDER" value="<?=(!empty($_GET["SORT_ORDER"]) ? $_GET["SORT_ORDER"] : 'DESC')?>">
						<input type="hidden" name="FILTER_PROPERTY" value="<?=$_GET["FILTER_PROPERTY"]?>">
						<input type="hidden" name="FILTER_VALUE" value="<?=$_GET["FILTER_VALUE"]?>">

						<?if(!empty($arParams['FILTER_PARAMS'])):?>
							<?foreach($arParams['FILTER_PARAMS'] as $key => $value):?>
								<input type="hidden" name="cost[<?=$key?>]" value="<?=$value?>">
							<?endforeach;?>
						<?endif;?>
						<input id="submit" type="submit" value="sort">
					</form>
					<span class="items_label">Сортировка:</span>
					<ul class="items_list">
						<li value="DATE_CREATE">
							<span class="<? echo (($_GET["SORT_BY"] == "DATE_CREATE" || empty($_GET["SORT_BY"])) ? 'active' : 'waited');?>">Дата добавления</span>
						</li>
						<li value="PROPERTY_price">
							<span class="<? echo (($_GET["SORT_BY"] == "PROPERTY_price") ? 'active' : 'waited');?>">Цена</span>
						</li>
						<li value="PROPERTY_price_1m">
							<span class="<? echo (($_GET["SORT_BY"] == "PROPERTY_price_1m") ? 'active' : 'waited');?>">Цена м<sup>2</sup></span>
						</li>
						<li value="PROPERTY_square">
							<span class="<? echo (($_GET["SORT_BY"] == "PROPERTY_square") ? 'active' : 'waited');?>">Площадь</span>
						</li>
					</ul>
				</div>
			</div>
			<?
			/*<div class="more">
				<a href="#" id="show_map_list">Список</a>
			</div>*/
			?>
		</div>
	</div>
</div>