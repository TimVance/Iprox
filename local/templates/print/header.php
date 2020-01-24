<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if($APPLICATION->GetCurPage() == '/personal/') {
	header('Location: '. 'http://morealty.dev.mnwb.com/personal/myobjects/');
} else if (!$USER->IsAuthorized() &&  CSite::InDir('/personal/')) {
	header('Location: '. 'http://morealty.dev.mnwb.com/auth/');
} else if (!$USER->IsAuthorized() &&  CSite::InDir('/auth/') && $_REQUEST['register'] == 'yes') {
	header('Location: '. 'http://morealty.dev.mnwb.com/register/');
}


$CD = $APPLICATION->GetCurDir();
$isPage['MAIN'] = ($APPLICATION->GetCurPage(true) == '/index.php' || $APPLICATION->GetCurPage(true) == '/en/index.php');


IncludeTemplateLangFile(__FILE__);
?>
<!DOCTYPE html>
<html>
<head>
	<title><?$APPLICATION->ShowTitle()?></title>
	<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" href="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/css/colorbox.css', true)?>" type="text/css" media="screen, projection" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>

	<?$APPLICATION->ShowHead();?>
</head>

<body>
<?$APPLICATION->ShowPanel()?>

	<div class="main">



		<?if (! $isPage['MAIN']) {?>
		<div class="content">
			<div class="breadcrumbs">
				<?$APPLICATION->IncludeComponent(
				"bitrix:breadcrumb",
				".default",
				array(
					"START_FROM" => "/",
					"PATH" => "",
					"SITE_ID" => "s3",
					"COMPONENT_TEMPLATE" => ".default"
				),
				false
			); ?>
			</div><!--breadcrumbs-->

			<h1><?=$APPLICATION->ShowTitle(false);?></h1>
		<?}?>

		<?if (CSite::InDir('/personal/') && strpos($APPLICATION->GetCurPage(), 'favorites') === false) {?>
			<?$APPLICATION->IncludeComponent('wexpert:includer', 'lk_informer')?>
			<?$APPLICATION->IncludeComponent(
				"bitrix:menu",
				"cab_menu",
				array(
					"ALLOW_MULTI_SELECT" => "N",
					"CHILD_MENU_TYPE" => "top",
					"COMPONENT_TEMPLATE" => "cab_menu",
					"DELAY" => "N",
					"MAX_LEVEL" => "3",
					"MENU_CACHE_GET_VARS" => array(
					),
					"MENU_CACHE_TIME" => "3600",
					"MENU_CACHE_TYPE" => "A",
					"MENU_CACHE_USE_GROUPS" => "N",
					"ROOT_MENU_TYPE" => "cab",
					"USE_EXT" => "N"
				),
				false
			);?>
		<?}?>

