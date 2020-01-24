<?
require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/iblock/prolog.php");
IncludeModuleLangFile(__FILE__);
$APPLICATION->SetTitle("Добавление пакетов пользователям");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
CJSCore::Init(array('fx', 'ajax', 'dd'));
CModule::IncludeModule("iblock");
$APPLICATION->AddHeadScript('/bitrix/js/main/file_upload_agent.js');

if ($USER->IsAdmin())
{
	
	
	$Fields = array(
			"FORM_NAME"	=> "user_packets_add",
			"USERS"		=> "users_to_action",
			"PACKETS"	=> "packets_to_action",
			
	);
	$isAdded = false;
	if (isset($_POST["setSettings"]) && $_POST["setSettings"])
	{
		$inputedFields = array_intersect_key($_POST, array_flip($Fields));
		
		if (
				($inputedFields[$Fields["USERS"]] && $inputedFields[$Fields["PACKETS"]])
				&&
				(count($inputedFields[$Fields["USERS"]]) > 0 && count($inputedFields[$Fields["PACKETS"]]) > 0)
				
			)
		{
			foreach ($inputedFields[$Fields["USERS"]] as $UserID)
			{
				if ($UserID)
				{
					foreach ($inputedFields[$Fields["PACKETS"]] as $PacketID)
					{
						if ($PacketID)
						{
							\MorealtySale\Packets::givePacketToUser($PacketID, 1, $UserID);
							$isAdded = true;
						}
					}
				}
			}
		}
	}
	$Result = array();
	if ($inputedFields && count($inputedFields) > 0)
	{
		$Result = array_merge($Result,$inputedFields);
	}
		
	
	
	
	$context = new CAdminContextMenu($aMenu);
	
	// вывод административного меню
	$context->Show();
	
	$aTabs = array(
			array("DIV" => "settings", "TAB" => "Добавление пакетов пользователям", "TITLE"=>"Добавление пакетов пользователям"),
	);
	$tabControl = new CAdminTabControl("tabControl", $aTabs);
	?>
<form method="POST" Action="<?echo $APPLICATION->GetCurPage()?>" ENCTYPE="multipart/form-data" id="<?=$Fields["FORM_NAME"]?>" name="<?=$Fields["FORM_NAME"]?>">
<?// проверка идентификатора сессии ?>
<?echo bitrix_sessid_post();?>
<input type="hidden" name="lang" value="<?=LANG?>">
<?
// отобразим заголовки закладок
$tabControl->Begin();
?>
<?
//********************
// первая закладка - форма редактирования параметров рассылки
//********************
$tabControl->BeginNextTab();
?>
  <tr>
    <td width="40%" style="vertical-align: top;">
        <span id="hint_userpackets"></span> Пользователи
			<script type="text/javascript">
			BX.hint_replace(BX('hint_userpackets'), '<?=CUtil::JSEscape("Пользователям будет добавлены пакеты")?>');
			</script>
    </td>
    <td class="superprice_dayofWeek">
    	<?
    	$arProp = array();
    	\AdminLib\User::UserSelectorMultiple($Fields["USERS"], $Fields["FORM_NAME"],array(),array("DEFAULT_STATE" => "SU","INPUT_NUMS" => 5));
    	?>
    </td>
  </tr>
  <tr>
  	<td width="40%" style="vertical-align: top;">Пакеты</td>
  	<td><?\AdminLib\Element::ElementsSelector($Fields["PACKETS"],array(),array("IBLOCK_ID"=>"6"))?></td>
  </tr>
<?

// завершение формы - вывод кнопок сохранения изменений
$tabControl->Buttons();
?>
<input class="setSettings" 
       type="submit" name="setSettings" 
       value="Применить настройки" 
       title="Применить настройки" />&nbsp;
<?
// завершаем интерфейс закладки
$tabControl->End();
}
else
{
	ShowError("Доступ запрещен");
}
if ($isAdded)
	CAdminMessage::ShowNote("Пакеты добавленны");


require_once ($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>