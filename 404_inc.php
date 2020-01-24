<?
header("HTTP/1.0 404 Not Found\r\n");
@define("ERROR_404","Y");
?>
<?
if (LANGUAGE_ID == 'en') {
	$APPLICATION->SetTitle("Page not found");
	?>
	Dear visitor! Unfortunately, the page you requested is unavailable. This can happen for the following reasons:
	<br>
	<ul>
		<li>page has been deleted</li>
		<li>page has been renamed</li>
		<li>you made a mistake in the address (http://<?=$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']?>)</li>
	</ul>
	<br>
	Please go to <a href="/">home page</a> site and try again or use <strong>site map</strong>:
	
	<?
	\Bitrix\Main\Config\Option::set("main", "map_top_menu_type", 	"en_top");
	\Bitrix\Main\Config\Option::set("main", "map_left_menu_type", 	"en_left,content");
	
} else {
	$APPLICATION->SetTitle("Страница не найдена (404 Not Found)");
	?><div class="content">	
<p>К сожалению, запрашиваемая Вами страница не найдена на сайте нашей компании.</p>
<p>Мы приносим свои извинения за доставленные неудобства и предлагаем следующие пути:</p>
<p>» перейти на <a href="http://morealti.com/">главную страницу</a>  или <a href="http://morealti.com/sitemap/">карту сайта</a>;</p>
<p>» связаться с нами по телефону 8 (800) 234-79-44;</p>
<p>» <a href="mailto:info@morealti.com">написать письмо</a>  нашим сотрудникам.</p>

	<?
	\Bitrix\Main\Config\Option::set("main", "map_top_menu_type", 	"top");
	\Bitrix\Main\Config\Option::set("main", "map_left_menu_type", 	"left,content");
	
}
?>
<br /><br />

</div>
