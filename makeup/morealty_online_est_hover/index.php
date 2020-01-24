<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Онлайн оценка квартиры в Сочи");
?>

			<div class="pad-empty">
				<div class="block-calculate">
					<div class="online-cost">
						<div class="t-online-cost"><span>Расположение квартиры</span></div>
						<div class="online-fields">
							<div class="sel-on sel-on1">
								<label>Район</label>
								<select>
									<option>Центральный</option>
									<option>Центральный</option>
									<option>Центральный</option>
									<option>Центральный</option>
									<option>Центральный</option>
								</select>
							</div><!--sel-on-->
							
							<div class="field-on">
								<label>Улица</label>
								<div class="field-die">
									<input type="text" />
									<div class="die-f">
										<ul>
											<li>Неверовского</li>
											<li>Невская</li>
											<li>Неверная</li>
										</ul>
									</div>
								</div>
							</div><!--field-on-->
						</div><!--online-fields-->
					</div><!--online-cost-->
					
					<div class="online-cost">
						<div class="t-online-cost"><span>Информация о квартире</span></div>
						<div class="online-fields">
							<div class="sel-on sel-on2">
								<label>Комнат</label>
								<select>
									<option>1</option>
									<option>2</option>
									<option>3</option>
									<option>4</option>
									<option>5</option>
								</select>
							</div><!--sel-on-->
							
							<div class="field-on field-on2">
								<label>Площадь</label>
								<input type="text" />
							</div><!--field-on-->
							
							<div class="sel-on sel-on2">
								<label>Этаж</label>
								<select>
									<option>1</option>
									<option>2</option>
									<option>3</option>
									<option>4</option>
									<option>5</option>
									<option>6</option>
									<option>7</option>
									<option>8</option>
									<option>9</option>
								</select>
							</div><!--sel-on-->
							
							<div class="sel-on sel-on2 sel-on3">
								<label>Этаж</label>
								<select>
									<option>1</option>
									<option>2</option>
									<option>3</option>
									<option>4</option>
									<option>5</option>
									<option>6</option>
									<option>7</option>
									<option>8</option>
									<option>9</option>
									<option>10</option>
									<option>11</option>
									<option>12</option>
									<option>13</option>
									<option>14</option>
									<option>15</option>
									<option>16</option>
									<option>17</option>
									<option>18</option>
									<option>19</option>
									<option>20</option>
								</select>
							</div><!--sel-on-->
						</div><!--online-fields-->
					</div><!--online-cost-->
					
					<div class="but-pop but-pop4"><button type="submit">Оценить</button></div>
				</div><!--block-calculate-->
			</div>
			
			<div class="calculate-cost">
				<div class="t-online-calc">
					Ориентировочная стоимость Вашей квартиры
					<div class="ico-info ico-info2">
						<i>i</i>
						<div class="die-benef">
							Стоимость рассчитывается как средняя на квартиры с аналогичными параметрами в указанном районе
						</div><!--die-benef-->
					</div>
				</div>
				
				<div class="all-cost">5 880 000 руб.</div>
				
				<div class="slider-cost">
					<div class="value-cost">
						<input type="text" id="amount" readonly>
						<label>руб./м<sup>2</sup></label>
					</div>
					 
					<div id="slider-range-min"></div>
					<div class="scale">
						<div class="star-sc">
							<div class="ot">От</div>
							<div class="value-scale">
								<span>4 780 000 руб.</span>
								49 000 руб./м<sup>2</sup>
							</div>
						</div>
						
						<div class="end-sc">
							<div class="ot">до</div>
							<div class="value-scale">
								<span>6 440 000 руб.</span>
								63 000 руб./м<sup>2</sup>
							</div>
						</div>
					</div><!--scale-->
				</div><!--slider-cost-->
				
				<div class="bottom-cost">
					<div class="but-pop but-addv"><button type="submit">Отправить заявку</button></div>
					
					<div class="link-advertise"><a href="#">Разместить объявление</a></div>
				</div><!--bottom-cost-->
			</div><!--calculate-cost-->
			
			<div class="about-ruvit">
				<div class="img-ruvit"><img src="<?=SITE_TEMPLATE_PATH?>/images/logo-ruvit.png" alt="" /></div>
				
				<div class="desc-ruvit">
					<p>Точную оценку вашей недвижимости вы можете получить у нашего партнёра-эксперта, компании РуВит.</p>
					
					<div class="cont-ruvit">Телефон: <span>8 988-233-98-80</span> E-mail: <a href="mailto:price@ruvit-sochi.ru">price@ruvit-sochi.ru</a>  Сайт: <a target="_blank" href="http://www.ruvit-sochi.ru">www.ruvit-sochi.ru</a></div>
				</div><!--desc-ruvit-->
			</div><!--about-ruvit-->
			
			<div class="b-propos b-propos2">
				<div class="t-emploe">Похожие предложения</div>
				
				<div class="list-agents list-agents2">
					<div class="item-agents">
						<div class="img-agent">
							<div class="gal-agent">
								<div class="slider-agent">
									<div class="slide"><img src="<?=SITE_TEMPLATE_PATH?>/images_tmp/img-propos.jpg" alt="" /></div><!--slide-->
									<div class="slide"><img src="<?=SITE_TEMPLATE_PATH?>/images_tmp/img-propos2.jpg" alt="" /></div><!--slide-->
								</div><!--slider-agent-->
								<div class="nums-photo"><span>12</span></div>
							</div>
						</div>
						<div class="desc-agents">
							<div class="but-favor"><a href="#"></a></div>
							<div class="t-agents"><a href="#">1-комнатная квартира 30.8 м² в АК "Горизонт"</a></div>
							<div class="adress-agent">Сочи, Адлерский район, Адлер-центр, ул. Просвещения, 24</div>
							
							<div class="info-agents">
								<div class="params-propos">
									<div class="list-prm">
										<ul>
											<li>Общая площадь: <strong>30,8</strong> м<sup>2</sup></li>
											<li>Этаж: <strong>5/8</strong></li>
											<li>Срок сдачи: <strong>сдан</strong></li>
											<li>До моря: <strong>200 м</strong></li>
											<li>Отделка: <strong>с отделкой и мебелью</strong></li>
										</ul>
									</div><!--list-prm-->
									
									<div class="name-r">ЖК "Орион-3"</div>
								</div><!--params-propos-->
								
								<div class="func-propos">
									<div class="price-propos">
										<span>2 772 000 Руб.</span>
										81 000 Руб./м<sup>2</sup>
									</div>
									<div class="more-but"><a href="#">Подробнее об объекте</a></div>
								</div><!--func-propos-->
							</div><!--info-agents-->
						</div><!--desc-agents-->
					</div><!--item-agents-->
					
					<div class="item-agents">
						<div class="img-agent">
							<div class="gal-agent">
								<div class="slider-agent">
									<div class="slide"><img src="<?=SITE_TEMPLATE_PATH?>/images_tmp/img-propos2.jpg" alt="" /></div><!--slide-->
									<div class="slide"><img src="<?=SITE_TEMPLATE_PATH?>/images_tmp/img-propos.jpg" alt="" /></div><!--slide-->
								</div><!--slider-agent-->
								<div class="nums-photo"><span>8</span></div>
							</div>
						</div>
						<div class="desc-agents">
							<div class="but-favor active"><a href="#"></a></div>
							<div class="t-agents"><a href="#">1-комнатная квартира 30.8 м² в АК "Горизонт"</a></div>
							<div class="adress-agent">Сочи, Адлерский район, Адлер-центр, ул. Просвещения, 24</div>
							
							<div class="info-agents">
								<div class="params-propos">
									<div class="list-prm">
										<ul>
											<li>Общая площадь: <strong>30,8</strong> м<sup>2</sup></li>
											<li>Этаж: <strong>5/8</strong></li>
											<li>Срок сдачи: <strong>сдан</strong></li>
											<li>До моря: <strong>200 м</strong></li>
											<li>Отделка: <strong>с отделкой и мебелью</strong></li>
										</ul>
									</div><!--list-prm-->
									
									<div class="name-r">ЖК "Орион-3"</div>
								</div><!--params-propos-->
								
								<div class="func-propos">
									<div class="price-propos">
										<span>2 772 000 Руб.</span>
										81 000 Руб./м<sup>2</sup>
									</div>
									<div class="more-but"><a href="#">Подробнее об объекте</a></div>
								</div><!--func-propos-->
							</div><!--info-agents-->
						</div><!--desc-agents-->
					</div><!--item-agents-->
					
					<div class="item-agents">
						<div class="img-agent">
							<div class="gal-agent">
								<div class="slider-agent">
									<div class="slide"><img src="<?=SITE_TEMPLATE_PATH?>/images_tmp/img-propos.jpg" alt="" /></div><!--slide-->
									<div class="slide"><img src="<?=SITE_TEMPLATE_PATH?>/images_tmp/img-propos2.jpg" alt="" /></div><!--slide-->
								</div><!--slider-agent-->
								<div class="nums-photo"><span>12</span></div>
							</div>
						</div>
						<div class="desc-agents">
							<div class="but-favor"><a href="#"></a></div>
							<div class="t-agents"><a href="#">1-комнатная квартира 30.8 м² в АК "Горизонт"</a></div>
							<div class="adress-agent">Сочи, Адлерский район, Адлер-центр, ул. Просвещения, 24</div>
							
							<div class="info-agents">
								<div class="params-propos">
									<div class="list-prm">
										<ul>
											<li>Общая площадь: <strong>30,8</strong> м<sup>2</sup></li>
											<li>Этаж: <strong>5/8</strong></li>
											<li>Срок сдачи: <strong>сдан</strong></li>
											<li>До моря: <strong>200 м</strong></li>
											<li>Отделка: <strong>с отделкой и мебелью</strong></li>
										</ul>
									</div><!--list-prm-->
									
									<div class="name-r">ЖК "Орион-3"</div>
								</div><!--params-propos-->
								
								<div class="func-propos">
									<div class="price-propos">
										<span>2 772 000 Руб.</span>
										81 000 Руб./м<sup>2</sup>
									</div>
									<div class="more-but"><a href="#">Подробнее об объекте</a></div>
								</div><!--func-propos-->
							</div><!--info-agents-->
						</div><!--desc-agents-->
					</div><!--item-agents-->
					
					<div class="item-agents">
						<div class="img-agent">
							<div class="gal-agent">
								<div class="slider-agent">
									<div class="slide"><img src="<?=SITE_TEMPLATE_PATH?>/images_tmp/img-propos2.jpg" alt="" /></div><!--slide-->
									<div class="slide"><img src="<?=SITE_TEMPLATE_PATH?>/images_tmp/img-propos.jpg" alt="" /></div><!--slide-->
								</div><!--slider-agent-->
								<div class="nums-photo"><span>8</span></div>
							</div>
						</div>
						<div class="desc-agents">
							<div class="but-favor active"><a href="#"></a></div>
							<div class="t-agents"><a href="#">1-комнатная квартира 30.8 м² в АК "Горизонт"</a></div>
							<div class="adress-agent">Сочи, Адлерский район, Адлер-центр, ул. Просвещения, 24</div>
							
							<div class="info-agents">
								<div class="params-propos">
									<div class="list-prm">
										<ul>
											<li>Общая площадь: <strong>30,8</strong> м<sup>2</sup></li>
											<li>Этаж: <strong>5/8</strong></li>
											<li>Срок сдачи: <strong>сдан</strong></li>
											<li>До моря: <strong>200 м</strong></li>
											<li>Отделка: <strong>с отделкой и мебелью</strong></li>
										</ul>
									</div><!--list-prm-->
									
									<div class="name-r">ЖК "Орион-3"</div>
								</div><!--params-propos-->
								
								<div class="func-propos">
									<div class="price-propos">
										<span>2 772 000 Руб.</span>
										81 000 Руб./м<sup>2</sup>
									</div>
									<div class="more-but"><a href="#">Подробнее об объекте</a></div>
								</div><!--func-propos-->
							</div><!--info-agents-->
						</div><!--desc-agents-->
					</div><!--item-agents-->
				</div><!--list-agents-->
				
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
			</div><!--b-propos-->

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>