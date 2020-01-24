<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<p class="message-confirmation"><?echo $arResult["MESSAGE_TEXT"]?></p>
<?//here you can place your own messages
	switch($arResult["MESSAGE_CODE"])
	{
	case "E01":
		?><? //When user not found
		break;
	case "E02":
		?><? //User was successfully authorized after confirmation
		break;
	case "E03":
		?><? //User already confirm his registration
		break;
	case "E04":
		?><? //Missed confirmation code
		break;
	case "E05":
		?><? //Confirmation code provided does not match stored one
		break;
	case "E06":
		?><? //Confirmation was successfull
		break;
	case "E07":
		?><? //Some error occured during confirmation
		break;
	}
?>
<?if($arResult["SHOW_FORM"]):?>
<div class="auth-block">
	<form method="post" action="<?echo $arResult["FORM_ACTION"]?>">
		<table class="data-table bx-confirm-table auth-table">
			<tr>
				<td>
					<?echo GetMessage("CT_BSAC_LOGIN")?>:
				</td>
				<td>
					<input type="text" name="<?echo $arParams["LOGIN"]?>" maxlength="50" value="<?echo $arResult["LOGIN"]?>" size="17" />
				</td>
			</tr>
			<tr>
				<td>
					<?echo GetMessage("CT_BSAC_CONFIRM_CODE")?>:
				</td>
				<td>
					<input type="text" name="<?echo $arParams["CONFIRM_CODE"]?>" maxlength="50" value="<?echo $arResult["CONFIRM_CODE"]?>" size="17" />
				</td>
			</tr>
		</table>
		<p>
			<div class="but-choice">
				<input type="submit" value="<?echo GetMessage("CT_BSAC_CONFIRM")?>" />
			</div>
		</p>
		<input type="hidden" name="<?echo $arParams["USER_ID"]?>" value="<?echo $arResult["USER_ID"]?>" />
	</form>
</div>
<?elseif(!$USER->IsAuthorized()):?>
	<?$APPLICATION->IncludeComponent("bitrix:system.auth.authorize", "", array());?>
<?endif?>