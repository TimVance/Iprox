<? if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
$APPLICATION->SetTitle($arResult['FULL_NAME']);
CModule::IncludeModule("iblock");

$GLOBALS['EMAILS_FOR_FORM'] = $arResult['EMAIL'];
//my_print_r($arResult);
?>
<?
if ($arResult['UF_PERSON_POST'] || ($arResult["UF_AGENT_NAME"] && $arResult["UF_AGENT_ID"]) )
{
	$text = array();
	if ($arResult['UF_PERSON_POST'])
	{
		$text[] = $arResult['UF_PERSON_POST'];
	}
	if ($arResult["UF_AGENT_NAME"] && $arResult["UF_AGENT_ID"])
	{
		$text[] = '<a href="/agents/' . $arResult["UF_AGENT_ID"] . '/">'.$arResult["UF_AGENT_NAME"].'</a>';
	}
	?>
	<div class="stat-r">
		<?=implode(",", $text);?>
	</div>
	<?
}
?>


<div class="block-rielt">
	<div class="bl-rielt">
		<div class="photo-r">
			<?if(!empty($arResult["PERSONAL_PHOTO"]) && $arResult["PERSONAL_PHOTO"] != null) :?>
				<img src="<?=$arResult["PERSONAL_PHOTO"]?>" alt="" />
			<? else: ?>
				<img src="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" alt="" />
			<? endif; ?>
		</div>
		<div class="descr-r">
			<div class="link-r">
				<ul>
					<li><a target="_blank" href="<?=$arResult['UF_SITE_ADDRESS']?>"><?=$arResult['UF_SITE_ADDRESS']?></a></li>
					<li><a href="mailto:<?=$arResult['EMAIL']?>"><?=$arResult['EMAIL']?></a></li>
				</ul>
			</div>

			<div class="view-phone">
				<input type="hidden" id="realtor_id" value="<?=$arResult['ID']?>">
				<a href="javascript:void(0);">Показать телефон</a>
				<div class="phone-v"><span></span></div>
			</div>

			<div class="send-mess"><a class="inline" href="#send_message_to_realtor-form">Оставить сообщение</a></div>
		</div><!--descr-r-->
		<? 
		if ($arResult["UF_ABOUT_ME"])
		{
			?>
			<div class="realtor_about_block">
				<p><?=$arResult["UF_ABOUT_ME"]?></p>
			</div>
			<?
		}
		?>
		
	</div><!--bl-rielt-->

	<div class="logo-agents"><a href="<?='/agents/' . $arResult["UF_AGENT_ID"] . '/' ?>"><img src="<?=$arResult['UF_AGENT_PHOTO']?>" alt="" /></a></div>
</div><!--block-rielt-->

<div class="line-propos">
	<?
	$APPLICATION->IncludeComponent("wexpert:iblock.list","rieltor.offer",Array(
		//"ORDER"                             => array($_GET["SORT_BY"] => $_GET["SORT_ORDER"]),
		"ORDER"                             => array('SORT' => 'asc'),
		"FILTER"                            => array('PROPERTY_realtor' => $arResult["ID"],"!PROPERTY_IS_ACCEPTED"=>false,"!PROPERTY_SPECIAL_OFFER"=>false),
		"IBLOCK_ID"							=> $GLOBALS['CATALOG_IBLOCKS_ARRAY'],
		"PAGESIZE"						    => 1,
		"GET_PROPERTY"						=> "Y",
		"CACHE_TIME"    => 3600 * 24 * 10,
		"BLOCK_TITLE"						=> "Специальное предложение",
	));
	?>
	<?
	$APPLICATION->IncludeComponent("wexpert:iblock.list","rieltor.offer",Array(
		//"ORDER"                             => array($_GET["SORT_BY"] => $_GET["SORT_ORDER"]),
		"ORDER"                             => array('created' => 'DESC'),
		"FILTER"                            => array('PROPERTY_realtor' => $arResult["ID"],"!PROPERTY_IS_ACCEPTED"=>false),
		"IBLOCK_ID"							=> $GLOBALS['CATALOG_IBLOCKS_ARRAY'],
		"PAGESIZE"						    => 1,
		"GET_PROPERTY"						=> "Y",
		"CACHE_TIME"    => 3600 * 24 * 10,
		"BLOCK_TITLE"						=> "Новое поступление",
	));
	?>
<?
//$GLOBALS['CATALOG_IBLOCKS_ARRAY']
?>
	<?/* ?><div class="spec-propos">
		<div class="tit-spec">Специальное предложение</div>

		<div class="bl-spec">
			<div class="head-spec">
				<a href="#">ЖК «Меридиан» на ул. Воровского, 45</a>
				<span>Сочи, Хостинский район, Кудепста пос., ул. Камо</span>
			</div>

			<div class="body-spec">
				<div class="img-body-spec"><img src="<?=SITE_TEMPLATE_PATH?>/images_tmp/img-body-spec.jpg" alt="" /></div><!--img-body-spec-->
				<div class="desc-body-spec">
					<div class="price-spec">8 500 000 руб.</div>
					<ul>
						<li><span>Общая площадь:</span> <strong>122 м<sup>2</sup></strong></li>
						<li><span>Этаж/этажей:</span> <strong>3/9</strong></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="spec-propos">
		<div class="tit-spec">Новое поступление</div>

		<div class="bl-spec">
			<div class="head-spec">
				<a href="#">ЖК «Меридиан» на ул. Воровского, 45</a>
				<span>Сочи, Хостинский район, Кудепста пос., ул. Камо</span>
			</div>

			<div class="body-spec">
				<div class="img-body-spec"><img src="<?=SITE_TEMPLATE_PATH?>/images_tmp/img-body-spec.jpg" alt="" /></div><!--img-body-spec-->
				<div class="desc-body-spec">
					<div class="price-spec">8 500 000 руб.</div>
					<ul>
						<li><span>Общая площадь:</span> <strong>122 м<sup>2</sup></strong></li>
						<li><span>Этаж/этажей:</span> <strong>3/9</strong></li>
					</ul>
				</div>
			</div><!--body-spec-->
		</div><!--bl-spec-->
	</div><!--spec-propos-->
	<? */?>
</div><!--line-propos-->



<?
$APPLICATION->IncludeComponent("morealty:rieltor.offers","",Array(
"RIELTOR" => $arResult["ID"],
//"SORT" => $_REQUEST["SORT"],
//"ORDER"	=> $_REQUEST["ORDER"],
"ORDER" => array($_GET["SORT_BY"] => $_GET["SORT_ORDER"]),
"IB_TYPE" => $_REQUEST["TYPE_BUILD"],
"VIEW_TYPE" => $arParams["OBJECTS_TEMPLATE_VIEW"]
));
?>