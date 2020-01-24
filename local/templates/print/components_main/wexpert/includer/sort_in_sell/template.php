<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?

?>
<div class="sort-line catalog-sort <?=($arParams["CUSTOM_EVENT"] === "Y")? 'stop_default' : ""?>">
	<div class="sub_wrap">
		<div class="float_wrapper">

			<div class="items_wrap">
			<div class="total_counts_wrapper"><? 
			$APPLICATION->ShowViewContent("all_count");
			?>
			</div>
				<?/* ?><span class="count">3 589 объектов</span><? */?>
				<div class="items">
					<form action="" name="SORT_FORM" method="GET">
						<?foreach($_GET as $key => $value):?>
							<input type="hidden" name="<?=$key?>" value="<?=$value?>">
						<?endforeach;?>

						<input type="hidden" name="SORT_BY" value="<?=(!empty($_GET["SORT_BY"]) ? $_GET["SORT_BY"] : 'SORT')?>">
						<input type="hidden" name="SORT_ORDER" value="<?=(!empty($_GET["SORT_ORDER"]) ? $_GET["SORT_ORDER"] : 'DESC')?>">
						<input type="hidden" name="FILTER_PROPERTY" value="<?=$_GET["FILTER_PROPERTY"]?>">
						<input type="hidden" name="FILTER_VALUE" value="<?=$_GET["FILTER_VALUE"]?>">
						<input type="hidden" name="VIEW_TYPE" value="<?=(!empty($_SESSION['VIEW_TYPE']) ? $_SESSION['VIEW_TYPE'] : 'LIST')?>">

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
			<div class="more">
			<? if ($arParams["NOT_RELOAD_ON_CHANGE_TILE"])
				{
					if ($arParams["REVERSE"] == "Y")
					{
						?>
							<a value="list" <?=($_SESSION['VIEW_TYPE'] != 'tiles')? 'style="display:none;"' : "";?> href="javascript:void(0);"  class="show_map_list not-reload <?=$arParams["CHANGE_VIEW_CLASS"]?> <?=($_SESSION['VIEW_TYPE'] != 'tiles')? 'activated' : "";?>">Список</a>
							<a value="tiles" <?=($_SESSION['VIEW_TYPE'] == 'tiles')? 'style="display:none;"' : "";?> href="javascript:void(0);"  class="show_map_list not-reload <?=$arParams["CHANGE_VIEW_CLASS"]?> <?=($_SESSION['VIEW_TYPE'] == 'tiles')? 'activated' : "";?>">Плитки</a>
						<?
					}
					else 
					{
						?>
							<a value="list" <?=($_SESSION['VIEW_TYPE'] != 'tiles')? 'style="display:none;"' : "";?> href="javascript:void(0);"  class="show_map_list not-reload <?=$arParams["CHANGE_VIEW_CLASS"]?> <?=($_SESSION['VIEW_TYPE'] == 'tiles')? 'activated' : "";?>">Список</a>
							<a value="tiles" <?=($_SESSION['VIEW_TYPE'] == 'tiles')? 'style="display:none;"' : "";?> href="javascript:void(0);"  class="show_map_list not-reload <?=$arParams["CHANGE_VIEW_CLASS"]?> <?=($_SESSION['VIEW_TYPE'] != 'tiles')? 'activated' : "";?>">Плитки</a>
						<?
					}
					
				}
			else 
			{
				?>
				<? if($_SESSION['VIEW_TYPE'] == 'tiles'):?>
					<a value="list" href="javascript:void(0);" id="show_map_list" class="<?=$arParams["ADDED_CLASS"]?> <?=($arParams["NOT_RELOAD_ON_CHANGE_TILE2"] == "Y")? "not-reload" : ""?>">Список</a>
				<? else: ?>
					<a value="tiles" href="javascript:void(0);" id="show_map_list" class="<?=$arParams["ADDED_CLASS"]?> <?=($arParams["NOT_RELOAD_ON_CHANGE_TILE2"] == "Y")? "not-reload" : ""?>">Плитки</a>
				<? endif; ?>
				<?
			}?>

			</div>
		</div>
	</div>
</div>