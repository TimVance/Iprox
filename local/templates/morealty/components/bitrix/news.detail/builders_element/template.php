<?
$arProperties = $arResult["PROPERTIES"];
$GLOBALS['EMAILS_FOR_FORM'] = $arProperties['EMAIL']['VALUE'];
?>
<div class="adress-agency"><?= $arProperties['ADDRESS']['VALUE'] ?></div>

<div class="about-agency about-agency2">
	<div class="about-agency-l">
		<div class="bl-rielt">
			<div class="photo-r">
				<img src="<?= $arResult['PREVIEW_PICTURE']["SRC"] ?>" alt=""/>
			</div>
			<div class="descr-r">
				<div class="link-r">
					<ul>
						<li>
							<a target="_blank" href="<?= $arProperties['SITE_ADDRESS']['VALUE'] ?>" rel="nofollow"><?= $arProperties['SITE_ADDRESS']['VALUE'] ?></a>
						</li>
						<li>
							<a href="mailto:<?= $arProperties['EMAIL']['VALUE'] ?>"><?= $arProperties['EMAIL']['VALUE'] ?></a>
						</li>
					</ul>
				</div>
				<?/*= $arProperties['PHONE_NUMBER']['VALUE'] */?>
				<div class="view-phone">
					<a href="javascript:void(0);" data-builder="<?=$arResult["ID"]?>">Показать телефон</a>
					<div class="phone-v"><span></span></div>
				</div>

				<div class="send-mess"><a class="inline inline-message" data-user="<?=$arResult["ID"]?>" href="#send_message_to_realtor-form">Оставить сообщение</a></div>
			</div><!--descr-r-->
		</div><!--bl-rielt-->

		<div class="text-ab-ag text-ab-ag2">
			<div class="t-ab">О компании</div>
			<div class="text-acoord">
				<?= $arResult["DETAIL_TEXT"] ?>
			</div>
		</div><!--text-about-ag-->

		<div class="accord-card">

			<? if ($arProperties["ACTIVITIES"]["VALUE"])
			{
				?>
				<div class="item-accrod">
					<div class="tit-accord"><span>Виды деятельности</span></div>
					<div class="text-accrod">
						<ul>
							<? foreach ($arProperties["ACTIVITIES"]["VALUE"] as $itemValue): ?>
								<li><?= $itemValue ?></li>
							<? endforeach; ?>
						</ul>
					</div>
				</div>
				<?
			}?>

			<? if ($arProperties["LICENSE"]["VALUE"])
			{
				?>
				<div class="item-accrod">
					<div class="tit-accord"><span>Лицензии</span></div>
					<div class="text-accrod">
						<ul>
							<? foreach ($arProperties["LICENSE"]["VALUE"] as $itemValue): ?>
								<li><?= $itemValue ?></li>
							<? endforeach; ?>
						</ul>
					</div>
				</div>
				<?
			}?>

			<? if ($arProperties["PARTNERS"]["VALUE"]) 
			{
				?>
					<div class="item-accrod">
						<div class="tit-accord"><span>Партнеры</span></div>
						<div class="text-accrod">
							<ul>
								<? foreach ($arProperties["PARTNERS"]["VALUE"] as $itemValue): ?>
									<li><?= $itemValue ?></li>
								<? endforeach; ?>
							</ul>
						</div>
					</div>
				<?
			}?>

		</div>
	</div>

	<div class="about-agency-r">
		<div class="cont-rielt">
			<? foreach ($arResult["PROPERTIES"]["CONTACT_PERSON"]["VALUE"] as $userID): ?>
				<?
				$rsUser = CUser::GetByID($userID);
				$arUser = $rsUser->Fetch();?>
				<?if(!empty($arUser)): ?>
			<div class="t-cont-rielt">Контактные лица</div>
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
								<span><?= $arUser["UF_PERSON_POST"] ?></span>
							</div>
							<div class="view-rielt-phone">
								<input type="hidden" id="realtor_id" value="<?= $arUser['ID'] ?>">
								<a href="javascript:void(0);">Показать телефон</a>
								<span><?=$arUser['PERSONAL_MOBILE']?></span>
							</div>
						</div>
					</div>
				</div>
				<? endif; ?>
			<? endforeach; ?>
		</div><!--cont-rielt-->
		<div class="video-agency">
			<iframe width="100%" height="315" src="<?= $arProperties["VIDEO_LINK"]["VALUE"] ?>" frameborder="0"
			        allowfullscreen></iframe>
		</div>

		<div class="head-answ-rielt">
			<div class="ask-wrapper">
				<div class="t-answ-rielt">Вопросы и ответы</div><!--t-answ-rielt-->

				<div class="add-ask"><a href="javascript:void(0);">Задать вопрос</a></div>
			</div>

			<div id="form-question-wrap" style="display: none;">
				<? $APPLICATION->IncludeComponent(
					"bitrix:form.result.new",
					"ask_question",
					Array(
						"CACHE_TIME"             => "3600",
						"CACHE_TYPE"             => "A",
						"IGNORE_CUSTOM_TEMPLATE" => "N",
						"SEF_MODE"               => "N",
						"USE_EXTENDED_ERRORS"    => "N",
						"WEB_FORM_ID"            => 2,
						'elementID'              => $arResult['ID']
					)
				); ?>
			</div>
		</div>

		<div class="ask-rielt ask-rielt2">
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
										<?/*<a href="/realtors/<?= $realtorID . '/' ?>"><?= $arUser["FULL_NAME"] ?></a>
										<span><?= $arUser["UF_AGENT_NAME"] ?></span>*/?>
									</div>
									<p><?= $arAskText ?></p>
								</div>
							</div>

						<? endwhile; ?>
					</div>
				</div>
			<? endwhile; ?>
		</div>
	</div>
</div>


<?
$APPLICATION->IncludeComponent("wexpert:iblock.list", "newbuildings_at_builders_page", Array(
	"ORDER"        => array("sort" => "desc"),
	"FILTER"       => array("PROPERTY_builder" => $arResult['ID']),
	"IBLOCK_ID"    => 19,
	"PAGESIZE"     => 4,
	"GET_PROPERTY" => "Y",
	//"NAV_TEMPLATE" => "morealty",
	"CACHE_TIME"   => 3600,
	"SHOW_PROPERTY" => array(
		"class", "type", "floors", "square_ot", "square_do",
		"price_m2_ot", "price_m2_do", "deadline"=>array("NAME"=>"Срок сдачи")),
));
?>

<?
$newbuildingsID = array();
$arSelect = Array("ID");
$arFilter = Array("IBLOCK_ID" => 19, "PROPERTY_OBJECTS_FOR_SALE" => $arResult['ID'],'PROPERTY_builder'=>$arResult["ID"], "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while($ob = $res->Fetch())
{
	$newbuildingsID[] = $ob['ID'];
}
?>

<?
$APPLICATION->IncludeComponent("wexpert:iblock.list", "all_objects_at_builders_page", Array(
	"ORDER"        => array("sort" => "desc"),
	"FILTER"       => array("PROPERTY_newbuilding" => $newbuildingsID),
	"IBLOCK_ID"    => 7,
	"PAGESIZE"     => 100,
	"SELECT"       => "SHOW_COUNTER",
	"GET_PROPERTY" => "Y",
	//"NAV_TEMPLATE" => "morealty",
	"CACHE_TIME"   => 3600,
	"SHOW_PROPERTY" => array(
		"class", "type", "floors", "square_ot", "square_do",
		"price_m2_ot", "price_m2_do", "deadline"),
));
?>





	<?
	
	//сортировка в корневом /sell/
	$templateByView = ($_SESSION['VIEW_TYPE'] == 'tiles') ? 'special_offers_view_tiles' : 'special_offers';

	$APPLICATION->IncludeComponent("wexpert:iblock.list", $templateByView, Array(
		"ORDER"                             => array($_GET["SORT_BY"] => $_GET["SORT_ORDER"]),
		"FILTER"                            => array($GLOBALS['FILTER_PROPERTY'] => $GLOBALS['FILTER_VALUES'],
		                                             '!SECTION_ID' => array(1, 2, 3),
		                                             'PROPERTY_SPECIAL_OFFER' => 359,"PROPERTY_newbuilding"=>$newbuildingsID),
		"IBLOCK_ID"							=> $GLOBALS['CATALOG_IBLOCKS_ARRAY'],
		"PAGESIZE"						    => 4,
		"SELECT"						    => "DATE_CREATE",
		"GET_PROPERTY"						=> "Y",
		"CACHE_TIME"    => 0
	));
	?>


