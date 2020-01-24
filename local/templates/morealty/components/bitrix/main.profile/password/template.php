<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) { die(); } ?>
<?
	CConsole::$admin_only = false;
	CConsole::log($_REQUEST);

?>

<div class="b-pass">
	<form method="post" name="form1" action="<?=$arResult["FORM_TARGET"]?>" enctype="multipart/form-data">
		<?=$arResult["BX_SESSION_CHECK"]?>
		<input type="hidden" name="lang" value="<?=LANG?>" />
		<input type="hidden" name="ID" value=<?=$arResult["ID"]?> />
		<input type="hidden" name="EMAIL" maxlength="50" value="<? echo $arResult["arUser"]["EMAIL"]?>"/>
		<input type="hidden" name="LOGIN" maxlength="50" value="<? echo $arResult["arUser"]["LOGIN"]?>"/>


		<div class="change-pass">

			<div class="field-pass">
				<label>Новый пароль<span class="req-star">*</span></label>
				<input name="NEW_PASSWORD" type="password" />
			</div><!--field-pass-->
			<div class="field-pass">
				<label>Ещё раз новый<span class="req-star">*</span></label>
				<input name="NEW_PASSWORD_CONFIRM" type="password" />
			</div><!--field-pass-->
		</div><!--change-pass-->

		<div class="but-pop but-pop4">
			<input name="save" type="submit" value="Сохранить" />
		</div>
	</form>
</div>
<?if(!empty($_REQUEST["save"]) && !empty($_REQUEST["NEW_PASSWORD"]) && $_REQUEST["NEW_PASSWORD"] == $_REQUEST["NEW_PASSWORD_CONFIRM"]) :?>
	<script>
		$('.b-pass').before('<span class="submit-ok-text">Изменения успешно сохранены. </span>');
	</script>
<? endif; ?>