<?
/**
 * Company developer: Intellectrix            
 * Developer: AXEL (DMITRIEV EVGENIY)                              
 * Site: http://intellectrix.ru/
 * Copyright (c) 2016 Intellectrix
 */
 
if(!check_bitrix_sessid()) return;
echo CAdminMessage::ShowNote(GetMessage("MOD_INST_OK"));?>
<p><a href="/bitrix/admin/settings.php?lang=ru&mid=intellectrix.facebook"><?=GetMessage("INTELLECTRIX_FACEBOOK_INSTALL_OK")?></a></p>
<form action="<?echo $APPLICATION->GetCurPage()?>">
    <input type="hidden" name="lang" value="<?echo LANG?>">
    <input type="submit" name="" value="<?echo GetMessage("MOD_BACK")?>">
<form>