<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

/* @var $this CBitrixComponentTemplate */

if (CModule::IncludeModule('iblock')) {
	//общее количество объявлений и количество объявлений квартир
	$allOffersCount  = 0;
	$flatOffersCount = 0;
	$arSelect        = Array('ID');
	$arFilter        = Array('TYPE' => 'catalog', 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y');
	$res             = CIBlock::GetList(Array('SORT' => 'ASC'), $arFilter, true, $arSelect);
	while ($item = $res->GetNext()) {
		$arFilter = Array('IBLOCK_ID' => $item['ID'], "ACTIVE" => "Y","!PROPERTY_IS_ACCEPTED"=>false);
		$countRes = CIBlockElement::GetList(Array(), $arFilter, false, Array(), Array());
		$allOffersCount += $countRes->SelectedRowsCount();
	}

	$flatSectionID = 7;
	$arFilter      = Array('IBLOCK_ID' => $flatSectionID, "ACTIVE" => "Y","!PROPERTY_IS_ACCEPTED"=>false);
	$countRes      = CIBlockElement::GetList(Array(), $arFilter, false, Array(), Array());
	$flatOffersCount += $countRes->SelectedRowsCount();
}

$arFilter      = Array('IBLOCK_ID' => 3, "ACTIVE" => "Y");
$countAgents      = CIBlockElement::GetList(Array(), $arFilter, false, Array(), Array())->SelectedRowsCount();

?>

<div class="wr-top main-page-wr-top">
<? 
$APPLICATION->IncludeComponent(
		"wexpert:iblock.list",
		"main-page-image",
		array(
			"ORDER"			=> array("SORT"=>"ASC"),
			"FILTER"		=> array("ACTIVE"=>"Y"),
			"IBLOCK_ID"		=> 29,
			"NTOPCOUNT"		=> 1,
			"GET_PROPERTY"	=> "Y",
			"CACHE_TIME"  	=> 3600		
		)
);
?>
	<div class="block-top">
		<div class="tabs-func tabs-tb">
			<?
			if (true)
			{
				$APPLICATION->IncludeComponent("wexpert:includer", "main.filters", 
						array(
								"STRUCTURE" => 
								array(
										7 => array(
												"NAME" => "Квартиру",
												"PROPS" => array(
														"room_number",
														"price",
												),
												"ADDITIONAL_PROPS" => array(
														"square"
												),
												"MAP" => "/sell/map/"
												
										),
										8 => array(
												"NAME" => "Дом",
												"PROPS" => array(
														"price",
														"sector_square",
												),
												"MAP" => "/sell/map/#IBLOCK_CODE#/"
												
										),
										11 => array(
												"NAME" => "Коммерция",
												"PROPS" => array(
														"price",
												),
												"ADDITIONAL_PROPS" => array(
														"square",
														"commerc_type"
												),
												"MAP" => "/sell/map/#IBLOCK_CODE#/"
										),
										10 => array(
												"NAME" => "Участок",
												"PROPS" => array(
														"price",
														"sector_square"
												),
												"MAP" => "/sell/map/#IBLOCK_CODE#/"
										)
								)
						), 
						$component
				);
			}
			else 
			{
				?>
					<? $APPLICATION->IncludeComponent('wexpert:includer', 'main_page_search_form', array(), $component) ?>
				<?
			}
			?>
			
		</div><!--tabs-func-->

		<div class="text-top">
			<h1>Квартиры в Сочи</h1>
			<p>Наибольшим спросом пользуется покупка квартиры в новостройках и на вторичном рынке. В базе данных
				<span><?= $allOffersCount ?></span> объявлений по недвижимости в Сочи, в том числе
				<span><?= $flatOffersCount ?></span> по продаже квартир.</p>
		</div><!--text-top-->
	</div><!--block-top-->
</div><!--w-top-->

<? $APPLICATION->IncludeComponent(
	'morealty:main.statistic',
	'',
	array(
		'CACHE_TIME' => 3600,
		"SELL_LINK_TEMPLATE" => "/sell/#code#/",
	)); ?>

<div class="benefits">
	<ul>
		<li class="item1">
			<a href="/sell/map/">Поиск по карте</a>
			<span>Быстро, удобно, наглядно</span>
		</li>
		<li class="item2">
			<a href="/cost/">Morealty оценка</a>
			<span>ОНЛАЙН</span>
		</li>
		<?/*<li class="item3">
			<a href="/agents/">Агентства недвижимости</a>
			<span>Более <?=((int) ($countAgents/10)) * 10?></span>
		</li>*/?>
	</ul>
</div><!--benefits-->

<? $APPLICATION->IncludeComponent(
	'morealty:main.last_objects',
	'',
	array(
		'CACHE_TIME' => 3600
	)); ?>

<? /*
	<div class="block-propos">
		<div class="title">Предложения застройщиков</div>
		
		<div class="body-propos">
			<div class="left-propos">
				<div class="list-propos">
					<div class="item-propos">
						<div class="top-propos">
							<div class="img-propos"><a href="#"><img src="<?=SITE_TEMPLATE_PATH?>/images_tmp/room1.jpg" alt="" /></a></div>
							<div class="t-propos"><p><a href="#">2-комнатная в ЖК Green Sail на ул. Белорусская</a></p></div>
						</div><!--top-propos-->
						
						<div class="bot-propos">
							<div class="adress-p">Сочи, Адлерский район, Адлер-центр, ул. Белорусская</div>
							
							<div class="price-p">4 420 000 руб.</div>
							
							<div class="params-p">
								<ul>
									<li>Общая площадь: <span>53,2</span> м<sup>2</sup></li>
									<li>Этаж: <span>2/10</span></li>
								</ul>
							</div>
							
							<div class="compl-prop">Офис продаж ЖК "Green Sail"</div>
						</div><!--bot-propos-->
					</div><!--item-propos-->
					
					<div class="item-propos">
						<div class="top-propos">
							<div class="img-propos"><a href="#"><img src="<?=SITE_TEMPLATE_PATH?>/images_tmp/room2.jpg" alt="" /></a></div>
							<div class="t-propos"><p><a href="#">2-комнатная в ЖК Green Sail на ул. Белорусская</a></p></div>
						</div><!--top-propos-->
						
						<div class="bot-propos">
							<div class="adress-p">Сочи, Адлерский район, Адлер-центр, ул. Белорусская</div>
							
							<div class="price-p">4 420 000 руб.</div>
							
							<div class="params-p">
								<ul>
									<li>Общая площадь: <span>53,2</span> м<sup>2</sup></li>
									<li>Этаж: <span>2/10</span></li>
								</ul>
							</div>
							
							<div class="compl-prop">Офис продаж ЖК "Green Sail"</div>
						</div><!--bot-propos-->
					</div><!--item-propos-->
					
					<div class="item-propos">
						<div class="top-propos">
							<div class="img-propos"><a href="#"><img src="<?=SITE_TEMPLATE_PATH?>/images_tmp/room3.jpg" alt="" /></a></div>
							<div class="t-propos"><p><a href="#">2-комнатная в ЖК Green Sail на ул. Белорусская</a></p></div>
						</div><!--top-propos-->
						
						<div class="bot-propos">
							<div class="adress-p">Сочи, Адлерский район, Адлер-центр, ул. Белорусская</div>
							
							<div class="price-p">4 420 000 руб.</div>
							
							<div class="params-p">
								<ul>
									<li>Общая площадь: <span>53,2</span> м<sup>2</sup></li>
									<li>Этаж: <span>2/10</span></li>
								</ul>
							</div>
							
							<div class="compl-prop">Офис продаж ЖК "Green Sail"</div>
						</div><!--bot-propos-->
					</div><!--item-propos-->
					
					<div class="item-propos">
						<div class="top-propos">
							<div class="img-propos"><a href="#"><img src="<?=SITE_TEMPLATE_PATH?>/images_tmp/room1.jpg"v /></a></div>
							<div class="t-propos"><p><a href="#">2-комнатная в ЖК Green Sail на ул. Белорусская</a></p></div>
						</div><!--top-propos-->
						
						<div class="bot-propos">
							<div class="adress-p">Сочи, Адлерский район, Адлер-центр, ул. Белорусская</div>
							
							<div class="price-p">4 420 000 руб.</div>
							
							<div class="params-p">
								<ul>
									<li>Общая площадь: <span>53,2</span> м<sup>2</sup></li>
									<li>Этаж: <span>2/10</span></li>
								</ul>
							</div>
							
							<div class="compl-prop">Офис продаж ЖК "Green Sail"</div>
						</div><!--bot-propos-->
					</div><!--item-propos-->
					
					<div class="item-propos">
						<div class="top-propos">
							<div class="img-propos"><a href="#"><img src="<?=SITE_TEMPLATE_PATH?>/images_tmp/room2.jpg" alt="" /></a></div>
							<div class="t-propos"><p><a href="#">2-комнатная в ЖК Green Sail на ул. Белорусская</a></p></div>
						</div><!--top-propos-->
						
						<div class="bot-propos">
							<div class="adress-p">Сочи, Адлерский район, Адлер-центр, ул. Белорусская</div>
							
							<div class="price-p">4 420 000 руб.</div>
							
							<div class="params-p">
								<ul>
									<li>Общая площадь: <span>53,2</span> м<sup>2</sup></li>
									<li>Этаж: <span>2/10</span></li>
								</ul>
							</div>
							
							<div class="compl-prop">Офис продаж ЖК "Green Sail"</div>
						</div><!--bot-propos-->
					</div><!--item-propos-->
					
					<div class="item-propos">
						<div class="top-propos">
							<div class="img-propos"><a href="#"><img src="<?=SITE_TEMPLATE_PATH?>/images_tmp/room3.jpg" alt="" /></a></div>
							<div class="t-propos"><p><a href="#">2-комнатная в ЖК Green Sail на ул. Белорусская</a></p></div>
						</div><!--top-propos-->
						
						<div class="bot-propos">
							<div class="adress-p">Сочи, Адлерский район, Адлер-центр, ул. Белорусская</div>
							
							<div class="price-p">4 420 000 руб.</div>
							
							<div class="params-p">
								<ul>
									<li>Общая площадь: <span>53,2</span> м<sup>2</sup></li>
									<li>Этаж: <span>2/10</span></li>
								</ul>
							</div>
							
							<div class="compl-prop">Офис продаж ЖК "Green Sail"</div>
						</div><!--bot-propos-->
					</div><!--item-propos-->
					
					<div class="item-propos">
						<div class="top-propos">
							<div class="img-propos"><a href="#"><img src="<?=SITE_TEMPLATE_PATH?>/images_tmp/room1.jpg" alt="" /></a></div>
							<div class="t-propos"><p><a href="#">2-комнатная в ЖК Green Sail на ул. Белорусская</a></p></div>
						</div><!--top-propos-->
						
						<div class="bot-propos">
							<div class="adress-p">Сочи, Адлерский район, Адлер-центр, ул. Белорусская</div>
							
							<div class="price-p">4 420 000 руб.</div>
							
							<div class="params-p">
								<ul>
									<li>Общая площадь: <span>53,2</span> м<sup>2</sup></li>
									<li>Этаж: <span>2/10</span></li>
								</ul>
							</div>
							
							<div class="compl-prop">Офис продаж ЖК "Green Sail"</div>
						</div><!--bot-propos-->
					</div><!--item-propos-->
					
					<div class="item-propos">
						<div class="top-propos">
							<div class="img-propos"><a href="#"><img src="<?=SITE_TEMPLATE_PATH?>/images_tmp/room2.jpg" alt="" /></a></div>
							<div class="t-propos"><p><a href="#">2-комнатная в ЖК Green Sail на ул. Белорусская</a></p></div>
						</div><!--top-propos-->
						
						<div class="bot-propos">
							<div class="adress-p">Сочи, Адлерский район, Адлер-центр, ул. Белорусская</div>
							
							<div class="price-p">4 420 000 руб.</div>
							
							<div class="params-p">
								<ul>
									<li>Общая площадь: <span>53,2</span> м<sup>2</sup></li>
									<li>Этаж: <span>2/10</span></li>
								</ul>
							</div>
							
							<div class="compl-prop">Офис продаж ЖК "Green Sail"</div>
						</div><!--bot-propos-->
					</div><!--item-propos-->
					
					<div class="item-propos">
						<div class="top-propos">
							<div class="img-propos"><a href="#"><img src="<?=SITE_TEMPLATE_PATH?>/images_tmp/room3.jpg" alt="" /></a></div>
							<div class="t-propos"><p><a href="#">2-комнатная в ЖК Green Sail на ул. Белорусская</a></p></div>
						</div><!--top-propos-->
						
						<div class="bot-propos">
							<div class="adress-p">Сочи, Адлерский район, Адлер-центр, ул. Белорусская</div>
							
							<div class="price-p">4 420 000 руб.</div>
							
							<div class="params-p">
								<ul>
									<li>Общая площадь: <span>53,2</span> м<sup>2</sup></li>
									<li>Этаж: <span>2/10</span></li>
								</ul>
							</div>
							
							<div class="compl-prop">Офис продаж ЖК "Green Sail"</div>
						</div><!--bot-propos-->
					</div><!--item-propos-->
				</div><!--list-propos-->
				
				<div class="more"><a href="#">Показать ещё</a></div><!--more-->
			</div><!--left-propos-->
			
			<div class="right-propos">
				<div class="list-ban">
					<ul>
						<li><a href="#"><img src="<?=SITE_TEMPLATE_PATH?>/images_tmp/ban1.jpg" alt="" /></a></li>
						<li><a href="#"><img src="<?=SITE_TEMPLATE_PATH?>/images_tmp/ban2.jpg" alt="" /></a></li>
						<li><a href="#"><img src="<?=SITE_TEMPLATE_PATH?>/images_tmp/ban3.jpg" alt="" /></a></li>
					</ul>
				</div><!--list-ban-->
			</div><!--right-propos-->
		</div><!--body-propos-->
	</div><!--block-propos-->
	*/ ?>


<? $APPLICATION->IncludeComponent("bitrix:news.list", "news_main", Array(
		"DISPLAY_DATE"                    => "Y",
		"DISPLAY_NAME"                    => "Y",
		"DISPLAY_PICTURE"                 => "Y",
		"DISPLAY_PREVIEW_TEXT"            => "Y",
		"IBLOCK_TYPE"                     => "info",
		"IBLOCK_ID"                       => "1",
		"NEWS_COUNT"                      => "4",
		"SORT_BY1"                        => "ACTIVE_FROM",
		"SORT_ORDER1"                     => "DESC",
		"SORT_BY2"                        => "SORT",
		"SORT_ORDER2"                     => "ASC",
		"FILTER_NAME"                     => "",
		"FIELD_CODE"                      => Array("ID"),
		"PROPERTY_CODE"                   => Array("DESCRIPTION"),
		"CHECK_DATES"                     => "Y",
		"DETAIL_URL"                      => "/info/news/",
		"PREVIEW_TRUNCATE_LEN"            => "",
		"ACTIVE_DATE_FORMAT"              => "d.m.Y",
		"SET_TITLE"                       => "N",
		"SET_BROWSER_TITLE"               => "N",
		"SET_META_KEYWORDS"               => "Y",
		"SET_META_DESCRIPTION"            => "Y",
		"SET_LAST_MODIFIED"               => "Y",
		"INCLUDE_IBLOCK_INTO_CHAIN"       => "N",
		"ADD_SECTIONS_CHAIN"              => "N",
		"HIDE_LINK_WHEN_NO_DETAIL"        => "Y",
		"PARENT_SECTION"                  => "",
		"PARENT_SECTION_CODE"             => "",
		"INCLUDE_SUBSECTIONS"             => "Y",
		"CACHE_TYPE"                      => "A",
		"CACHE_TIME"                      => "3600",
		"CACHE_FILTER"                    => "Y",
		"CACHE_GROUPS"                    => "Y",
		"DISPLAY_TOP_PAGER"               => "N",
		"DISPLAY_BOTTOM_PAGER"            => "Y",
		"PAGER_TITLE"                     => "Новости и мнения о рынке недвижимости",
		"PAGER_SHOW_ALWAYS"               => "Y",
		"PAGER_TEMPLATE"                  => "",
		"PAGER_DESC_NUMBERING"            => "Y",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL"                  => "Y",
		"PAGER_BASE_LINK_ENABLE"          => "Y",
		"SET_STATUS_404"                  => "Y",
		"SHOW_404"                        => "Y",
		"MESSAGE_404"                     => "",
		"PAGER_BASE_LINK"                 => "",
		"PAGER_PARAMS_NAME"               => "arrPager",
	)
); ?>

</div><!--main-->
