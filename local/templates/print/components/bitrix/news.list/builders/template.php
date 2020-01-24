<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="list-agents list-agents2">
	<? $counter = 0; ?>
	<?foreach($arResult["ITEMS"] as $currentItem):?>
		<?
			$realtorsID = $currentItem['PROPERTIES']['CONTACT_PERSON']['VALUE'];
			//считаем количество предложений
			$arSelect = Array('ID', 'NAME');
			$arFilter = Array('TYPE'=> 'catalog', 'ACTIVE'=>'Y', 'GLOBAL_ACTIVE'=>'Y');
			$res = CIBlock::GetList(Array('SORT'=>'ASC'), $arFilter, true, $arSelect);
			while($item = $res->GetNext()) {
				$arID[]   = $item['ID'];
				$arNAME[]   = $item['NAME'];
			}
			$arSECTION = array();

			for($i = 0; $i < count($arID) ; $i++) {
				$arSECTION[$arID[$i]] = $arNAME[$i];
			}

			$arSelect = Array('PROPERTY_REALTOR', 'IBLOCK_ID');
			$arFilter = Array("IBLOCK_ID"=>$arID, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
			$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
			$currentID = $arItem['ID'];
			$arRealtorOffers = '';
			while($ob = $res->GetNextElement())
			{
				$arFields = $ob->GetFields();
				if(in_array($arFields['PROPERTY_REALTOR_VALUE'], $realtorsID)) {
					if(!empty($arRealtorOffers[$arFields['IBLOCK_ID']])) {
						$arRealtorOffers[$arFields['IBLOCK_ID']]++;
					}
					else {
						$arRealtorOffers[$arFields['IBLOCK_ID']] = 1;
					}
				}
			}
			$totalCount = 0;
			foreach($arRealtorOffers as $offer) {
				$totalCount += $offer;
			}

			$number = $totalCount;
		?>
		<div class="item-agents">
			<div class="img-agent">
				<a href="<?=$currentItem["DETAIL_PAGE_URL"]?>">
					<img
						class="preview_picture"
						border="0"
						src="<?=$currentItem['PREVIEW_PICTURE']["SRC"]?>"
						alt="<?=$currentItem["PREVIEW_PICTURE"]["ALT"]?>"
						title="<?=$currentItem["PREVIEW_PICTURE"]["TITLE"]?>"
					/>
				</a>
			</div>
			<div class="desc-agents">
				<? if($currentItem["PROPERTIES"]["SHARES"]["VALUE_ENUM_ID"] == 1): ?>
					<div class="action-ag"><a href="<?=$currentItem["DETAIL_PAGE_URL"]?>">Акции!</a></div>
				<? endif; ?>
				<div class="t-agents"><a href="<?=$currentItem["DETAIL_PAGE_URL"]?>"><?=$currentItem["NAME"]?></a></div>
				<div class="adress-agent"><?=$currentItem["PROPERTIES"]["ADDRESS"]["VALUE"]?></div>

				<div class="info-agents">
					<div class="func-agents">
						<div class="contacts-agents">
							<ul>
								<li><span><?=$currentItem["PROPERTIES"]["PHONE_NUMBER"]["VALUE"]?></span></li>
								<li><a href="<?=$currentItem["PROPERTIES"]["EMAIL"]["VALUE"]?>"><?=$currentItem["PROPERTIES"]["EMAIL"]["VALUE"]?></a></li>
							</ul>
						</div><!--contacts-agents-->

						<div class="nums-contacts">
							<ul>
								<li>Всего предложений <span><?=$number?></span></li>
							</ul>
						</div>
					</div><!--func-agents-->

					<div class="text-agents">
						<div class="list-obj">
							<span>Объекты застройщика в продаже:</span>
							<ul>
								<? foreach($currentItem["PROPERTIES"]["OBJECTS_FOR_SALE"]["VALUE"] as $itemValue): ?>
									<li><a href="<?=$currentItem["DETAIL_PAGE_URL"]?>"><?=$itemValue?></a></li>
								<? endforeach; ?>
							</ul>
							<div class="more-but"><a href="<?=$currentItem["DETAIL_PAGE_URL"]?>">Подробнее</a></div>
						</div>
					</div><!--text-agents-->
				</div><!--info-agents-->
			</div><!--desc-agents-->
		</div><!--item-agents-->
			<? if($counter == 3): ?>
			<div class="item-ads"><a href="#"><img src="<?=SITE_TEMPLATE_PATH?>/images_tmp/ban-partner.jpg" alt="" /></a></div>
			<? endif; ?>
			<? $counter++; ?>
		<?endforeach;?>
</div><!--list-agents-->
<?=$arResult["NAV_STRING"]?>