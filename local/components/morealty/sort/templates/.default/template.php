<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
  <div class="sort-line">
	<div class="sub_wrap">
	  <div class="float_wrapper">
		<div class="items_wrap">
		 <?/* ?> <span class="count">3 589 объектов</span><? */?>
		  <div class="items sort-block">
			<span class="items_label">Сортировка:</span>
			<ul class="items_list">
			<? foreach ($arResult["ITEMS"] as $arItem)
			{
				?>
				<li>
				<? if ($arItem["SELECTED"] == "Y")
				{
					?><strong><?
				}?>
				<a class="sort-item <?=($arItem["SELECTED"] == "Y")? "current": "";?>" <?=($arItem["SELECTED"] == "Y")? "data-direction=\"".$arResult["CHECKED"]["DIRECTTION"]."\"": "";?>  data-prop="<?=$arItem["CODE"]?>" href="javascript:void(0);"><?=$arItem["TEXT"]?></a>
				<? if ($arItem["SELECTED"] == "Y")
				{
					?></strong><?
				}?>
				</li>
				<?
			}?>

			</ul>
			<form class="hidden submit-form" mothod="get">
				<input type="hidden" value="" name="SORT_BY">
				<input type="hidden" value="" name="SORT_DIRECTION">
			</form>
		  </div>
		</div>
		<div class="more">
		  <a href="javascript:void(0);" class="map-object-list" id="show_map_list">Список</a>
		</div>
	  </div>
	</div>
  </div>