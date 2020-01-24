<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?


if($arParams["~AUTH_RESULT"]["TYPE"] != "ERROR") {
	ShowMessage($arParams["~AUTH_RESULT"]);
}
?>

<div><?=GetMessage("AUTH_FORGOT_PASSWORD_1")?></div>


<div class="change-pass b-rekviz b-forgot">
	<form name="bform" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
		<?
		if (strlen($arResult["BACKURL"]) > 0)
		{
			?>
			<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
			<?
		}
		?>
		<input type="hidden" name="AUTH_FORM" value="Y">
		<input type="hidden" name="TYPE" value="SEND_PWD">
		<div class="bl-rekviz">
			<div class="t-rekviz">Информация о юридическом лице</div>
			<div class="field-pass field-pass2">
				<label><?=GetMessage("AUTH_LOGIN")?></label>
				<input type="text" name="USER_LOGIN" maxlength="50" value="<?=$arResult["LAST_LOGIN"]?>" /><span class="or"><?=GetMessage("AUTH_OR")?></span>
			</div><!--field-pass-->
			<div class="field-pass field-pass2">
				<label><?=GetMessage("AUTH_EMAIL")?></label>
				<input type="text" name="USER_EMAIL" maxlength="255" />
			</div>
			<div class="but-pop but-pop4">
				<input type="submit" name="send_account_info" value="<?=GetMessage("AUTH_SEND")?>"
			</div>
		</div><!--bl-rekviz-->
		<!--bl-rekviz-->
	</form>
</div>



<script type="text/javascript">
	document.bform.USER_LOGIN.focus();
</script>



