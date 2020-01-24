<? if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die(); ?>

<?
$IBLOCKS = $arResult['IBLOCKS'];
$arProperties = $arResult['arProperties'];
?>

<div class="last-objects">


	<div class="head-object">
		<p>Последние объекты:</p>
		<ul>
			<li <?if(empty($arProperties[7])) echo "style='display:none;'"; ?> class="active"><a href="javascript:void(0);" data-slider="slider1">Квартиры</a></li>
			<li <?if(empty($arProperties[8])) echo "style='display:none;'"; ?>><a href="javascript:void(0);" data-slider="slider2">Дома</a></li>
			<li <?if(empty($arProperties[11])) echo "style='display:none;'"; ?>><a href="javascript:void(0);" data-slider="slider3">Помещение</a></li>
			<li <?if(empty($arProperties[10])) echo "style='display:none;'"; ?>><a href="javascript:void(0);" data-slider="slider4">Участки</a></li>
			<li <?if(empty($arProperties[12])) echo "style='display:none;'"; ?>><a href="javascript:void(0);" data-slider="slider5">Офисы</a></li>
		</ul>
	</div><!--head-object-->

	<?  $counter = 1; ?>
	<?	foreach($IBLOCKS as $IBLOCK_ID): ?>
		<div class="slier-objects <?if($counter == 1) {echo 'visible'; }?>">
			<div class="slider<?=$counter?>">
				<? foreach($arProperties[$IBLOCK_ID] as $properties): ?>
					<div class="slide">
						<div class="item-propos">
							<div class="top-propos">
								<div class="img-propos last-objectsblock">
									<div class="slider-agent2 <?=($USER->GetLogin() == "vadim") ? "23" : ""?>">
									<? 
									$images = 0;
									?>
										<?if(count($properties["PROPERTIES"]['photo_gallery']["VALUE"]) > 0 && $properties["PROPERTIES"]['photo_gallery']["VALUE"]):?>
											<? foreach($properties["PROPERTIES"]['photo_gallery']["VALUE"] as $photo): ?>
												<? 
												$images++;
												?>
												<? 	$photoLink = AddWaterMarkResized($photo,235,176,CImg::M_CROP); ?>
												<div class="slide"><a href="<?=$properties['DETAIL_PAGE_URL']?>"><img src="<?=$photoLink?>" alt="" /></a></div><!--slide-->
											<? endforeach; ?>
										<?else:?>
											<div class="slide img-grey"><a href="<?=$properties['DETAIL_PAGE_URL']?>"><div class="img-grey"></div></a></div><!--slide-->
										<?endif;?>
									</div><!--slider-agent-->
									<div class="nums-photo"><span><?=$images?></span></div>
								</div>
								<div class="t-propos"><p><a href="<?=$properties['DETAIL_PAGE_URL']?>"><?=$properties['NAME']?></a></p></div>
							</div><!--top-propos-->

							<div class="bot-propos">
								<div class="adress-p">
									<?=$properties['PROPERTY_CITY_VALUE']?>,
									<?=$properties['PROPERTY_DISTRICT_VALUE']?>,
									<?=$properties['PROPERTY_MICRODISTRICT_VALUE']?>,
									<?=$properties['PROPERTY_STREET_VALUE']?>
								</div>

								<div class="price-p"><?=$properties['PROPERTY_PRICE_VALUE']?> <?=$properties['PROPERTY_CURRENCY_VALUE']?></div>

								<div class="params-p">
									<ul>
									<? 
									if ($properties['PROPERTY_SQUARE_VALUE'])
									{
										?>
											<li>Общая площадь: <span><?=$properties['PROPERTY_SQUARE_VALUE']?></span> м2</li>
										<?
									}
									else if($properties['PROPERTY_SUMMARY_BUILDINGS_SQUARE_VALUE'])
									{
										//summary_buildings_square
										?>
											<li>Общая площадь строений: <span><?=$properties['PROPERTY_SUMMARY_BUILDINGS_SQUARE_VALUE']?></span> м2</li>
										<?
									}
									if ($properties['PROPERTY_FLOOR_VALUE'])
									{
										?>
											<li>Этаж: <span><?=$properties['PROPERTY_FLOOR_VALUE']?></span></li>
										<?
									}
									if ($properties['PROPERTY_NUMBER_OF_STOREYS_VALUE'])
									{
										//number_of_storeys
										?>
											<li>Этажей: <span><?=$properties['PROPERTY_NUMBER_OF_STOREYS_VALUE']?></span></li>
										<?
									}
									?>

										
									</ul>
								</div>

								<div class="compl-prop"><?=$properties['PROPERTY_APARTMENT_COMPLEX_VALUE']?></div>
							</div><!--bot-propos-->
						</div><!--item-propos-->
					</div><!--slide-->
				<? endforeach; ?>
			</div><!--slider1-->
		</div><!--slider-objects-->
		<? $counter++; ?>
	<? endforeach; ?>


	<div class="temp-hidden" class="more"><a href="javascript:void(0);">Все предложения</a></div><!--more-->
</div><!--last-objects-->