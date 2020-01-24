<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

	<?
	
	if ($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR'])
		ShowMessage($arResult['ERROR_MESSAGE']);
	?>

<div class="pop-input pop_auth_new" id="wr-pop-inp2">
	<style>
		.on_error {display: none !important;}
		.phone_wrong label {display: none !important;}
		.phone_wrong label.on_error {display: block !important; color: #d80000;}
		.phone_wrong input {color: #d80000;}
	</style>
	<div class="close"></div>
	<div class="head-pop-in">
		<div class="t-pop-inp">Вход</div>
		<?/* ?><div class="t-right-pop"><a href="<?=$arResult["AUTH_REGISTER_URL"]?>">Зарегистрироваться</a></div><?*/ ?>
	</div>
	<?if($arResult["FORM_TYPE"] == "login"):?>

	<form id="auth_form" name="system_auth_form<?=$arResult["RND"]?>" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
		<?if($arResult["BACKURL"] <> ''):?>
			<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
		<?endif?>
		<?foreach ($arResult["POST"] as $key => $value):?>
			<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
		<?endforeach?>
			<input type="hidden" name="AUTH_FORM" value="Y" />
			<input type="hidden" name="TYPE" value="AUTH" />
			<input type="hidden" name="is_phone" value="N" />
		<div class="field-pop">
			
			<label for="email-field">Пожалуйста, введите свой номер<br> мобильного телефона</label>
			<label for="email-field" class="on_error"></label>
			<input type="text" id="email-field" name="USER_PHONE"  placeholder="Ваш телефон"/>
		</div>
		<div class="field-pop"   style="display: none;">
			<label for="email-field">Введите код, отправленный<br>на указанный номер телефона</label>
			<label for="email-field" class="on_error">Введен не верный код</label>
			<input type="text" id="code-field" name="USER_PHONE_CODE" pattern=".{4}"  placeholder="4-значный код"/>
		</div>
		<div class="but-pop auth_button"><input type="submit" value="Войти"></div>
	</form>

	<? endif; ?>
	
	<script>

		$(document).ready(function(){
			$("#email-field").inputmask("+9(999)999-99-99", {clearIncomplete : true});
			//$("#code-field").inputmask("999999");
			$("#auth_form").bind("submit", function(){
				var t = $(this);
				var Data = t.serializeArray();
				Data.push({name : "ajax", value : "Y"});
				var phone = t.find("[name=\"USER_PHONE\"]");
				var phone_value = phone.val();
				var isPhoneEntered = t.find("[name=\"is_phone\"]").val() == "Y";
				var phone_code = t.find("[name=\"USER_PHONE_CODE\"]");
				var phone_code_value = phone_code.val();
				var is_allow = (isPhoneEntered)? typeof(phone_code_value) != "undefined" && phone_code_value.length > 0 : true;
				if (typeof(phone_value) != "undefined" && phone_value.length > 0 && is_allow)
				{
					$.post("<?=SITE_TEMPLATE_PATH?>/ajax_php/auth.php", Data, function(data){
						if (typeof(data) != "undefined")
						{
							if (data.status)
							{
								if (!isPhoneEntered)
								{
									t.find("[name=\"is_phone\"]").val("Y");
									phone.parents(".field-pop").hide();
									phone_code.parents(".field-pop").show();
									phone.parents(".field-pop").removeClass("phone_wrong");
								}
								else 
								{
									phone_code.parents(".field-pop").removeClass("phone_wrong");
									location.reload();
								}
							}
							else
							{
								if (!isPhoneEntered)
								{
									var text = "Ошибка";
									if (typeof(data.error) != "undefined" && data.error)
										text = data.error;
									phone.parents(".field-pop").addClass("phone_wrong").find(".on_error").html(text);
								}
								else 
								{
									phone_code.parents(".field-pop").addClass("phone_wrong");
								}
							}
							$.colorbox.resize();
						}
					}, "json");
				}
				
				return false;
			})
		})
	</script>
</div>
<?

?>
