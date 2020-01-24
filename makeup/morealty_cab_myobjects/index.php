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
			
			<div class="tabs-tb lich-obj">
				<div class="add-advice"><a class="inline" href="#pop7">Добавить объявление</a></div>
				
				<div class="nav-regist nav-tb">
					<ul>
						<li class="active"><a href="#">Продажа</a></li>
						<li><a href="#">Аренда</a></li>
					</ul>
				</div><!--nav-regist-->
				
				<div class="cont-tb">
					<div class="tab-tb">
						<div class="menu-tages">
							<ul>	
								<li><a href="#">Квартиры в новостройках <span>(25)</span></a></li>       
								<li><a href="#">Дома, коттеджи, таунхаусы <span>(10)</span></a></li>         
								<li><a href="#">Гаражи и эллинги <span>(3)</span></a></li>          
								<li><a href="#">Земельные участки <span>(19)</span></a></li>
								<li><a href="#">Коммерческая недвижимость <span>(25)</span></a></li>        
								<li><a href="#">Инвестиционные проекты <span>(6)</span></a></li>
							</ul>
						</div><!--menu-tags-->
						
						<div class="control-view"><a href="#">Активировать групповое управление публикацией</a></div>
						
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
									<div class="ico-view"><a href="#"></a></div>
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
											<div class="more-but"><a href="#">Редактировать</a></div>
										</div><!--func-propos-->
									</div><!--info-agents-->
								</div><!--desc-agents-->
							</div><!--item-agents-->
							
							<div class="item-agents noactive">
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
									<div class="ico-view"><a href="#"></a></div>
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
											<div class="more-but"><a href="#">Редактировать</a></div>
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
									<div class="ico-view"><a href="#"></a></div>
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
											<div class="more-but"><a href="#">Редактировать</a></div>
										</div><!--func-propos-->
									</div><!--info-agents-->
								</div><!--desc-agents-->
							</div><!--item-agents-->
							
							<div class="item-agents noactive">
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
									<div class="ico-view"><a href="#"></a></div>
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
											<div class="more-but"><a href="#">Редактировать</a></div>
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
					</div><!--tab-tb-->
					
					<div class="tab-tb">
						<div class="menu-tages">
							<ul>	
								<li><a href="#">Квартиры в новостройках <span>(25)</span></a></li>       
								<li><a href="#">Дома, коттеджи, таунхаусы <span>(10)</span></a></li>         
								<li><a href="#">Гаражи и эллинги <span>(3)</span></a></li>          
								<li><a href="#">Земельные участки <span>(19)</span></a></li>
								<li><a href="#">Коммерческая недвижимость <span>(25)</span></a></li>
							</ul>
						</div><!--menu-tags-->
						
						<div class="control-view"><a href="#">Активировать групповое управление публикацией</a></div>
						
						<div class="list-agents list-agents2">
							<div class="item-agents noactive">
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
									<div class="ico-view"><a href="#"></a></div>
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
											<div class="more-but"><a href="#">Редактировать</a></div>
										</div><!--func-propos-->
									</div><!--info-agents-->
								</div><!--desc-agents-->
							</div><!--item-agents-->
							
							<div class="item-agents noactive">
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
									<div class="ico-view"><a href="#"></a></div>
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
											<div class="more-but"><a href="#">Редактировать</a></div>
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
									<div class="ico-view"><a href="#"></a></div>
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
											<div class="more-but"><a href="#">Редактировать</a></div>
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
					</div><!--tab-tb-->
				</div><!--cont-tb-->
			</div><!--tabs-tb-->

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>