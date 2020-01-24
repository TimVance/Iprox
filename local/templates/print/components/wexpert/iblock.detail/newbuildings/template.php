<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
CIBlockElement::CounterInc($arResult['ID']);

$arItems = $arResult['arItems'];
$arUser = $arResult['arUser'];
$arProperties = $arResult['PROPERTIES'];

/*if ($USER->GetLogin() == "vadim") {

	my_print_r($arResult["PROPERTIES"]["yandex_map"]);
}*/
?>

<div class="adress-agency">
	<?= $arProperties['city']['MODIFIED_VALUE'] ?>
	, <?= $arProperties['district']['MODIFIED_VALUE'] ?>
	, <?= $arProperties['microdistrict']['MODIFIED_VALUE'] ?>
	, <?= $arProperties['street']['VALUE'] ?>
</div>

<div class="info-card">
	<? $curTimestamp = rus_date("j F Y", strtotime($arResult['TIMESTAMP_X'])); ?>
	<div class="refresh">Обновлено: <strong><?=$curTimestamp?></strong></div>
	<div class="num-view"><?= intval($arResult['SHOW_COUNTER']) ?></div>
</div>

<div class="func-card">
	<div class="view-func">
		<ul>
			<?if ($arProperties["yandex_map"]["VALUE"]) {
				?>
					<li class="item1"><a class="showmap" href="javascript:void(0);">На карте</a></li>
				<?
			} ?>
		
			<?/* ?><li class="item2"><a href="<?=$arResult['DETAIL_PAGE_URL']?>plan/">Планировка</a></li><? */?>
			<? if (!empty( $arProperties['brochure_html']['~VALUE']['TEXT'] )): ?>
				<li class="item3"><a class="inline cBoxElement" href="#broshure_html">Смотреть брошюру</a></li>
			<? elseif (!empty( $arProperties['file']['VALUE'] )): ?>
				<li class="item3"><a download href="<?= CFile::GetPath($arProperties['file']['VALUE']) ?>">Смотреть брошюру</a></li>
			<? endif; ?>
			<li><a href="<?=$arResult['DETAIL_PAGE_URL']?>questions/">Вопросы</a></li>
		</ul>
	</div>
	<div class="action-func">
		<input id="item-id" type="hidden" value="<?= $arResult['ID'] ?>">
		<ul>
			<li class="item1"><a id="btn-favor" data-element="<?=$arResult["ID"]?>" class="check-favor" href="javascript:void(0);">В избранное</a></li>
			<li class="item2"><a href="#complain_form" class="inline cBoxElement">Пожаловаться</a></li>
			<li class="item3"><a target="_blank" href="?PRINT=Y">Распечатать</a></li>
			<li class="item4"><a href="#pop13" class="inline cBoxElement">Поделиться</a></li>
		</ul>
	</div>
</div>

<div class="card-advertise">
	<div class="card-l">
		<div class="card-gal">
			<div class="cont-big cont-all">
				<div class="tab-big tab-all">
					<div class="main-img">
						<ul class="slider-big1">
							<?$counter = 0;?>
							<? foreach ($arProperties['photo_gallery'] as $photo): ?>
								<? $photoLink = addWatermark($photo['VALUE']); ?>
								
								<?//=CFile::GetPath($photo['VALUE']); ?>
									<li href="<?=$photoLink; ?>" ><p><img src="<?= $photoLink ?>" alt=""/></p></li>
								<?$counter++;?>
							<? endforeach; ?>
						</ul>
					</div>
				</div>

				<div class="tab-big tab-all">
					<div class="main-img">
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
					</div>
				</div>

				<div class="tab-big tab-all">
					<div class="main-img">
						<ul class="slider-big3">
							<? foreach ($arProperties['layouts_gallery'] as $photo): ?>
								<? $photoLink = 'http://' . $_SERVER['HTTP_HOST'] . '/' . addWatermark($photo['VALUE']); ?>
								<li><p><img src="<?= $photoLink ?>" alt=""/></p></li>
							<? endforeach; ?>
						</ul>
					</div>
				</div>
				<div class="tab-big tab-all map-state">
					<?	
					if ($arProperties["yandex_map"]["VALUE"]) {
						$arTemp["hintContent"] = $arResult["NAME"];
						$coords = explode(",", $arProperties["yandex_map"]["VALUE"]);
						$arTemp["LAT"] = $coords[0];
						$arTemp["LON"] = $coords[1];
						$arMapData["PLACEMARKS"][] = $arTemp;
						$arMapData["yandex_lat"] = $arTemp["LAT"];
						$arMapData["yandex_lon"] = $arTemp["LON"];
						$arMapData["yandex_scale"] = "16";
						?>
							<?$APPLICATION->IncludeComponent("bitrix:map.yandex.view","newbuildings",Array(
							        "INIT_MAP_TYPE" => "MAP",
									"MAP_DATA" => serialize($arMapData),
							        "MAP_WIDTH" => "auto",
							        "MAP_HEIGHT" => "auto",
							        "CONTROLS" => array(

							        ),
									"ONMAPREADY"=> "EventMapReady",
									
							        "OPTIONS" => array(
							            "ENABLE_DBLCLICK_ZOOM",
							            "ENABLE_DRAGGING"
							        ),
							        "MAP_ID" => "newbuildings_1"
							    )
							);?>
						<?
					}?>
					

				</div>
			</div>
			<div class="nav-gal">
				<ul>
					<? if (count($arProperties['photo_gallery']) > 0): ?>
						<li class="active"><span>Фотографии</span></li>
						<? $have_photo = true ?>
					<? endif; ?>
					<? if (count($arProperties['video_gallery']) > 0): ?>
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
			</div>

			<div class="cont-thumbs cont-all">
				<div class="tab-thumbs tab-all">
					<div class="thumb-img" id="bx-pager">
						<? $counter = 0; ?>
						<? foreach ($arProperties['photo_gallery'] as $photo): ?>
							<? $photoLink = CFile::GetPath($photo['VALUE']); ?>
							<a data-slide-index="<?= $counter ?>" href=""><p><img src="<?= $photoLink ?>" alt=""/></p>
							</a>
							<? $counter++; ?>
						<? endforeach; ?>
					</div>
				</div>

				<div class="tab-thumbs tab-all">
					<div class="thumb-img" id="bx-pager2">
						<?$counter = 0;?>
						<? foreach ($arProperties['video_gallery'] as $video): ?>
							<a data-slide-index="<?=$counter?>" href="">
								<span class="glush"></span>
								<?if(substr_count($video['VALUE'], "youtu") > 0):?>
									<?$link = str_replace("watch?v=", "embed/", $video['VALUE'])?>
									<iframe width="117" height="80" src="<?=$link?>" frameborder="0" allowfullscreen></iframe>
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
							<?$counter++;?>
						<? endforeach; ?>
					</div>
				</div>

				<div class="tab-thumbs tab-all">
					<div class="thumb-img" id="bx-pager3">
						<? foreach ($arProperties['layouts_gallery'] as $photo): ?>
							<? $photoLink = CFile::GetPath($photo['VALUE']); ?>
							<a data-slide-index="0" href=""><p><img src="<?= $photoLink ?>" alt=""/></p></a>
						<? endforeach; ?>
					</div>
				</div>
			</div>
		</div>

		<div class="tabs-info tabs-tb">
			<div class="nav-tabs-info nav-tb">
				<ul>
					<li><span>Общая информация</span></li>
					<li><span>Конкурентные преимущества</span></li>
				</ul>
			</div>

			<div class="conts-info cont-tb">
				<div class="tab-info tab-tb">
					<?= $arResult['DETAIL_TEXT'] ?>

					<div class="accord-card accord-card2">
						<?if(!empty($arProperties['architectural_features']['VALUE']['TEXT'])):?>
							<div class="item-accrod">
								<div class="tit-accord"><span>Архитектурные особенности</span></div>
								<div class="text-accrod">
									<?=$arProperties['architectural_features']['VALUE']['TEXT']?>
								</div>
							</div>
						<?endif;?>

						<?if(!empty($arProperties['constructive_solutions']['VALUE']['TEXT'])):?>
							<div class="item-accrod">
								<div class="tit-accord"><span>Конструктивные решения</span></div>
								<div class="text-accrod">
									<?=$arProperties['constructive_solutions']['VALUE']['TEXT']?>
								</div>
							</div>
						<?endif;?>

						<?if(!empty($arProperties['decoration_html']['VALUE']['TEXT'])):?>
							<div class="item-accrod">
								<div class="tit-accord"><span>Отделка</span></div>
								<div class="text-accrod">
									<?=$arProperties['decoration_html']['VALUE']['TEXT']?>
								</div>
							</div>
						<?endif;?>

						<?if(!empty($arProperties['life_support_system']['VALUE']['TEXT'])):?>
							<div class="item-accrod">
								<div class="tit-accord"><span>Системы жизнеобеспечения</span></div>
								<div class="text-accrod">
									<?=$arProperties['life_support_system']['VALUE']['TEXT']?>
								</div>
							</div>
						<?endif;?>

						<?if(!empty($arProperties['internal_infrastructure']['VALUE']['TEXT'])):?>
							<div class="item-accrod">
								<div class="tit-accord"><span>Внутренняя инфраструктура</span></div>
								<div class="text-accrod">
									<?=$arProperties['internal_infrastructure']['VALUE']['TEXT']?>
								</div>
							</div>
						<?endif;?>

						<?if(!empty($arProperties['control']['VALUE']['TEXT'])):?>
							<div class="item-accrod">
								<div class="tit-accord"><span>Управление</span></div>
								<div class="text-accrod">
									<?=$arProperties['control']['VALUE']['TEXT']?>
								</div>
							</div>
						<?endif;?>

						<?if(!empty($arProperties['district_infrastructure']['VALUE']['TEXT'])):?>
							<div class="item-accrod">
								<div class="tit-accord"><span>Инфраструктура района</span></div>
								<div class="text-accrod">
									<?=$arProperties['district_infrastructure']['VALUE']['TEXT']?>
								</div>
							</div>
						<?endif;?>
					</div>
				</div>

				<div class="tab-info tab-tb"><p>Конкурентные преимущества</p></div>
			</div>
		</div>
	</div>
	<div class="card-r">
		<div class="price-card">от <?= number_format($arProperties['price_m2_ot']['VALUE'], 0, '', ' ') ?> <?= $arProperties['currency']['VALUE_XML_ID'] ?> за м<sup>2</sup></div>
		<div class="params-card">
			<ul>
				<?foreach($arParams['SHOW_PROPERTY'] as $code):?>
					<?
						$property = $arProperties[$code];
						$square_dimension = "м<sup>2</sup>";
						$metres = 'м';
					?>

					<? //если список ?>
					<?if(!empty($property['VALUE_ENUM'])):?>
						<li><span><?=$property['NAME']?></span> <span><?=$property['VALUE_ENUM']?></span></li>

					<? //если HTML поле ?>
					<?elseif($property['VALUE']['TYPE'] == 'HTML'):?>
						<li><span><?=$property['NAME']?></span> <span><?=$property['VALUE']['TEXT']?></span></li>

					<? //если текст, с исключениями(возможно + размерность) ?>
					<?elseif(!empty($property['VALUE'])):?>

						<?
						if(is_numeric($property['VALUE'])) {
							$property['VALUE'] = number_format($property['VALUE'], 0, '', ' ');
						}

						if($code == 'distance_to_sea') {
							$property['VALUE'] .= ' ' . $arProperties['dimension_distance_to_sea']['VALUE_ENUM'];
						} elseif($code == 'square') {
							$property['VALUE'] .= ' ' . $square_dimension;
						} elseif($code == 'square_do' || $code == 'price_m2_do') {
							continue;
						} elseif($code == 'square_ot') {
							$property['NAME'] = str_replace("от", '', $property['NAME']);
							$property['VALUE'] = 'от ' . $property['VALUE'] .  ' ' . $square_dimension . ' до ' . $arProperties['square_do']['VALUE'] . ' ' . $square_dimension;
						} elseif($code == 'price_m2_ot') {
							$property['NAME'] = str_replace("от", '', $property['NAME']);
							$property['VALUE'] = 'от ' . $property['VALUE'] .  ' ' . $arProperties['currency']['VALUE_ENUM'] . ' до ' . number_format($arProperties['price_m2_do']['VALUE'], 0, '', ' ') . ' ' . $arProperties['currency']['VALUE_ENUM'];
						} elseif($code == 'price_flat_min') {
							if ($arResult["BuildingMinPrice"]) {
								$property['VALUE'] = number_format($arResult["BuildingMinPrice"],0,'',' '). ' ' . $arProperties['currency']['VALUE_ENUM'];
							}
							else {
								$property['VALUE'] = false;
							}
							
						} elseif($code == 'ceilings_height') {
							$property['VALUE'] .= ' ' . $metres;
						}

						?>
						
						<? 
						if ($property['VALUE']) 
						{
							?>
								<li><span><?=$property['NAME']?></span> <span><?=$property['VALUE']?></span></li>
							<?
						}
						?>

					<? //если множественный чекбокс ?>
					<?elseif(count($property) > 1 && !empty($property[0]['NAME'])):?>
						<li>
							<span><?=$property[0]['NAME']?></span>
							<?$val = "";?>
							<?foreach($property as $prop):?>
								 <?$val .= $prop['VALUE_ENUM'] . ', '?>
							<?endforeach;?>
							<span><?=substr($val, 0, -2)?></span>
						</li>

					<? //если чекбокс не проставлен ?>
					<?else:?>
						<?if($property['PROPERTY_TYPE'] == "L"):?>
							<li><span><?=$property['NAME']?></span> <span>нет</span></li>
						<?else:?>
							<li><span><?=$property['NAME']?></span> <span>не указано</span></li>
						<?endif;?>
					<?endif;?>
				<?endforeach;?>
				<li><span>Всего квартир</span> <span><?=$flatsCount?></span></li>
			</ul>
		</div>

		<div class="b-investor">
			<div class="t-investor">Застройщик/Инвестор</div>
			<div class="link-investor">
				<a href="<?='/builders/' . $arProperties['BUILDER']['ID'] . '/'?>"><?=$arProperties['BUILDER']['NAME']?></a>
				<span><?=$arProperties['BUILDER']['PROPERTY_ADDRESS_VALUE']?></span>
			</div>

			<div class="view-ph">
				<div class="view-phone">
					<a href="javascript:void(0);">Показать телефон</a>
					<div class="phone-v"><span><?= $arUser['PERSONAL_MOBILE'] ?></span></div>
				</div>

				<?/* ?><div class="send-mess"><a class="inline inline-message" href="<?= ($USER->IsAuthorized()) ? '#send_message_to_realtor-form' : '#' . AUTHORIZE_FORM_ID ?>">Оставить сообщение</a></div><? */?>
				<div class="send-mess"><a class="inline inline-message" data-user="<?=$arProperties['BUILDER']['ID']?>" href="<?='#send_message_to_realtor-form';?>">Оставить сообщение</a></div>
			</div>
		</div>

		<div class="contacts-face">
			<div class="t-face">Контактные лица</div>
			<div class="info-cont-rielt">
				<div class="in-emp">
					<div class="img-emp">
						<? $photoLink = 'http://' . $_SERVER['HTTP_HOST'] . '/' . CFile::GetPath($arUser['PERSONAL_PHOTO']); ?>
						<? if (!empty( $arUser['PERSONAL_PHOTO'] )) : ?>
							<img src="<?= $photoLink ?>" alt=""/>
						<? else: ?>
							<img src="<?= SITE_TEMPLATE_PATH ?>/images/no-photo.jpg" alt=""/>
						<? endif; ?>
					</div>
					<div class="desc-emploe">
						<div class="name-emploe">
							<?= $arUser["NAME"] . " " . $arUser["SECOND_NAME"] . " " . $arUser["LAST_NAME"] ?>
							<span><?=$arUser['UF_PERSON_POST']?></span>
						</div>
						<div class="view-rielt-phone">
							<input type="hidden" id="realtor_id" value="<?=$arUser['ID']?>">
							<a href="javascript:void(0);">Показать телефон</a>
							<span><?=$arUser['PERSONAL_MOBILE']?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div style="clear: both;" id="flats"></div>
</div>

<div class="all-propos newbuilding-apartamets">
	<div class="t-emploe">Квартиры в <?=$arResult["NAME"]?></div>
	<ul class="newbuild-ap-type-selector">
		<li><a href="javascript:void(0);">От агентств / частных лиц</a></li>
		<li><a href="javascript:void(0);">От застройщика</a></li>
	</ul>
	<div class="apartaments-block" style="display:none;">
		<?$APPLICATION->IncludeComponent('wexpert:includer', "filter_apartament",
	array("TARGET_ID"=> "#not-builders","BUILDERS"=>"N","NEWBUILD"=>$arResult["ID"]),
	false
);?>
	<?	$APPLICATION->IncludeComponent("wexpert:iblock.list", "apartaments_at_newbuildings",Array(
			"ORDER"                             => array("SORT" => "ASC"),
			"FILTER"                            => array("PROPERTY_newbuilding"=>$arResult["ID"],"!PROPERTY_IS_ACCEPTED"=>false,"!CREATED_BY"=>CustomUsers::GetBuildersUsers()),
			"IBLOCK_ID"							=> 7,
			"SELECT"						    => array('ID', 'NAME', 'IBLOCK_ID', 'PROPERTY_*', 'SHOW_COUNTER', 'TIMESTAMP_X',"CREATED_BY"),
			"GET_PROPERTY"						=> "Y",
			"CACHE_TIME"    => 3600,
			"PARENT_NAME"						=> $arResult["NAME"],
			"ALWAYS_INCLUDE_TEMPLATE"			=> "Y",
			"BLOCK_ID"							=> "not-builders"
		)); ?>
	</div>
	<div class="apartaments-block" style="display:none;">
			<?$APPLICATION->IncludeComponent('wexpert:includer', "filter_apartament",
	array("TARGET_ID"=> "#builders","BUILDERS"=>"Y","NEWBUILD"=>$arResult["ID"]),
	false
);?>
	<?	$APPLICATION->IncludeComponent("wexpert:iblock.list", "apartaments_at_newbuildings",Array(
			"ORDER"                             => array("SORT" => "ASC"),
			"FILTER"                            => array("PROPERTY_newbuilding"=>$arResult["ID"],"!PROPERTY_IS_ACCEPTED"=>false,"CREATED_BY"=>CustomUsers::GetBuildersUsers()),
			"IBLOCK_ID"							=> 7,
			"SELECT"						    => array('ID', 'NAME', 'IBLOCK_ID', 'PROPERTY_*', 'SHOW_COUNTER', 'TIMESTAMP_X',"CREATED_BY"),
			"GET_PROPERTY"						=> "Y",
			"CACHE_TIME"    => 3600,
			"PARENT_NAME"						=> $arResult["NAME"],
			"ALWAYS_INCLUDE_TEMPLATE"			=> "Y",
			"BLOCK_ID"							=> "builders"
		)); ?>
	</div>
</div>
<?/* ?><div class="all-propos">
	<div class="t-emploe">Квартиры в <?=$arResult['NAME']?></div>
	<div class="table-history table-history2 hr">
		<table id="tb1">
			<thead>
			<tr>
				<th><a href="javascript:void(0);">Предложение</a>	<i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
				<th><a href="javascript:void(0);">Площадь</a> <i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
				<th><a href="javascript:void(0);">Этаж/этажей</a> <i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
				<th><a href="javascript:void(0);">Цена</a> <i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
				<th><a href="javascript:void(0);">Цена м<sup>2</sup></a> <i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
				<th><a href="javascript:void(0);">Обновлено</a> <i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
				<th><a href="javascript:void(0);">Просмотров</a> <i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
			</tr>
			</thead>
			<tbody>
			<?foreach($arItems as $item):?>
				<tr>
					<td><?=$item['PROPERTIES']['room_number']['VALUE']?>-комнатная</td>
					<td><strong><?=$item['PROPERTIES']['square']['VALUE']?></strong></td>
					<td><?=$item['PROPERTIES']['floor']['VALUE']?></td>
					<td><strong><?=$item['PROPERTIES']['price']['VALUE']?></strong></td>
					<td><?=$item['PROPERTIES']['price_1m']['VALUE']?></td>
					<? $day = rus_date("j F Y", strtotime($item['TIMESTAMP_X'])); ?>
					<td><?=$day?></td>
					<td><?=$item['SHOW_COUNTER']?></td>
				</tr>
			<?endforeach;?>
			</tbody>
		</table>
	</div>
</div>
<? */?>
<? 
$viewTemplate = ($_REQUEST["VIEW_TYPE"] == "tiles")? "special_offers_view_tiles": "special_offers";
?>
<?	$APPLICATION->IncludeComponent("wexpert:iblock.list", $viewTemplate,Array(
		"ORDER"                             => array($_GET["SORT_BY"] => $_GET["SORT_ORDER"]),
		"FILTER"                            => array($GLOBALS['FILTER_PROPERTY'] => $GLOBALS['FILTER_VALUES'], '!SECTION_ID' => array(1, 2, 3),"!PROPERTY_IS_ACCEPTED"=>false),
		"IBLOCK_ID"							=> $GLOBALS['CATALOG_IBLOCKS_ARRAY'],
		"PAGESIZE"						    => 4,
		"SELECT"						    => "DATE_CREATE",
		"GET_PROPERTY"						=> "Y",
		"CACHE_TIME"    => 0
	)); ?>

<? //брошюра ?>
<div class="hidden">
	<div id="broshure_html" class="pop pop6">
		<div class="close"></div>
		<a class='hidden inline cBoxElement' href="#broshure_html"></a>
		<?if(!empty($arProperties['brochure_html']['~VALUE']['TEXT'])):?>
			<?=$arProperties['brochure_html']['~VALUE']['TEXT'];?>
		<?endif;?>
	</div>
</div>
