<?
//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
//$APPLICATION->SetTitle("Офис продаж ЖК &ldquo;Люксембург&rdquo;");
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>
<?
CModule::IncludeModule("form");
CIBlockElement::CounterInc($arResult['ID']);
$IBLOCK_ID = 19;
$ELEMENT_ID = $_REQUEST['ID'];

$title = $arResult['NAME'] . " — вопросы";
$APPLICATION->SetTitle($title);
$APPLICATION->AddChainItem($arResult['NAME'], "/newbuildings/" . $_REQUEST['ID'] . '/');
$APPLICATION->AddChainItem("Вопросы", $APPLICATION->GetCurDir());



$arProperties = $arResult['PROPERTIES'];

$arSelect = array('ID', 'NAME', 'IBLOCK_ID', 'PROPERTY_*');
$arFilter = Array("IBLOCK_ID" => 7, "ACTIVE" => "Y", "PROPERTY_newbuilding" => $ELEMENT_ID);
$res = CIBlockElement::GetList(Array('sort' => 'desc'), $arFilter, false, false, $arSelect);
$arItems = array();
$min_square = 99999;
$max_square = 0;
$flatsCount = $res->SelectedRowsCount();
while($ob = $res->GetNextElement()) {
	$item = $ob->GetFields();
	$item['PROPERTIES'] = $ob->GetProperties();
	if($item['PROPERTIES']['square']['VALUE'] > $max_square && $item['PROPERTIES']['square']['VALUE'] != null) {
		$max_square = $item['PROPERTIES']['square']['VALUE'];
	}
	if($item['PROPERTIES']['square']['VALUE'] < $min_square && $item['PROPERTIES']['square']['VALUE'] != null) {
		$min_square = $item['PROPERTIES']['square']['VALUE'];
	}
	$arItems[] = $item;
}
?>
	<div class="adress-agency">
		<?= $arProperties['city']['MODIFIED_VALUE'] ?>
		, <?= $arProperties['district']['MODIFIED_VALUE'] ?>
		, <?= $arProperties['microdistrict']['MODIFIED_VALUE'] ?>
		, <?= $arProperties['street']['VALUE'] ?>
	</div>

	<div class="block-office">
		<div class="office-l">
				<? $photoLink = addWatermark($arProperties['photo_gallery'][0]['VALUE']); ?>
			<div class="img-office"><img src="<?=$photoLink?>" alt="" /></div>
			<div class="price-office"><?= number_format($arProperties['price_m2_ot']['VALUE'], 0, '', ' ') ?> <?= $arProperties['currency']['VALUE_XML_ID'] ?> за м<sup>2</sup></div>

			<div class="params-offie">
				<ul>
				<?foreach($arParams['SHOW_PROPERTY'] as $code):?>
				
				<?
						$property = $arProperties[$code];
						$square_dimension = "м<sup>2</sup>";
						$metres = 'м';
					?>

					<? //если список ?>
					<?if(!empty($property['VALUE_ENUM'])):?>
						<li><span><?=$property['NAME']?></span> <strong><?=$property['VALUE_ENUM']?></strong></li>

					<? //если HTML поле ?>
					<?elseif($property['VALUE']['TYPE'] == 'HTML'):?>
						<li><span><?=$property['NAME']?></span> <strong><?=$property['VALUE']['TEXT']?></strong></li>

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
								<li><span><?=$property['NAME']?></span> <strong><?=$property['VALUE']?></strong></li>
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
							<strong><?=substr($val, 0, -2)?></strong>
						</li>

					<? //если чекбокс не проставлен ?>
					<?else:?>
						<?if($property['PROPERTY_TYPE'] == "L"):?>
							<li><span><?=$property['NAME']?></span> <strong>нет</strong></li>
						<?else:?>
							<li><span><?=$property['NAME']?></span> <strong>не указано</strong></li>
						<?endif;?>
					<?endif;?>
				<?endforeach; ?>
					<?/* ?><li><span>Срок сдачи</span>	<strong>сдан</strong>
					<li><span>Предложений</span> <strong>20</strong>


					<li><span>Класс</span> <strong><?= $arProperties['class']['VALUE_ENUM'] ?></strong></li>
					<li><span>Площадь квартир</span> <strong>от <?=$min_square?> до <?=$max_square?> м2</strong></li>
					<li><span>Расстояние до моря</span>	<strong><?= $arProperties['distance_to_sea']['VALUE'] ?></strong></li>
					<li><span>Срок сдачи</span>	<strong>сдан</strong></li>
					<li><span>Всего квартир</span> <strong><?=$flatsCount?></strong></li><? */?>
					<li><span>Всего квартир</span> <strong><?=$arResult["flatsCount"]?></strong></li>
				</ul>
			</div><!--params-office-->

			<div class="func-office">
				<div class="but-view"><a href="/newbuildings/<?=$ELEMENT_ID?>/#flats">Смотреть квартиры</a></div>
				<div class="do-ask"><a class="scroll" href="#scroll-ask">Задать вопрос по новостройке</a></div>
			</div>
		</div><!--office-l-->

		<div class="office-r">
			<div class="search-answ">
				<div class="b-search-answ">
					<div class="tit-search-answ">Новостройки</div>
					<div class="field-answ">
						<input id="fast_search" type="text" placeholder="Поиск по названию" />
					</div>
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
		</div><!--office-r-->
	</div><!--blok-office-->

	<div class="b-askk">
		<div class="t-emploe">Вопросы и ответы</div>

		<div class="list-askk">
			<div class="ask-rielt">
			<?
			$arFilter = array();
			$arFields[]         = array(
				"CODE"           => "SIMPLE_QUESTION_974",
				"FILTER_TYPE"    => "text",                         // фильтруем по числовому полю
				"PARAMETER_NAME" => "USER",                      // фильтруем по введенному значению
				"VALUE"          => $arResult['ID'],                                  // значение по которому фильтруем
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

				?>
				<div class="item-ask <? if (!$IS_DISPLAY
					&& ( ( !$USER->IsAdmin()
							&& !in_array(7, CUser::GetUserGroup($USER->GetId())) )
						|| $USER->GetId() == '' )
				)
					echo 'hidden'; ?>">
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
								$arUser["PERSONAL_PHOTO"] = CFile::GetPath($arUser["PERSONAL_PHOTO"]);
							} else {
								$arUser["PERSONAL_PHOTO"] = '/local/templates/morealty/images/no-photo.jpg';
							}

							?>

							<div class="answer">
								<div class="img-answer"><img src="<?= $arUser["PERSONAL_PHOTO"] ?>" alt=""/></div>
								<!--img-answer-->
								<? $today = rus_date("j F Y", strtotime($arAskResult['DATE_CREATE'])); ?>
								<div class="desc-answer">
									<div class="date-answer"><?= $today ?></div>
									<div class="name-answ">
										<?/*<a href="/realtors/<?= $realtorID . '/' ?>"><?= $arUser["FULL_NAME"] ?></a>
										<span><?= $arUser["UF_AGENT_NAME"] ?></span>*/?>
									</div>
									<p><?= $arAskText ?></p>
								</div><!--desc-answer-->
							</div><!--answer-->

						<? endwhile; ?>
					</div><!--bl-ask-->
				</div>
			<? endwhile; ?>
		</div><!--ask-rielt-->
		</div>

	</div><!--b-askk-->

	<div id="scroll-ask" class="form-ask">
		<?
		if($USER->IsAuthorized()):
			$APPLICATION->IncludeComponent(
				"bitrix:form.result.new",
				"ask_question_at_detail_page",
				Array(
					"CACHE_TIME"             => "3600",
					"CACHE_TYPE"             => "A",
					"IGNORE_CUSTOM_TEMPLATE" => "N",
					"SEF_MODE"               => "N",
					"USE_EXTENDED_ERRORS"    => "N",
					"WEB_FORM_ID"            => 2,
					'elementID'              => $arResult['ID']
				)
			);
		 else: ?>
			<div class="t-form">Задать вопрос</div>
			 <span>Необходима <a href="/register/">авторизация</a>. </span>
		<?endif?>
	</div><!--form-ask-->

<?//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>