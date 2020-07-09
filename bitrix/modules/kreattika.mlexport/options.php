<?
##########################################################################
# Name: kreattika.mlexport                                               #
# http://marketplace.1c-bitrix.ru/solutions/kreattika.mlexport/          #
# File: options.php                                                      #
# Version: 1.0.5                                                         #
# (c) 2011-2016 Kreattika, Sedov S.Y.                                    #
# Proprietary licensed                                                   #
# http://kreattika.ru/                                                   #
# mailto:info@kreattika.ru                                               #
##########################################################################
?><?
use \Bitrix\Main\Localization\Loc;

use Bitrix\Main\Entity;
use Bitrix\Main\Type;

IncludeModuleLangFile(__FILE__);
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/options.php");

if(!$USER->IsAdmin()) return;

$MODULE_ID = $module_id = GetModuleID(__FILE__); //"kreattika.mlexport";
$SOLUTION_ID = substr($MODULE_ID, 10);
$strWarning = "";

$includeModule = \Bitrix\Main\Loader::IncludeModule($MODULE_ID);

$ModuleOn = COption::GetOptionString($MODULE_ID, "mlexport_on");
$ModuleRun = COption::GetOptionString($MODULE_ID, "mlexport_run");

$arASMRun = array(
    'AGENT'=>        Loc::getMessage("KREATTIKA_MLEXPORT_RUN_AGENT"),
    'CRON'=>         Loc::getMessage("KREATTIKA_MLEXPORT_RUN_CRON"),
);

$arASMPeriod = array(
    'HOUR'=>        Loc::getMessage("KREATTIKA_MLEXPORT_SET_PERIOD_HOUR"),
    'DAY'=>         Loc::getMessage("KREATTIKA_MLEXPORT_SET_PERIOD_DAY"),
    'WEEK'=>        Loc::getMessage("KREATTIKA_MLEXPORT_SET_PERIOD_WEEK"),
    'MONTH'=>       Loc::getMessage("KREATTIKA_MLEXPORT_SET_PERIOD_MONTH"),
);
/////////////////////////////////////////////////////////////////////////////////////////
//* Option field type: text, checkbox, selectbox, multiselectbox, textarea *//
/////////////////////////////////////////////////////////////////////////////////////////

$arAllOptions["main"][] = Array("mlexport_on", Loc::getMessage("KREATTIKA_MLEXPORT_SET_ON"), "Y", Array("checkbox"));
if($includeModule && $ModuleOn == 'Y'):
        $bProfileIsActive = CMLExport::CheckProfile();
        if(!$bProfileIsActive):
                $arAllOptions["main"][] = Array("note"=>Loc::getMessage("KREATTIKA_MLEXPORT_PROFILE_NOT_ACTIVATE_NOTE"));
        endif;
elseif($ModuleOn != 'Y'):
        $arAllOptions["main"][] = Array("note"=>Loc::getMessage("KREATTIKA_MLEXPORT_MODULE_DEACTIVATE_NOTE"));
endif;
$arAllOptions["main"][] = Array("mlexport_run", Loc::getMessage("KREATTIKA_MLEXPORT_RUN"), "", Array("selectbox", $arASMRun));
$arAllOptions["main"][] = Array("mlexport_period", Loc::getMessage("KREATTIKA_MLEXPORT_SET_PERIOD"), "", Array("selectbox", $arASMPeriod));

if($includeModule && $ModuleRun == "AGENT"):
        CMLExport::RestoreAgent();
        $arAgent = CMLExport::CheckAgent();
        if($ModuleOn == 'Y' && $arAgent['ACTIVE'] == 'Y' ):
                $arAllOptions["main"][] = Array("note"=>Loc::getMessage("KREATTIKA_MLEXPORT_CHECK_AGENT_NOTE", Array ("#AGENT_NEXT_EXEC#" => $arAgent['NEXT_EXEC'])));
        elseif($ModuleOn == 'Y' && $arAgent === false ):
                $arAllOptions["main"][] = Array("note"=>Loc::getMessage("KREATTIKA_MLEXPORT_CHECK_AGENT_NOT_EXIST_NOTE"));
        endif;
elseif($includeModule && $ModuleRun == "CRON"):
        if($ModuleOn == 'Y' ):
                $ModuleTime = COption::GetOptionString($MODULE_ID, "mlexport_time");
                $cronTime = ceil($ModuleTime/60);
                $cronPathName = CMLExport::GetCronPathName();
                $arAllOptions["main"][] = Array("note"=>Loc::getMessage("KREATTIKA_MLEXPORT_CRON_NOTE", Array ("#CRON_PATH_NAME#" => $cronPathName, "#CRON_TIME#" => $cronTime)));
        endif;
endif;

$arAllOptions["main"][] = Array("mlexport_include_external_classes", Loc::getMessage("KREATTIKA_MLEXPORT_SET_INCLUDE_EXTERNAL_CLASSES"), "", Array("checkbox"));

$arAllOptions["job_options"] = Array(
        Array("mlexport_del_old", Loc::getMessage("KREATTIKA_MLEXPORT_DEL_OLD"), "", Array("checkbox")),
        Array("mlexport_del_days", Loc::getMessage("KREATTIKA_MLEXPORT_DEL_DAYS"), "", Array("text")),
        Array("mlexport_reload_on_active_job", Loc::getMessage("KREATTIKA_MLEXPORT_RELOAD_ON_ACTIVE_JOB"), "", Array("checkbox")),
);

$arAllOptions["more_options"] = Array(
    Array("mlexport_time", Loc::getMessage("KREATTIKA_MLEXPORT_SET_TIME"), "", Array("text")),
    Array("mlexport_record_limit", Loc::getMessage("KREATTIKA_MLEXPORT_SET_RECORD_LIMIT"), "", Array("text")),
    Array("mlexport_folder_path", Loc::getMessage("KREATTIKA_MLEXPORT_SET_FOLDER_PATH"), COption::GetOptionString("main", "upload_dir", "upload")."/mlexport/", Array("text")),
);

$arAllOptions["log_options"] = Array(
    Array("mlexport_log_del_old", Loc::getMessage("KREATTIKA_MLEXPORT_LOG_DEL_OLD"), "", Array("checkbox")),
    Array("mlexport_log_del_days", Loc::getMessage("KREATTIKA_MLEXPORT_LOG_DEL_DAYS"), "", Array("text")),
);

$aTabs = array(
        array("DIV" => "edit1", "TAB" => Loc::getMessage("MAIN_TAB_SET"), "TITLE" => Loc::getMessage("MAIN_TAB_TITLE_SET")),
        array("DIV" => "edit2", "TAB" => Loc::getMessage("MAIN_TAB_RIGHTS"), "ICON" => "kreattika_comments_settings", "TITLE" => Loc::getMessage("MAIN_TAB_TITLE_RIGHTS")),
);

//Restore defaults
if ($USER->IsAdmin() && $_SERVER["REQUEST_METHOD"]=="GET" && strlen($RestoreDefaults)>0 && check_bitrix_sessid())
{
        COption::RemoveOption($module_id);
}
$tabControl = new CAdminTabControl("tabControl", $aTabs);

function ShowParamsHTMLByArray($module_id, $arParams)
{
        foreach($arParams as $Option)
        {
                 __AdmSettingsDrawRow($module_id, $Option);
        }
}

//Save options
if($REQUEST_METHOD=="POST" && strlen($Update.$Apply.$RestoreDefaults)>0 && check_bitrix_sessid())
{
        //restore Auto Sitemap agent
        if( $includeModule && $_POST["mlexport_on"] == 'Y' && $ModuleRun == "AGENT"):
                CMLExport::RestoreAgent();
        endif;

        if(strlen($RestoreDefaults)>0)
        {
                COption::RemoveOption($module_id);
        }
        else
        {
                foreach($arAllOptions as $aOptGroup)
                {
                        foreach($aOptGroup as $option)
                        {
                                __AdmSettingsSaveOption($module_id, $option);
                        }
                }
        }
        if(strlen($Update)>0 && strlen($_REQUEST["back_url_settings"])>0)
                LocalRedirect($_REQUEST["back_url_settings"]);
        else
                LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($mid)."&lang=".urlencode(LANGUAGE_ID)."&back_url_settings=".urlencode($_REQUEST["back_url_settings"])."&".$tabControl->ActiveTabParam());
}
?>

<form method="post" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=htmlspecialchars($mid)?>&amp;lang=<?echo LANG?>">
<?
$tabControl->Begin();
$tabControl->BeginNextTab();
?><?$ModuleInstallStatus = CModule::IncludeModuleEx($MODULE_ID);
        if($ModuleInstallStatus == 2 || $ModuleInstallStatus == 3):?>
        <tr>
                <td colspan="2">
                        <div style="background-color: #fff; padding: 10px; margin-bottom: 15px;">
                                <div style="padding: 7px; text-align: center;">
                                        <?if($ModuleInstallStatus == 2):?>
                                                <?=Loc::getMessage("KREATTIKA_IS_DEMO")?>
                                        <?elseif($ModuleInstallStatus == 3):?>
                                                <span style="color: #cc0000"><?=Loc::getMessage("KREATTIKA_IS_DEMO_EXPIRED")?></span>
                                        <?endif;?>
                                        <a href="http://marketplace.1c-bitrix.ru/solutions/<?=$MODULE_ID?>/" target="_blank" ><?=Loc::getMessage("KREATTIKA_FOOL_VERSION_BUY")?></a>
                                </div>
                        </div>
                </td>
        </tr>
        <?endif;?>
        <tr>
                <td colspan="2">
                        <div style="padding: 0; border-top: 1px solid #C4CED2; border-bottom: 1px solid #C4CED2; border-left: 1px solid #DCE7ED; border-right: 1px solid #DCE7ED;  margin-bottom: 15px;">
                                <div style="background-color: #fff; opacity: 0.9; height: 30px; padding: 7px; border: 1px solid #fff">
                                        <!--<a href="http://kreattika.ru/sale/?solution=<?=$SOLUTION_ID?>" target="_blank"><img src="/bitrix/modules/<?=$MODULE_ID?>/images/kreattika-logo.png" style="float: left; margin-right: 15px;" border="0" /></a>//-->
                                        <div style="margin: 5px 0px 0px 0px">
                                                <a href="http://kreattika.ru/sale/?solution=<?=$SOLUTION_ID?>" target="_blank" style="color: #ff6600; font-size: 18px; text-decoration: none"><?=Loc::getMessage("KREATTIKA_AGENSY")?></a>
                                        </div>
                                </div>
                        </div>
                </td>
        </tr>
	<?ShowParamsHTMLByArray($module_id, $arAllOptions["main"]);?>
	<tr class="heading">
		<td colspan="2"><?=Loc::getMessage("KREATTIKA_MLEXPORT_SET_OPT_JOB_TITLE")?></td>
	</tr>
	<?ShowParamsHTMLByArray($module_id, $arAllOptions["job_options"]);?>
    <tr class="heading">
        <td colspan="2"><?=Loc::getMessage("KREATTIKA_MLEXPORT_SET_OPT_TITLE")?></td>
    </tr>
    <?ShowParamsHTMLByArray($module_id, $arAllOptions["more_options"]);?>
    <tr class="heading">
        <td colspan="2"><?=Loc::getMessage("KREATTIKA_MLEXPORT_SET_OPT_LOG_TITLE")?></td>
    </tr>
    <?ShowParamsHTMLByArray($module_id, $arAllOptions["log_options"]);?>
<?$tabControl->BeginNextTab();?>
<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");?>
<?$tabControl->Buttons();?>
<script language="JavaScript">
function RestoreDefaults()
{
        if(confirm('<?echo AddSlashes(Loc::getMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>'))
                window.location = "<?echo $APPLICATION->GetCurPage()?>?RestoreDefaults=Y&lang=<?echo LANG?>&mid=<?echo urlencode($mid)?>&<?=bitrix_sessid_get()?>";
}
</script>
<div align="left">
        <input type="hidden" name="Update" value="Y">
        <input type="submit" <?if(!$USER->IsAdmin())echo " disabled ";?> name="Update" value="<?echo Loc::getMessage("MAIN_SAVE")?>">
        <input type="reset" <?if(!$USER->IsAdmin())echo " disabled ";?> name="reset" value="<?echo Loc::getMessage("MAIN_RESET")?>">
        <input type="button" <?if(!$USER->IsAdmin())echo " disabled ";?> title="<?echo Loc::getMessage("MAIN_HINT_RESTORE_DEFAULTS")?>" OnClick="RestoreDefaults();" value="<?echo Loc::getMessage("MAIN_RESTORE_DEFAULTS")?>">
</div>
<?$tabControl->End();?>
<?=bitrix_sessid_post();?>
</form>
