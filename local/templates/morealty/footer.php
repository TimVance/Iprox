<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
IncludeTemplateLangFile(__FILE__);
?>


		<?if ($_REQUEST["forgot_password"] == 'yes')  {?>
			</div><!--content-->
		<?}?>
		<?if (! $isPage['MAIN'])  {?>
			</div><!--content-->
		<?}?>


		<?if (CSite::InDir("/sell/map/") || (CSite::InDir("/agents/") && $_REQUEST["map"] == "Y")):?>
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
				<div class="copy">© IPROX , <?=date("Y");?>
				<br><?$APPLICATION->IncludeFile('/include_areas/footer_copy_slogan_'.LANGUAGE_ID.'.php', false, array('MODE' => 'text'))?></div>
				<div class="info-f"><? if($CD !="/contacts/"){?><a href="/contacts/"><?}?>Контактная информация<? if($CD !="/contacts/"){?></a><?}?></div>
			</div><!--foot-l-->
			<div class="menu-bf">
				<ul>
					<li><span>О проекте</span></li>
					<li><? if($APPLICATION->GetCurUri() !="/agreement/"){?><a href="/agreement/"><?}?>Политика конфиденциальности<? if($APPLICATION->GetCurUri() !="/agreement/"){?></a><?}?></li>
					<li><? if($APPLICATION->GetCurUri() !="/agreement/terms.php"){?><a href="/agreement/terms.php"><?}?>Пользовательское соглашение<? if($APPLICATION->GetCurUri() !="/agreement/terms.php"){?></a><?}?></li>
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
					<li class="item1"><a target="_blank" rel="nofollow" href="https://www.facebook.com/iprox.sochi/"></a></li>
					<li class="item2"><a target="_blank" rel="nofollow" href="https://www.instagram.com/_iprox/"></a></li>
					<li style="display: none" class="item3"><a href="#"></a></li>
				</ul>
                <ul class="donwload-links">
                    <li><a target="_blank" rel="nofollow" href="https://apps.apple.com/ru/app/iprox/id1456461525"><svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="app-store-ios" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-inline--fa fa-app-store-ios fa-w-14 fa-2x"><path fill="currentColor" d="M400 32H48C21.5 32 0 53.5 0 80v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V80c0-26.5-21.5-48-48-48zM127 384.5c-5.5 9.6-17.8 12.8-27.3 7.3-9.6-5.5-12.8-17.8-7.3-27.3l14.3-24.7c16.1-4.9 29.3-1.1 39.6 11.4L127 384.5zm138.9-53.9H84c-11 0-20-9-20-20s9-20 20-20h51l65.4-113.2-20.5-35.4c-5.5-9.6-2.2-21.8 7.3-27.3 9.6-5.5 21.8-2.2 27.3 7.3l8.9 15.4 8.9-15.4c5.5-9.6 17.8-12.8 27.3-7.3 9.6 5.5 12.8 17.8 7.3 27.3l-85.8 148.6h62.1c20.2 0 31.5 23.7 22.7 40zm98.1 0h-29l19.6 33.9c5.5 9.6 2.2 21.8-7.3 27.3-9.6 5.5-21.8 2.2-27.3-7.3-32.9-56.9-57.5-99.7-74-128.1-16.7-29-4.8-58 7.1-67.8 13.1 22.7 32.7 56.7 58.9 102h52c11 0 20 9 20 20 0 11.1-9 20-20 20z" class=""></path></svg></a></li>
                    <li><a target="_blank" rel="nofollow" href="https://play.google.com/store/apps/details?id=pro.mitapp.morealti"><svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="google-play" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-google-play fa-w-16 fa-2x"><path fill="currentColor" d="M325.3 234.3L104.6 13l280.8 161.2-60.1 60.1zM47 0C34 6.8 25.3 19.2 25.3 35.3v441.3c0 16.1 8.7 28.5 21.7 35.3l256.6-256L47 0zm425.2 225.6l-58.9-34.1-65.7 64.5 65.7 64.5 60.1-34.1c18-14.3 18-46.5-1.2-60.8zM104.6 499l280.8-161.2-60.1-60.1L104.6 499z" class=""></path></svg></a></li>
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
    <?$APPLICATION->IncludeComponent("morealty:show_application_link", "", array()); ?>
	<?
	/*
	 * всплывающие окна
	 */
	require __DIR__ . '/parts/popups.php';
	?>

	<input type="hidden" id="is_authorized" value="<?if($USER->IsAuthorized()) {echo 'y';} else {echo 'n';}?>">
	<script src="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/js/slick.min.js', true)?>"></script>
	<script src="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/js/jquery.bxslider.js', true)?>"></script>
	<script src="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/js/jquery.colorbox-min.js', true)?>"></script>
	<script src="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/js/jquery.selectbox.min.js', true)?>"></script>
	<script src="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/js/jquery.ezmark.min.js', true)?>"></script>
	<script src="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/js/jquery.inputmask.js', true)?>"></script>
	<script src="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/js/retina.min.js', true)?>"></script>
	<script src="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/js/lib.js', true)?>"></script>
	<script src="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/js/tablesorter.js', true)?>"></script>
	<script src="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/js/form.js', true)?>"></script>
	
	<?/* общий файл с приложением js. в него пишем все стили, в нем общий документ-реди */?>
	<script src="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH.'/js/script.js', true)?>"></script>
	<?if ($_REQUEST['text']):?>
		<a class="inline cboxElement" style="display: none;" id="pp8" href="#pop11"></a>
		<script>
			$(document).ready(function(){
				$('#pp8').trigger('click');
				$('.slider-big2').bxSlider({
					pagerCustom: '#bx-pager2'
				});
			});
		</script>
	<?endif;?>

<div class="hidden">
	<!-- Yandex.Metrika counter -->
	<script type="text/javascript" >
	    (function (d, w, c) {
	        (w[c] = w[c] || []).push(function() {
	            try {
	                w.yaCounter47701606 = new Ya.Metrika({
	                    id:47701606,
	                    clickmap:true,
	                    trackLinks:true,
	                    accurateTrackBounce:true,
	                    webvisor:true
	                });
	            } catch(e) { }
	        });
	
	        var n = d.getElementsByTagName("script")[0],
	            s = d.createElement("script"),
	            f = function () { n.parentNode.insertBefore(s, n); };
	        s.type = "text/javascript";
	        s.async = true;
	        s.src = "https://mc.yandex.ru/metrika/watch.js";
	
	        if (w.opera == "[object Opera]") {
	            d.addEventListener("DOMContentLoaded", f, false);
	        } else { f(); }
	    })(document, window, "yandex_metrika_callbacks");
	</script>
	<noscript><div><img src="https://mc.yandex.ru/watch/47701606" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
	<!-- /Yandex.Metrika counter -->
	
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-114200826-1"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());
	
	  gtag('config', 'UA-114200826-1');
	</script>




    <!-- The core Firebase JS SDK is always required and must be listed first -->
    <script src="https://www.gstatic.com/firebasejs/8.0.1/firebase-app.js"></script>

    <script src="https://www.gstatic.com/firebasejs/8.0.1/firebase-messaging.js"></script>

    <!-- TODO: Add SDKs for Firebase products that you want to use
         https://firebase.google.com/docs/web/setup#available-libraries -->
    <script src="https://www.gstatic.com/firebasejs/8.0.1/firebase-analytics.js"></script>

    <script>
        // Your web app's Firebase configuration
        // For Firebase JS SDK v7.20.0 and later, measurementId is optional
        var firebaseConfig = {
            apiKey: "AIzaSyDZUpK5pFs_Ks4jPW9HxYkE4smkmbq0BKk",
            authDomain: "iprox-ec392.firebaseapp.com",
            databaseURL: "https://iprox-ec392.firebaseio.com",
            projectId: "iprox-ec392",
            storageBucket: "iprox-ec392.appspot.com",
            messagingSenderId: "554969024398",
            appId: "1:554969024398:web:bba2c5d29134649d9986df",
            measurementId: "G-NB3SSH1L79"
        };
        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);
        firebase.analytics();

        const messaging = firebase.messaging();

        messaging.onBackgroundMessage(function(payload) {
            console.log('Received background message ', payload);
            // Customize notification here
            const notificationTitle = 'Background Message Title';
            const notificationOptions = {
                body: 'Background Message body.',
                icon: '/firebase-logo.png'
            };

            self.registration.showNotification(notificationTitle,
                notificationOptions);
        });

        messaging.onMessage((payload) => {
            console.log('Message received. ', payload);
            // ...
        });

    </script>



</div>
</body>
</html>
<?

// process 404 in content part
if (constant('ERROR_404') == 'Y' && $APPLICATION->GetCurPage() != '/404.php') {
	$APPLICATION->RestartBuffer();

	$contCacheFile  = $_SERVER['DOCUMENT_ROOT'] . '/404_cache.html';

	// TODO uncomment on production
	if (is_file($contCacheFile) && ((time() - filemtime($contCacheFile)) > 3600)) {
		unlink($contCacheFile);
	}

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