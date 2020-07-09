<?
/**
 * Company developer: Intellectrix            
 * Developer: AXEL (DMITRIEV EVGENIY)                              
 * Site: http://intellectrix.ru/
 * Copyright (c) 2016 Intellectrix
 */

$MODULE_ID = "intellectrix_facebook";

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/options.php");
IncludeModuleLangFile(__FILE__);

if(!$USER->IsAdmin()) return;
CModule::IncludeModule("intellectrix.facebook");
CModule::IncludeModule("iblock");

$arIBlockType = array();
$rsIBlockType = CIBlockType::GetList();
while($arr = $rsIBlockType->Fetch()) {
   if($arIBType = CIBlockType::GetByIDLang($arr["ID"], LANG)) {
		$arIBlockType[$arr["ID"]] = htmlspecialcharsex($arIBType["NAME"]);
   }
}

$arrIB = $arrIbIb = $arrIbName = $arrIbNameN = $arrIbType = array();
$rsIB = CIBlock::GetList(array(),array("ACTIVE"=>"Y"));
while($arr = $rsIB->Fetch()) {
	if(!isset($arrIB[$arr["IBLOCK_TYPE_ID"]])) $arrIB[$arr["IBLOCK_TYPE_ID"]] = array();
	$arrIB[$arr["IBLOCK_TYPE_ID"]][$arr["ID"]] = $arr["NAME"];
	$arrIbIb[$arr["IBLOCK_TYPE_ID"]][$arr["ID"]] = $arr["ID"];
	$arrIbName[$arr["ID"]] = "'".$arr["ID"]."':'".addslashes($arr["NAME"])."'";
	$arrIbNameN[$arr["ID"]] = addslashes($arr["NAME"]);
	$arrIbType[$arr["ID"]] = $arr["IBLOCK_TYPE_ID"];
}
$jsonIbIb = json_encode($arrIbIb);


$arAllOptions = array(
	"app_id" 			=> array("app_id", 	GetMessage('INTELLECTRIX_FACEBOOK_APP_ID'), 	"", array("text", 50)),
	"app_secret" 		=> array("app_secret", GetMessage('INTELLECTRIX_FACEBOOK_APP_SECRET'), "", array("text", 50)),
	"show_notice"		=> array("show_notice", GetMessage('INTELLECTRIX_FACEBOOK_SHOW_NOTICE'), "Y", array("checkbox")),
	"notice_end_token"	=> array("notice_end_token", GetMessage('INTELLECTRIX_FACEBOOK_NOTICE_END_TOKEN'), "5", array("text", 3)),
);

$arrOptionTabs = array(
	"tab1" => array($arAllOptions["app_id"],$arAllOptions["app_secret"]),
	"tab3" => array($arAllOptions["show_notice"],$arAllOptions["notice_end_token"]),
);

$aTabs = array(
	array("DIV" => "edit1", "TAB" => GetMessage('INTELLECTRIX_FACEBOOK_TAB_FACEBOOK'), 	"TITLE" => GetMessage('INTELLECTRIX_FACEBOOK_TAB_FACEBOOK_TITLE')),
	array("DIV" => "edit2", "TAB" => GetMessage("INTELLECTRIX_FACEBOOK_TAB_IBLOCK"), 	"TITLE" => GetMessage("INTELLECTRIX_FACEBOOK_TAB_IBLOCK_TITLE")),
	array("DIV" => "edit3", "TAB" => GetMessage("INTELLECTRIX_FACEBOOK_TAB_SETTINGS"), 	"TITLE" => GetMessage("INTELLECTRIX_FACEBOOK_TAB_SETTINGS_TITLE")),
	array("DIV" => "edit4", "TAB" => GetMessage("MAIN_TAB_RIGHTS"), 					"TITLE" => GetMessage("MAIN_TAB_TITLE_RIGHTS")),
);

$tabControl = new CAdminTabControl("tabControl", $aTabs);



function ShowParamsHTMLByArray($arParams) {
	foreach($arParams as $Option) {
        __AdmSettingsDrawRow("intellectrix_facebook", $Option);
	}
}


if($REQUEST_METHOD=="POST" && strlen($Update.$Apply.$RestoreDefaults)>0 && check_bitrix_sessid()) {
	if(isset($_REQUEST["IB_ID"]) && isset($_REQUEST["ID_GROUP_FB"])) {
		$arr_group = array();
		foreach($_REQUEST["IB_ID"] as $vi) {
			if(isset($arrIbName[$vi]) && isset($_REQUEST["ID_GROUP_FB"][$vi]) && !empty($_REQUEST["ID_GROUP_FB"][$vi])) {
				$arr_group[$vi] = $vi."|".$_REQUEST["ID_GROUP_FB"][$vi];
			}
		}
		$ib_and_group_fb = implode(";",$arr_group);
		COption::SetOptionString($MODULE_ID,"ib_and_group_fb",$ib_and_group_fb);
	} else {
		COption::SetOptionString($MODULE_ID,"ib_and_group_fb","");
	}
	if(strlen($RestoreDefaults)>0) {
		COption::RemoveOption("intellectrix_facebook");
    } else {
        foreach($arAllOptions as $option)__AdmSettingsSaveOption($MODULE_ID, $option);
    }
    if(strlen($Update)>0 && strlen($_REQUEST["back_url_settings"])>0) {
		LocalRedirect($_REQUEST["back_url_settings"]);
    } else {
        LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($mid)."&lang=".urlencode(LANGUAGE_ID)."&back_url_settings=".urlencode($_REQUEST["back_url_settings"])."&".$tabControl->ActiveTabParam());
	}
}

$php_version = phpversion();
$is_right_php =  explode(".",$php_version);
$tabControl->Begin();?>
<form method="POST" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=htmlspecialcharsbx($mid)?>&amp;lang=<?echo LANG?>" name="seo_settings">
<?=bitrix_sessid_post();?>
<?$tabControl->BeginNextTab();?>
<?if($is_right_php[0] >= 5 && $is_right_php[1] >= 4) {?>
<?ShowParamsHTMLByArray($arrOptionTabs["tab1"]);?>
<?
$app_id 	= COption::GetOptionString($MODULE_ID, "app_id");
$app_secret = COption::GetOptionString($MODULE_ID, "app_secret");
if($app_id && !empty($app_id) && $app_secret && !empty($app_secret)){?>
<tr>
	<td valign="top" width="50%">
	<?=GetMessage('INTELLECTRIX_FACEBOOK_TOKEN')?>
	</td>
	<td valign="top" width="50%"><div style="max-width:400px;word-wrap: break-word;">
	<?
	$token = CIntellectrixFacebook::GetToken();
	echo ($token)?$token:GetMessage('INTELLECTRIX_FACEBOOK_TOKEN_NONE')?></div>
	</td>
<tr>
<tr>
	<td valign="top" width="50%">
	<?=GetMessage('INTELLECTRIX_FACEBOOK_TOKEN_TIME_END')?>
	</td>
	<td valign="top" width="50%">
	<?
	$token = CIntellectrixFacebook::GetTokenTime();
	echo ($token)?$token:GetMessage('INTELLECTRIX_FACEBOOK_TOKEN_NONE')?>
	</td>
<tr>
<tr>
	<td valign="top" width="50%">
	</td>
	<td valign="top" width="50%">
	<input type="button" onclick="return hand_get_token();" value="<?=GetMessage('INTELLECTRIX_FACEBOOK_TOKEN_UPDATE')?>" />
	</td>
<tr>
<?}
$tabControl->BeginNextTab();

$ib_and_group_fb = CIntellectrixFacebook::GetIbGroupFB();
if(empty($ib_and_group_fb)) {
	$ib_and_group_fb = array("" => "");
}
?>
<tr class="heading">
	<td colspan="2" valign="top" align="center"><?=GetMessage("INTELLECTRIX_FACEBOOK_IBLOCK_REMARK");?></td>
</tr>
<tr>
	<td valign="top" width="50%">
	<?=GetMessage('INTELLECTRIX_FACEBOOK_IBLOCK_ADD')?>
	</td>
	<td valign="top" width="50%">
		<div id="<?=$MODULE_ID?>_block_iblock">
			<?$n=0;
			$is_code = $is_ib = false;
			foreach($ib_and_group_fb as $ib => $group){$n++;?>
			<select class="<?=$MODULE_ID?>_select_ibtype" id="<?=$MODULE_ID?>_num_ib_<?=$n?>">
				<option value=""><?=GetMessage('IBLOCK_CHOOSE_IBLOCK_TYPE')?></option>
				<?foreach($arIBlockType as $code => $name) {?>
				<option value="<?=$code?>"<?if(isset($arrIbType[$ib]) && $arrIbType[$ib] == $code){$is_code = $code;?> selected<?}?>><?=$name?></option>
				<?}?>
			</select><select class="<?=$MODULE_ID?>_select_iblock" name="IB_ID[]" id="<?=$MODULE_ID?>_select_iblock_<?=$n?>">
				<option value=""><?=GetMessage('IBLOCK_CHOOSE_IBLOCK')?></option>
				<?if($is_code && $arrIbNameN[$ib]){
					foreach($arrIB[$is_code] as $id_ib => $name_ib) {?>
					<option value="<?=$id_ib?>"<?if($id_ib == $ib){$is_ib = $ib;?> selected<?}?>><?=$name_ib?></option>
					<?}
				}?>
			</select><br />
			<input type="text" id="<?=$MODULE_ID?>_id_group_fb_<?=$n?>" name="<?if($is_ib){?>ID_GROUP_FB[<?=$is_ib?>]<?}?>" value="<?=$group?>" size="40" placeholder="<?=GetMessage('INTELLECTRIX_FACEBOOK_ID_GROUP_PLACEHOLDER')?>"/>
			<div>&nbsp;</div>
			<?}?>
		</div>
	</td>
<tr>
<tr>
	<td valign="top" width="50%">
		
	</td>
	<td valign="top" width="50%">
		<input type="button" onclick="return add_new_ib();" value="<?=GetMessage('INTELLECTRIX_FACEBOOK_IBLOCK_ADD_BTN')?>" />
	</td>
<tr>
<?$tabControl->BeginNextTab();?>
<?ShowParamsHTMLByArray($arrOptionTabs["tab3"]);?>
<?$tabControl->BeginNextTab();?>
<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");?>

<?} else {
	echo str_replace('#CURRENT_PHP_VERSION#', $php_version, GetMessage("INTELLECTRIX_FACEBOOK_OLD_PHP"));
}?>


<?$tabControl->Buttons();?>
<script language="JavaScript">
var arrIbName = {<?=implode(",",$arrIbName)?>};
var jsonIbIb = <?=$jsonIbIb?>;
var num_ib = 1;

BX.ready(function(){
	BX.bindDelegate(
		document.body, 'change', {className: '<?=$MODULE_ID?>_select_ibtype'},
		function(e){
			var val = e.target.value;
			var num = parseInt(e.target.id.replace('<?=$MODULE_ID?>_num_ib_',''),10);
			var sel_ib = BX("<?=$MODULE_ID?>_select_iblock_"+num);
			if(jsonIbIb[val] != undefined) {
				var obj = jsonIbIb[val];
				var html = '<option value=""><?=GetMessage('IBLOCK_CHOOSE_IBLOCK')?></option>';
				for(k in obj) {
					html += '<option value="'+k+'">'+arrIbName[k]+'</option>';
				}
				BX.adjust(
					sel_ib, 
					{html:html}
				);
			} else {
				BX.adjust(
					sel_ib, 
					{html:'<option value=""><?=GetMessage('IBLOCK_CHOOSE_IBLOCK')?></option>'}
				);
			}
			return BX.PreventDefault(e);
		}
	);
	BX.bindDelegate(
		document.body, 'change', {className: '<?=$MODULE_ID?>_select_iblock'},
		function(e){
			var val = e.target.value;
			var num = parseInt(e.target.id.replace('<?=$MODULE_ID?>_select_iblock_',''),10);
			var input = BX("<?=$MODULE_ID?>_id_group_fb_"+num);
			if(arrIbName[val] != undefined) {
				input.name = 'ID_GROUP_FB['+val+']';
			} else {
				input.value = "";
				input.name = "";
			}
			return BX.PreventDefault(e);
		}
	);
});


function add_new_ib() {
	num_ib++;
	document.getElementById('<?=$MODULE_ID?>_block_iblock').
	appendChild(BX.create('select', {
		props: {className: '<?=$MODULE_ID?>_select_ibtype', id: '<?=$MODULE_ID?>_num_ib_'+num_ib},
		html:'<option value=""><?=GetMessage('IBLOCK_CHOOSE_IBLOCK_TYPE')?></option>'+
		'<?foreach($arIBlockType as $code => $name) {?><option value="<?=$code?>"><?=$name?></option><?}?>'
	}));
	document.getElementById('<?=$MODULE_ID?>_block_iblock').
	appendChild(BX.create('select', {
		props: {className: '<?=$MODULE_ID?>_select_iblock', id: '<?=$MODULE_ID?>_select_iblock_'+num_ib, name: 'IB_ID[]'},
		html:'<option value=""><?=GetMessage('IBLOCK_CHOOSE_IBLOCK')?></option>'
	}));
	document.getElementById('<?=$MODULE_ID?>_block_iblock').
	appendChild(BX.create('br'));
	document.getElementById('<?=$MODULE_ID?>_block_iblock').
	appendChild(BX.create('input', {
		props: {id: '<?=$MODULE_ID?>_id_group_fb_'+num_ib, value: '', placeholder: '<?=GetMessage('INTELLECTRIX_FACEBOOK_ID_GROUP_PLACEHOLDER')?>', type: 'text', size: '40'}
	}));
	document.getElementById('<?=$MODULE_ID?>_block_iblock').
	appendChild(BX.create('div', {
		html:'&nbsp;'
	}));
}

function hand_get_token() {
	BX.ajax({
		url: '/bitrix/tools/intellectrix.facebook/intellectrix_facebook_action.php',
		method: 'POST',
		dataType: 'json',
		data: {'action':'hand_get_token'},
		onsuccess: function (json) {
			console.log(json);
			if(json.error != undefined) {
			
			} else if (json.url != undefined) {
				location.href = json.url;
			}
		}
	});
	return false;
}
function confirmRestoreDefaults()
{
	return confirm('<?echo AddSlashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>');
}
</script>
<input type="submit" name="Update" value="<?echo GetMessage("MAIN_SAVE")?>">
<input type="hidden" name="Update" value="Y">
<input type="reset" name="reset" value="<?echo GetMessage("MAIN_RESET")?>">
<input type="submit" name="RestoreDefaults" title="<?echo GetMessage("MAIN_HINT_RESTORE_DEFAULTS")?>" OnClick="return confirmRestoreDefaults();" value="<?echo GetMessage("MAIN_RESTORE_DEFAULTS")?>">
</form>
<?$tabControl->End();?>
