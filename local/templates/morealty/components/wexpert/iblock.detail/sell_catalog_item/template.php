<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die(); ?>
<?

CIBlockElement::CounterInc($arResult['ID']);


$ELEMENT_ID = $_REQUEST['ID'];
$IBLOCK_ID = $GLOBALS['IBLOCK_ID'];



$arProperties = $arResult['PROPERTIES'];


if (\MorealtySale\Offer::GetOfferType($arResult["ID"]) == \MorealtySale\Offer::$AredtType)
{
	$Type = "Аренда";
	$sellOrArendString = "Сдается";
}
else if (\MorealtySale\Offer::GetOfferType($arResult["ID"]) == \MorealtySale\Offer::$SellType)
{
	$Type = "Продажа";
	$sellOrArendString = "Продается";
}

if ($arProperties['realtor']['VALUE']) {
$rsUser                    = CUser::GetByID($arProperties['realtor']['VALUE']);
}
else //if($arResult["CREATED_BY"])
{
	$rsUser = CUser::GetByID($arResult["CREATED_BY"]);
}

$arUser                    = $rsUser->Fetch();
$fullName                  = $arUser['NAME'] . ' ' . $arUser['SECOND_NAME'] . ' ' . $arUser['LAST_NAME'];
$realtor_email             = $arUser['EMAIL'];
$GLOBALS['EMAILS_FOR_FORM'] = $realtor_email;

$PersonalPhoto = ($arUser["PERSONAL_PHOTO"])? weImg::Resize($arUser["PERSONAL_PHOTO"],100,100,weImg::M_CROP) : false;
//определение имени агенства
$res = CIBlockElement::GetByID($arUser['UF_AGENT_NAME']);
if ($ar_res = $res->GetNext()) {
	$arUser['UF_AGENT_VALUE'] = $arUser['UF_AGENT_NAME'];
	$arUser['UF_AGENT_NAME']  = $ar_res['NAME'];
}

//favorites IDs

if ($USER->IsAuthorized()) {
	$arSort   = array('SORT' => 'ASC');
	$arFilter = array('USER_ID' => $USER->GetID());
	$rsItems  = CFavorites::GetList($arSort, $arFilter);
	$arID     = array();
	while ($Res = $rsItems->Fetch()) {
		$arID[] = $Res['URL'];
	}
}


//считаем количество предложений
$arSelect = Array('ID', 'NAME');
$arFilter = Array('TYPE' => 'catalog', 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y');
$res      = CIBlock::GetList(Array('SORT' => 'ASC'), $arFilter, true, $arSelect);
while ($item = $res->GetNext()) {
	$arID2[]  = $item['ID'];
	$arNAME[] = $item['NAME'];
}

$arSECTION = array();

for ($i = 0; $i < count($arID2); $i++) {
	$arSECTION[$arID2[$i]] = $arNAME[$i];
}
/*$totalCount = 0;
foreach ($arRealtorOffers as $offer) {
	$totalCount += $offer;
}
$offersCount = $totalCount;
$arSelect                 = Array(
	'PROPERTY_REALTOR', 'IBLOCK_ID', 'ID', 'NAME', 'PROPERTY_PRICE', 'PROPERTY_apartment_complex', 'PROPERTY_street',
	'PROPERTY_district', 'PROPERTY_microdistrict', 'PROPERTY_city', 'PROPERTY_floor', 'PROPERTY_currency'
);
$arFilter                 = Array("IBLOCK_ID" => $arID2, "ACTIVE_DATE" => "Y", "ACTIVE" => "Y","CREATED_BY"=>$arUser["ID"]);
$res                      = CIBlockElement::GetList(Array(), $arFilter, array("CREATED_BY"), array(), $arSelect);
if ($arTemp = $res->GetNext())
{
	$offersCount = $arTemp["CNT"];
}*/
$offersCount = ($arProperties["realtor"]["VALUE"]) ? array_sum(\MorealtySale\User::realtorObjects($arProperties["realtor"]["VALUE"])) : false;


$isMap = false;

if ($arProperties["newbuilding"]["VALUE"])
{
	$newBuildRes = CIBlockElement::GetList(array(),array("IBLOCK_ID"=>19,"ID"=>$arProperties["newbuilding"]["VALUE"]),false,false,array("ID","IBLOCK_ID","PROPERTY_yandex_map"));
	if ($arTemp = $newBuildRes->GetNext())
	{
		
		if ($arTemp["PROPERTY_YANDEX_MAP_VALUE"])
		{
			$isMap = $arTemp["PROPERTY_YANDEX_MAP_VALUE"];
		}
	}
}
else if($arProperties["yandex_map"]["VALUE"])
{
	$isMap = $arProperties["yandex_map"]["VALUE"];
}
/*$currentID                = $arProperties['realtor']['VALUE'];
$arRealtorOffers          = '';
$arCurrentRealtorOffersID = array();
while ($ob = $res->GetNextElement()) {
	$arFields = $ob->GetFields();
	if ($arFields['PROPERTY_REALTOR_VALUE'] == $currentID) {
		$arCurrentRealtorOffersID[] = $arFields;
		if (!empty( $arRealtorOffers[$arFields['IBLOCK_ID']] )) {
			$arRealtorOffers[$arFields['IBLOCK_ID']]++;
		} else {
			$arRealtorOffers[$arFields['IBLOCK_ID']] = 1;
		}
	}
}*/




$number = $totalCount;

$term = getEnding($number);

$arResult['PROPERTIES'] = $arProperties;
?>
<div class="adress-agency adress-agent">
	<?= $arProperties['city']['MODIFIED_VALUE'] ?>
	<? if(!empty($arProperties['district']['MODIFIED_VALUE'])) echo ', '.$arProperties['district']['MODIFIED_VALUE'] ?>
	<? if(!empty($arProperties['microdistrict']['MODIFIED_VALUE'])) echo ', '.$arProperties['microdistrict']['MODIFIED_VALUE']; ?>
	<? if(!empty($arProperties['street']['VALUE'])) echo ', '.$arProperties['street']['VALUE']; ?>
</div>

<div class="info-card">
	<div class="refresh-date">№<?=$arResult["ID"]?></div>
	<div class="refresh-date">Размещено: <?= rus_date("j F Y", strtotime($arResult['DATE_CREATE'])); ?></div>
	<?
	//TO DO
	// сегодня\вчера\
	?>
	<div class="refresh">Обновлено: <strong><?= $arResult['TIMESTAMP_X'] ?></strong></div>

	<div class="num-view"><?= intval($arResult['SHOW_COUNTER']) ?></div>
</div><!--info-card-->

<div class="func-card">
	<div class="view-func">
		<ul>
			<? if ($isMap)
			{
				?><li class="item1"><a class="showmap" href="javascript:void(0);">На карте</a></li><?
			}?>
			
			<? if (!empty( $arProperties['file']['VALUE'] )): ?>
				<li class="item3"><a download href="<?= CFile::GetPath($arProperties['file']['VALUE']) ?>">Смотреть брошюру</a></li>
			<? endif; ?>
		</ul>
	</div><!--view-func-->
	<div class="action-func">
		<input id="item-id" type="hidden" value="<?= $arResult['ID'] ?>">
		<ul>
			<li class="item1"><a id="btn-favor" class="check-favor" data-element="<?=$arResult["ID"]?>" href="javascript:void(0);">В избранное</a></li>
			<li class="item2"><a href="#complain_form" class="inline cBoxElement">Пожаловаться</a></li>
			<li class="item3"><a href="javascript:void(0);" onclick="window.print();">Распечатать</a></li>
			<li class="item4"><a href="#pop13" class="inline cBoxElement">Поделиться</a></li>
		</ul>
	</div><!--action-func-->
</div><!--func-card-->
<?
$isImages = (bool) $arResult["DETAIL_PICTURE"] ||
	($arProperties['photo_gallery'] && count($arProperties['photo_gallery']) > 0 )
;
?>
<div class="card-advertise">
	<div class="card-l">
		<div class="card-gal">
			<div class="cont-big cont-all">
				<?if ($isImages) { ?>
				<div class="tab-big tab-all">
					<div class="main-img">
						<ul class="slider-big1">
							<? if ($arResult["DETAIL_PICTURE"]) {
								$photoLink = addWatermark($arResult["DETAIL_PICTURE"]);
								?>
								<li href="<?=$photoLink; ?>"><p><img src="<?= $photoLink ?>" alt=""/></p></li>
								<?
							}?>
							<? foreach ($arProperties['photo_gallery'] as $photo): ?>
								<? $photoLink = addWatermark($photo['VALUE']); ?>
								<li href="<?=$photoLink; ?>"><p><img src="<?= $photoLink ?>" alt=""/></p></li>
							<? endforeach; ?>
						</ul>
					</div><!--main-img-->
				</div><!--tab-big-->
				<?} ?>
				<? 
				if (count($arProperties['video_gallery']) > 0 && $arProperties['video_gallery'][0]["VALUE"])
				{
					?>
					<div class="tab-big tab-all">
						<div class="main-img">
							<style>
								.slider-big2 li, .slider-big2 iframe {
									width: 500px !important;
									height: 359px;
									display: inline-block;
								}
							</style>
							<ul class="slider-big2">
								<? foreach ($arProperties['video_gallery'] as $video): ?>
									<li>
											<?if(substr_count($video['VALUE'], "youtu") > 0):?>
												<?$link = str_replace("watch?v=", "embed/", $video['VALUE'])?>
												<iframe width="560" height="315" src="<?=$link?>" frameborder="0" allowfullscreen></iframe>
											<?else:?>
												<?$APPLICATION->IncludeComponent("bitrix:player","",Array(
														"PLAYER_TYPE" => "flv",
														"USE_PLAYLIST" => "N",
														//"PATH" => "https://www.youtube.com/watch?v=4EvNxWhskf8",
														//"PLAYLIST_DIALOG" => "",
														//											"PATH" => $video['VALUE'],
														"PROVIDER" => "youtube",
														"STREAMER" => "",
														"WIDTH" => "400",
														"HEIGHT" => "400",
														"PREVIEW" => "",
														"SKIN_PATH" => "/bitrix/components/bitrix/player/mediaplayer/skins",
														"SKIN" => "bitrix.swf",
														"CONTROLBAR" => "bottom",
														"WMODE" => "transparent",
														"PLAYLIST" => "right",
														"PLAYLIST_SIZE" => "180",
														"ADDITIONAL_FLASHVARS" => "
												width=500
												height=359
												",
														"WMODE_WMV" => "window",
														"SHOW_CONTROLS" => "Y",
														"PLAYLIST_TYPE" => "xspf",
														"PLAYLIST_PREVIEW_WIDTH" => "64",
														"PLAYLIST_PREVIEW_HEIGHT" => "48",
														"SHOW_DIGITS" => "Y",
														"CONTROLS_BGCOLOR" => "FFFFFF",
														"CONTROLS_COLOR" => "000000",
														"CONTROLS_OVER_COLOR" => "000000",
														"SCREEN_COLOR" => "000000",
														"AUTOSTART" => "n",
														"REPEAT" => "list",
														"VOLUME" => "90",
														"MUTE" => "N",
														"HIGH_QUALITY" => "Y",
														"SHUFFLE" => "N",
														"START_ITEM" => "1",
														"PLAYER_ID" => "",
														"BUFFER_LENGTH" => "10",
														"DOWNLOAD_LINK_TARGET" => "_self",
														"ADDITIONAL_WMVVARS" => "",
														"ALLOW_SWF" => "Y",
													)
												);?>
											<?endif;?>
									</li>
								<? endforeach; ?>
							</ul>
						</div><!--main-img-->
					</div><!--tab-big-->
					<?
				}
				?>


				<div class="tab-big tab-all">
					<div class="main-img">
						<ul class="slider-big3">
							<? if ($arResult["DETAIL_PICTURE"]) {
								$photoLink = addWatermark($arResult["DETAIL_PICTURE"]);
								?>
								<li><p><img src="<?= $photoLink ?>" alt=""/></p></li>
								<?
							}?>
							<? foreach ($arProperties['layouts_gallery'] as $photo): ?>
								<? $photoLink = addWatermark($photo['VALUE']); ?>
								<li><p><img src="<?= $photoLink ?>" alt=""/></p></li>
							<? endforeach; ?>
						</ul>
					</div><!--main-img-->
				</div>
				<?if ($isMap) {?>
				<div class="tab-big tab-all map-state">
					
						<?$arTemp["hintContent"] = $arResult["NAME"];
						$coords = explode(",", $isMap);
						$arTemp["LAT"] = $coords[0];
						$arTemp["LON"] = $coords[1];
						$arMapData["PLACEMARKS"][] = $arTemp;
						$arMapData["yandex_lat"] = $arTemp["LAT"];
						$arMapData["yandex_lon"] = $arTemp["LON"];
						$arMapData["yandex_scale"] = "16";
						?>
							<?$APPLICATION->IncludeComponent("bitrix:map.yandex.view","contacts",Array(
							        "INIT_MAP_TYPE" => "MAP",
									"MAP_DATA" => serialize($arMapData),
							        "MAP_WIDTH" => "auto",
							        "MAP_HEIGHT" => "auto",
							        "CONTROLS" => array(
							            "TOOLBAR",
							        ),
							        "OPTIONS" => array(
							            "ENABLE_DBLCLICK_ZOOM",
							            "ENABLE_DRAGGING"
							        ),
							        "MAP_ID" => "newbuildings_1"
							    )
							);?>
				</div>
				<?}?>
			</div><!--cont-big-->

			<div class="nav-gal">
				<ul>
					<? if (count($arProperties['photo_gallery']) > 0 && $isImages): ?>
						<li class="active"><span>Фотографии</span></li>
						<? $have_photo = true ?>
					<? endif; ?>
					<? if (count($arProperties['video_gallery']) > 0 && $arProperties['video_gallery'][0]["VALUE"]): ?>
						<li <? if ($have_photo != true) {
							echo 'class="active"';
						} ?>><span>Видео</span></li>
						<? $have_video = true ?>
					<? endif; ?>
					<? if (count($arProperties['layouts_gallery']) > 0): ?>
						<li <? if ($have_photo != true && $have_video != true) {
							echo 'class="active"';
						} ?>><span>Планировки</span></li>
					<? endif; ?>

				</ul>
			</div><!--nav-gal-->

			<div class="cont-thumbs cont-all">
				<?
				if ($isImages) {
				?>
				<div class="tab-thumbs tab-all">
					<div class="thumb-img only-three-rows" id="bx-pager">
						<? $counter = 0; ?>
						<? if ($arResult["DETAIL_PICTURE"]) {
							//$photoLink = 'http://' . $_SERVER['HTTP_HOST'] . '/' . CFile::GetPath($arResult["DETAIL_PICTURE"]); 
							$photoLink = AddWaterMarkResized($arResult["DETAIL_PICTURE"],121 ,100,CImg::M_PROPORTIONAL);
							?>
							<a class="full-screener" data-slide-index="<?= $counter ?>" href=""><p><img src="<?= $photoLink ?>" alt=""/></p>
							</a>
							<? $counter++;
						}?>
						<? foreach ($arProperties['photo_gallery'] as $photo): ?>
							<?

							//$photoLink = 'http://' . $_SERVER['HTTP_HOST'] . '/' . CFile::GetPath($photo['VALUE']); 
							$photoLink = AddWaterMarkResized($photo['VALUE'],121 ,100,CImg::M_PROPORTIONAL);
							?>
							<a class="full-screener" data-slide-index="<?= $counter ?>" href=""><p><img src="<?= $photoLink ?>" alt=""/></p>
							</a>
							<? $counter++; ?>
						<? endforeach; ?>
					</div>
					<div class="temp-hidden toogle-thumbs-row"><p><a href="javascript:void(0);" data-other-var="Скрыть">Показать все</a></p></div>
				</div><!--tab-thumbs-->
				<?} ?>
				
				<? 
				if (count($arProperties['video_gallery']) > 0 && $arProperties['video_gallery'][0]["VALUE"])
				{
					?>
						<div class="tab-thumbs tab-all">
							<div class="thumb-img" id="bx-pager2">
								<? foreach ($arProperties['video_gallery'] as $video): ?>
									<a data-slide-index="0" href="">
											<?if(substr_count($video['VALUE'], "youtu") > 0):?>
												<?$link = str_replace("watch?v=", "embed/", $video['VALUE'])?>
												<iframe width="560" height="315" src="<?=$link?>" frameborder="0" allowfullscreen></iframe>
											<?else:?>
												<?$APPLICATION->IncludeComponent("bitrix:player","",Array(
														"PLAYER_TYPE" => "flv",
														"USE_PLAYLIST" => "N",
														//"PATH" => "https://www.youtube.com/watch?v=4EvNxWhskf8",
														//"PLAYLIST_DIALOG" => "",
														//											"PATH" => $video['VALUE'],
														"PROVIDER" => "youtube",
														"STREAMER" => "",
														"WIDTH" => "400",
														"HEIGHT" => "400",
														"PREVIEW" => "",
														"SKIN_PATH" => "/bitrix/components/bitrix/player/mediaplayer/skins",
														"SKIN" => "bitrix.swf",
														"CONTROLBAR" => "bottom",
														"WMODE" => "transparent",
														"PLAYLIST" => "right",
														"PLAYLIST_SIZE" => "180",
														"ADDITIONAL_FLASHVARS" => "
													width=500
													height=359
													",
														"WMODE_WMV" => "window",
														"SHOW_CONTROLS" => "Y",
														"PLAYLIST_TYPE" => "xspf",
														"PLAYLIST_PREVIEW_WIDTH" => "64",
														"PLAYLIST_PREVIEW_HEIGHT" => "48",
														"SHOW_DIGITS" => "Y",
														"CONTROLS_BGCOLOR" => "FFFFFF",
														"CONTROLS_COLOR" => "000000",
														"CONTROLS_OVER_COLOR" => "000000",
														"SCREEN_COLOR" => "000000",
														"AUTOSTART" => "n",
														"REPEAT" => "list",
														"VOLUME" => "90",
														"MUTE" => "N",
														"HIGH_QUALITY" => "Y",
														"SHUFFLE" => "N",
														"START_ITEM" => "1",
														"PLAYER_ID" => "",
														"BUFFER_LENGTH" => "10",
														"DOWNLOAD_LINK_TARGET" => "_self",
														"ADDITIONAL_WMVVARS" => "",
														"ALLOW_SWF" => "Y",
													)
												);?>
											<?endif;?>
									</a>
								<? endforeach; ?>
							</div>
						</div>
					<?
				}
				?>

				
				

				<div class="tab-thumbs tab-all">
					<div class="thumb-img" id="bx-pager3">
						<? foreach ($arProperties['layouts_gallery'] as $index=> $photo): ?>
							<? $photoLink = CFile::GetPath($photo['VALUE']); ?>
							<a data-slide-index="<?=$index?>" href=""><p><img src="<?= $photoLink ?>" alt=""/></p></a>
						<? endforeach; ?>
					</div>
				</div><!--tab-thumbs-->
			</div><!--cont-thumbs-->
		</div><!--card-gal-->

		<div class="text-card">
			<div class="newbuilding_detail_text detail_text">
				<?= $arResult['DETAIL_TEXT'] ?>
			</div>
			<div class="show_detail_text_wrapper">
				<a href="javascript:void(0);" class="show_detail_text" data-on="Свернуть" data-off="Развернуть"></a>
			</div>
		</div>
	</div><!--card-l-->

	<div class="card-r">
		<? 
		if ($arProperties["realtor"]["VALUE"])
		{
			\SiteTemplates\Realtor::getInstance($arProperties["realtor"]["VALUE"])->shortInfo(array("HIDE_AGENT"=>(strtolower($arProperties["realtor_not"]["VALUE_ENUM"]) == "да")? "Y" : "N"));
		}
		?>

		<div class="price-card price-card2 sell-item-price-field">
		<? 
		if ($arProperties['currency']['VALUE_XML_ID'])
		{
			if ($arProperties['price']['VALUE'])
			{
				?>
				<?= $arProperties['price']['VALUE'] ?> <?=str_replace("RUB", '<font class="ruble medium-ruble">p</font>', $arProperties['currency']['VALUE_ENUM']) ?>
				<?
			}
			if ($arProperties['price_1m']['VALUE'])
			{
				?>
				<span>/ <?= $arProperties['price_1m']['VALUE'] ?> <?= str_replace("RUB", '<font class="ruble medium-ruble">f</font>', $arProperties['currency']['VALUE_ENUM']) ?>
				за м²</span>
				<?
			}
			else if($arProperties["summary_apartment_square"]['VALUE'])
			{
				?>
				<span>/ <?= MoneyOutPut(intval(str_replace(" ","",$arProperties['price']['VALUE']))/intval($arProperties["summary_apartment_square"]['VALUE'])) ?> <?= str_replace("RUB", '<font class="ruble medium-ruble">f</font>', $arProperties['currency']['VALUE_ENUM']) ?>
				за м²</span>
				<?
			}
			/*else if($arProperties["square"]['VALUE'])
			{
				?>
				<span>/ <?= MoneyOutPut(intval(str_replace(" ","",$arProperties['price']['VALUE']))/intval($arProperties["square"]['VALUE'])) ?> <?= $arProperties['currency']['VALUE_XML_ID'] ?>
				за м²</span>
				<?
			}*/
		}

		?>
			
			
		</div>
			<? if ($arProperties['tel']['VALUE'])
			{
				?>
		<div class="ipoteka"><span><span class="contact_phone"></span><?= $arProperties['tel']['VALUE'] ?></span> (собственник)</div>
				<?
			}?>

		<div class="params-card params-card2">
			<ul>
			<?
			if ($arProperties["STATUS"]["VALUE"])
			{
				?>
				<li><span>Статус</span> <span><?= $arProperties["STATUS"]["VALUE_ENUM"] ?></span></li>
				<?
			}
			?>
			<? if ($arProperties['flat_square']['VALUE']) 
			{
				?>
					<li><span>Площадь общая</span> <span><?= $arProperties['flat_square']['VALUE'] ?> м2</span></li>
				<?
			}?>
			<? if ($arProperties['square']['VALUE']) 
			{
				?>
					<li><span>Площадь</span> <span><?= $arProperties['square']['VALUE'] ?> м2</span></li>
				<?
			}?>
			<? if ($arProperties['summary_buildings_square']['VALUE']) 
			{
				?>
					<li><span>Общая площадь строений</span> <span><?= $arProperties['summary_buildings_square']['VALUE'] ?> м2</span></li>
				<?
			}?>
			<? if ($arProperties['summary_apartment_square']['VALUE']) 
			{
				?>
					<li><span>Общая площадь дома</span> <span><?= $arProperties['summary_apartment_square']['VALUE'] ?> м2</span></li>
				<?
			}?>
			
			
			
			
			
			<? if ($arProperties[MorealtySettings::$FLOAT_PROP_LIVING_SQUARE_CODE]['VALUE']) 
			{
				?>
					<li><span>Жилая площадь</span> <span><?= $arProperties[MorealtySettings::$FLOAT_PROP_LIVING_SQUARE_CODE]['VALUE'] ?> м2</span></li>
				<?
			}?>
			<? if ($arProperties[MorealtySettings::$FLOAT_PROP_KITCHEN_SQUARE_CODE]['VALUE']) 
			{
				?>
					<li><span>Площадь кухни</span> <span><?= $arProperties[MorealtySettings::$FLOAT_PROP_KITCHEN_SQUARE_CODE]['VALUE'] ?> м2</span></li>
				<?
			}?>
			
			
			
			
			
			
			<? if ($arProperties['sector_square']['VALUE']) 
			{
				if (!$arProperties["dimension"]["VALUE_ENUM"] && $arProperties["plot_dimension"]["VALUE_ENUM"])
				{
					$arProperties["dimension"]["VALUE_ENUM"] = $arProperties["plot_dimension"]["VALUE_ENUM"];
				}
				?>
					<li><span>Площадь участка </span> <span><?= $arProperties['sector_square']['VALUE'] ?> <?=($arProperties["dimension"]["VALUE_ENUM"]) ? $arProperties["dimension"]["VALUE_ENUM"] : "м2"?></span></li>
				<?
			}?>
			<? if ($arProperties['room_number']['VALUE']) 
			{
				?>
					<li><span>Количество комнат</span> <span><?= $arProperties['room_number']['VALUE'] ?></span></li>
				<?
			}?>
			<? if ($arProperties['number_of_bedrooms']['VALUE']) 
			{
				?>
					<li><span>Количество спален </span> <span><?= $arProperties['number_of_bedrooms']['VALUE'] ?></span></li>
				<?
			}?>
			<? if ($arProperties['floor']['VALUE']) 
			{
				$etaj = "Этажей";
				if ($arResult["IBLOCK_ID"] == 7)
					$etaj = "Этаж";
				?>
					<li><span><?=($arResult["IBLOCK_ID"] == 7 && $arProperties["newbuilding"]["ELEM"]["PROPERTIES"]["floors"]["VALUE"])? "Этаж / этажей" : $etaj;?></span> <span><?= $arProperties['floor']['VALUE'] ?><?=($arResult["IBLOCK_ID"] == 7 && $arProperties["newbuilding"]["ELEM"]["PROPERTIES"]["floors"]["VALUE"])? " / ".$arProperties["newbuilding"]["ELEM"]["PROPERTIES"]["floors"]["VALUE"] : "";?></span></li>
				<?
			}?>
			<? if ($arProperties['number_of_storeys']['VALUE']) 
			{
				
				$etaj = "Этажей";
				if ($arResult["IBLOCK_ID"] == 7)
					$etaj = "Этаж";
				?>
					<li><span><?=$etaj?></span> <span><?= $arProperties['number_of_storeys']['VALUE_ENUM'] ?></span></li>
				<?
			}?>
			<? if ($arProperties['decoration']['VALUE_ENUM']) 
			{
				$TargetName = "";
				switch ($arResult["IBLOCK_ID"])
				{
					case "7":
						$TargetName = "квартиры";
					break;
					case "8":
						$TargetName = "дома";
					break;
				}
				?>
					<li><span>Отделка <?=$TargetName?></span> <span><?= $arProperties['decoration']['VALUE_ENUM'] ?></span></li>
				<?
			}?>
			<? 
			if ($arProperties["newbuilding"]['ELEM']["PROPERTIES"]["distance_to_sea"]["VALUE"] && $arProperties["newbuilding"]['ELEM']["PROPERTIES"]["dimension_distance_to_sea"]["VALUE"])
			{
				?>
				<li><span>Расстояние до моря:</span> <span><?=$arProperties["newbuilding"]['ELEM']["PROPERTIES"]["distance_to_sea"]["VALUE"]?> <?=$arProperties["newbuilding"]['ELEM']["PROPERTIES"]["dimension_distance_to_sea"]["VALUE_ENUM"]?></span></li>
				<?
			}
			else if ($arProperties["distance_to_sea"]["VALUE"] && $arProperties["dimension_distance_to_sea"]["VALUE_ENUM"])
			{
				?>
				<li><span>Расстояние до моря:</span> <span><?=$arProperties["distance_to_sea"]["VALUE"]?> <?=$arProperties["dimension_distance_to_sea"]["VALUE_ENUM"]?></span></li>
				<?
			}
			?>
			
			<? if ($arProperties['wc']['VALUE_ENUM']) 
			{
				?>
					<li><span>Санузел</span> <span><?= $arProperties['wc']['VALUE_ENUM'] ?></span></li>
				<?
			}?>
			<? if ($arProperties["special_purpose"]["VALUE_ENUM"]) 
			{
				?>
					<li><span>Целевое назначение</span> <span><?= $arProperties['special_purpose']['VALUE_ENUM'] ?></span></li>
				<?
			}?>

			<?/* if ($arProperties["number_of_storeys"]["VALUE_ENUM"]) 
			{
				?>
					<li><span>Этажность</span> <span><?= $arProperties['number_of_storeys']['VALUE_ENUM'] ?></span></li>
				<?
			}*/?>
			<? if ($arProperties["have_phone"]["VALUE_ENUM"])
			{
				?>
					<li><span>Телефон</span> <span>есть</span></li>
				<?
			} 
			?>
			<? if ($arProperties["have_loggia"]["VALUE_ENUM"])
			{
				?>
					<li><span>Лоджия</span> <span>есть</span></li>
				<?
			} 
			?>
			<? if ($arProperties["have_balcony"]["VALUE_ENUM"])
			{
				?>
					<li><span>Балкон</span> <span>есть</span></li>
				<?
			} 
			?>
			<? if ($arProperties["have_furniture"]["VALUE_ENUM"])
			{
				?>
					<li><span><?=$sellOrArendString?> С мебелью</span> <span>да</span></li>
				<?
			} 
			?>
			<? if ($arProperties["can_mortgage"]["VALUE_ENUM"])
			{
				?>
					<li><span>Возможна ипотека</span> <span>да</span></li>
				<?
			} 
			?>
			<? if ($arProperties['garage']['VALUE']) 
			{
				?>
					<li><span>Гараж </span> <span>Да</span></li>
				<?
			}?>
			<? if ($arProperties['build_year']['VALUE']) 
			{
				?>
					<li><span>Год постройки </span> <span><?=date("Y",MakeTimeStamp($arProperties['build_year']['VALUE']))?></span></li>
				<?
			}?>
			<? 
			if ($Type)
			{
				?><li><span>Тип предложения</span> <span><?=$Type?></span></li><?
			}
			?>

			
			</ul>
		</div><!--params-card-->

		<?/* ?><div class="b-investor b-investor2 temp-hidden">
			<div class="link-investor">
				<a href="#">Сведения о ЖД на ул. Воровского, 45</a>
				<span>Сочи, Хостинский район, Кудепста пос., ул. Камо</span>
			</div>

			<div class="body-spec">
				<div class="img-body-spec"><img src="<?= SITE_TEMPLATE_PATH ?>/images_tmp/img-body-spec.jpg" alt=""/>
				</div><!--img-body-spec-->
				<div class="desc-body-spec">
					<div class="info-r">
						<ul>
							<li>
								Застройщик
								<span>ООО "БГ-Инвест"</span>
							</li>
							<li>
								Срок сдачи:
								<span>III кв. 2016 г.</span>
							</li>
						</ul>
					</div><!--info-r-->
				</div>
			</div><!--body-spec-->

			<div class="list-invest">
				<ul>
					<li><span>Класс:</span> <span>бизнес</span></li>
					<li><span>Тип: здания</span><span>монолитное</span></li>
					<li><span>Этажей:</span> <span>12</span></li>
					<li><span>Парковка:	</span> <span>подземная</span></li>
					<li><span>Лифт:</span> <span>есть</span></li>
					<li><span>Расстояние до моря:</span> <span>2 км</span></li>
				</ul>
			</div><!--list-invest-->

			<div class="more-invest"><a href="#">Подробнее</a></div>
		</div><!--b-investor--><? */?>
		<? 
		if ($arProperties["newbuilding"]["ELEM"])
		{
			$APPLICATION->IncludeComponent("wexpert:includer", "newbuilding",
					array(
						"ITEMS"=>array($arProperties["newbuilding"]["ELEM"]),	
						"SHOW_PROPERTY" => array(
							"class", "type", "floors", "square_ot", "square_do",
							"price_m2_ot", "price_m2_do", "deadline"=>array("NAME"=>"Срок сдачи")),
			),$component);
		}

		?>
	</div><!--card-r-->
</div><!--card-advertise-->

<?

$Filter = propsToORFilter(array(
		
		"room_number",
		"floor",
		"price",
		"price_1m",
		//"realtor",
		"square",
		"newbuilding",
		"summary_buildings_square",
		"summary_apartment_square",
		"sector_square",
		"square"
), $arResult["PROPERTIES"],
			array(
				"MAIN" => array("price"),
				"PRICE" => array("price","price_1m"),
				"SQUARE"	=> array("square"),
					
			)
		);

if ($Filter && count($Filter) > 0)
{
	$Filter["!ID"] = $arResult["ID"];
	?>
	<?$APPLICATION->IncludeComponent("wexpert:iblock.list", "similar_objects",Array(
		"ORDER"                             => array("SORT" => "ASC"),
		"FILTER"                            => $Filter,
		"IBLOCK_ID"							=> $arResult["IBLOCK_ID"],
		"PAGESIZE"						    => 15,
		"SELECT"						    => "DATE_CREATE",
		"GET_PROPERTY"						=> "Y",
		"CACHE_TIME"    					=> $arParams["CACHE_TIME"],
		"CACHE_TYPE"						=> $arParams["CACHE_TYPE"]
	),$component); ?>
	<?
}
?>


<?/* ?><div class="b-propos b-propos2 temp-hidden">
	<div class="siblings-obj">
		<div class="t-emploe">Другие предложения риэлтора</div>
		<?
		$arSelect = Array("ID", "NAME");
		$arFilter = Array("IBLOCK_ID" => 7, "PROPERTY_realtor_VALUE" => 23, "ACTIVE" => "Y");
		$res      = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize" => 50), $arSelect);
		while ($ob = $res->GetNextElement()) {
			$arFields = $ob->GetFields();
		}
		?>
		<div class="slier-objects">
			<div class="slider1">
				<? foreach ($arCurrentRealtorOffersID as $currentOffer): ?>
					<?
					$res = CIBlockElement::GetByID($currentOffer['PROPERTY_CITY_VALUE']);
					//city
					if ($ar_res = $res->GetNext()) {
						$currentOffer['PROPERTY_CITY_VALUE'] = $ar_res['NAME'];
					}
					$res = CIBlockElement::GetByID($currentOffer['PROPERTY_DISTRICT_VALUE']);
					//district
					if ($ar_res = $res->GetNext()) {
						$currentOffer['PROPERTY_DISTRICT_VALUE'] = $ar_res['NAME'];
					}
					$res = CIBlockElement::GetByID($currentOffer['PROPERTY_MICRODISTRICT_VALUE']);
					//microdistrict
					if ($ar_res = $res->GetNext()) {
						$currentOffer['PROPERTY_MICRODISTRICT_VALUE'] = $ar_res['NAME'];
					}
					//apartment_complex
					$res = CIBlockElement::GetByID($currentOffer['PROPERTY_APARTMENT_COMPLEX_VALUE']);
					if ($ar_res = $res->GetNext()) {
						$currentOffer['PROPERTY_APARTMENT_COMPLEX_VALUE'] = $ar_res['NAME'];
					}
					//price view
					$price    = (string)$currentOffer['PROPERTY_PRICE_VALUE'];
					$priceLen = strlen($price);
					if ($priceLen >= 5) {
						switch ($priceLen) {
							case 5:
								$price = substr($price, 0, 2) . ' ' . substr($price, 2, 3);
								break;
							case 6:
								$price = substr($price, 0, 3) . ' ' . substr($price, 3, 3);
								break;
							case 7:
								$price = substr($price, 0, 1) . ' ' . substr($price, 1, 3) . ' ' . substr($price, 4, 3);
								break; //
							case 8:
								$price = substr($price, 0, 2) . ' ' . substr($price, 2, 3) . ' ' . substr($price, 5, 3);
								break;
							case 9:
								$price = substr($price, 0, 3) . ' ' . substr($price, 3, 3) . ' ' . substr($price, 6, 3);
								break;
							case 10:
								$price = substr($price, 0, 1) . ' ' . substr($price, 1, 3) . ' ' . substr($price, 4, 3) . ' ' . substr($price, 7, 3);
								break;
							default:
								break;
						}
						$currentOffer['PROPERTY_PRICE_VALUE'] = $price;
					}
					//CURRENCY
					switch ($currentOffer['PROPERTY_CURRENCY_VALUE']) {
						case 'Рубли':
							$currentOffer['PROPERTY_CURRENCY_VALUE'] = 'руб.';
							break;
						case 'Доллары':
							$currentOffer['PROPERTY_CURRENCY_VALUE'] = '$';
							break;
						case 'Евро':
							$currentOffer['PROPERTY_CURRENCY_VALUE'] = 'E';
							break;
					}
					//
					$res          = CIBlock::GetList(array('SORT' => 'ASC'), array('TYPE' => 'catalog', 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y'), true, array('ID', 'NAME'));
					$arSectionIDS = '';
					while ($ob = $res->GetNext()) {
						$arSectionIDS[] = $ob['ID'];
					}
					foreach ($arSectionIDS as $id) {
						$db_props = CIBlockElement::GetProperty($id['ID'], $currentOffer['ID'], array("sort" => "asc"), Array("CODE" => "photo_gallery"));
						if ($ar_img = $db_props->Fetch()) {
							break;
						} // ok
					}

					?>
					<div class="slide">
						<div class="item-propos">
							<div class="top-propos">
								<div class="img-propos">
									<a href="#">
										<img width='100%' src="<?= CFile::GetPath($ar_img['VALUE']) ?>" alt="">
									</a>
								</div>
								<div class="t-propos"><p><a href="#"><?= $currentOffer['NAME'] ?></a></p></div>
							</div><!--top-propos-->

							<div class="bot-propos">
								<div class="adress-p"><?= $currentOffer['PROPERTY_CITY_VALUE'] ?>
									, <?= $currentOffer['PROPERTY_DISTRICT_VALUE'] ?>
									, <?= $currentOffer['PROPERTY_MICRODISTRICT_VALUE'] ?>
									, <?= $currentOffer['PROPERTY_APARTMENT_COMPLEX_VALUE'] ?>
								</div>
								<div
									class="price-p"><?= $currentOffer['PROPERTY_PRICE_VALUE'] ?> <?= $currentOffer['PROPERTY_CURRENCY_VALUE'] ?>
								</div>
								<div class="params-p">
									<ul>
										<li>Общая площадь: <span>53,2</span> м2</li>
										<li>Этаж: <span><?= $currentOffer['PROPERTY_FLOOR_VALUE'] ?></span></li>
									</ul>
								</div>

								<div class="compl-prop"><?= $currentOffer['PROPERTY_APARTMENT_COMPLEX_VALUE'] ?></div>
							</div><!--bot-propos-->
						</div><!--item-propos-->
					</div><!--slide-->
				<? endforeach; ?>
			</div><!--slider1-->
		</div><!--slider-objects-->

		<div class="more"><a href="#">Смотреть все предложения</a> <span><?= $totalCount ?></span></div><!--more-->
	</div><!--siblings-obj-->


</div><!--b-propos--><? */?>
