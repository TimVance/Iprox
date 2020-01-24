<? if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die(); ?>

<div class="last-objects">
	<?
	CModule::IncludeModule("iblock");
	$IBLOCKS = array('7', '8', '11', '10', '12');
	foreach($IBLOCKS as $IBLOCK_ID) {
		$arSelect = Array("ID",  'IBLOCK_ID', 'DETAIL_PAGE_URL', 'NAME', 'PROPERTY_city', 'PROPERTY_district', 'PROPERTY_microdistrict', 'PROPERTY_street', 'PROPERTY_apartment_complex', 'PROPERTY_square', 'PROPERTY_floor', 'PROPERTY_price', 'PROPERTY_currency', 'PROPERTY_photo_gallery');
		$arFilter = Array("IBLOCK_ID" => $IBLOCK_ID, "ACTIVE"=>"Y");
		$mainRes = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, Array("nPageSize" => 5), $arSelect);
		while($ob = $mainRes->GetNext()) {
			$res = CIBlockElement::GetByID($ob['PROPERTY_CITY_VALUE']);
			if($ar_res = $res->GetNext()){
				$ob['PROPERTY_CITY_VALUE'] = $ar_res['NAME'];
			}
			$res = CIBlockElement::GetByID($ob['PROPERTY_DISTRICT_VALUE']);
			if($ar_res = $res->GetNext()){
				$ob['PROPERTY_DISTRICT_VALUE'] = $ar_res['NAME'];
			}
			$res = CIBlockElement::GetByID($ob['PROPERTY_MICRODISTRICT_VALUE']);
			if($ar_res = $res->GetNext()){
				$ob['PROPERTY_MICRODISTRICT_VALUE'] = $ar_res['NAME'];
			}
			$res = CIBlockElement::GetByID($ob['PROPERTY_APARTMENT_COMPLEX_VALUE']);
			if($ar_res = $res->GetNext()){
				$ob['PROPERTY_APARTMENT_COMPLEX_VALUE'] = $ar_res['NAME'];
			}
			switch($ob['PROPERTY_CURRENCY_VALUE']) {
				case 'Рубли' : $ob['PROPERTY_CURRENCY_VALUE'] = 'Руб.'; break;
				case 'Доллары': $ob['PROPERTY_CURRENCY_VALUE'] = '$'; break;
				case 'Евро'   : $ob['PROPERTY_CURRENCY_VALUE'] = 'Евро'; break;
				default: ; // ok
			}
			$ob['PHOTO_LINK'] = 'http://' . $_SERVER['HTTP_HOST'] . CFile::GetPath($ob['PROPERTY_PHOTO_GALLERY_VALUE']);

			$price = (string) $ob['PROPERTY_PRICE_VALUE'];
			$priceLen = strlen($price);
			if($priceLen >= 5) {
				switch($priceLen) {
					case 5: $price = substr($price, 0, 2) . ' ' . substr($price, 2, 3); break;
					case 6: $price = substr($price, 0, 3) . ' ' . substr($price, 3, 3); break;
					case 7: $price = substr($price, 0, 1) . ' ' . substr($price, 1, 3) . ' ' . substr($price, 4, 3); break; //
					case 8: $price = substr($price, 0, 2) . ' ' . substr($price, 2, 3) . ' ' . substr($price, 5, 3); break;
					case 9: $price = substr($price, 0, 3) . ' ' . substr($price, 3, 3) . ' ' . substr($price, 6, 3); break;
					case 10: $price = substr($price, 0, 1) . ' ' . substr($price, 1, 3) . ' ' . substr($price, 4, 3) . ' ' . substr($price, 7, 3); break;
					default: break;
				}
				$ob['PROPERTY_PRICE_VALUE'] = $price;
			}
			$arProperties[$IBLOCK_ID][] = $ob;
		}
	}
	?>

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
								<div class="img-propos"><a href="<?='/sell'.$properties['DETAIL_PAGE_URL']?>"><img src="<?=$properties['PHOTO_LINK']?>" alt="" /></a></div>
								<div class="t-propos"><p><a href="<?='/sell'.$properties['DETAIL_PAGE_URL']?>"><?=$properties['NAME']?></a></p></div>
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
										<li>Общая площадь: <span><?=$properties['PROPERTY_SQUARE_VALUE']?></span> м2</li>
										<li>Этаж: <span><?=$properties['PROPERTY_FLOOR_VALUE']?></span></li>
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


	<div class="temp-hidden" class="more"><a href="#">Все предложения</a></div><!--more-->
</div><!--last-objects-->