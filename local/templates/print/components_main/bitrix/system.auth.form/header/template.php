<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

	<?
	if ($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR'])
		ShowMessage($arResult['ERROR_MESSAGE']);
	?>

<div class="pop-input" id="wr-pop-inp">
	<div class="close"></div>
	<div class="head-pop-in">
		<div class="t-pop-inp">Вход</div>
		<div class="reg-pop-inp"><a href="/register/">Зарегистрироваться</a></div>
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
		<div class="field-pop"  id="email-field">
		<? /*?>	<input type="text" name="USER_EMAIL" placeholder="Электронная почта" /><?*/?>
			<input type="text" name="USER_LOGIN"  placeholder="Ваш логин"/>
		</div>
		<div class="field-pop field-pop2">
			<input type="password" name="USER_PASSWORD" placeholder="Пароль" />
			<div class="forgot"><a href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>" rel="nofollow"><?=GetMessage("AUTH_FORGOT_PASSWORD_2")?>Напомнить</a></div>
		</div>
		<div class="assest-inp customP">
			<input type="checkbox" id="assest" name="USER_REMEMBER" checked value="Y" />
			<label for="assest">Запомнить меня на этом компьютере</label>
		</div>
		<div class="but-pop"><input type="submit" value="Войти"></div>
	</form>

	<? endif; ?>
</div><!--pop-input-->

<?
if(
	!$USER->IsAuthorized()	&& (!empty($_REQUEST["USER_LOGIN"]) || !empty($_REQUEST["USER_PASSWORD"])
		&& !empty($_REQUEST["USER_REMEMBER"])
		&& $_REQUEST["TYPE"] == "AUTH") && (!$_REQUEST["forgot_password"] == 'yes')) {
	echo "<script>";
	echo "$('body').addClass('act');";
	echo "$('.wr-pop-inp, .ov').show();";
	echo "$('#email-field').before('<div class=error-auth-mess><span>Неверный логин или пароль. </span></div>');";
	echo "</script>";
}
?>