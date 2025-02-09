<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/iblock/prolog.php");
$moduleId = 'kda.importexcel';
CModule::IncludeModule('iblock');
CModule::IncludeModule($moduleId);
IncludeModuleLangFile(__FILE__);

$MODULE_RIGHT = $APPLICATION->GetGroupRight($moduleId);
if($MODULE_RIGHT < "W") $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

if(!is_array($_POST['EMAIL_SETTINGS']))
{
	if(strlen($_POST['EMAIL_SETTINGS']) > 0 && strpos($_POST['EMAIL_SETTINGS'], '{')===false) $_POST['EMAIL_SETTINGS'] = base64_decode($_POST['EMAIL_SETTINGS']);
	$_POST['EMAIL_SETTINGS'] = CUtil::JsObjectToPhp($_POST['EMAIL_SETTINGS']);
	if(!is_array($_POST['EMAIL_SETTINGS'])) $_POST['EMAIL_SETTINGS'] = array();
	$_POST['EMAIL_SETTINGS'] = array_map('stripcslashes', $_POST['EMAIL_SETTINGS']); //replace \'
	$EMAIL_SETTINGS = $_POST['EMAIL_SETTINGS'];
}

if(is_array($_POST['EMAIL_SETTINGS']) && (!defined('BX_UTF') || !BX_UTF)) 
{
	$_POST['EMAIL_SETTINGS'] = $EMAIL_SETTINGS = $APPLICATION->ConvertCharsetArray($_POST['EMAIL_SETTINGS'], 'UTF-8', 'CP1251');
}

if($_POST['action']=='checkconnect')
{
	$sess = $_SESSION;
	session_write_close();
	$_SESSION = $sess;
	$APPLICATION->RestartBuffer();
	ob_end_clean();
		
	$arParams = $_POST['EMAIL_SETTINGS'];
	$mail = new \Bitrix\KdaImportexcel\SMail($arParams);
	$res = $mail->CheckParams();
	$arResult = array('result'=>($res ? 'success' : 'fail'));
	if($res)
	{
		$arFolders = $mail->GetListingFolders();
		$arResult['folders'] = $arFolders;
	}
	echo CUtil::PhpToJSObject($arResult);
	die();
}

if($_POST['action']=='save' && $_POST['EMAIL_SETTINGS'])
{
	$APPLICATION->RestartBuffer();
	ob_end_clean();
	
	echo '<script>';
	echo 'if($(".kda-ie-file-choose input[name=\"SETTINGS_DEFAULT[EMAIL_DATA_FILE]\"]").length == 0){$(".kda-ie-file-choose").prepend(\'<input type="hidden" name="SETTINGS_DEFAULT[EMAIL_DATA_FILE]" value="">\');}';
	echo '$(".kda-ie-file-choose input[name=\"SETTINGS_DEFAULT[EMAIL_DATA_FILE]\"]").val("'.htmlspecialcharsex(base64_encode(CUtil::PhpToJSObject($_POST['EMAIL_SETTINGS']))).'");';
	echo '$(".kda-ie-file-choose input[name=\"EXT_DATA_FILE\"]").val("");';
	echo 'BX.WindowManager.Get().Close();';
	echo '</script>';
	die();
}

$mail = new \Bitrix\KdaImportexcel\SMail($EMAIL_SETTINGS);
$arFolders = $mail->GetListingFolders();
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_popup_admin.php");
?>
<form action="<?echo $APPLICATION->GetCurUri();?>" method="post" enctype="multipart/form-data" name="field_settings">
	<input type="hidden" name="action" value="save">
	<?//ShowPostData($_POST);?>
	<table width="100%" class="kda-ie-list-settings">
		<col width="50%">
		<col width="50%">
		<tr class="heading">
			<td colspan="2">
				<?echo GetMessage("KDA_IE_ECON_SETTINGS"); ?>
			</td>
		</tr>
		<tr>
			<td class="adm-detail-content-cell-l"><?echo GetMessage("KDA_IE_ECON_INPUT_SERVER");?>:</td>
			<td class="adm-detail-content-cell-r">
				<input type="text" name="EMAIL_SETTINGS[SERVER]" value="<?echo htmlspecialcharsex($EMAIL_SETTINGS['SERVER'])?>">
			</td>
		</tr>
		<tr>
			<td class="adm-detail-content-cell-l"><?echo GetMessage("KDA_IE_ECON_EMAIL");?>:</td>
			<td class="adm-detail-content-cell-r">
				<input type="text" name="EMAIL_SETTINGS[EMAIL]" value="<?echo htmlspecialcharsex($EMAIL_SETTINGS['EMAIL'])?>">
			</td>
		</tr>
		<tr>
			<td class="adm-detail-content-cell-l"><?echo GetMessage("KDA_IE_ECON_PASSWORD");?>:</td>
			<td class="adm-detail-content-cell-r">
				<input type="text" name="EMAIL_SETTINGS[PASSWORD]" value="<?echo htmlspecialcharsex($EMAIL_SETTINGS['PASSWORD'])?>">
			</td>
		</tr>
		<tr>
			<td class="adm-detail-content-cell-l"><?echo GetMessage("KDA_IE_ECON_SECURITY");?>:</td>
			<td class="adm-detail-content-cell-r">
				<select name="EMAIL_SETTINGS[SECURITY]">
					<option name="ssl" <?if($EMAIL_SETTINGS['SECURITY']=='ssl'){echo 'selected';}?>><?echo GetMessage("KDA_IE_ECON_SECURITY_SSL");?></option>
					<option name="tls" <?if($EMAIL_SETTINGS['SECURITY']=='tls'){echo 'selected';}?>><?echo GetMessage("KDA_IE_ECON_SECURITY_TLS");?></option>
					<option name=""><?echo GetMessage("KDA_IE_ECON_SECURITY_NO");?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="kda-ie-email-checkparams">
				<a href="javascript:void(0)" onclick="EProfile.CheckEmailConnectData(this)"><?echo GetMessage("KDA_IE_ECON_CHECK_SETTINGS");?></a> <div id="connect_result"></div>
			</td>
		</tr>
		
		<tr class="heading">
			<td colspan="2">
				<?echo GetMessage("KDA_IE_ECON_FILE_PARAMS"); ?>
			</td>
		</tr>
		<tr>
			<td class="adm-detail-content-cell-l"><?echo GetMessage("KDA_IE_ECON_FOLDER");?>:</td>
			<td class="adm-detail-content-cell-r">
				<select name="EMAIL_SETTINGS[FOLDER]">
					<?
					foreach($arFolders as $k=>$v)
					{
						echo '<option value="'.htmlspecialcharsex($k).'"'.($EMAIL_SETTINGS['FOLDER']==$k ? ' selected' : '').'>'.$v.'</option>';
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td class="adm-detail-content-cell-l"><?echo GetMessage("KDA_IE_ECON_FROM");?>:</td>
			<td class="adm-detail-content-cell-r">
				<input type="text" name="EMAIL_SETTINGS[FROM]" value="<?echo htmlspecialcharsex($EMAIL_SETTINGS['FROM'])?>">
			</td>
		</tr>
		<tr>
			<td class="adm-detail-content-cell-l"><?echo GetMessage("KDA_IE_ECON_SUBJECT");?>:</td>
			<td class="adm-detail-content-cell-r">
				<input type="text" name="EMAIL_SETTINGS[SUBJECT]" value="<?echo htmlspecialcharsex($EMAIL_SETTINGS['SUBJECT'])?>">
			</td>
		</tr>
		<tr>
			<td class="adm-detail-content-cell-l"><?echo GetMessage("KDA_IE_ECON_FILENAME");?>:</td>
			<td class="adm-detail-content-cell-r">
				<input type="text" name="EMAIL_SETTINGS[FILENAME]" value="<?echo htmlspecialcharsex($EMAIL_SETTINGS['FILENAME'])?>">
				&nbsp;
				<input type="hidden" name="EMAIL_SETTINGS[FILENAME_REGEXP]" value="N">
				<input type="checkbox" name="EMAIL_SETTINGS[FILENAME_REGEXP]" value="Y" <?if($EMAIL_SETTINGS['FILENAME_REGEXP']=='Y'){echo 'checked';}?> id="email_filename_regexp"> <label for="email_filename_regexp"><?echo GetMessage("KDA_IE_ECON_FILENAME_REGEXP");?></label>
			</td>
		</tr>
		<tr>
			<td class="adm-detail-content-cell-l"><?echo GetMessage("KDA_IE_ECON_UNSEEN_ONLY");?>:</td>
			<td class="adm-detail-content-cell-r">
				<input type="hidden" name="EMAIL_SETTINGS[UNSEEN_ONLY]" value="N">
				<input type="checkbox" name="EMAIL_SETTINGS[UNSEEN_ONLY]" value="Y" <?if($EMAIL_SETTINGS['UNSEEN_ONLY']!='N'){echo 'checked';}?>>
			</td>
		</tr>
	</table>
</form>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_popup_admin.php");?>