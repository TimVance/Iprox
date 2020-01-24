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
			
			<div class="pad-empty">
				<div class="info-object">
					<div class="adress-obj">
						<div class="t-adress">Адрес объекта</div>
						
						<div class="line-select">
							<label>Регион <span>*</span></label>
							
							<div class="sel-on sel-line sel-line1">
								<select>
									<option>Сочи</option>
									<option>Адлер</option>
									<option>Сочи</option>
									<option>Адлер</option>
								</select>
							</div><!--sel-on-->
						</div><!--line-select-->
						
						<div class="line-select">
							<label>Новостройка</label>
							
							<div class="sel-on sel-line sel-line2">
								<select>
									<option>Выберите новостройку из списка</option>
									<option>Новострой1</option>
									<option>Новострой2</option>
									<option>Новострой3</option>
								</select>
							</div><!--sel-on-->
							
							<div class="ico-info">
								<i>i</i>
								<div class="die-benef">
									подсказка
								</div><!--die-benef-->
							</div>
						</div><!--line-select-->
						
						<div class="ln-map"><a href="#">Найти на карте</a></div>
					</div><!--adress-obj-->
					
					<div class="map-object"><img src="<?=SITE_TEMPLATE_PATH?>/images_tmp/map-obj.jpg" alt="" /></div>
					
					<div class="new-builds">
						<div class="t-adress">Новостройка</div>
						<div class="list-agents list-agents2">
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
									<div class="t-agents"><a href="#">1-комнатная квартира 30.8 м² в АК "Горизонт"</a></div>
									<div class="adress-agent">Сочи, Адлерский район, Адлер-центр, ул. Просвещения, 24</div>
									
									<div class="info-agents">
										<div class="params-propos">
											<div class="list-prm">
												<ul>
													<li><span>Класс:</span> <strong>бизнес</strong></li>
													<li><span>Тип: здания</span> <strong>монолитное</strong></li>
													<li><span>Этажей:</span> <strong>12</strong></li>
													<li><span>Парковка:</span> <strong>подземная</strong></li>
													<li><span>Лифт:</span> <strong>есть</strong></li>
													<li><span>Расстояние до моря:</span> <strong>2 км</strong></li>
												</ul>
											</div><!--list-prm-->
										</div><!--params-propos-->
										
										<div class="more-but"><a href="#">Подробнее об объекте</a></div>
									</div><!--info-agents-->
								</div><!--desc-agents-->
							</div><!--item-agents-->
						</div><!--list-agents-->
					</div><!--new-builds-->
						
					<div class="adress-obj">
						<div class="t-adress">Квартира</div>
						
						<div class="line-form">
							<div class="line-select">
								<label>Тип <span>*</span></label>
								
								<div class="sel-on sel-line sel-line3">
									<select>
										<option>1-комнатная</option>
										<option>2-комнатная</option>
										<option>3-комнатная</option>
										<option>4-комнатная</option>
									</select>
								</div><!--sel-on-->
							</div><!--line-select-->
							
							<div class="line-field">
								<label>Этаж <span>*</span></label>
								<input type="text" />
							</div><!--line-field-->
							
							<div class="ico-info">
								<i>i</i>
								<div class="die-benef">
									подсказка
								</div><!--die-benef-->
							</div>
						</div><!--line-form-->
						
						<div class="line-form">
							<div class="line-field">
								<label>Площадь общая <span>*</span></label>
								<input type="text" />
								<i>м<sup>2</sup></i>
							</div><!--line-field-->
							
							<div class="line-field">
								<label>Жилая</label>
								<input type="text" />
								<i>м<sup>2</sup></i>
							</div><!--line-field-->
							
							<div class="line-field">
								<label>Кухня</label>
								<input type="text" />
								<i>м<sup>2</sup></i>
							</div><!--line-field-->
						</div><!--line-form-->
						
						<div class="line-form line-form2">
							<div class="line-select">
								<label>Отделка</label>
								
								<div class="sel-on sel-line sel-line4">
									<select>
										<option>Серая</option>
										<option>Предчистовая</option>
										<option>Белая</option>
									</select>
								</div><!--sel-on-->
							</div><!--line-select-->
							
							<div class="line-select">
								<label>Плита</label>
								
								<div class="sel-on sel-line sel-line4">
									<select>
										<option></option>
										<option>Электрическая</option>
										<option>Газовая</option>
									</select>
								</div><!--sel-on-->
							</div><!--line-select-->
							
							<div class="line-select">
								<label>Санузел</label>
								
								<div class="sel-on sel-line sel-line4">
									<select>
										<option></option>
										<option>Раздельный</option>
										<option>Совмещенный</option>
									</select>
								</div><!--sel-on-->
							</div><!--line-select-->
						</div><!--line-form-->
						
						<div class="line-check">
							<ul>
								<li class="customP">
									<input id="check1" type="checkbox" />
									<label for="check1">Лоджия</label>
								</li>
								<li class="customP">
									<input id="check2" type="checkbox" />
									<label for="check2">Балкон</label>
								</li>
								<li class="customP">
									<input id="check3" type="checkbox" />
									<label for="check3">Телефон</label>
								</li>
							</ul>
						</div><!--line-check-->
					</div><!--adress-obj-->
					
					<div class="adress-obj adress-obj3">
						<div class="t-adress">Цена</div>
						
						<div class="radio">
							<p>Стоимость:</p>
							<ul>
								<li class="defaultP">
									<input id="radio1" checked name="radio" type="radio" />
									<label for="radio1">Общая</label>
								</li>
								<li class="defaultP">
									<input id="radio2" name="radio" type="radio" />
									<label for="radio2">за м<sup>2</sup></label>
								</li>
							</ul>
						</div>
						
						<div class="line-form">
							<div class="line-field field-bold">
								<label>Сумма <span>*</span></label>
								<input type="text" />
							</div><!--line-field-->
							
							<div class="line-select line-select2">
								<div class="sel-on sel-line sel-line4">
									<select>
										<option>Руб.</option>
										<option>Евро</option>
										<option>Дол.</option>
									</select>
								</div><!--sel-on-->
							</div><!--line-select-->
							
						</div><!--line-form-->
						
						<div class="line-check line-check2">
							<ul>
								<li class="customP">
									<input id="check4" checked type="checkbox" />
									<label for="check4">Продается с мебелью</label>
								</li>
								<li class="customP">
									<input id="check5" type="checkbox" />
									<label for="check5">Возможна ипотека</label>
								</li>
							</ul>
						</div><!--line-check-->
					</div><!--adress-obj-->
					
					<div class="more-info">
						<div class="t-adress">Дополнительная информация</div>
						
						<div class="textar-info"><textarea></textarea></div>
					</div><!--more-info-->
				</div><!--info-object-->
				
				<div class="but-pop but-pop4">
					<button type="submit">Сохранить</button>
					
					<div class="del-object"><a href="#">Удалить объект</a></div>
				</div>
			</div><!--pad-empty-->

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>