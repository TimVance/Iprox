<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if ($arParams['WEB_FORM_ID'] == 5) {
	$inptNum = 17;
} else {
	$inptNum = 7;
}
?>

<div id="send_message_to_realtor-form" class="pop pop6">
	<div class="close"></div>



		<?if ($arResult["FORM_NOTE"]) {?>
			<div class="logo-form"><img src="<?=SITE_TEMPLATE_PATH.'/images/logo_ru.svg'?>" alt="" /></div>
			<p class="successtext"><?=$arResult["FORM_NOTE"]?></p>
		<?}?>

		<?if ($arResult["isFormNote"] != "Y") {?>


			<?
			/***********************************************************************************
			form header
			 ***********************************************************************************/
			if ($arResult["isFormTitle"])
			{
				?>
				<div class="t-pop t-pop4"><?=$arResult["FORM_TITLE"]?></div>
				<?
			}

			if ($arResult["isFormDescription"])
			{
				?>
				<p><?=$arResult["FORM_DESCRIPTION"]?></p>
				<?
			}?>
			<span class="err_cont"><?if ($arResult["isFormErrors"] == "Y"):?><?=$arResult["FORM_ERRORS_TEXT"];?><?endif;?></span>
			<?
			/***********************************************************************************
			form questions
			 ***********************************************************************************/
			?>
			<form name="SIMPLE_FORM_<?=$arParams['WEB_FORM_ID']?>">
			<input type="hidden" name="WEB_FORM_ID" value="<?=$arParams['WEB_FORM_ID']?>" />
			<input type="hidden" name="TPL" value="send_message_to_realtor" />
			<?=bitrix_sessid_post()?>
			<?

			foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion) {
				$isEmail = strpos(strtolower($arQuestion['CAPTION']), "почта") !== false;
				if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden') {
					echo $arQuestion["HTML_CODE"];
				} else {
					if($arQuestion['CAPTION'] == "ID Пользователя")
					{
						?><div style="display:none;"><?echo $arQuestion["HTML_CODE"];?></div><?
						continue;
					}
					$type = $arQuestion['STRUCTURE'][0]['FIELD_TYPE'];
					$id = $arQuestion['STRUCTURE'][0]['ID'];
					$name = 'form_'.$type.'_'.$id;
					$value = $arResult['arrVALUES'][$name];
					if($name == 'form_text_4') {
						//in local\templates\morealty\components\wexpert\iblock.detail\sell_catalog_item\template.php
						$value = $GLOBALS['EMAILS_FOR_FORM'];
					}
					$placeholder = (($arQuestion['REQUIRED']=='Y')?'*':'').$arQuestion['CAPTION'];
					if($arQuestion['REQUIRED'] == 'Y') {
						$placeholder = substr($placeholder, 1);
						$placeholder .= ' *';
					}
					if ($type == 'textarea') {?>
						<div class="textarea-pop"><textarea name="<?=$name?>" placeholder="<?=$placeholder?>"><?=$value?></textarea></div>
					<?} else {?>
						<div class="field-pop field-pop4 <?if($name == 'form_text_4') echo 'hidden';?>"><input type="text" name="<?=$name?>" class="<?=($isEmail)? "mail-input" : "";?>" placeholder="<?=$placeholder?>" value="<?=$value?>" /></div>
					<?}?>


					<?
				}
			} //endwhile
			?>
			<div class="field-f field-f2 form-checkbox">
							<div class="customP">
                                <input id="confident_checked_<?=$arParams['WEB_FORM_ID']?>"  name="confident_checked" type="checkbox" value="Y"/>
                                <label for="confident_checked_<?=$arParams['WEB_FORM_ID']?>">Я даю согласие на <a href="/confident/" target="_blank">обработку моих персональных данных.</a></label>
                            </div>
			</div>
			
			<?
			if($arResult["isUseCaptcha"] == "Y")
			{
				?>

				<div class="field-f field-f2"><input type="text" name="captcha_word" value="" placeholder="*Введите код с картинки" /></div>

				<div class="capcha">
					<input type="hidden" name="captcha_sid" value="<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" /><img src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" width="180" height="40" />
				</div>

				<?
			} // isUseCaptcha
			?>
			<div class="but-pop but-pop3">
				<?$btnVal = htmlspecialcharsbx(strlen(trim($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]);?>
				<button <?=(intval($arResult["F_RIGHT"]) < 10 ? "disabled=\"disabled\"" : "");?> type="submit" name="web_form_submit" value="<?=$btnVal?>"><?=$btnVal?></button>
			</div>
		</form>

			<?
		} //endif (isFormNote)
		?>
</div>
<script>
	$(function() {
		var $CallBack = $('#send_message_to_realtor-form');

		$($CallBack).on('input, keypress, keydown', 'input[name=form_text_6], input[name=form_text_16]',  function(e){
			return isPhone(e.which);
		});
		$($CallBack).on('input, keypress, keydown', '#time-from, #time-to',  function(e){
			return isNum(e.which) || e.which == 109 || e.which == 186 || e.which == 32 || e.which == 189;
		});

		$('#send_message_to_realtor-form').submit(function(e) {
			e.preventDefault();
			var $button = $(this);

			$button.data('submited', 'Y').attr('disabled','disabled');
			var isError = false;
			var arr = [$('form[name=SIMPLE_FORM_1] input[name=form_text_2]'), $('form[name=SIMPLE_FORM_1] input[name=form_text_3]')];


			for(var i = 0; i < arr.length; i++) {
				var el = arr[i];
				var BadVal = $(el).val() == "";
				if ($(el).is(".mail-input"))
				{
					BadVal = !isMail($(el).val());
				}
				if(BadVal &&  $button.find(el).length > 0) {
					$(el).addClass('field-error');

					if($(el).parents('.field-pop').find('.comp-s-new').length <= 0) {
						$(el).parents('.field-pop').append("<span class='comp-s-new'>Заполните обязательное поле «" + $(el).attr('placeholder').replace('*', '').trim() + "»</span>");
						$.colorbox.resize();
					}

					isError = true;
				} else {
					$(el).removeClass('field-error');
					$(el).parents('.field-pop').find('.comp-s-new').remove();
				}
			}

			var textar = $('form[name=SIMPLE_FORM_1] textarea');
			if($('form[name=SIMPLE_FORM_1] textarea').val() == '') {
				$(textar).addClass('field-error');
				if($(textar).parents('.textarea-pop').find('.comp-s-new').length <= 0) {
					$(textar).parents('.textarea-pop').append("<span class='comp-s-new'>Заполните обязательное поле «" + $(textar).attr('placeholder').replace('*', '').trim() + "»</span>");
				}
				isError = true;
			} else {
				$(textar).removeClass('field-error');
				$(textar).parents('.textarea-pop').find('.comp-s-new').remove();
			}
			if (!$button.find("#confident_checked_<?=$arParams['WEB_FORM_ID']?>").is(":checked"))
			{
				$button.find("#confident_checked_<?=$arParams['WEB_FORM_ID']?>").parents(".form-checkbox").addClass("field-error");
				isError = true;
				
			}
			else
			{
				$button.find("#confident_checked_<?=$arParams['WEB_FORM_ID']?>").parents(".form-checkbox").removeClass("field-error");
			}

			var $MailElement = $(this).find("[name='form_text_3']");
			var $MailStr = $MailElement.val();
			if(!isMail($MailStr))
			{
				$($MailElement).addClass('field-error');

				if($($MailElement).parents('.field-pop').find('.comp-s-new').length <= 0) {
					$($MailElement).parents('.field-pop').append("<span class='comp-s-new'>Заполните обязательное поле «" + $($MailElement).attr('placeholder').replace('*', '').trim() + "»</span>");
					$.colorbox.resize();
				}
				isError = true;
			}
			else {
				$($MailElement).removeClass('field-error');
				$($MailElement).parents('.field-pop').find('.comp-s-new').remove();
			}
			//$CallBack.find('>').wrap('<form name="SIMPLE_FORM_<?=$arParams['WEB_FORM_ID']?>"></form>');
			var Data = $('form[name=SIMPLE_FORM_<?=$arParams['WEB_FORM_ID']?>]').serializeJSON();
			Data.web_form_submit = '<?=GetMessage("FORM_SEND_BTN")?>';
			if(!isError) {
				$('.field-error').removeClass('field-error');
				$.ajax({
					async: true,
					data:  Data,
					timeout: 8000,
					type: 'POST',
					url: '/local/templates/morealty/ajax_php/forms.php',
					error: function(jqXHR){
						$button.data('submited', 'N').removeAttr('disabled');
						$.colorbox.resize();
					},
					success: function(data){
						$('form[name=SIMPLE_FORM_1] .but-pop').remove();
						$('form[name=SIMPLE_FORM_1] .field-pop').remove();
						$button.find("#confident_checked_<?=$arParams['WEB_FORM_ID']?>").parents(".form-checkbox").remove();
						$('form[name=SIMPLE_FORM_1] .textarea-pop').html('<span>Ваше сообщение успешно отправлено.</span>');
						$.colorbox.resize();
						yaCounter47701606.reachGoal("vopros");
						ga("send", "event" , "forms" , "submit" , "vopros");
					}
				});
			}
		});
	});
	var $data = null;

	//$('input[name="form_text_6"]').inputmask("+7 (999) 999-99-99");
</script>