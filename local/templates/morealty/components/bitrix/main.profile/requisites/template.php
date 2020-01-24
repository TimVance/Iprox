<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) { die(); } ?>


<?
$arProperties = $arResult["USER_PROPERTIES"]["DATA"];
?>


	<div class="b-pass">
		<form method="post" name="form1" action="<?=$arResult["FORM_TARGET"]?>" enctype="multipart/form-data">
			<?=$arResult["BX_SESSION_CHECK"]?>
			<input type="hidden" name="lang" value="<?=LANG?>" />
			<input type="hidden" name="ID" value=<?=$arResult["ID"]?> />
			<input type="hidden" name="EMAIL" maxlength="50" value="<? echo $arResult["arUser"]["EMAIL"]?>"/>
			<input type="hidden" name="LOGIN" maxlength="50" value="<? echo $arResult["arUser"]["LOGIN"]?>"/>

			<div class="change-pass b-rekviz">
				<div class="bl-rekviz">
					<div class="t-rekviz">Информация о юридическом лице</div>
					<div class="field-pass field-pass2">
						<label><?=$arProperties["UF_FULL_NAME"]["EDIT_FORM_LABEL"]?><span class="req-star">*</span></label>
						<input type="text" name="<?=$arProperties["UF_FULL_NAME"]["FIELD_NAME"]?>" value="<?=$arProperties["UF_FULL_NAME"]["VALUE"]?>" />
					</div><!--field-pass-->

					<div class="field-pass field-pass2">
						<label><?=$arProperties["UF_ABBREVIATION"]["EDIT_FORM_LABEL"]?></label>
						<input type="text" name="<?=$arProperties["UF_ABBREVIATION"]["FIELD_NAME"]?>" value="<?=$arProperties["UF_ABBREVIATION"]["VALUE"]?>" />
					</div><!--field-pass-->

					<div class="field-pass field-pass2">
						<label><?=$arProperties["UF_LEGAL_ADDRESS"]["EDIT_FORM_LABEL"]?><span class="req-star">*</span></label>
						<input type="text" name="<?=$arProperties["UF_LEGAL_ADDRESS"]["FIELD_NAME"]?>" value="<?=$arProperties["UF_LEGAL_ADDRESS"]["VALUE"]?>" />
					</div><!--field-pass-->

					<div class="field-pass field-pass3">
						<label><?=$arProperties["UF_OGRN"]["EDIT_FORM_LABEL"]?><span class="req-star">*</span></label>
						<input type="text" name="<?=$arProperties["UF_OGRN"]["FIELD_NAME"]?>" value="<?=$arProperties["UF_OGRN"]["VALUE"]?>" />
					</div><!--field-pass-->

					<div class="field-pass field-pass3">
						<label><?=$arProperties["UF_INN"]["EDIT_FORM_LABEL"]?><span class="req-star">*</span></label>
						<input type="text" name="<?=$arProperties["UF_INN"]["FIELD_NAME"]?>" value="<?=$arProperties["UF_INN"]["VALUE"]?>" />
					</div><!--field-pass-->

					<div class="field-pass field-pass3">
						<label><?=$arProperties["UF_KPP"]["EDIT_FORM_LABEL"]?><span class="req-star">*</span></label>
						<input type="text" name="<?=$arProperties["UF_KPP"]["FIELD_NAME"]?>" value="<?=$arProperties["UF_KPP"]["VALUE"]?>" />
					</div><!--field-pass-->

					<div class="field-pass field-pass2">
						<label><?=$arProperties["UF_DIRECTOR_FULL_NAM"]["EDIT_FORM_LABEL"]?><span class="req-star">*</span></label>
						<input type="text" name="<?=$arProperties["UF_DIRECTOR_FULL_NAM"]["FIELD_NAME"]?>" value="<?=$arProperties["UF_DIRECTOR_FULL_NAM"]["VALUE"]?>" />
					</div><!--field-pass-->
				</div><!--bl-rekviz-->

				<div class="bl-rekviz">
					<div class="t-rekviz">Банковские реквизиты</div>
					<div class="field-pass field-pass4">
						<label><?=$arProperties["UF_CHECKING_ACCOUNT"]["EDIT_FORM_LABEL"]?><span class="req-star">*</span></label>
						<input type="text" name="<?=$arProperties["UF_CHECKING_ACCOUNT"]["FIELD_NAME"]?>" value="<?=$arProperties["UF_CHECKING_ACCOUNT"]["VALUE"]?>" />
					</div><!--field-pass-->

					<div class="field-pass field-pass2">
						<label><?=$arProperties["UF_BANK_NAME"]["EDIT_FORM_LABEL"]?><span class="req-star">*</span></label>
						<input type="text" name="<?=$arProperties["UF_BANK_NAME"]["FIELD_NAME"]?>" value="<?=$arProperties["UF_BANK_NAME"]["VALUE"]?>" />
					</div><!--field-pass-->

					<div class="field-pass field-pass3">
						<label><?=$arProperties["UF_COR_ACCOUNT"]["EDIT_FORM_LABEL"]?><span class="req-star">*</span></label>
						<input type="text" name="<?=$arProperties["UF_COR_ACCOUNT"]["FIELD_NAME"]?>" value="<?=$arProperties["UF_COR_ACCOUNT"]["VALUE"]?>" />
					</div><!--field-pass-->

					<div class="field-pass field-pass3">
						<label><?=$arProperties["UF_BIC"]["EDIT_FORM_LABEL"]?><span class="req-star">*</span></label>
						<input type="text" name="<?=$arProperties["UF_BIC"]["FIELD_NAME"]?>" value="<?=$arProperties["UF_BIC"]["VALUE"]?>" />
					</div><!--field-pass-->
				</div><!--bl-rekviz-->
			</div><!--change-pass-->

			<div class="but-pop but-pop4">
				<input type="submit" name="save" value="Сохранить" />
			</div>
		</form>
	</div>
<?if(!empty($_REQUEST["save"])) :?>
	<script>
		$('.b-pass').before('<span class="submit-ok-text">Изменения успешно сохранены. </span>');
	</script>
<? endif; ?>