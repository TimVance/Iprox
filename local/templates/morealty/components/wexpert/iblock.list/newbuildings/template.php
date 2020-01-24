<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die(); ?>

<?
$itemsCount = count($arResult['ITEMS']);
$dopPadding = ( $arResult['NAV_RESULT']['PAGES_COUNT'] == 1 ) ? true : false;
?>

<div class="list-agents list-agents2 <? if ($dopPadding)
	echo( "dop-padding-lent" ) ?> newbuilding_catalog_block">
	<? if ($arResult["ITEMS"] && count($arResult["ITEMS"]) > 0) { ?>
		<? $counter = 0; ?>
		<? foreach ($arResult['ITEMS'] as $currentItem): ?>
			<?
			$arProperties = $currentItem['PROPERTIES'];
			$res          = CIBlockElement::GetByID($arProperties['city']['VALUE']);
			//city
			if ($ar_res = $res->GetNext()) {
				$arProperties['city']['MODIFIED_VALUE'] = $ar_res['NAME'];
			}
			$res = CIBlockElement::GetByID($arProperties['district']['VALUE']);
			//district
			if ($ar_res = $res->GetNext()) {
				$arProperties['district']['MODIFIED_VALUE'] = $ar_res['NAME'];
			}
			$res = CIBlockElement::GetByID($arProperties['microdistrict']['VALUE']);
			//microdistrict
			if ($ar_res = $res->GetNext()) {
				$arProperties['microdistrict']['MODIFIED_VALUE'] = $ar_res['NAME'];
			}
			//apartment_complex
			$res = CIBlockElement::GetByID($arProperties['apartment_complex']['VALUE']);
			if ($ar_res = $res->GetNext()) {
				$arProperties['apartment_complex']['MODIFIED_VALUE'] = $ar_res['NAME'];
			}
			//currency
			switch ($arProperties['currency']['VALUE_XML_ID']) {
				case 'rubles' :
					$arProperties['currency']['VALUE_XML_ID'] = 'Руб.';
					break;
				case 'dollars':
					$arProperties['currency']['VALUE_XML_ID'] = '$';
					break;
				case 'euro'   :
					$arProperties['currency']['VALUE_XML_ID'] = 'Евро';
					break;
				default:
					; // ok
			}
			//price view
			$price                          = (string)$arProperties['price']['VALUE'];
			$arProperties['price']['VALUE'] = priceDigit($price);
			//price1m view
			$price1m                           = (string)$arProperties['price_1m']['VALUE'];
			$arProperties['price_1m']['VALUE'] = priceDigit($price1m);
			//ID
			$currentItemID = $currentItem['ID'];
			//favorites IDs
			$arSort   = array('SORT' => 'ASC');
			$arFilter = array('USER_ID' => $USER->GetID());
			$rsItems  = CFavorites::GetList($arSort, $arFilter);
			$arID     = array();
			while ($Res = $rsItems->Fetch()) {
				$arID[] = $Res['URL'];
			}
			?>
			<div class="item-agents">
				<input type="hidden" id="item-id" value="<?= $currentItem['ID'] ?>">
				<div class="img-agent">
					<div class="gal-agent">
						<div class="slider-agent">
							<? if (count($arProperties['photo_gallery']) > 0): ?>
								<? foreach ($arProperties['photo_gallery'] as $photo): ?>
									<? $photoLink = AddWaterMarkResized($photo["VALUE"], 322, 242, CImg::M_PROPORTIONAL); ?>
									<div class="slide"><img src="<?= $photoLink ?>" alt=""/></div>
								<? endforeach; ?>
							<? else: ?>
								<div class="slide img-grey">
									<div class="img-grey"></div>
								</div>
							<? endif; ?>
						</div>
						<div class="nums-photo"><span><?= count($arProperties['photo_gallery']) ?></span></div>
					</div>
				</div>
				<div class="desc-agents">
					<div class="but-favor check-favor" data-element="<?= $currentItem["ID"] ?>"><a href="javascript:void(0)"></a></div>
					<div class="t-agents"><a href="<?= $currentItem['DETAIL_PAGE_URL'] ?>"><?= $currentItem['NAME'] ?></a></div>
					<div class="adress-agent">
						<?= $arProperties['city']['MODIFIED_VALUE'] ?>,
						<?= $arProperties['district']['MODIFIED_VALUE'] ?>,
						<?= $arProperties['microdistrict']['MODIFIED_VALUE'] ?>,
						<?= $arProperties['street']['VALUE'] ?>
					</div>
					<div class="info-agents">
						<div class="params-propos">
							<div class="list-prm">
								<ul>
									<? if (!empty($arProperties['square']['VALUE'])): ?>
										<li>Общая площадь: <strong><?= $arProperties['square']['VALUE'] ?></strong> м<sup>2</sup></li>
									<? endif; ?>
									<? if (!empty($arProperties['distance_to_sea']['VALUE']) && $arProperties['dimension_distance_to_sea']['VALUE_ENUM']): ?>
										<li>До моря: <strong><?= $arProperties['distance_to_sea']['VALUE'] ?> <?= $arProperties['dimension_distance_to_sea']['VALUE_ENUM'] ?></strong></li>
									<? endif; ?>

									<? if (!empty($arProperties['floors']['VALUE'])): ?>
										<li>Этажность: <strong><?= $arProperties['floors']['VALUE'] ?></strong></li>
									<? endif; ?>
									<? if (!empty($arProperties['class']['VALUE'])): ?>
										<li>Класс: <strong><?= $arProperties['class']['VALUE_ENUM'] ?></strong></li>
									<? endif; ?>
									<? if (!empty($arProperties['deadline']['VALUE'])): ?>
										<li>Срок сдачи: <strong><?= $arProperties['deadline']['VALUE'] ?></strong></li>
									<? endif; ?>

									<? /*if(!empty($arProperties['decoration']['VALUE_ENUM'])):?>
										<li>Отделка: <strong><?=$arProperties['decoration']['VALUE_ENUM']?></strong></li>
									<?endif;*/ ?>
								</ul>
							</div>

							<div class="name-r">
								<?= $arProperties['apartment_complex']['MODIFIED_VALUE'] ?>
							</div>
						</div>
						<div class="func-propos">
							<div class="price-propos">
								<p><span>от <?= number_format($arProperties['price_flat_min']['VALUE'], 0, '', ' ') ?> <?= $arProperties['currency']['VALUE_XML_ID'] ?></span></p>
								<? if ($arProperties['price_m2_ot']['VALUE']) {
									?>
									<p>от <?= number_format($arProperties['price_m2_ot']['VALUE'], 0, '', ' ') ?> <?= $arProperties['currency']['VALUE_XML_ID'] ?>/м<sup>2</sup></p>
									<?
								} ?>

							</div>
							<div class="more-but"><a href="<?= $currentItem['DETAIL_PAGE_URL'] ?>">Подробнее об объекте</a></div>
						</div>
					</div>
				</div>
			</div>
			<? /*
			<? if((($counter + 1) == $itemsCount / 2) || $itemsCount == 1): ?>
				<div class="item-ads"><a href="#"><img src="<?=SITE_TEMPLATE_PATH?>/images_tmp/ban-partner.jpg" alt="" /></a></div>
			<? endif; ?>
	        */ ?>
			<? $counter++; ?>
		<? endforeach; ?>
	<? } else {
		ShowError("Ничего не найдено");
	} ?>

</div><!--list-agents-->


<?= $arResult["NAV_STRING"] ?>
<div>
	<!--BOTTOM_TEXT-->
</div>



