<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Личный кабинет");
?>

			<div class="info-lk">
				<ul>
					<li><a href="#">Олег Александрович Коротких</a></li>
					<li><span>+7 (925) 658-68-98</span></li>
				</ul>
				<div class="img-lk"><a href="#"><img src="<?=SITE_TEMPLATE_PATH?>/images_tmp/img-lk.jpg" alt="" /></a></div>
			</div><!--info-lk-->
			
			<?$APPLICATION->IncludeFile('/include_areas/cab_menu.php', false, array('MODE' => 'php'))?>
			
			<div class="sort">
				<p>Сортировка:</p>
				<ul>
					<li><span>Стоимость</span></li>
					<li><a href="#">Название</a></li>
				</ul>
			</div><!--sort-->
			
			<div class="list-pakets">
				<ul>
					<li>
						<div class="tit-pakets"><p>Комбо 100</p></div>
						<div class="num-pakets"><strong>25</strong> квартир, <strong>25</strong> участков, <strong>25</strong> домов, <strong>25</strong> коммерческая недвижимость</div>
						<div class="price-pakets">
							90 000 руб.
						</div>
						<div class="condit-tarif">
							<div class="shelf">Действует 1 месяц с момента покупки</div>
						</div>
						<div class="but-paket"><a href="#">Купить</a></div>
					</li>
					<li>
						<div class="tit-pakets"><p>Комбо 100</p></div>
						<div class="num-pakets"><strong>25</strong> квартир, <strong>25</strong> участков, <strong>25</strong> домов, <strong>25</strong> коммерческая недвижимость</div>
						<div class="price-pakets">
							90 000 руб.
						</div>
						<div class="condit-tarif">
							<div class="shelf">Действует 1 месяц с момента покупки</div>
						</div>
						<div class="but-paket"><a href="#">Купить</a></div>
					</li>
					<li>
						<div class="tit-pakets"><p>Комбо 100</p></div>
						<div class="num-pakets"><strong>25</strong> квартир, <strong>25</strong> участков, <strong>25</strong> домов, <strong>25</strong> коммерческая недвижимость</div>
						<div class="price-pakets">
							90 000 руб.
						</div>
						<div class="condit-tarif">
							<div class="shelf">Действует 1 месяц с момента покупки</div>
						</div>
						<div class="but-paket"><a href="#">Купить</a></div>
					</li>
					<li>
						<div class="tit-pakets"><p>Комбо 100</p></div>
						<div class="num-pakets"><strong>25</strong> квартир, <strong>25</strong> участков, <strong>25</strong> домов, <strong>25</strong> коммерческая недвижимость</div>
						<div class="price-pakets">
							90 000 руб.
						</div>
						<div class="condit-tarif">
							<div class="shelf">Действует 1 месяц с момента покупки</div>
						</div>
						<div class="but-paket"><a href="#">Купить</a></div>
					</li>
					<li>
						<div class="tit-pakets"><p>Комбо 100</p></div>
						<div class="num-pakets"><strong>25</strong> квартир, <strong>25</strong> участков, <strong>25</strong> домов, <strong>25</strong> коммерческая недвижимость</div>
						<div class="price-pakets">
							90 000 руб.
						</div>
						<div class="condit-tarif">
							<div class="shelf">Действует 1 месяц с момента покупки</div>
						</div>
						<div class="but-paket"><a href="#">Купить</a></div>
					</li>
					<li>
						<div class="tit-pakets"><p>Комбо 100</p></div>
						<div class="num-pakets"><strong>25</strong> квартир, <strong>25</strong> участков, <strong>25</strong> домов, <strong>25</strong> коммерческая недвижимость</div>
						<div class="price-pakets">
							90 000 руб.
						</div>
						<div class="condit-tarif">
							<div class="shelf">Действует 1 месяц с момента покупки</div>
						</div>
						<div class="but-paket"><a href="#">Купить</a></div>
					</li>
					<li>
						<div class="tit-pakets"><p>Комбо 100</p></div>
						<div class="num-pakets"><strong>25</strong> квартир, <strong>25</strong> участков, <strong>25</strong> домов, <strong>25</strong> коммерческая недвижимость</div>
						<div class="price-pakets">
							90 000 руб.
						</div>
						<div class="condit-tarif">
							<div class="shelf">Действует 1 месяц с момента покупки</div>
						</div>
						<div class="but-paket"><a href="#">Купить</a></div>
					</li>
					<li>
						<div class="tit-pakets"><p>Комбо 100</p></div>
						<div class="num-pakets"><strong>25</strong> квартир, <strong>25</strong> участков, <strong>25</strong> домов, <strong>25</strong> коммерческая недвижимость</div>
						<div class="price-pakets">
							90 000 руб.
						</div>
						<div class="condit-tarif">
							<div class="shelf">Действует 1 месяц с момента покупки</div>
						</div>
						<div class="but-paket"><a href="#">Купить</a></div>
					</li>
				</ul>
			</div><!--list-pakets-->
			
			<div class="pages">
				<p><a href="#">Предыдущая</a></p>
				<ul>
					<li><span>1</span></li>
					<li>...</li>
					<li><a href="#">5</a></li>
					<li><a href="#">6</a></li>
					<li><a href="#">7</a></li>
					<li><a href="#">8</a></li>
					<li><a href="#">9</a></li>
					<li><a href="#">10</a></li>
					<li><a href="#">11</a></li>
					<li>...</li>
					<li><a href="#">897</a></li>
				</ul>
				<p><a href="#">Следующая</a></p>
			</div><!--pages-->

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>