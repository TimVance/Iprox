<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$RoomSizes = array(1=>1,2=>2,3=>3,4=>4,5=>5);
?>
<div class="filter-line">
	<div class="f-visible">
		<div class="sub_wrap">
			<div class="float_wrapper">
				<div class="secs">
					<form class="aprtaments-filter"  action="" name="FILTER_FORM" data-building="<?=$arParams["NEWBUILD"]?>" data-builders="<?=$arParams["BUILDERS"]?>" data-target-block="<?=$arParams["TARGET_ID"]?>" method="GET">
						
						
						<?/* ?><div class="sec sec-form">
							<div class="sec-head">Количество комнат</div>
							<div class="form slide-pop">
								<div class="field pricefield">
									<input type="number" min="" name="roomes_from" placeholder="от" value="">
									<input type="number" min="" name="roomes_to" placeholder="до" value="">
								</div>
								<a class='pick pick-search' href="javascript:void(0);" value="">Подобрать</a>
							</div>
						</div><?*/ ?>
						
						<div class="sec">
							<div class="sec-head">
								<span>Количество комнат <? if($filterValuesCount == 1 && $filterProperty == 'PROPERTY_cout_rooms'): ?>:<strong>&nbsp;<?=$RoomSizes[$filterValues[0]]?></strong><? endif; ?></span>
							</div>
							<div class="form slide-pop">
								<?/* ?><span class="label">Количество комнат</span><?*/ ?>
								<div class="field">
									<select name="roomes" class="">
										<option value="">Кол-во комнат</option>
										<? foreach($RoomSizes as $Size):?>
											<option value="<?=$Size?>"><?=$Size?></option>
										<? endforeach; ?>
									</select>
								</div>
								<a class="pick pick-search" href="javascript:void(0);" value="">Подобрать</a>
							</div>
						</div>
						<div class="sec">
							<div class="sec-head">
								<span>Цена:</span>
							</div>
							<div class="form slide-pop">
								<div class="field pricefield ">
									<input type="number" min="" name="price_from" placeholder="от" value="">
									<input type="number" min="" name="price_to" placeholder="до" value="">
								</div>
								<span>руб.</span>
								<a class="pick" href="javascript:void(0);" value="">Подобрать</a>
							</div>
						</div>
						<div class="sec">
							<div class="sec-head">
								<span>Цена за метр:</span>
							</div>
							<div class="form slide-pop">
								<div class="field pricefield ">
									<input type="number" min="" name="price_m1_from" placeholder="от" value="">
									<input type="number" min="" name="price_m1_to" placeholder="до" value="">
								</div>
								<span>руб.</span>
								<a class="pick" href="javascript:void(0);" value="">Подобрать</a>
							</div>
						</div>
						<div class="sec">
							<div class="sec-head">
								<span>Площадь</span>
							</div>
							<div class="form slide-pop">
								<div class="field">
									<input type="number" min="" name="square_from" placeholder="от">
									<input type="number" min="" name="square_to" placeholder="до" value="">
								</div>
								<span>м<sup>2</sup></span>
								<a class="pick" href="javascript:void(0);" value="">Подобрать</a>
							</div>
						</div>
						<a href="javascript:void(0);" class="more filter-cancel">Сбросить все фильтры</a>
					</form>
				</div>
				
			</div>
		</div>
	</div>
	<div class="f-toggle slide-pop">
		<div class="sub_wrap"></div>
	</div>
</div>