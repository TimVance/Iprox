<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) { die(); } ?>

<?
//CConsole::$admin_only = false;
//CConsole::log($arResult);
?>

	<div class="pad-empty">
		<form method="post" name="form1" action="<?=$arResult["FORM_TARGET"]?>" enctype="multipart/form-data">
			<?=$arResult["BX_SESSION_CHECK"]?>
			<input type="hidden" name="lang" value="<?=LANG?>" />
			<input type="hidden" name="ID" value=<?=$arResult["ID"]?> />
			<input type="hidden" name="LOGIN" maxlength="50" value="<?=$arResult["arUser"]["LOGIN"]?>" />

			<div class="data prof">
				<div class="data-left">
					<div class="tit-regist tit-regist2">Персональная информация</div>
					<div class="bl-regist">
						<div class="field-pass">
							<label>Имя<span class="req-star">*</span></label>
							<input type="text" name="NAME" maxlength="50" value="<?=$arResult["arUser"]["NAME"]?>" />
						</div><!--field-pass-->

						<div class="field-pass">
							<label>Отчество</label>
							<input type="text" name="SECOND_NAME" maxlength="50" value="<?=$arResult["arUser"]["SECOND_NAME"]?>" />
						</div>
						<div class="field-pass">
							<label>Фамилия</label>
							<input type="text" name="LAST_NAME" maxlength="50" value="<?=$arResult["arUser"]["LAST_NAME"]?>" />
						</div><!--field-pass-->

						<div class="field-pass">
							<label>Телефон<span class="req-star">*</span></label>
							<input type="text" name="PERSONAL_PHONE" maxlength="50" value="<? echo $arResult["arUser"]["PERSONAL_PHONE"]?>" />
						</div><!--field-pass-->

						<div class="field-pass">
							<label>E-mail</label>
							<input type="text" name="EMAIL" maxlength="50" value="<? echo $arResult["arUser"]["EMAIL"]?>" />
						</div><!--field-pass-->

						<div class="assest-inp assest-inp2 customP">
							<input id="assest" value="" name="UF_DISPLAY_PRIVATE-chkbx" <? if($arResult["arUser"]["UF_DISPLAY_PRIVATE"] == "1" ) {echo "1";}?>" <? if($arResult["arUser"]["UF_DISPLAY_PRIVATE"] == "1" ) {echo "checked=\"checked\"";}?> type="checkbox" />
							<label for="assest">Не показывать на сайте</label>
						</div>

						<div class="field-pass field-skype">
							<label>Skype</label>
							<input type="text" name="UF_SKYPE" maxlength="50" value="<? echo $arResult["arUser"]["UF_SKYPE"]?>" />
						</div><!--field-pass-->
					</div><!--bl-regist-->
				</div><!--data-left-->

				<div class="data-right">
					<div class="tit-regist tit-regist2">Фото</div>
					<div class="down-photo">
						<div class="cont-photo">
							<?if(!empty($arResult["arUser"]["PERSONAL_PHOTO_HTML"])) :?>
								<?=$arResult["arUser"]["PERSONAL_PHOTO_HTML"]?>
							<? else: ?>
								<img src="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" alt="" />
							<? endif; ?>
						</div>

						<div class="func-photo">
							<div class="link-photo">
								<label>
									<input type="file" name="PERSONAL_PHOTO" style="display: none;">
									<span>Добавить фото</span>
								</label>
								<input type="text" id="filename" class="filename" disabled>
							</div>
							<p>Объявление с фото риэлтора вызывает больший интерес и доверие у посетителей сайта.</p>
						</div><!--func-photo-->
					</div><!--down-photo-->

					<div class="textar-about">
						<label>Обо мне</label>
						<textarea name="UF_ABOUT_ME"><? echo $arResult["arUser"]["UF_ABOUT_ME"]?></textarea>
					</div>
				</div><!--data-right-->
			</div><!--data-->

			<div class="block-assest"> 
				<div class="assest-inp assest-inp4 customP">
					<input id="assest2" name="UF_TAKE_MESSAGE-chkbx" value="<? if($arResult["arUser"]["UF_TAKE_MESSAGE"] == "1" ) {echo "1";}?>" <? if($arResult["arUser"]["UF_TAKE_MESSAGE"] == "1" ) {echo "checked=\"checked\"";}?> type="checkbox" />
					<label for="assest2">Не получать в личном кабинете сообщения от посетителей сайта</label>
				</div>
				<? CConsole::log($_REQUEST); ?>
				<? CConsole::log($arResult["arUser"]["UF_TAKE_EMAIL"]); ?>
				<div class="assest-inp assest-inp4 customP">
					<input id="assest3"  name="UF_TAKE_EMAIL-chkbx" value="<? if($arResult["arUser"]["UF_TAKE_EMAIL"] == "1" ) {echo "Y";}?>" <? if($arResult["arUser"]["UF_TAKE_EMAIL"] == "1" ) {echo "checked=\"checked\"";}?> type="checkbox" />
					<label for="assest3">Не получать e-mail уведомлений о сообщениях</label>
				</div>
			</div>

			<div class="but-pop but-pop4">
				<input type="hidden" name="UF_DISPLAY_PRIVATE" value="<? if($arResult["arUser"]["UF_DISPLAY_PRIVATE"] == "1" ) {echo "1";} else {echo "0";}?>">
				<input type="hidden" name="UF_TAKE_MESSAGE" value="<? if($arResult["arUser"]["UF_TAKE_MESSAGE"] == "1" ) {echo "1";} else {echo "0";}?>">
				<input type="hidden" name="UF_TAKE_EMAIL" value="<? if($arResult["arUser"]["UF_TAKE_EMAIL"] == "1" ) {echo "1";} else {echo "0";}?>">
				<input name="save" type="submit" value="Сохранить" />
			</div>
		</form>
	</div>
<?if(!empty($_REQUEST["save"])):?>
	<script>
		$('.b-pass').before('<span class="submit-ok-text">Изменения успешно сохранены. </span>');
		if($('.pad-empty')) {
			$('.pad-empty').before('<span class="submit-ok-text">Изменения успешно сохранены. </span>');
		}
	</script>
<? endif; ?>