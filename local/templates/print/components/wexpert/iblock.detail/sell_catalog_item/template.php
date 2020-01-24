<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die(); ?>
<?

CIBlockElement::CounterInc($arResult['ID']);


//TO DO: IN RESULT_MODIFIER
$arProperties = $arResult['PROPERTIES'];
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
$price    = (string)$arProperties['price']['VALUE'];
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
	$arProperties['price']['VALUE'] = $price;
}
//price1m view
$price1m  = (string)$arProperties['price_1m']['VALUE'];
$priceLen = strlen($price1m);
if ($priceLen >= 4) {
	switch ($priceLen) {
		case 4:
			$price1m = substr($price1m, 0, 1) . ' ' . substr($price1m, 1, 3);
			break;
		case 5:
			$price1m = substr($price1m, 0, 2) . ' ' . substr($price1m, 2, 3);
			break;
		case 6:
			$price1m = substr($price1m, 0, 3) . ' ' . substr($price1m, 3, 3);
			break;
		default:
			break;
	}
	$arProperties['price_1m']['VALUE'] = $price1m;
}

$rsUser                    = CUser::GetByID($arProperties['realtor']['VALUE']);
$arUser                    = $rsUser->Fetch();
$fullName                  = $arUser['NAME'] . ' ' . $arUser['SECOND_NAME'] . ' ' . $arUser['LAST_NAME'];
$realtor_email             = $arUser['EMAIL'];
$GLOBALS['REALTOR_EMAILS'] = $realtor_email;

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
$arSelect                 = Array(
	'PROPERTY_REALTOR', 'IBLOCK_ID', 'ID', 'NAME', 'PROPERTY_PRICE', 'PROPERTY_apartment_complex', 'PROPERTY_street',
	'PROPERTY_district', 'PROPERTY_microdistrict', 'PROPERTY_city', 'PROPERTY_floor', 'PROPERTY_currency'
);
$arFilter                 = Array("IBLOCK_ID" => $arID2, "ACTIVE_DATE" => "Y", "ACTIVE" => "Y");
$res                      = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize" => 50), $arSelect);
$currentID                = $arProperties['realtor']['VALUE'];
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
}

$totalCount = 0;
foreach ($arRealtorOffers as $offer) {
	$totalCount += $offer;
}
$offersCount = $totalCount;


$number = $totalCount;

if ($number > 10 and $number < 15) {
	$term = "ий";
} else {
	if ($number > 16) {
		$number = (int)substr($number, -1);
		if ($number == 0) {
			$term = "ий";
		}
		if ($number == 1) {
			$term = "ие";
		}
		if ($number > 1) {
			$term = "ия";
		}
		if ($number > 4) {
			$term = "ий";
		}
	} else {
		if ($number == 0) {
			$term = "ий";
		}
		if ($number == 1) {
			$term = "ие";
		}
		if ($number > 1) {
			$term = "ия";
		}
		if ($number > 4) {
			$term = "ий";
		}
	}
}

$arResult['PROPERTIES'] = $arProperties;

$arProperties = $arResult['PROPERTIES'];
?>
<div class="adress-agency">
	<?= $arProperties['city']['MODIFIED_VALUE'] ?>,
	<?= $arProperties['district']['MODIFIED_VALUE'] ?>,
	<?= $arProperties['microdistrict']['MODIFIED_VALUE'] ?>,
	<?= $arProperties['street']['VALUE'] ?>
</div>

<div class="info-card">
	<div class="refresh-date">Размещено: 9 декабря</div>
	<?
	//TO DO
	// сегодня\вчера\
	?>
	<div class="refresh">Обновлено: <strong><?= $arResult['TIMESTAMP_X'] ?></strong></div>

	<div class="num-view"><?= $arResult['SHOW_COUNTER'] ?></div>
</div><!--info-card-->

<div class="func-card">
	<div class="view-func">
		<ul>
			<li class="item1"><a href="#">На карте</a></li>
			<li class="item2"><a href="#">Панорамы</a></li>
			<li class="item3"><a href="#">Смотреть брошюру</a></li>
		</ul>
	</div><!--view-func-->
	<div class="action-func">
		<input id="item-id" type="hidden" value="<?= $arResult['ID'] ?>">
		<ul>
			<li class="item1"><a id="btn-favor" class="<? if (in_array($arResult['ID'], $arID)) {
					echo 'active';
				} ?>" href="javascript:void(0);">В избранное</a></li>
			<li class="item2"><a href="#">Пожаловаться</a></li>
			<li class="item3"><a href="#">Распечатать</a></li>
			<li class="item4"><a href="#pop13" class="inline cBoxElement">Поделиться</a></li>
		</ul>
	</div><!--action-func-->
</div><!--func-card-->
<div class="card-advertise">
	<div class="card-l">
		<div class="card-gal">
			<div class="cont-big cont-all">
				<div class="tab-big tab-all">
					<div class="main-img">
						<ul class="slider-big1">
							<? foreach ($arProperties['photo_gallery'] as $photo): ?>
								<? $photoLink = 'http://' . $_SERVER['HTTP_HOST'] . '/' . CFile::GetPath($photo['VALUE']); ?>
								<li><p><img src="<?= $photoLink ?>" alt=""/></p></li>
							<? endforeach; ?>
						</ul>
					</div><!--main-img-->
				</div><!--tab-big-->

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
									<p>
										<iframe src="<?= $video['VALUE'] ?>" frameborder="0" allowfullscreen></iframe>
									</p>
								</li>
							<? endforeach; ?>
						</ul>
					</div><!--main-img-->
				</div><!--tab-big-->

				<div class="tab-big tab-all">
					<div class="main-img">
						<ul class="slider-big3">
							<? foreach ($arProperties['layouts_gallery'] as $photo): ?>
								<? $photoLink = 'http://' . $_SERVER['HTTP_HOST'] . '/' . CFile::GetPath($photo['VALUE']); ?>
								<li><p><img src="<?= $photoLink ?>" alt=""/></p></li>
							<? endforeach; ?>
						</ul>
					</div><!--main-img-->
				</div><!--tab-big-->
			</div><!--cont-big-->

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
			</div><!--nav-gal-->

			<div class="cont-thumbs cont-all">
				<div class="tab-thumbs tab-all">
					<div class="thumb-img" id="bx-pager">
						<? $counter = 0; ?>
						<? foreach ($arProperties['photo_gallery'] as $photo): ?>
							<? $photoLink = 'http://' . $_SERVER['HTTP_HOST'] . '/' . CFile::GetPath($photo['VALUE']); ?>
							<a data-slide-index="<?= $counter ?>" href=""><p><img src="<?= $photoLink ?>" alt=""/></p>
							</a>
							<? $counter++; ?>
						<? endforeach; ?>
					</div>
				</div><!--tab-thumbs-->

				<div class="tab-thumbs tab-all">
					<div class="thumb-img" id="bx-pager2">
						<? foreach ($arProperties['video_gallery'] as $video): ?>
							<a data-slide-index="0" href="">
								<p>
									<iframe width="500" height="315" src="<?= $video['VALUE'] ?>" frameborder="0"
									        allowfullscreen></iframe>
								</p>
							</a>
						<? endforeach; ?>
					</div>
				</div><!--tab-thumbs-->

				<div class="tab-thumbs tab-all">
					<div class="thumb-img" id="bx-pager3">
						<? foreach ($arProperties['layouts_gallery'] as $photo): ?>
							<? $photoLink = 'http://' . $_SERVER['HTTP_HOST'] . '/' . CFile::GetPath($photo['VALUE']); ?>
							<a data-slide-index="0" href=""><p><img src="<?= $photoLink ?>" alt=""/></p></a>
						<? endforeach; ?>
					</div>
				</div><!--tab-thumbs-->
			</div><!--cont-thumbs-->
		</div><!--card-gal-->

		<div class="text-card">
			<p><?= $arResult['DETAIL_TEXT'] ?></p>
		</div>
	</div><!--card-l-->

	<div class="card-r">
		<div class="full-rielt">
			<div class="info-cont-rielt">
				<div class="in-emp">
					<div class="img-emp"><img src="<?= SITE_TEMPLATE_PATH ?>/images_tmp/img-emploe1.jpg" alt=""/></div>
					<div class="desc-emploe">
						<div class="name-rr">
							<? $link = 'http://' . $_SERVER['HTTP_HOST'] . '/agents/' . $arUser['UF_AGENT_VALUE'] . '/'; ?>
							<a href="<?= $link ?>"><?= $arUser['UF_AGENT_NAME'] ?></a>
						</div>
						<div class="name-emploe">
							<? $link = 'http://' . $_SERVER['HTTP_HOST'] . '/realtors/' . $arProperties['realtor']['VALUE'] . '/'; ?>
							<a href="<?= $link ?>"><?= $fullName ?></a>
							<span><?= $arUser['UF_PERSONAL_POST'] ?></span>
						</div>
						<div class="propos-rr">(<?= $offersCount ?> предложен<?= $term ?>)</div>
					</div><!--desc-emploe-->
				</div>
			</div>

			<div class="view-ph">
				<div class="view-phone">
					<a href="#">Показать телефон</a>
					<div class="phone-v"><span><?= $arUser['PERSONAL_MOBILE'] ?></span></div>
				</div>

				<div class="send-mess"><a class="inline" href="#send_message_to_realtor-form">Оставить сообщение</a>
				</div>
			</div>
		</div><!--full-rielt-->

		<div class="price-card price-card2">
			<?= $arProperties['price']['VALUE'] ?> <?= $arProperties['currency']['VALUE_XML_ID'] ?>
			<span>/ <?= $arProperties['price_1m']['VALUE'] ?> <?= $arProperties['currency']['VALUE_XML_ID'] ?>
				за м²</span>
		</div>
		<div class="ipoteka">Ипотека: <span>89 000 руб./мес.</span></div>

		<div class="params-card params-card2">
			<ul>
				<li><span>Площадь общая</span> <span><?= $arProperties['flat_square']['VALUE'] ?> м2</span></li>
				<li><span>Этаж / этажей</span> <span><?= $arProperties['floor']['VALUE'] ?></span></li>
				<li><span>Отделка квартиры</span> <span><?= $arProperties['decoration']['VALUE_ENUM'] ?></span></li>
				<li><span>Санузел</span> <span><?= $arProperties['wc']['VALUE_ENUM'] ?></span></li>
				<li><span>Вид на море</span> <span>есть</span></li>
				<li><span>Балкон</span> <span>есть</span></li>
			</ul>
		</div><!--params-card-->

		<div class="b-investor b-investor2 temp-hidden">
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
		</div><!--b-investor-->

	</div><!--card-r-->
</div><!--card-advertise-->
<div class="b-propos b-propos2">
	<div class="siblings-obj temp-hidden">
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
									, <?= $currentOffer['PROPERTY_APARTMENT_COMPLEX_VALUE'] ?></div>

								<div
									class="price-p"><?= $currentOffer['PROPERTY_PRICE_VALUE'] ?> <?= $currentOffer['PROPERTY_CURRENCY_VALUE'] ?></div>

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

	<div class="siblings-obj temp-hidden">
		<div class="t-emploe">Похожие объекты</div>

		<div class="slier-objects">
			<div class="slider1">
				<div class="slide">
					<div class="item-propos">
						<div class="top-propos">
							<div class="img-propos"><a href="#"><img
										src="<?= SITE_TEMPLATE_PATH ?>/images_tmp/room1.jpg" alt=""/></a></div>
							<div class="t-propos"><p><a href="#">2-комнатная в ЖК Green Sail на ул. Белорусская</a></p>
							</div>
						</div><!--top-propos-->

						<div class="bot-propos">
							<div class="adress-p">Сочи, Адлерский район, Адлер-центр, ул. Белорусская</div>

							<div class="price-p">4 420 000 руб.</div>

							<div class="params-p">
								<ul>
									<li>Общая площадь: <span>53,2</span> м2</li>
									<li>Этаж: <span>2/10</span></li>
								</ul>
							</div>

							<div class="compl-prop">Офис продаж ЖК "Green Sail"</div>
						</div><!--bot-propos-->
					</div><!--item-propos-->
				</div><!--slide-->

				<div class="slide">
					<div class="item-propos">
						<div class="top-propos">
							<div class="img-propos"><a href="#"><img
										src="<?= SITE_TEMPLATE_PATH ?>/images_tmp/room2.jpg" alt=""/></a></div>
							<div class="t-propos"><p><a href="#">2-комнатная в ЖК Green Sail на ул. Белорусская</a></p>
							</div>
						</div><!--top-propos-->

						<div class="bot-propos">
							<div class="adress-p">Сочи, Адлерский район, Адлер-центр, ул. Белорусская</div>

							<div class="price-p">4 420 000 руб.</div>

							<div class="params-p">
								<ul>
									<li>Общая площадь: <span>53,2</span> м2</li>
									<li>Этаж: <span>2/10</span></li>
								</ul>
							</div>

							<div class="compl-prop">Офис продаж ЖК "Green Sail"</div>
						</div><!--bot-propos-->
					</div><!--item-propos-->
				</div><!--slide-->

				<div class="slide">
					<div class="item-propos">
						<div class="top-propos">
							<div class="img-propos"><a href="#"><img
										src="<?= SITE_TEMPLATE_PATH ?>/images_tmp/room3.jpg" alt=""/></a></div>
							<div class="t-propos"><p><a href="#">2-комнатная в ЖК Green Sail на ул. Белорусская</a></p>
							</div>
						</div><!--top-propos-->

						<div class="bot-propos">
							<div class="adress-p">Сочи, Адлерский район, Адлер-центр, ул. Белорусская</div>

							<div class="price-p">4 420 000 руб.</div>

							<div class="params-p">
								<ul>
									<li>Общая площадь: <span>53,2</span> м2</li>
									<li>Этаж: <span>2/10</span></li>
								</ul>
							</div>

							<div class="compl-prop">Офис продаж ЖК "Green Sail"</div>
						</div><!--bot-propos-->
					</div><!--item-propos-->
				</div><!--slide-->

				<div class="slide">
					<div class="item-propos">
						<div class="top-propos">
							<div class="img-propos"><a href="#"><img
										src="<?= SITE_TEMPLATE_PATH ?>/images_tmp/room1.jpg" alt=""/></a></div>
							<div class="t-propos"><p><a href="#">2-комнатная в ЖК Green Sail на ул. Белорусская</a></p>
							</div>
						</div><!--top-propos-->

						<div class="bot-propos">
							<div class="adress-p">Сочи, Адлерский район, Адлер-центр, ул. Белорусская</div>

							<div class="price-p">4 420 000 руб.</div>

							<div class="params-p">
								<ul>
									<li>Общая площадь: <span>53,2</span> м2</li>
									<li>Этаж: <span>2/10</span></li>
								</ul>
							</div>

							<div class="compl-prop">Офис продаж ЖК "Green Sail"</div>
						</div><!--bot-propos-->
					</div><!--item-propos-->
				</div><!--slide-->

				<div class="slide">
					<div class="item-propos">
						<div class="top-propos">
							<div class="img-propos"><a href="#"><img
										src="<?= SITE_TEMPLATE_PATH ?>/images_tmp/room2.jpg" alt=""/></a></div>
							<div class="t-propos"><p><a href="#">2-комнатная в ЖК Green Sail на ул. Белорусская</a></p>
							</div>
						</div><!--top-propos-->

						<div class="bot-propos">
							<div class="adress-p">Сочи, Адлерский район, Адлер-центр, ул. Белорусская</div>

							<div class="price-p">4 420 000 руб.</div>

							<div class="params-p">
								<ul>
									<li>Общая площадь: <span>53,2</span> м2</li>
									<li>Этаж: <span>2/10</span></li>
								</ul>
							</div>

							<div class="compl-prop">Офис продаж ЖК "Green Sail"</div>
						</div><!--bot-propos-->
					</div><!--item-propos-->
				</div><!--slide-->

				<div class="slide">
					<div class="item-propos">
						<div class="top-propos">
							<div class="img-propos"><a href="#"><img
										src="<?= SITE_TEMPLATE_PATH ?>/images_tmp/room3.jpg" alt=""/></a></div>
							<div class="t-propos"><p><a href="#">2-комнатная в ЖК Green Sail на ул. Белорусская</a></p>
							</div>
						</div><!--top-propos-->

						<div class="bot-propos">
							<div class="adress-p">Сочи, Адлерский район, Адлер-центр, ул. Белорусская</div>

							<div class="price-p">4 420 000 руб.</div>

							<div class="params-p">
								<ul>
									<li>Общая площадь: <span>53,2</span> м2</li>
									<li>Этаж: <span>2/10</span></li>
								</ul>
							</div>

							<div class="compl-prop">Офис продаж ЖК "Green Sail"</div>
						</div><!--bot-propos-->
					</div><!--item-propos-->
				</div><!--slide-->
			</div><!--slider1-->
		</div><!--slider-objects-->

		<?/*<div class="more"><a href="/sell/all_houses/">Смотреть все предложения</a> <span>53</span></div><!--more-->*/?>
	</div><!--siblings-obj-->
</div><!--b-propos-->
