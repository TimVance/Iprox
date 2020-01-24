<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Контактная информация");
?><div class="bl-sitemap contact-block">
	<div class="block_contact">
		<div class="block-half">
			<p class="cont-comp">
				 
			</p>
			<p>
 <span class="mail_label"></span><a href="mailto:info@iprox.ru">info@iprox.ru</a>
			</p>
			 <?
 //contact_phone
 //<b>Бесплатная линия: 8 (800) 234-79-44</b>
 ?>
			<p>
			</p>
			<ul class="contacts-list">
				<li><label>ОГРН:</label> 1152366008915</li>
				<li><label>ИНН:</label> 2320234097</li>
				<li><label>КПП:</label> 232001001</li>
				<li><label>Р/счет:</label> 40702810430060001965</li>
				<li><label>Банк:</label> ОАО Сбербанк России г. Ростов-на-Дону</li>
				<li><label>К/счет:</label> 30101810600000000602</li>
				<li><label>БИК:</label> 046015602</li>
			</ul>
		</div>
		<div class="block-half">
			 <?$APPLICATION->IncludeComponent(
	"bitrix:map.yandex.view", 
	"contacts", 
	array(
		"CONTROLS" => array(
			0 => "ZOOM",
		),
		"INIT_MAP_TYPE" => "MAP",
		"MAP_DATA" => "a:4:{s:10:\"yandex_lat\";d:55.74773802544105;s:10:\"yandex_lon\";d:37.554694783962546;s:12:\"yandex_scale\";i:12;s:10:\"PLACEMARKS\";a:2:{i:0;a:3:{s:3:\"LON\";d:39.718265;s:3:\"LAT\";d:43.583447849379;s:4:\"TEXT\";s:67:\"ООО «Портал недвижимости «Мореалти»\";}i:1;a:3:{s:3:\"LON\";d:37.541861640119464;s:3:\"LAT\";d:55.74892475344114;s:4:\"TEXT\";s:67:\"ООО «Портал недвижимости «Мореалти»\";}}}",
		"MAP_HEIGHT" => "auto",
		"MAP_ID" => "yam_1",
		"MAP_WIDTH" => "auto",
		"ONMAPREADY" => "EventMapReady",
		"OPTIONS" => array(
			0 => "ENABLE_DBLCLICK_ZOOM",
			1 => "ENABLE_DRAGGING",
		),
		"COMPONENT_TEMPLATE" => "contacts"
	),
	false
);?>
		</div>
	</div>
	<div class="callback_contact">
		 <?$APPLICATION->IncludeComponent(
	"bitrix:form",
	"callback_contact",
	Array(
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"CHAIN_ITEM_LINK" => "",
		"CHAIN_ITEM_TEXT" => "",
		"EDIT_ADDITIONAL" => "N",
		"EDIT_STATUS" => "N",
		"IGNORE_CUSTOM_TEMPLATE" => "N",
		"NOT_SHOW_FILTER" => array("SIMPLE_QUESTION_591","SIMPLE_QUESTION_439","SIMPLE_QUESTION_600",""),
		"NOT_SHOW_TABLE" => array("",""),
		"RESULT_ID" => $_REQUEST[RESULT_ID],
		"SEF_FOLDER" => "/contacts/",
		"SEF_MODE" => "Y",
		"SEF_URL_TEMPLATES" => Array("edit"=>"#WEB_FORM_ID#/edit/#RESULT_ID#/","list"=>"#WEB_FORM_ID#/list/","new"=>"#WEB_FORM_ID#/","view"=>"#WEB_FORM_ID#/view/#RESULT_ID#/"),
		"SHOW_ADDITIONAL" => "N",
		"SHOW_ANSWER_VALUE" => "N",
		"SHOW_EDIT_PAGE" => "N",
		"SHOW_LIST_PAGE" => "N",
		"SHOW_STATUS" => "N",
		"SHOW_VIEW_PAGE" => "N",
		"START_PAGE" => "new",
		"SUCCESS_URL" => "/contacts/spasibo/",
		"USE_EXTENDED_ERRORS" => "N",
		"WEB_FORM_ID" => "6"
	)
);?>
	</div>
</div>
 <br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>