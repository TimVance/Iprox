<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) { die(); } ?>
<? 
global $APPLICATION;
$APPLICATION->AddChainItem("Авторизация","/auth/");
$APPLICATION->AddChainItem("Подтверждение регистрации");
?>