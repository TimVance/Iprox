<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule('iblock');
if (
    preg_match('~^(.*[^/])?(/{2,})?$~sm',$_SERVER['REQUEST_URI'],$url) && 
    !strpos($_SERVER['REQUEST_URI'], ".") && 
    !strpos($_SERVER['REQUEST_URI'], "?") && 
    count($_POST) == 0 && 
    $_SERVER['REQUEST_URI'] != '/' 
     
) {
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: http://".$_SERVER['HTTP_HOST'].$url[1]."/");
    exit();
}

if($APPLICATION->GetCurPage() == '/personal/' && false && constant('IS_USER_OWNER_ONE_REALTY') === true) {
	LocalRedirect('/personal/myobjects/');
} else if (!$USER->IsAuthorized() &&  CSite::InDir('/personal/') && !CSite::InDir('/auth/')) {
	LocalRedirect('/auth/');
} else if (!$USER->IsAuthorized() &&  CSite::InDir('/auth/') && $_REQUEST['register'] == 'yes') {
	LocalRedirect('/register/');
} else if (CSite::InDir('/personal/auth/')) {
	LocalRedirect('/auth/');
}

if(!empty($_GET["VIEW_TYPE"])) {
	$_SESSION['VIEW_TYPE'] = $_GET['VIEW_TYPE'];
}
//для sell, arend, newbuildings template
$templateByView = ($_SESSION['VIEW_TYPE'] == 'tiles') ? 'sell_view_tiles' : 'sell';


$CD = $APPLICATION->GetCurDir();
$isPage['MAIN'] = ($APPLICATION->GetCurPage(true) == '/index.php' || $APPLICATION->GetCurPage(true) == '/en/index.php');

$isCatalogListPage = CSite::InDir("/sell/") && (!defined("CATALOG_DETAIL_PAGE") || CATALOG_DETAIL_PAGE !== true);

IncludeTemplateLangFile(__FILE__);
?>
<!DOCTYPE html>
<html>
<head>
	<title><?$APPLICATION->ShowTitle()?></title>
	<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="yandex-verification" content="c883b0e26080fbaa" />
	<link rel="stylesheet" href="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/css/colorbox.css', true)?>" type="text/css" media="screen, projection" />
	<link rel="stylesheet" href="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/css/slick.css', true)?>" type="text/css" media="screen, projection" />
	<link rel="stylesheet" href="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/css/slick-theme.css', true)?>" type="text/css" media="screen, projection" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
	<script src=<?=SITE_TEMPLATE_PATH."/js/lightGallery/lightgallery.js"?>></script>
	<script src=<?=SITE_TEMPLATE_PATH."/js/lightGallery/lg-thumbnail.js"?>></script>
	<script src=<?=SITE_TEMPLATE_PATH."/js/lightGallery/lg-fullscreen.js"?>></script>
	<script src=<?=SITE_TEMPLATE_PATH."/js/lightGallery/lg-video.js"?>></script>
	<script src=<?=SITE_TEMPLATE_PATH."/js/jquery.cookie.js"?>></script>
	
	<link rel="stylesheet" href="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/js/lightGallery/css/lightgallery.css', true)?>" type="text/css" media="screen, projection" />
	<?$APPLICATION->ShowHead();?>
	<?
	// @see /local/templates/morealty/components/bitrix/system.pagenavigation
	$APPLICATION->ShowViewContent('pager_link_prev');
	$APPLICATION->ShowViewContent('pager_link_next');
	?>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-KWB4TCD');</script>
<!-- End Google Tag Manager -->

</head>

<body>
<?$APPLICATION->ShowPanel()?>

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KWB4TCD"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->


<div class="main">
	<div class="wr-header">
		<div class="header">
			<div class="logo"><? if(!$isPage['MAIN']) {?><a href="/"><? }?><img src="<?=SITE_TEMPLATE_PATH?>/images/logo_iprox.png" data-rjs="2" alt="Мореалти - портал недвижимости" /><? if(!$isPage['MAIN']){?></a><?}?></div><!--logo-->

			<div class="select-town">
				<?/* ?><div class="select-town__phone">8 (800) 234-79-44</div><?*/ ?>
				<div class="select-town__phone"></div>
				<label>Недвижимость в</label>

				<div class="sel-t">
					<select>
						<option>Сочи</option>
					</select>
				</div><!--sel-t-->
			</div><!--select-town-->

			<div class="header-r">
				<?/* ?><div class="but-add"><a href="/personal/myobjects/">Разместить объявление</a></div><?*/ ?>
				<br>
				<div class="func-head">
					<?if ($USER->IsAuthorized()):?>

						<?
						$rsItems = CFavorites::GetList(array('SORT' => 'ASC'), array('USER_ID' => (int)$USER->GetID()));
						$arID = array();
						while ($Res = $rsItems->Fetch()) {
							$arID[] = $Res['URL'];
						}
						$favCount = count($arID);
						?>

						<div class="favor"><a href="/personal/favorites/">Избранное <span><?=$favCount?></span></a></div>
						<div class="link-lich"><a href="/personal/">Личный кабинет</a></div>
					<?elseif (false):?>
						<div class="link-lich"><a class="lich-pop" href="javascript:void(0);">Войти</a></div>
						<div class="link-lich"><a href="/register/">Регистрация</a></div>
					<?endif;?>

				</div><!--func-head-->
			</div><!--header-r-->
		</div><!--header-->
	</div><!--wr-header-->
	<div class="menu new_header_menu">
	<?$APPLICATION->IncludeComponent(
		"bitrix:menu",
		"top_multilevel2",
		array(
			"ALLOW_MULTI_SELECT" => "N",
			//"CHILD_MENU_TYPE" => "left",
			"COMPONENT_TEMPLATE" => "horizontal_multilevel",
			"DELAY" => "N",
			"MAX_LEVEL" => "3",
			"MENU_CACHE_GET_VARS" => array(
			),
			"MENU_CACHE_TIME" => 0, //"3600",
			"MENU_CACHE_TYPE" => "A",
			"MENU_CACHE_USE_GROUPS" => "N",
			"ROOT_MENU_TYPE" => "top",
			"USE_EXT" => "Y",
			"MAIN_SECTION"  => constant('MAIN_SECTION')
		),
		false
	);?>
	
		<div class="menu_search">
			<input type="text" placeholder="Поиск объекта по номеру лота, улице, номеру телефона" id="top_search">
			<div class="search_elements">
				<div class="searched_elements">
					
					
				</div>
			</div>
		</div>
		
	</div>
	<div class="menu-clear-left"></div>

	<?if (! $isPage['MAIN']) {?>
	<div class="content">
		<?
		if (!$isCatalogListPage)
		{
			?>
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
			<?
		}
		?>
		
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
				"MENU_CACHE_USE_GROUPS" => "Y",
				"ROOT_MENU_TYPE" => "cab",
				"USE_EXT" => "N"
			),
			false
		);?>
	<?}?>

