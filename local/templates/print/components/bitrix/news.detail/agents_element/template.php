<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
	$arProperties = $arResult["PROPERTIES"];
	//
	$rsUser = CUser::GetList(($by="ID"), ($order="desc"), array('GROUPS_ID' => array(7)), array("SELECT"=>array("UF_*")));
	$users = array();
	while($user = $rsUser->Fetch()) {
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
		$arSelect = Array('PROPERTY_REALTOR', 'IBLOCK_ID', 'ID');
		$arFilter = Array("IBLOCK_ID"=>$arID, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
		$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
		$currentID = $arResult['ID'];
		$arRealtorOffers = '';
		$arCurrentRealtorOffersID = array();
		while($ob = $res->GetNextElement()) {
			$arFields = $ob->GetFields();
			if($arFields['PROPERTY_REALTOR_VALUE'] == $currentID) {
				$arCurrentRealtorOffersID[] = $arFields['ID'];
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
		$user['OFFER_COUNT'] = $totalCount;

		if($user['UF_AGENT_NAME'] == $arResult['ID']) {
			$users[] = $user;
		}
	}
?>
<div class="adress-agency"><?=$arProperties['ADDRESS']['VALUE']?></div>
<div class="about-agency">
	<div class="about-agency-l">
		<div class="card-agency">
			<div class="img-card-agency">
				<img
					class="preview_picture"
					border="0"
					src="<?=$arResult['PREVIEW_PICTURE']["SRC"]?>"
					alt="<?=$arResult["PREVIEW_PICTURE"]["ALT"]?>"
					title="<?=$arResult["PREVIEW_PICTURE"]["TITLE"]?>"
				/>
			</div>
			<div class="desc-card-agency">
				<div class="link-ag">
					<ul>
						<li><a target="_blank" href="http://www.lidersochi.com"><?=$arProperties['SITE_ADDRESS']['VALUE']?></a></li>
						<li><a href="mailto:office@lidersochi.com"><?=$arProperties['EMAIL']['VALUE']?></a></li>
					</ul>
				</div>
				<div class="work-ag">
					<ul>
						<? foreach($arProperties["TIMETABLE"]["VALUE"] as $itemValue): ?>
							<li>
								<?
								//формируем правильный вывод нулей в часах путем добавления тегов
								$fullString = $itemValue;
								for($i = 0; $i < substr_count($fullString, ':'); $i += 1) { // break
									$pos = strpos($itemValue, ':');
									if($pos > 0) {
										$part1 = substr($itemValue, 0, $pos);
										$part2 = substr($itemValue, $pos + 1, count($itemValue) - 2);
										$part21= substr($itemValue, -1);
										$part2 .= $part21;
										$nextSymbol = is_numeric(substr($part2, 1, 1));
										if($nextSymbol) {
											echo substr($part1, 2);
											echo "<sup>";
											echo substr($part2, 0, 2);
											echo "</sup>";
										}
										else {
											echo $part1;
										}
										$itemValue = $part2;
									}
									else {
										break;
									}
								}
								if(substr_count($fullString, ':') == 0) {
									echo $itemValue;
								}
								?>
							</li>
						<? endforeach; ?>
					</ul>
				</div><!--work-ag-->

				<div class="phone-ag"><?=$arProperties['PHONE_NUMBER']['VALUE']?></div>

				<div class="mess-ag"><a class="inline" href="#pop8">Оставить сообщение</a></div>
			</div><!--desc-card-agency-->
		</div><!--card-agency-->

		<div class="text-ab-ag">
			<div class="t-ab">Об агентстве</div>
			<div class="text-acoord">
				<?=$arResult["DETAIL_TEXT"]?>
			</div>
			<div class="link-acc"><a href="#"><span class="open-text">Развернуть</span> <span class="close-text">Свернуть</span></a></div>
		</div><!--text-about-ag-->
		<div class="accord-card">
			<div class="item-accrod">
				<div class="tit-accord"><span><?=$arProperties["BRANCHES"]["NAME"]?></span></div>
				<div class="text-accrod">
					<ul>
						<? foreach($arProperties["BRANCHES"]["VALUE"] as $itemValue): ?>
							<li><?=$itemValue?></li>
						<? endforeach; ?>
					</ul>
				</div>
			</div><!--item-accord-->

			<div class="item-accrod">
				<div class="tit-accord"><span><?=$arProperties["ACTIVITIES"]["NAME"]?></span></div>
				<div class="text-accrod">
					<ul>
						<? foreach($arProperties["ACTIVITIES"]["VALUE"] as $itemValue): ?>
							<li><?=$itemValue?></li>
						<? endforeach; ?>
					</ul>
				</div>
			</div><!--item-accord-->
		</div><!--accord-card-->
	</div><!--about-agency-l-->

	<div class="about-agency-r">
		<div class="map-agency">

			<? $arProperty = $arResult["PROPERTIES"]; ?>
				<? $arPos = explode(",", $arProperty['YANDEX_MAP']['VALUE']);?>
					<?$APPLICATION->IncludeComponent(
						"bitrix:map.yandex.view",
						"",
						Array(
							"INIT_MAP_TYPE" => "MAP",
							"MAP_DATA" => serialize(array(
								'yandex_lat' => $arPos[0],
								'yandex_lon' => $arPos[1],
								'yandex_scale' => 13,
								'PLACEMARKS' => array (
									array(
										'TEXT' => $arProperty["YAMAP"]["VALUE"].", ".$arProperty["YANDEX_MAP"]["VALUE"],
										'LON' => $arPos[1],
										'LAT' => $arPos[0],
									),
								),
							)),
							"MAP_WIDTH" => "100%",
							"MAP_HEIGHT" => "500",
							"CONTROLS" => array("ZOOM", "MINIMAP", "TYPECONTROL", "SCALELINE"),
							"OPTIONS" => array("DESABLE_SCROLL_ZOOM", "ENABLE_DBLCLICK_ZOOM", "ENABLE_DRAGGING"),
							"MAP_ID" => ""
						),
						false
					);?>
		</div>
		<div class="video-agency">
			<iframe width="100%" height="315" src="<?=$arProperties["VIDEO_LINK"]["VALUE"]?>" frameborder="0" allowfullscreen></iframe>
		</div>
	</div><!--about-agency-r-->
</div><!--about-agency-->

<div class="b-amploe">
	<div class="t-emploe">Сотрудники <span>(<?=count($users)?>)</span></div>

	<div class="list-emploe">
		<ul>
			<? foreach($users as $user): ?>
				<?
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
				$arSelect = Array('PROPERTY_REALTOR', 'IBLOCK_ID', 'ID');
				$arFilter = Array("IBLOCK_ID"=>$arID, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
				$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
				$currentID = $user['ID'];
				$arRealtorOffers = '';
				$arCurrentRealtorOffersID = array();
				while($ob = $res->GetNextElement()) {
					$arFields = $ob->GetFields();
					if($arFields['PROPERTY_REALTOR_VALUE'] == $currentID) {
						$arCurrentRealtorOffersID[] = $arFields['ID'];
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

				if(!empty($user['PERSONAL_PHOTO'])) {
					$photoLink = 'http://' . $_SERVER['HTTP_HOST'] . '/' . CFile::GetPath($user['PERSONAL_PHOTO']);
				}
				else {
					$photoLink = SITE_TEMPLATE_PATH . '/images/no-photo.jpg';
				}
				?>
				<li>
					<div class="in-emp">
						<div class="img-emp"><img src="<?=$photoLink?>" alt="" /></div>
						<div class="desc-emploe">
							<div class="name-emploe">
								<a href="<?='http://' . $_SERVER['HTTP_HOST'] . '/realtors/' . $user['ID'] . '/' ?>"><?=$user['NAME'] . ' ' . $user['SECOND_NAME'] . ' ' . $user['LAST_NAME']?></a>
								<span><?=$user['UF_PERSON_POST']?></span>
							</div>
							<div class="propos-emploe">
								<a href="<?='http://' . $_SERVER['HTTP_HOST'] . '/realtors/' . $user['ID'] . '/#offers' ?>">Предложений <span><?=$totalCount?></span></a>
							</div>
						</div><!--desc-emploe-->
					</div>
				</li>				
			<? endforeach; ?>
		</ul>
		<? if(!empty($arCurrentRealtorOffersID)):?>
			<div class="view-all"><a href="#">Показать всех</a></div>
		<? endif; ?>
	</div><!--list-emploe-->
</div><!--b-emploe-->

<div class="b-propos">
	<div class="t-emploe">Предложения <?=$arResult["NAME"]?> <span>(<?=$totalCount?>)</span></div>





	<? if(!empty($arCurrentRealtorOffersID)):?>
	<div class="line-view">
		<p>Показать</p>

		<div class="sel-view">
			<select>
				<option>Все объекты (<?=$totalCount?>)</option>
				<? foreach($arRealtorOffers as $key => $value): ?>
					<option><?=strtolower($arSECTION[$key]) . '(' . $value . ')'?></option>
				<? endforeach; ?>
			</select>
		</div><!--sel-view-->
	</div><!--line-view-->

	<div class="nav-agents">
		<div class="sort">
			<p>Сортировка:</p>
			<ul>
				<li><span>Дата обновления</span></li>
				<li><a href="#">Название</a></li>
				<li><a href="#">Число предложений</a></li>
				<li><a href="#">Число сотрудников</a></li>
			</ul>
		</div><!--sort-->

		<div class="link-map"><a href="#">Карта</a></div>
	</div><!--nav-agents-->
	<?
		$APPLICATION->IncludeComponent("wexpert:iblock.list","sell",Array(
			//"ORDER"                             => array($_GET["SORT_BY"] => $_GET["SORT_ORDER"]),
			"ORDER"                             => array('PROPERTY_price' => 'asc'),
			"FILTER"                            => array('ID' => $arCurrentRealtorOffersID),
			"IBLOCK_ID"							=> $arID,
			"PAGESIZE"						    => 6,
			"GET_PROPERTY"						=> "Y",
			"CACHE_TIME"    => 3600 * 24 * 10
		));
	?>
	<? endif; ?>
</div><!--b-propos-->