<?
##########################################################################
# Name: kreattika.mlexport                                               #
# http://marketplace.1c-bitrix.ru/solutions/kreattika.mlexport/          #
# File: step1.php                                                        #
# Version: 1.0.1                                                         #
# (c) 2011-2016 Kreattika, Sedov S.Y.                                    #
# Proprietary licensed                                                   #
# http://kreattika.ru/                                                   #
# mailto:info@kreattika.ru                                               #
##########################################################################
?>
<?
IncludeModuleLangFile(__FILE__);

if ($GLOBALS["install_step"] == 2):

	if(!check_bitrix_sessid()) return;

	if($ex = $APPLICATION->GetException())
		echo CAdminMessage::ShowMessage(Array(
			"TYPE" => "ERROR",
			"MESSAGE" => GetMessage("MOD_INST_ERR"),
			"DETAILS" => $ex->GetString(),
			"HTML" => true,
		));
	else
		echo CAdminMessage::ShowNote(GetMessage("MOD_INST_OK"));
?>
<form action="<?echo $APPLICATION->GetCurPage()?>">
        <input type="hidden" name="lang" value="<?echo LANG?>">
        <input type="submit" name="" value="<?echo GetMessage("MOD_BACK")?>">
<form>
<?
	return;
endif;

$arSitesList = $GLOBALS["install_data"]["SitesList"];
$arServicesList = $GLOBALS["install_data"]["ServicesList"];
$arIBlockList = $GLOBALS["install_data"]["IBlockList"];
?>
<form action="<?=$APPLICATION->GetCurPage()?>" name="form1">
<?=bitrix_sessid_post()?>
<input type="hidden" name="lang" value="<?=LANGUAGE_ID?>" />
<input type="hidden" name="id" value="<?=GetModuleID(__FILE__);?>" />
<input type="hidden" name="install" value="Y" />
<input type="hidden" name="step" value="2" />

<br />

	<p><b><?=GetMessage("KREATTIKA_MLEXPORT_SELECT_SITES_TITLE")?></b></p>
	<p>
		<select name="sites[]" multiple="multiple" style="min-width: 250px;">
			<?foreach($arSitesList as $arSites):?>
				<option value="<?=$arSites["LID"]?>">[ <?=$arSites["LID"]?> ] <?=$arSites["NAME"]?></option>
			<?endforeach;?>
		</select>
	</p>

	<p><b><?=GetMessage("KREATTIKA_MLEXPORT_SELECT_SERVICES_TITLE")?></b></p>
	<p>
		<select name="services[]" multiple="multiple" style="min-width: 250px;">
			<?foreach($arServicesList as $arService):?>
				<option value="<?=$arService["CODE"]?>"><?=$arService["NAME"]?></option>
			<?endforeach;?>
		</select>
	</p>

	<p><b><?=GetMessage("KREATTIKA_MLEXPORT_SELECT_IBLOCK_TITLE")?></b></p>
	<p>
		<select name="iblock[]" multiple="multiple" style="min-width: 250px;">
			<?foreach($arIBlockList as $arIBlock):?>
				<option value="<?=$arIBlock["ID"]?>">[ <?=$arIBlock["CODE"]?> ] <?=$arIBlock["NAME"]?></option>
			<?endforeach;?>
		</select>
	</p>

<input type="submit" name="inst" value="<?= GetMessage("MOD_INSTALL")?>" />
</form>
<??>

