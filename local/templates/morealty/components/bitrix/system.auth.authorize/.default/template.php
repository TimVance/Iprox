<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$bAuthorized = $USER->IsAuthorized();
if (!$bAuthorized)
{
	$APPLICATION->RestartBuffer();
	?><!DOCTYPE html>
<html> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
	<title>Вход</title> 
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" href="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/template_styles.css', true)?>" type="text/css" media="screen, projection" />
	<link rel="stylesheet" href="<?=CUtil::GetAdditionalFileURL($templateFolder.'/auth_style.css', true)?>" type="text/css" media="screen, projection" />
	<link rel="stylesheet" href="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/css/colorbox.css', true)?>" type="text/css" media="screen, projection" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<script src="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/js/jquery.inputmask.js', true)?>"></script>
	<script src="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/js/jquery.colorbox-min.js', true)?>"></script>
	<script src="<?=CUtil::GetAdditionalFileURL($templateFolder.'/auth_script.js', true)?>"></script>
</head>
<body>
<div class="back-z"></div>

<div class="top-descr-z">
	<div class="logo"><img src="<?=$templateFolder?>/images/logo_iprox.png" /></div>
	<h1>Впервые В Сочи!</h1>
	<h3>Уникальная база недвижимости<br> от cобственников</h3>
</div>

<div class="but-input-z new_start_login"><a href="#wr-pop-inp2" id="start_login" class="inline">Вход и регистрация</a></div>
<?
//$template = $_SERVER["REMOTE_ADDR"] == "31.31.24.204" ? "header_new_new" : "header_new";
$template = "header_new";
?>
<div class="wr-pop-inp">
	<?$APPLICATION->IncludeComponent("bitrix:system.auth.form",$template,Array(
			"REGISTER_URL"			=> "/register/",
			"FORGOT_PASSWORD_URL"	=> "/auth/",
			"PROFILE_URL" => "/personal/",
			"AJAX_MODE" => "Y",
			"SHOW_ERRORS" => "Y"
		),
		$component
	);?>
</div>
</body>
</html>
<?
}
else
{
	ShowError("Доступ в данный раздел запрещен");
}




