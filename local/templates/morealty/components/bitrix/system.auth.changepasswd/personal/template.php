<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) { die(); } ?>



	<div class="b-pass">
		<form method="post" action="<?=$arResult["AUTH_FORM"]?>" name="bform">
			<?if (strlen($arResult["BACKURL"]) > 0): ?>
				<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
			<? endif ?>
			<input type="hidden" name="AUTH_FORM" value="Y">
			<input type="hidden" name="TYPE" value="CHANGE_PWD">

			<div class="change-pass">

				<div class="field-pass">
					<label>Новый пароль<span class="req-star">*</span></label>
					<input name="USER_PASSWORD" type="password" />
				</div><!--field-pass-->

				<div class="field-pass">
					<label>Ещё раз новый<span class="req-star">*</span></label>
					<input name="USER_CONFIRM_PASSWORD" type="password" />
				</div><!--field-pass-->
			</div><!--change-pass-->

			<div class="but-pop but-pop4">
				<input name="change_pwd" type="submit" value="Сохранить" />
			</div>
		</form>
	</div>