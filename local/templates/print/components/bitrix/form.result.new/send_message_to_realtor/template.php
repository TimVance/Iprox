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
				if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden') {
					echo $arQuestion["HTML_CODE"];
				} else {
					$type = $arQuestion['STRUCTURE'][0]['FIELD_TYPE'];
					$id = $arQuestion['STRUCTURE'][0]['ID'];
					$name = 'form_'.$type.'_'.$id;
					$value = $arResult['arrVALUES'][$name];
					if($name == 'form_text_4') {
						//in local\templates\morealty\components\wexpert\iblock.detail\sell_catalog_item\template.php
						$value = $GLOBALS['REALTOR_EMAILS'];
					}
					$placeholder = (($arQuestion['REQUIRED']=='Y')?'*':'').$arQuestion['CAPTION'];
					if($arQuestion['REQUIRED'] == 'Y') {
						$placeholder = substr($placeholder, 1);
						$placeholder .= ' *';
					}
					if ($type == 'textarea') {?>
						<div class="textarea-pop"><textarea name="<?=$name?>" placeholder="<?=$placeholder?>"><?=$value?></textarea></div>
					<?} else {?>
						<div class="field-pop field-pop4 <?if($name == 'form_text_4') echo 'hidden';?>"><input type="text" name="<?=$name?>" placeholder="<?=$placeholder?>" value="<?=$value?>" /></div>
					<?}?>


					<?
				}
			} //endwhile
			?>
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
				console.log('WORKED');
				var el = arr[i];
				if($(el).val() == '') {
					$(el).addClass('field-error');
					isError = true;
				} else {
					$(el).remove('field-error');
				}
			}

			if($('form[name=SIMPLE_FORM_1] textarea').val() == '') {
				$('form[name=SIMPLE_FORM_1] textarea').addClass('field-error');
				isError = true;
			} else {
				$('form[name=SIMPLE_FORM_1] textarea').removeClass('field-error');
			}


			//$CallBack.find('>').wrap('<form name="SIMPLE_FORM_<?=$arParams['WEB_FORM_ID']?>"></form>');
			var Data = $('form[name=SIMPLE_FORM_<?=$arParams['WEB_FORM_ID']?>]').serializeJSON();
			Data.web_form_submit = '<?=GetMessage("FORM_SEND_BTN")?>';
			console.log(Data);
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
						$('form[name=SIMPLE_FORM_1] .textarea-pop').html('<span>Ваше сообщение успешно отправлено.</span>');
						$.colorbox.resize();
					}
				});
			}
		});
	});
	var $data = null;

	//$('input[name="form_text_6"]').inputmask("+7 (999) 999-99-99");
</script>