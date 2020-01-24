<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
IncludeTemplateLangFile(__FILE__);
?>


<?if ($_REQUEST["forgot_password"] == 'yes')  {?>
	</div><!--content-->
<?}?>
<?if (! $isPage['MAIN'])  {?>
		</div><!--content-->
<?}?>


<?if(CSite::InDir("/sell/map/")):?>
	<?
	// кусок от карт (подбор на карте)
	require __DIR__ . '/parts/maps.php';
	?>
<?endif;?>



	</div><!--main-->
	
	<div class="footer footer2">
		<div class="menu-f">
			<ul>
				<?$APPLICATION->IncludeComponent(
				"bitrix:menu",
				"bottom_menu",
				array(
					"ALLOW_MULTI_SELECT" => "N",
					"CHILD_MENU_TYPE" => "left",
					"COMPONENT_TEMPLATE" => "bottom_top",
					"DELAY" => "N",
					"MAX_LEVEL" => "3",
					"MENU_CACHE_GET_VARS" => array(
					),
					"MENU_CACHE_TIME" => 0, //"3600",
					"MENU_CACHE_TYPE" => "Y",
					"MENU_CACHE_USE_GROUPS" => "N",
					"ROOT_MENU_TYPE" => "bottom",
					"USE_EXT" => "N",
					"MAIN_SECTION"  => constant('MAIN_SECTION')
				),
				false );?>
			</ul>
		</div><!--menu-f-->

		<div class="bot-f">
			<div class="foot-l">
				<div class="copy">©МОРЕАЛТИ , <?=date("Y");?>
				<br><?$APPLICATION->IncludeFile('/include_areas/footer_copy_slogan_'.LANGUAGE_ID.'.php', false, array('MODE' => 'text'))?></div>
				<div class="info-f"><? if($CD !="/contacts/"){?><a href="/contacts/"><?}?>Контактная информация<? if($CD !="/contacts/"){?></a><?}?></div>
			</div><!--foot-l-->

			<div class="menu-bf">
				<ul>
					<li><span>О проекте</span></li>
					<li><? if($CD !="/agreement/"){?><a href="/agreement/"><?}?>Пользовательское соглашение<? if($CD !="/agreement/"){?></a><?}?></li>
					<li><? if($CD !="/sitemap/"){?><a href="/sitemap/"><?}?>Карта сайта<? if($CD !="/sitemap/"){?></a><?}?></li>
				</ul>
			</div><!--menu-bf-->

			<div class="contacts-f">
				<div class="rek"><? if($CD !="/advertisers/"){?><a href="/advertisers/"><?}?>Рекламодателям<? if($CD !="/advertisers/"){?></a><?}?></div>
				<div class="phone-f"><?$APPLICATION->IncludeFile('/include_areas/phone_' . LANGUAGE_ID . '.php', false, array('MODE' => 'text'))?></div>
				<div class="mail-f">E-mail: <span title="Написать нам" class="mailme"><?$APPLICATION->IncludeFile('/include_areas/mail_' . LANGUAGE_ID . '.php', false, array('MODE' => 'text'))?></span></div>
			</div><!--contacts-f-->

			<div class="soc-f">
				<ul>
					<li class="item1"><a href="#"></a></li>
					<li class="item2"><a href="#"></a></li>
					<li class="item3"><a href="#"></a></li>
				</ul>
			</div><!--soc-f-->
		</div><!--bot-f-->
	</div><!--footer-->
	
	<div class="wr-pop-inp">
		<?$APPLICATION->IncludeComponent("bitrix:system.auth.form","header",Array(
				"REGISTER_URL"			=> "/register/",
				"FORGOT_PASSWORD_URL"	=> "/auth/",
				"PROFILE_URL" => "/personal/",
				"SHOW_ERRORS" => "n"
			)
		);?>


	</div><!--wr-pop-inp-->

	<?
	/*
	 * всплывающие окна
	 */
	require __DIR__ . '/parts/popups.php';
	?>

	<input type="hidden" id="is_authorized" value="<?if($USER->IsAuthorized()) {echo 'y';} else {echo 'n';}?>">

	<script src="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/js/jquery.bxslider.min.js', true)?>"></script>
	<script src="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/js/jquery.colorbox-min.js', true)?>"></script>
	<script src="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/js/jquery.selectbox.min.js', true)?>"></script>
	<script src="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/js/jquery.ezmark.min.js', true)?>"></script>
	<script src="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/js/lib.js', true)?>"></script>
	<script src="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/js/script.js', true)?>"></script>
	<script src="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/js/form.js', true)?>"></script>
	<script>
	$(document).ready(function(){
		$('body').on('click', '.dropdown ul li', function(){
			var ID = $(this).parents('.sel-on').find('option[selected=selected]').val();
			var object = $(this).parents('.sel-on').find('select').attr('name');

			var lineSelect = $(this).parents('.line-select');
			var miniType;
			if(object == 'city') {
				miniType = 'district';
			} else if(object == 'district') {
				miniType = 'microdistrict';
			}

			$.ajax({
				url: '/local/templates/morealty/ajax_php/get-parent-object.php?object=' + object + '&ID=' + ID,
				success: function(data) {
					//console.log(data);

					var html = $('select[name=' + miniType + ']').html();
					$('select[name=' + miniType + ']').html('');
					$('select[name=' + miniType + ']').append(data);
					$('select[name=' + miniType + ']').parents('.sel-on').children('.selectbox:eq(0)').remove();
					$('.sel-pr select, .select-town select, .sel-on select, .sel-view select').selectbox();
				},
				error: function(data) {
					console.log('error');
				}
			});

			if(object == 'microdistrict') {
				var cityID = $('select[name=city]').children('option[selected=selected]');
				var districtID = $('select[name=district]').children('option[selected=selected]');
				var microdistrictID = $('select[name=microdistrict]').children('option[selected=selected]');
				$.ajax({
					url: '/local/templates/morealty/ajax_php/get-parent-object.php?city=' + cityID + '&district=' + districtID + '&microdistrict=' + microdistrictID,
					success: function(data) {
						//console.log(data);
					},
					error: function(data) {
						console.log(data);
					}
				});
			}
		})
	});
</script>


<? if($_REQUEST['text']):?>
	<a class="inline cboxElement" style="display: none;" id="pp8" href="#pop11"></a>
	<script>
		$(document).ready(function(){
			$('#pp8').trigger('click');
		});
	</script>
<?endif; ?>




</body>
</html>


<?

// process 404 in content part
if (constant('ERROR_404') == 'Y' && $APPLICATION->GetCurPage() != '/404.php') {
	$APPLICATION->RestartBuffer();

	$contCacheFile  = $_SERVER['DOCUMENT_ROOT'] . '/404_cache.html';

	// TODO uncomment on production
	/*if (is_file($contCacheFile) && ((time() - filemtime($contCacheFile)) > 3600)) {
		unlink($contCacheFile);
	}*/

	$cont = file_get_contents($contCacheFile);
	if (trim($cont) == '') {
		$cont = QueryGetData($_SERVER['HTTP_HOST'], 80, '/404.php', '', $errno, $errstr);
		file_put_contents($contCacheFile, $cont);
	}

	header("HTTP/1.0 404 Not Found\r\n");
	echo $cont;
	exit;
}


?>