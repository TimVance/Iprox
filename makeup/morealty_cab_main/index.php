<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Личный кабинет");
?>

			<div class="info-lk">
				<ul>
					<li><a href="#">Олег Александрович Коротких</a></li>
					<li><span>+7 (925) 658-68-98</span></li>
				</ul>
				<div class="img-lk"><a href="#"><img src="images_tmp/img-lk.jpg" alt="" /></a></div>
			</div><!--info-lk-->
			
			<? /*<div class="menu-cab">
				<ul>
					<li class="active"><span><i>Финансы</i></span></li> 
					<li><a href="#"><i>Пакеты услуг</i></a></li>          
					<li><a href="#"><i>Мои объекты</i> <b>57</b></a></li>           
					<li><a href="#"><i>Профиль</i></a></li>          
					<li><a href="#"><i>Смена пароля</i></a></li>            
					<li><a href="#"><i>Реквизиты</i></a></li>
				</ul>
			</div><!--manu-cab-->
			*/?>
			<?$APPLICATION->IncludeFile('/include_areas/cab_menu.php', false, array('MODE' => 'php'))?>
			<div class="b-balans">
				<div class="balans">Баланс: <span>96 800 руб.</span></div>
				
				<div class="add-balans"><a class="inline" href="#pop9">Пополнить счёт</a></div>
			</div><!--b-balans-->
			
			<div class="b-history">
				<div class="history-pay"><a href="#">История платежей</a></div>
				
				<div class="table-history">
					<table>
						<tr>
							<th>Дата</th>
							<th>Платежный документ</th>
							<th>Сумма</th>
							<th>Статус</th>
						</tr>
						<tr>
							<td>02.09.2016</td>
							<td><strong><a href="#">№ 15465 АН 4654</a></strong></td>
							<td>15 000 руб.</td>
							<td>Не оплачен</td>
						</tr>
						<tr>
							<td>02.09.2016</td>
							<td><strong><a href="#">№ 15465 АН 4654</a></strong></td>
							<td>15 000 руб.</td>
							<td><strong>Зачислен</strong></td>
						</tr>
						<tr>
							<td>02.09.2016</td>
							<td><strong><a href="#">№ 15465 АН 4654</a></strong></td>
							<td>15 000 руб.</td>
							<td><strong>Зачислен</strong></td>
						</tr>
						<tr>
							<td>02.09.2016</td>
							<td><strong><a href="#">№ 15465 АН 4654</a></strong></td>
							<td>15 000 руб.</td>
							<td><strong>Зачислен</strong></td>
						</tr>
						<tr>
							<td>02.09.2016</td>
							<td><strong><a href="#">№ 15465 АН 4654</a></strong></td>
							<td>15 000 руб.</td>
							<td><strong>Зачислен</strong></td>
						</tr>
						<tr>
							<td>02.09.2016</td>
							<td><strong><a href="#">№ 15465 АН 4654</a></strong></td>
							<td>15 000 руб.</td>
							<td><strong>Зачислен</strong></td>
						</tr>
						<tr>
							<td>02.09.2016</td>
							<td><strong><a href="#">№ 15465 АН 4654</a></strong></td>
							<td>15 000 руб.</td>
							<td><strong>Зачислен</strong></td>
						</tr>
						<tr>
							<td>02.09.2016</td>
							<td><strong><a href="#">№ 15465 АН 4654</a></strong></td>
							<td>15 000 руб.</td>
							<td><strong>Зачислен</strong></td>
						</tr>
						<tr>
							<td>02.09.2016</td>
							<td><strong><a href="#">№ 15465 АН 4654</a></strong></td>
							<td>15 000 руб.</td>
							<td><strong>Зачислен</strong></td>
						</tr>
						<tr>
							<td>02.09.2016</td>
							<td><strong><a href="#">№ 15465 АН 4654</a></strong></td>
							<td>15 000 руб.</td>
							<td><strong>Зачислен</strong></td>
						</tr>
						<tr>
							<td>02.09.2016</td>
							<td><strong><a href="#">№ 15465 АН 4654</a></strong></td>
							<td>15 000 руб.</td>
							<td><strong>Зачислен</strong></td>
						</tr>
					</table>
				</div><!--table-history-->
			</div><!--b-history-->
			
			<div class="b-pakets">
				<div class="t-paket">Мои пакеты</div>
				
				<div class="nav-pakets">
					<div class="sort-object">
						<p>Мои объекты:</p>
						<ul>
							<li>Квартиры <span>27</span></li>   
							<li>Дома <span>12</span></li>       
							<li>Участки <span>3</span></li> 
						</ul>
					</div><!--sort-object-->
					
					<div class="buy-pakets"><a href="#">Купить пакеты</a></div>
				</div><!--nav-pakets-->
			</div><!--b-pakets-->
			
			<div class="list-pakets">
				<ul>
					<li>
						<div class="tit-pakets"><p>Комбо 100</p></div>
						<div class="num-pakets"><strong>25</strong> квартир, <strong>25</strong> участков, <strong>25</strong> домов, <strong>25</strong> коммерческая недвижимость</div>
						<div class="price-pakets">
							45 000 руб.
						</div>
						<div class="condit-tarif">
							<div class="shelf">Активен с 05.10.2016 по 05.11.2016</div>
							<div class="days-off">Осталось 25 дней</div>
						</div>
					</li>
					<li>
						<div class="tit-pakets"><p>Мульти 200</p></div>
						<div class="num-pakets"><strong>50</strong> квартир, <strong>50</strong> участков, <strong>50</strong> домов, <strong>50</strong> коммерческая недвижимость</div>
						<div class="price-pakets">
							45 000 руб.
						</div>
						<div class="condit-tarif">
							<div class="shelf">Активен с 05.10.2016 по 05.11.2016</div>
							<div class="days-off">Осталось 19 дней</div>
						</div>
					</li>
					<li>
						<div class="tit-pakets"><p>Коммерческий 50</p></div>
						<div class="num-pakets"><strong>50</strong> коммерческая недвижимость</div>
						<div class="price-pakets">
							45 000 руб.
						</div>
						<div class="condit-tarif">
							<div class="shelf">Активен с 05.10.2016 по 05.11.2016</div>
							<div class="days-off">Осталось 19 дней</div>
						</div>
					</li>
					<li>
						<div class="tit-pakets"><p>Квартира 20</p></div>
						<div class="num-pakets"><strong>25</strong> квартир, <strong>25</strong> участков, <strong>25</strong> домов, <strong>25</strong> коммерческая недвижимость</div>
						<div class="price-pakets">
							45 000 руб.
						</div>
						<div class="condit-tarif">
							<div class="shelf">Активен с 05.10.2016 по 05.11.2016</div>
							<div class="days-off">Осталось 19 дней</div>
						</div>
					</li>
					<li>
						<div class="tit-pakets"><p>Участок 50</p></div>
						<div class="num-pakets"><strong>50</strong> участков</div>
						<div class="price-pakets">
							45 000 руб.
						</div>
						<div class="condit-tarif">
							<div class="shelf">Активен с 05.10.2016 по 05.11.2016</div>
							<div class="days-off">Осталось 19 дней</div>
						</div>
					</li>
					<li>
						<div class="tit-pakets"><p>Квартира 10</p></div>
						<div class="num-pakets"><strong>25</strong> квартир, <strong>25</strong> участков, <strong>25</strong> домов, <strong>25</strong> коммерческая недвижимость</div>
						<div class="price-pakets">
							45 000 руб.
							<s>40 000 руб.</s>
						</div>
						<div class="condit-tarif">
							<div class="shelf">Активен с 05.10.2016 по 05.11.2016</div>
							<div class="days-off">Осталось 19 дней</div>
						</div>
						<div class="but-paket"><a href="#">Продлить</a></div>
					</li>
					<li class="no-active">
						<div class="tit-pakets"><p>Коммерческий 50</p></div>
						<div class="num-pakets"><strong>25</strong> квартир, <strong>25</strong> участков, <strong>25</strong> домов, <strong>25</strong> коммерческая недвижимость</div>
						<div class="price-pakets">
							45 000 руб.
						</div>
						<div class="condit-tarif">
							<div class="shelf">Активен с 05.10.2016 по 05.11.2016</div>
							<div class="days-off">Осталось 19 дней</div>
						</div>
					</li>
					<li class="no-active">
						<div class="tit-pakets"><p>Квартира 20</p></div>
						<div class="num-pakets"><strong>25</strong> квартир, <strong>25</strong> участков, <strong>25</strong> домов, <strong>25</strong> коммерческая недвижимость</div>
						<div class="price-pakets">
							45 000 руб.
						</div>
						<div class="condit-tarif">
							<div class="shelf">Активен с 05.10.2016 по 05.11.2016</div>
							<div class="days-off">Осталось 19 дней</div>
						</div>
						<div class="archive">в архиве</div>
					</li>
					<li class="no-active">
						<div class="tit-pakets"><p>Коммерческий 50</p></div>
						<div class="num-pakets"><strong>25</strong> квартир, <strong>25</strong> участков, <strong>25</strong> домов, <strong>25</strong> коммерческая недвижимость</div>
						<div class="price-pakets">
							45 000 руб.
						</div>
						<div class="condit-tarif">
							<div class="shelf">Активен с 05.10.2016 по 05.11.2016</div>
							<div class="days-off">Осталось 19 дней</div>
						</div>
					</li>
					<li class="no-active">
						<div class="tit-pakets"><p>Квартира 20</p></div>
						<div class="num-pakets"><strong>25</strong> квартир, <strong>25</strong> участков, <strong>25</strong> домов, <strong>25</strong> коммерческая недвижимость</div>
						<div class="price-pakets">
							45 000 руб.
						</div>
						<div class="condit-tarif">
							<div class="shelf">Активен с 05.10.2016 по 05.11.2016</div>
							<div class="days-off">Осталось 19 дней</div>
						</div>
						<div class="archive">в архиве</div>
					</li>
					<li class="no-active">
						<div class="tit-pakets"><p>Коммерческий 50</p></div>
						<div class="num-pakets"><strong>25</strong> квартир, <strong>25</strong> участков, <strong>25</strong> домов, <strong>25</strong> коммерческая недвижимость</div>
						<div class="price-pakets">
							45 000 руб.
						</div>
						<div class="condit-tarif">
							<div class="shelf">Активен с 05.10.2016 по 05.11.2016</div>
							<div class="days-off">Осталось 19 дней</div>
						</div>
					</li>
					<li class="no-active">
						<div class="tit-pakets"><p>Квартира 20</p></div>
						<div class="num-pakets"><strong>25</strong> квартир, <strong>25</strong> участков, <strong>25</strong> домов, <strong>25</strong> коммерческая недвижимость</div>
						<div class="price-pakets">
							45 000 руб.
						</div>
						<div class="condit-tarif">
							<div class="shelf">Активен с 05.10.2016 по 05.11.2016</div>
							<div class="days-off">Осталось 19 дней</div>
						</div>
						<div class="archive">в архиве</div>
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