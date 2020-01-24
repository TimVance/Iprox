<?
$arProperties = $arResult["PROPERTIES"];
	?>
<div class="adress-agency"><?=$arProperties['ADDRESS']['VALUE']?></div>

<div class="about-agency about-agency2">
	<div class="about-agency-l">
		<div class="bl-rielt">
			<div class="photo-r">
				<img src="<?=$arResult['PREVIEW_PICTURE']["SRC"]?>" alt="" />
				</div>
			<div class="descr-r">
				<div class="link-r">
					<ul>
						<li><a target="_blank" href="<?=$arProperties['SITE_ADDRESS']['VALUE']?>"><?=$arProperties['SITE_ADDRESS']['VALUE']?></a></li>
						<li><a href="mailto:<?=$arProperties['EMAIL']['VALUE']?>"><?=$arProperties['EMAIL']['VALUE']?></a></li>
					</ul>
				</div>

				<div class="view-phone">
					<a href="#">Показать телефон</a>
					<div class="phone-v"><span><?=$arProperties['PHONE_NUMBER']['VALUE']?></span></div>
				</div>

				<div class="send-mess"><a class="inline" href="#pop8">Оставить сообщение</a></div>
			</div><!--descr-r-->
		</div><!--bl-rielt-->

		<div class="text-ab-ag text-ab-ag2">
			<div class="t-ab">О компании</div>
			<div class="text-acoord">
				<?=$arResult["DETAIL_TEXT"]?>
			</div>
		</div><!--text-about-ag-->

		<div class="accord-card">
			<div class="item-accrod">
				<div class="tit-accord"><span>Виды деятельности</span></div>
				<div class="text-accrod">
					<ul>
						<? foreach($arProperties["ACTIVITIES"]["VALUE"] as $itemValue): ?>
							<li><?=$itemValue?></li>
						<? endforeach; ?>
					</ul>
				</div>
			</div><!--item-accord-->

			<div class="item-accrod">
				<div class="tit-accord"><span>Лицензии</span></div>
				<div class="text-accrod">
					<ul>
						<? foreach($arProperties["LICENSE"]["VALUE"] as $itemValue): ?>
							<li><a href="#"><?=$itemValue?></a></li>
						<? endforeach; ?>
					</ul>
				</div>
			</div><!--item-accord-->

			<div class="item-accrod">
				<div class="tit-accord"><span>Партнеры</span></div>
				<div class="text-accrod">
					<ul>
						<? foreach($arProperties["PARTNERS"]["VALUE"] as $itemValue): ?>
							<li><a href="#"><?=$itemValue?></a></li>
						<? endforeach; ?>
					</ul>
				</div>
			</div><!--item-accord-->
		</div><!--accord-card-->
	</div><!--about-agency-l-->

	<div class="about-agency-r">
		<div class="cont-rielt">
			<div class="t-cont-rielt">Контактные лица</div>
			<? foreach($arResult["PROPERTIES"]["CONTACT_PERSON"]["VALUE"] as $userID): ?>
				<?
					$rsUser = CUser::GetByID($userID);
					$arUser = $rsUser->Fetch();
				?>
			<div class="info-cont-rielt">
				<div class="in-emp">
					<div class="img-emp">
						<? $photoLink = 'http://' . $_SERVER['HTTP_HOST'] . '/' . CFile::GetPath($arUser['PERSONAL_PHOTO']); ?>
						<?if(!empty($arUser['PERSONAL_PHOTO'])) :?>
							<img src="<?=$photoLink?>" alt="" />
						<? else: ?>
							<img src="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" alt="" />
						<? endif; ?>
					</div>
					<div class="desc-emploe">
						<div class="name-emploe">
							<?=$arUser["NAME"] . " " . $arUser["SECOND_NAME"] . " " . $arUser["LAST_NAME"]?>
							<span><?=$arUser["UF_PERSON_POST"]?></span>
						</div>
						<div class="view-rielt-phone">
							<input type="hidden" id="realtor_id" value="<?=$arUser['ID']?>">
							<a href="javascript:void(0);">Показать телефон</a>
							<span></span>
						</div><!--view-rielt-phone-->
					</div><!--desc-emploe-->
				</div>
			</div>
			<? endforeach; ?>
		</div><!--cont-rielt-->
		<div class="video-agency">
			<iframe width="100%" height="315" src="<?=$arProperties["VIDEO_LINK"]["VALUE"]?>" frameborder="0" allowfullscreen></iframe>
		</div>

		<div class="head-answ-rielt">
			<div class="ask-wrapper" >
				<div class="t-answ-rielt">Вопросы и ответы</div><!--t-answ-rielt-->

				<div class="add-ask"><a href="javascript:void(0);">Задать вопрос</a></div>
			</div>

			<div id="form-question-wrap" style="display: none;">
				<?$APPLICATION->IncludeComponent(
					"bitrix:form.result.new",
					"ask_question",
					Array(
						"CACHE_TIME" => "3600",
						"CACHE_TYPE" => "A",
						"IGNORE_CUSTOM_TEMPLATE" => "N",
						"SEF_MODE" => "N",
						"USE_EXTENDED_ERRORS" => "N",
						"WEB_FORM_ID" => 2,
					    'elementID' => $arResult['ID']
					)
				);?>
			</div>
		</div>

		<div class="ask-rielt ask-rielt2">
			<?

			$arFilter = array(
				//"ID"                   => 11,              // ID результата
			);


			$arFields[] = array(
				"CODE"              => "SIMPLE_QUESTION_974",
			    "FILTER_TYPE"       => "text",                         // фильтруем по числовому полю
			    "PARAMETER_NAME"    => "USER",                      // фильтруем по введенному значению
			    "VALUE"             => $arResult['ID'],                                  // значение по которому фильтруем
			    "EXACT_MATCH"       => "Y"                              // ищем точное совпадение
			);
			$arFilter["FIELDS"] = $arFields;

			$is_filtered = true;

			$rsResults = CFormResult::GetList(2, $by = 's_id', $order = 'asc', $arFilter, $is_filtered, 'N', 20);

			while ($arAskResult = $rsResults->Fetch()): ?>
				<?
					$arAnswer = CFormResult::GetDataByID($arAskResult['ID'], array(), $someUselessArray, $arAnswer2);
					unset($someUselessArray);
					unset($arAnswer2);
					$arAskAuthor = $arAnswer['SIMPLE_QUESTION_161'][0]['USER_TEXT'];
					$arAskText   = $arAnswer['SIMPLE_QUESTION_632'][0]['USER_TEXT'];

					$arFilter = array();
					$arFields[] = array(
						"CODE"              => "SIMPLE_QUESTION_102",
						"FILTER_TYPE"       => "text",                         // фильтруем по числовому полю
						"PARAMETER_NAME"    => "USER",                      // фильтруем по введенному значению
						"VALUE"             => $arAskResult['ID'],                                  // значение по которому фильтруем
						"EXACT_MATCH"       => "Y"                              // ищем точное совпадение
					);

					$arFilter["FIELDS"] = $arFields;

					$rsResults1 = CFormResult::GetList(3, $by = 's_id', $order = 'asc', $arFilter, $is_filtered, 'N', false);
					if($arAskResultTemp = $rsResults1->Fetch()) {
						$IS_DISPLAY = true;
					}
					else {
						$IS_DISPLAY = false;
					}
				$today = rus_date("j F Y", strtotime($arAskResult['DATE_CREATE']));

				?>
			<div class="item-ask <?if(!$IS_DISPLAY
				&& ((!$USER->IsAdmin()
					&& !in_array(7, CUser::GetUserGroup($USER->GetId())))
					|| $USER->GetId() == '')) echo 'hidden'; ?>">
				<div class="bl-ask">
					<div class="name-ask"><strong><?=$arAskAuthor?>,</strong> <?=$today?></div>
					<div class="body-ask"><?=$arAskText?></div>
					<div class="reply-btn <? if((!$USER->IsAdmin() && !in_array(7, CUser::GetUserGroup($USER->GetId())))) echo 'hidden'; ?>">
						<a href="javascript:void(0);">Ответить</a>
					</div>

					<div class="reply-form">
						<?$APPLICATION->IncludeComponent(
							"bitrix:form.result.new",
							"reply_question",
							Array(
								"CACHE_TIME" => "3600",
								"CACHE_TYPE" => "A",
								"IGNORE_CUSTOM_TEMPLATE" => "N",
								"SEF_MODE" => "N",
								"USE_EXTENDED_ERRORS" => "N",
								"WEB_FORM_ID" => 3,
								'REALTOR_ID' => $arUser['ID'],
								'ELEMENT_ID' => $arResult['ID'],
								'QUESTION_ID' => $arAskResult['ID'],
								'REALTOR_EMAIL' => $arUser['EMAIL']
							)
						);?>
					</div>

					<?

					$rsResults2 = CFormResult::GetList(3, $by = 's_id', $order = 'asc', $arFilter, $is_filtered, 'N', false);

					while ($arAskResult = $rsResults2->Fetch()): ?>
						<?
							$arAnswer = CFormResult::GetDataByID($arAskResult['ID'], array(), $someUselessArray, $arAnswer2);
							unset($someUselessArray);
							unset($arAnswer2);
							$realtorID = $arAnswer['SIMPLE_QUESTION_565'][0]['USER_TEXT'];
							$arAskText   = $arAnswer['SIMPLE_QUESTION_997'][0]['USER_TEXT'];


							$rsUser = CUser::GetByID($realtorID);
							$arUser = $rsUser->Fetch();

							$arUser['FULL_NAME'] = $arUser['NAME'] . ' ' . $arUser['SECOND_NAME'] . ' ' . $arUser['LAST_NAME'];
							$IBLOCK_ID = 3;

							$Res = CIBlockElement::GetByID($arUser["UF_AGENT_NAME"]);
							if ($ar_props = $Res->GetNext()) {
								$arUser["UF_AGENT_NAME"] = $ar_props["NAME"];
								$arUser["UF_AGENT_PHOTO_ID"] = $ar_props["PREVIEW_PICTURE"];
							}
							if(!empty($arUser["PERSONAL_PHOTO"])) {
								$arUser["PERSONAL_PHOTO"] = 'http://' . $_SERVER['HTTP_HOST'] . '/' . CFile::GetPath($arUser["PERSONAL_PHOTO"]);
							}
							else {
								$arUser["PERSONAL_PHOTO"] = 'http://' . $_SERVER['HTTP_HOST'] . '/' . 'local/templates/morealty/images/no-photo.jpg';
							}

						?>

					<div class="answer">
						<div class="img-answer"><img src="<?=$arUser["PERSONAL_PHOTO"]?>" alt="" /></div><!--img-answer-->
						<?$today = rus_date("j F Y", strtotime($arAskResult['DATE_CREATE']));?>
						<div class="desc-answer">
							<div class="date-answer"><?=$today?></div>
							<div class="name-answ">
								<a href="/realtors/<?=$realtorID.'/'?>"><?=$arUser["FULL_NAME"]?></a>
								<span><?=$arUser["UF_AGENT_NAME"]?></span>
							</div>
							<p><?=$arAskText?></p>
						</div><!--desc-answer-->
					</div><!--answer-->

					<? endwhile; ?>
				</div><!--bl-ask-->
			</div>
			<? endwhile; ?>
		</div><!--ask-rielt-->
	</div><!--about-agency-r-->
</div><!--about-agency-->

<div class="object-sale">
	<div class="tit-spec">Объекты в продаже <span>(2)</span></div>

	<div class="line-propos">
		<div class="spec-propos">
			<div class="bl-spec">
				<div class="head-spec">
					<a href="#">ЖК «Меридиан» на ул. Воровского, 45</a>
					<span>Сочи, Хостинский район, Кудепста пос., ул. Камо</span>
				</div>

				<div class="body-spec">
					<div class="img-body-spec"><img src="<?=SITE_TEMPLATE_PATH?>/images_tmp/img-body-spec.jpg" alt="" /></div><!--img-body-spec-->
					<div class="desc-body-spec">
						<ul>
							<li>
								<span>Цена кв.м от:</span>
								<strong>135 000</strong>
							</li>
							<li>
								<span>Класс:</span>
								<strong>бизнес</strong>
							</li>
							<li>
								<span>Этажей:</span>
								<strong>5</strong>
							</li>
							<li>
								<span>Площадь квартир:</span>
								<strong>17—141 м<sup>2</sup></strong>
							</li>
							<li>
								<span>Срок сдачи:</span>
								<strong>III кв. 2016</strong>
							</li>
						</ul>
					</div>
				</div><!--body-spec-->
			</div><!--bl-spec-->
		</div><!--spec-propos-->

		<div class="spec-propos">

			<div class="bl-spec">
				<div class="head-spec">
					<a href="#">ЖК «Меридиан» на ул. Воровского, 45</a>
					<span>Сочи, Хостинский район, Кудепста пос., ул. Камо</span>
				</div>

				<div class="body-spec">
					<div class="img-body-spec"><img src="<?=SITE_TEMPLATE_PATH?>/images_tmp/img-body-spec.jpg" alt="" /></div><!--img-body-spec-->
					<div class="desc-body-spec">
						<ul>
							<li>
								<span>Цена кв.м от:</span>
								<strong>135 000</strong>
							</li>
							<li>
								<span>Класс:</span>
								<strong>бизнес</strong>
							</li>
							<li>
								<span>Этажей:</span>
								<strong>5</strong>
							</li>
							<li>
								<span>Площадь квартир:</span>
								<strong>17—141 м<sup>2</sup></strong>
							</li>
							<li>
								<span>Срок сдачи:</span>
								<strong>III кв. 2016</strong>
							</li>
						</ul>
					</div>
				</div><!--body-spec-->
			</div><!--bl-spec-->
		</div><!--spec-propos-->
	</div><!--line-propos-->
</div><!--object-sale-->

<div class="all-propos">
	<div class="t-emploe">Все предложения застройщика <span>(120)</span></div>

	<div class="table-history table-history2">
		<table>
			<tr>
				<th><a class="active" href="#">Предложение</a>	<i>&uarr;</i></th>
				<th><a href="#">Площадь</a></th>
				<th><a href="#">Этаж/этажей</a></th>
				<th><a href="#">Цена</a></th>
				<th><a href="#">Цена м<sup>2</sup></a></th>
				<th><a href="#">Обновлено</a></th>
				<th><a href="#">Просмотров</a></th>
			</tr>
			<tr>
				<td>1-комнатная</td>
				<td><strong>26.1</strong></td>
				<td>1</td>
				<td><strong>1 488 000</strong></td>
				<td>57 011</td>
				<td>сегодня</td>
				<td>23</td>
			</tr>
			<tr>
				<td>3-комнатная</td>
				<td><strong>26.1</strong></td>
				<td>1</td>
				<td><strong>1 488 000</strong></td>
				<td>57 011</td>
				<td>сегодня</td>
				<td>23</td>
			</tr>
			<tr>
				<td>1-комнатная</td>
				<td><strong>26.1</strong></td>
				<td>1</td>
				<td><strong>1 488 000</strong></td>
				<td>57 011</td>
				<td>сегодня</td>
				<td>23</td>
			</tr>
			<tr>
				<td>3-комнатная</td>
				<td><strong>26.1</strong></td>
				<td>1</td>
				<td><strong>1 488 000</strong></td>
				<td>57 011</td>
				<td>сегодня</td>
				<td>23</td>
			</tr>
			<tr>
				<td>1-комнатная</td>
				<td><strong>26.1</strong></td>
				<td>1</td>
				<td><strong>1 488 000</strong></td>
				<td>57 011</td>
				<td>сегодня</td>
				<td>23</td>
			</tr>
			<tr>
				<td>3-комнатная</td>
				<td><strong>26.1</strong></td>
				<td>1</td>
				<td><strong>1 488 000</strong></td>
				<td>57 011</td>
				<td>сегодня</td>
				<td>23</td>
			</tr>
			<tr>
				<td>1-комнатная</td>
				<td><strong>26.1</strong></td>
				<td>1</td>
				<td><strong>1 488 000</strong></td>
				<td>57 011</td>
				<td>сегодня</td>
				<td>23</td>
			</tr>
			<tr>
				<td>3-комнатная</td>
				<td><strong>26.1</strong></td>
				<td>1</td>
				<td><strong>1 488 000</strong></td>
				<td>57 011</td>
				<td>сегодня</td>
				<td>23</td>
			</tr>
			<tr>
				<td>1-комнатная</td>
				<td><strong>26.1</strong></td>
				<td>1</td>
				<td><strong>1 488 000</strong></td>
				<td>57 011</td>
				<td>сегодня</td>
				<td>23</td>
			</tr>
			<tr>
				<td>3-комнатная</td>
				<td><strong>26.1</strong></td>
				<td>1</td>
				<td><strong>1 488 000</strong></td>
				<td>57 011</td>
				<td>сегодня</td>
				<td>23</td>
			</tr>
		</table>
	</div><!--table-history-->
</div>

<div class="b-propos b-propos2">
	<div class="t-emploe">Специальные предложения</div>

	<?

		//сортировка в корневом /sell/
		$APPLICATION->IncludeComponent('wexpert:includer', "sort_in_sell",
			array('OK' => 'no'),
			false
		);
		$arr = explode(',', CATALOG_IBLOCKS);
		$APPLICATION->IncludeComponent("wexpert:iblock.list","sell",Array(
			"ORDER"                             => array($_GET["SORT_BY"] => $_GET["SORT_ORDER"]),
			"FILTER"                            => array($GLOBALS['FILTER_PROPERTY'] => $GLOBALS['FILTER_VALUES'], 'PROPERTY_SPECIAL_OFFER_VALUE' => 'Да'),
			"IBLOCK_ID"							=> $arr,
			"PAGESIZE"						    => 4,
			"SELECT"						    => "DATE_CREATE",
			"GET_PROPERTY"						=> "Y",
			"CACHE_TIME"    => 3600
		));
	?>


</div><!--b-propos-->