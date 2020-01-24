<? if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();?>

<? 


?>

<div class="b-propos agent_objects_workarea">
	<? if($arResult["TOTAL_CNT"] > 0): ?>
	<div class="t-emploe">Мои предложения <span>(<?=$arResult["TOTAL_CNT"]?>)</span></div>
		<div class="line-view">
			<p>Показать</p>

			<div class="sel-view">
				<select class="select-offer-type">
					<option value="all">Все объекты (<?=$arResult["TOTAL_CNT"]?>)</option>
					<? foreach($arResult["IBLOCK_CNT"] as $key => $value): ?>
						<option data-type="<?=$arResult["UsedIBLOCKS"][$key]["CODE"]?>" value="<?=$arResult["UsedIBLOCKS"][$key]["CODE"]?>" <?=($arResult["UsedIBLOCKS"][$key]["SELECTED"])? 'selected' : "";?>><?=$arResult["UsedIBLOCKS"][$key]["NAME"] . '(' . $value . ')'?></option>
					<? endforeach; ?>
				</select>
			</div><!--sel-view-->
		</div><!--line-view-->

		<? 
		/*if (count($arResult["SORT_BLOCK"]) > 0)
		{
			?>
			<div class="nav-agents">
				<div class="sort offers-sort-js">
					<p>Сортировка:</p>
					<ul>
					<? 
					foreach ($arResult["SORT_BLOCK"] as $key=> $SortEle)
					{
						?>
							<li>
							<? if ($SortEle["SELECTED"])
							{
								?>
								<span>
								<?
							}?>
								<a data-direction="<?=$SortEle["NEW_DIRECTION"]?>" data-type="<?=$key?>" href="javascript:void(0);"><?=$SortEle["NAME"]?></a>
							<? if ($SortEle["SELECTED"])
							{
								?>
								</span>
								<?
							}?>
							</li>
						<?
					}
					?>
					</ul>
					
				</div>
				<div class="more">
					<a value="list" <?=($_SESSION['VIEW_TYPE'] != 'tiles')? 'style="display:none;"' : "";?> href="javascript:void(0);"  class="show_map_list not-reload  im_myself <?=($_SESSION['VIEW_TYPE'] != 'tiles')? 'activated' : "";?>">Список</a>
					<a value="tiles" <?=($_SESSION['VIEW_TYPE'] == 'tiles')? 'style="display:none;"' : "";?> href="javascript:void(0);"  class="show_map_list not-reload  im_myself <?=($_SESSION['VIEW_TYPE'] == 'tiles')? 'activated' : "";?>">Плитки</a>
				</div>
			</div>
			<?
		}*/
		?>
		<div class="sort_wrapper">
		<?
		$APPLICATION->IncludeComponent('wexpert:includer', "sort_in_sell",
				array("CUSTOM_EVENT" => "Y", "NOT_RELOAD_ON_CHANGE_TILE" => "Y", "CHANGE_VIEW_CLASS" => "im_myself", "REVERSE" => "Y"),
				$component
		);
		?>
		</div>
		<div class="agents_objects_prewrapper">
		<?
		
		if (\Morealty\Main::isAjax())
		{
			$APPLICATION->RestartBuffer();
		}
		?>
		<div class="agents_objects_wrapper">
			<script>
			$(function(){
				$(".total_counts_wrapper").text("<?=$arResult["COUNT_OBJECTS"]?> <?=Suffix($arResult["COUNT_OBJECTS"], array("Объект", "Объекта", "Объектов"))?>");
			})
			</script>
			<?
			$template = "list";
			if (in_array(strtolower($arParams["VIEW_TYPE"]), array("tiles", "list")))
			{
				$template = strtolower($arParams["VIEW_TYPE"]);
			}
			@include $template.".php";
			?>
			<?/*$dopPadding = ($arResult['NAV_RESULT']['PAGES_COUNT'] == 1)? true: false;
			?>
			
			<div class="list-agents list-agents2 <?if($dopPadding)echo("dop-padding-lent")?>">
				<? $counter = 0; ?>
				<? foreach($arResult['ITEMS'] as $currentItem): ?>
					<?
					$arProperties = $currentItem['PROPERTIES'];
					?>
					<div class="item-agents">
						<input type="hidden" id="item-id" value="<?=$currentItem['ID']?>">
						<div class="img-agent">
							<div class="gal-agent">
								<div class="slider-agent">
									<?if(count($arProperties['photo_gallery']['VALUE']) > 0):?>
										<? foreach($arProperties['photo_gallery']['VALUE'] as $photo): ?>
											<? 	$photoLink = 'http://' . $_SERVER['HTTP_HOST'] . '/' .AddWaterMarkResized($photo,322,242,CImg::M_PROPORTIONAL) ?>
											<div class="slide"><img src="<?=$photoLink?>" alt="" /></div>
										<? endforeach; ?>
									<?else:?>
										<div class="slide img-grey"><div class="img-grey"></div></div>
									<?endif;?>
								</div><!--slider-agent-->
								<div class="nums-photo"><span><?=count($arProperties['photo_gallery']["VALUE"])?></span></div>
							</div>
						</div>
						<div class="desc-agents">
							<div class="but-favor check-favor" data-element="<?=$currentItem['ID']?>"><a href="javascript:void(0)"></a></div>
							<div class="t-agents"><a href="<?=$link_added_part . $currentItem['DETAIL_PAGE_URL']?>"><?=$currentItem['NAME']?></a></div>
							<div class="adress-agent">
								<?=\SiteTemplates\Object::buildAddress($arProperties);?>
							</div>
							<div class="info-agents">
								<div class="params-propos">
									<?=\SiteTemplates\Object::buildProps($arProperties);?>
									<div class="name-r">
										<?=$arProperties['apartment_complex']['MODIFIED_VALUE']?>
									</div>
								</div>
								<div class="func-propos">
									<div class="price-propos">
									<? 
									if ($arProperties['price']['VALUE'])
									{
										?>
										<p><span><?=MoneyOutPut($arProperties['price']['VALUE'])?> <?=$arProperties['currency']['VALUE_XML_ID']?></span></p>
										<?
									}
									?>
										
										<? if ($arProperties['price_1m']['VALUE'])
										{
											?>
											<p><?=MoneyOutPut($arProperties['price_1m']['VALUE'])?> <?=$arProperties['currency']['VALUE_XML_ID']?>/м<sup>2</sup></p>
											<?
										}?>
										
									</div>
									<div class="more-but"><a href="<?=$link_added_part .  $currentItem['DETAIL_PAGE_URL']?>">Подробнее об объекте</a></div>
								</div>
							</div>
						</div>
					</div>
					<? $counter++; ?>
				<? endforeach; ?>
			</div>
			<?*/ ?>
			<?=$arResult["NAV_STRING"]?>
		</div>
		<?
		if (\Morealty\Main::isAjax())
			die();
		?>
	</div>
	<? endif; ?>

</div>