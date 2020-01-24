<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Последние 10 вопросов");

CModule::IncludeModule("form");
CModule::IncludeModule("iblock");
CIBlockElement::CounterInc($arResult['ID']);
$IBLOCK_ID = 19;
$ELEMENT_ID = $_REQUEST['ID'];?>

	<div class="b-ask">
		<div class="ask-left">
				<div class="ask-rielt">
					<?
					$arFilter = array();
					$arFields[]         = array(
						"CODE"           => "SIMPLE_QUESTION_974",
						"FILTER_TYPE"    => "text",                         // фильтруем по числовому полю
						"PARAMETER_NAME" => "USER",                      // фильтруем по введенному значению
						"VALUE"          => '',                                  // значение по которому фильтруем
						"EXACT_MATCH"    => "Y"                              // ищем точное совпадение
					);
					$arFilter["FIELDS"] = $arFields;
					$is_filtered        = true;
					$rsResults          = CFormResult::GetList(2, $by = 's_id', $order = 'asc', $arFilter, $is_filtered, 'N', 20);

					while ($arAskResult = $rsResults->Fetch()): ?>
						<?
						$arAnswer = CFormResult::GetDataByID($arAskResult['ID'], array(), $someUselessArray, $arAnswer2);
						unset( $someUselessArray );
						unset( $arAnswer2 );
						$arAskAuthor = $arAnswer['SIMPLE_QUESTION_161'][0]['USER_TEXT'];
						$arAskText   = $arAnswer['SIMPLE_QUESTION_632'][0]['USER_TEXT'];
						$newbuildingID   = $arAnswer['SIMPLE_QUESTION_974'][0]['USER_TEXT'];
						$curElID = CIBlockElement::GetByID($newbuildingID)->Fetch();
						if($curElID['IBLOCK_ID'] != 19) {
							continue;
						}

						$arFilter   = array();
						$arFields[] = array(
							"CODE"           => "SIMPLE_QUESTION_102",
							"FILTER_TYPE"    => "text",                         // фильтруем по числовому полю
							"PARAMETER_NAME" => "USER",                      // фильтруем по введенному значению
							"VALUE"          => $arAskResult['ID'],                                  // значение по которому фильтруем
							"EXACT_MATCH"    => "Y"                              // ищем точное совпадение
						);

						$arFilter["FIELDS"] = $arFields;

						$rsResults1 = CFormResult::GetList(3, $by = 's_id', $order = 'asc', $arFilter, $is_filtered, 'N', false);
						if ($arAskResultTemp = $rsResults1->Fetch()) {
							$IS_DISPLAY = true;
						} else {
							$IS_DISPLAY = false;
						}
						$today = rus_date("j F Y", strtotime($arAskResult['DATE_CREATE']));

						$arSelect3 = Array("ID", "NAME", "IBLOCK_ID", "PROPERTY_photo_gallery", "PROPERTY_CITY", "PROPERTY_DISTRICT", "PROPERTY_MICRODISTRICT", "PROPERTY_STREET");
						$arFilter2 = Array("IBLOCK_ID" => 19, "ID" => $newbuildingID, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
						$res5 = CIBlockElement::GetList(Array(), $arFilter2, false, Array("nPageSize"=>50), $arSelect3);
						while($item = $res5->Fetch()) {
							$ob = $item;
						}
						$res7 = CIBlockElement::GetByID($ob['PROPERTY_CITY_VALUE']);
						//city
						if ($ar_res = $res7->GetNext()) {
							$ob['PROPERTY_CITY_VALUE'] = $ar_res['NAME'];
						}
						$res7 = CIBlockElement::GetByID($ob['PROPERTY_DISTRICT_VALUE']);
						//district
						if ($ar_res = $res7->GetNext()) {
							$ob['PROPERTY_DISTRICT_VALUE'] = $ar_res['NAME'];
						}
						$res7 = CIBlockElement::GetByID($ob['PROPERTY_MICRODISTRICT_VALUE']);
						//microdistrict
						if ($ar_res = $res7->GetNext()) {
							$ob['PROPERTY_MICRODISTRICT_VALUE'] = $ar_res['NAME'];
						}
						$photoLink = 'http://' . $_SERVER['HTTP_HOST'] . '/' . CFile::GetPath($ob['PROPERTY_PHOTO_GALLERY_VALUE']);
						?>
						<div class="item-ask <? if (!$IS_DISPLAY
							&& ( ( !$USER->IsAdmin()
									&& !in_array(7, CUser::GetUserGroup($USER->GetId())) )
								|| $USER->GetId() == '' )
						)
							echo 'hidden'; ?>">
							<div class="info-ask">
								<div class="img-info-ask"><img src="<?=$photoLink?>" alt="" /></div><!--img-info-ask-->
								<div class="desc-info-ask">
									<div class="t-info-ask"><a href="/newbuildings/<?=$ob['ID']?>/questions/"><?=$ob['NAME']?></a></div>
									<p>
										<?=$ob['PROPERTY_CITY_VALUE']?>,
										<?=$ob['PROPERTY_DISTRICT_VALUE']?>,
										<?=$ob['PROPERTY_MICRODISTRICT_VALUE']?>,
										<?=$ob['PROPERTY_STREET_VALUE']?>
									</p>
								</div><!--desc-info-ask-->
							</div><!--info-ask-->

							<div class="bl-ask">
								<div class="name-ask"><strong><?= $arAskAuthor ?>,</strong> <?= $today ?></div>
								<div class="body-ask"><?= $arAskText ?></div>
								<div
									class="reply-btn <? if (( !$USER->IsAdmin() && !in_array(7, CUser::GetUserGroup($USER->GetId())) ))
										echo 'hidden'; ?>">
									<a href="javascript:void(0);">Ответить</a>
								</div>

								<div class="reply-form">
									<? $APPLICATION->IncludeComponent(
										"bitrix:form.result.new",
										"reply_question",
										Array(
											"CACHE_TIME"             => "3600",
											"CACHE_TYPE"             => "A",
											"IGNORE_CUSTOM_TEMPLATE" => "N",
											"SEF_MODE"               => "N",
											"USE_EXTENDED_ERRORS"    => "N",
											"WEB_FORM_ID"            => 3,
											'REALTOR_ID'             => $arUser['ID'],
											'ELEMENT_ID'             => $arResult['ID'],
											'QUESTION_ID'            => $arAskResult['ID'],
											'REALTOR_EMAIL'          => $arUser['EMAIL']
										)
									); ?>
								</div>

								<?

								$rsResults2 = CFormResult::GetList(3, $by = 's_id', $order = 'asc', $arFilter, $is_filtered, 'N', false);

								while ($arAskResult = $rsResults2->Fetch()): ?>
									<?
									$arAnswer = CFormResult::GetDataByID($arAskResult['ID'], array(), $someUselessArray, $arAnswer2);
									unset( $someUselessArray );
									unset( $arAnswer2 );
									$realtorID = $arAnswer['SIMPLE_QUESTION_565'][0]['USER_TEXT'];
									$arAskText = $arAnswer['SIMPLE_QUESTION_997'][0]['USER_TEXT'];


									$rsUser = CUser::GetByID($realtorID);
									$arUser = $rsUser->Fetch();

									$arUser['FULL_NAME'] = $arUser['NAME'] . ' ' . $arUser['SECOND_NAME'] . ' ' . $arUser['LAST_NAME'];
									$IBLOCK_ID           = 3;

									$Res = CIBlockElement::GetByID($arUser["UF_AGENT_NAME"]);
									if ($ar_props = $Res->GetNext()) {
										$arUser["UF_AGENT_NAME"]     = $ar_props["NAME"];
										$arUser["UF_AGENT_PHOTO_ID"] = $ar_props["PREVIEW_PICTURE"];
									}
									if (!empty( $arUser["PERSONAL_PHOTO"] )) {
										$arUser["PERSONAL_PHOTO"] = 'http://' . $_SERVER['HTTP_HOST'] . '/' . CFile::GetPath($arUser["PERSONAL_PHOTO"]);
									} else {
										$arUser["PERSONAL_PHOTO"] = 'http://' . $_SERVER['HTTP_HOST'] . '/' . 'local/templates/morealty/images/no-photo.jpg';
									}

									?>

									<div class="answer">
										<div class="img-answer"><img src="<?= $arUser["PERSONAL_PHOTO"] ?>" alt=""/></div>
										<!--img-answer-->
										<? $today = rus_date("j F Y", strtotime($arAskResult['DATE_CREATE'])); ?>
										<div class="desc-answer">
											<div class="date-answer"><?= $today ?></div>
											<div class="name-answ">
												<a href="/realtors/<?= $realtorID . '/' ?>"><?= $arUser["FULL_NAME"] ?></a>
												<span><?= $arUser["UF_AGENT_NAME"] ?></span>
											</div>
											<p><?= $arAskText ?></p>
										</div><!--desc-answer-->
									</div><!--answer-->


								<? endwhile; ?>
							</div><!--bl-ask-->
						</div>
					<? endwhile; ?>
				</div><!--ask-rielt-->
		</div><!--ask-left-->

		<div class="ask-right">
			<div class="search-answ floating">
				<div class="b-search-answ">
					<div class="tit-search-answ">Новостройки</div>
					<div class="field-answ"><input id="fast_search" type="text" placeholder="Поиск по названию"></div>
				</div>

				<?
				$arNewbuildings = array();
				$arSelect = Array("ID", "NAME", "IBLOCK_ID", "DETAIL_PAGE_URL");
				$arFilter = Array("IBLOCK_ID"=> 19, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
				$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
				while($ob = $res->GetNextElement()) {
					$item = $ob->GetFields();
					$arNewbuildings[] = $item;
				}
				?>
				<div class="list-search-answ list-search-answ2">
					<ul>
						<?foreach($arNewbuildings as $newbuildings):?>
							<?
							$countAnswers = 0;
							$arFilter = array();
							$arFields[]         = array(
								"CODE"           => "SIMPLE_QUESTION_974",
								"FILTER_TYPE"    => "text",                         // фильтруем по числовому полю
								"PARAMETER_NAME" => "USER",                      // фильтруем по введенному значению
								"VALUE"          => $newbuildings['ID'],                                  // значение по которому фильтруем
								"EXACT_MATCH"    => "Y"                              // ищем точное совпадение
							);
							$arFilter["FIELDS"] = $arFields;
							$is_filtered        = true;
							$rsResults          = CFormResult::GetList(2, $by = 's_id', $order = 'asc', $arFilter, $is_filtered, 'N', 20);
							$countQuestions = $rsResults->SelectedRowsCount();
							while ($arAskResult = $rsResults->Fetch()) {
								$arAnswer = CFormResult::GetDataByID($arAskResult['ID'], array(), $someUselessArray, $arAnswer2);
								unset( $someUselessArray );
								unset( $arAnswer2 );
								$arAskAuthor = $arAnswer['SIMPLE_QUESTION_161'][0]['USER_TEXT'];
								$arAskText   = $arAnswer['SIMPLE_QUESTION_632'][0]['USER_TEXT'];

								$arFilter   = array();
								$arFields[] = array(
									"CODE"           => "SIMPLE_QUESTION_102",
									"FILTER_TYPE"    => "text",                         // фильтруем по числовому полю
									"PARAMETER_NAME" => "USER",                      // фильтруем по введенному значению
									"VALUE"          => $arAskResult['ID'],                                  // значение по которому фильтруем
									"EXACT_MATCH"    => "Y"                              // ищем точное совпадение
								);

								$arFilter["FIELDS"] = $arFields;

								$rsResults1 = CFormResult::GetList(3, $by = 's_id', $order = 'asc', $arFilter, $is_filtered, 'N', false);
								$countAnswers += $rsResults1->SelectedRowsCount();

							}

							?>
							<?if($newbuildings['ID'] == $ELEMENT_ID):?>
								<li>
									<div>
										<i><?=$newbuildings['NAME']?> </i>
										<?if($countQuestions > 0):?>
											<span>вопросов <?=$countQuestions?> / ответов <?=$countAnswers?></span>
										<?endif;?>
									</div>
								</li>
							<?else:?>
								<li>
									<a href="<?=$newbuildings['DETAIL_PAGE_URL']?>questions/">
										<i><?=$newbuildings['NAME']?></i>
										<?if($countQuestions > 0):?>
											<span>вопросов <?=$countQuestions?> / ответов <?=$countAnswers?></span>
										<?endif;?>
									</a>
								</li>
							<?endif;?>
						<?endforeach;?>
					</ul>
				</div><!--list-search-answ-->
			</div><!--search-answ-->
		</div><!--ask-right-->
	</div><!--b-ask-->

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>