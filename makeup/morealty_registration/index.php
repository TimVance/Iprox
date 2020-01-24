<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Регистрация нового пользователя");
?>

			<div class="pad-empty">
				<div class="block-regist tabs-tb">
					<div class="nav-regist nav-tb">
						<ul>
							<li><a href="#">Владелец недвижимости</a></li>
							<li><a href="#">Риелтор</a></li>
						</ul>
					</div><!--nav-regist-->
					
					<div class="free-ads">одно объявление, бесплатно</div>
					
					<div class="body-regist">
						<div class="cont-tab-regist cont-tb">
							<div class="tab-regist tab-tb">
								<div class="data">
									<div class="data-left">
										<div class="tit-regist">Персональная информация</div>
										<div class="bl-regist">
											<div class="field-pass">
												<label>Имя</label>
												<input type="text" />
											</div><!--field-pass-->
											
											<div class="field-pass">
												<label>Отчество</label>
												<input type="text" />
											</div><!--field-pass-->
											
											<div class="field-pass">
												<label>Фамилия</label>
												<input type="text" />
											</div><!--field-pass-->
											
											<div class="field-pass">
												<label>Город</label>
												<div class="sel-on sel-on4">
													<select>
														<option>Город</option>
														<option>Город</option>
														<option>Город</option>
														<option>Город</option>
														<option>Город</option>
													</select>
												</div>
											</div><!--field-pass-->
											
											<div class="field-pass">
												<label>Телефон</label>
												<input type="text" />
											</div><!--field-pass-->
											
											<div class="field-pass">
												<label>E-mail</label>
												<input type="text" />
											</div><!--field-pass-->
											
											<div class="assest-inp assest-inp2 customP">
												<input id="assest" checked type="checkbox" />
												<label for="assest">Не показывать на сайте</label>
											</div>
										</div><!--bl-regist-->
									</div><!--data-left-->
									
									<div class="data-right">
										<div class="tit-regist">Данные для входа</div>
										<div class="bl-regist">
											<div class="field-pass field-pass5">
												<label>Логин</label>
												<input type="text" />
											</div><!--field-pass-->
											
											<div class="field-pass field-pass5">
												<label>Пароль</label>
												<input type="text" />
											</div><!--field-pass-->
											
											<div class="field-pass field-pass5">
												<label>Повторите пароль</label>
												<input type="text" />
											</div><!--field-pass-->
										</div><!--bl-regist-->
									</div><!--data-right-->
								</div><!--data-->
								
								<div class="assest-inp assest-inp3 customP">
									<input id="assest2" checked type="checkbox" />
									<label for="assest2">Я прочитал и принимаю <a href="#">правила публикации</a> объявлений</label>
								</div>
								
								<div class="but-pop but-pop5"><button type="submit">Зарегистрироваться</button></div>
							</div><!--tab-regist-->
							
							<div class="tab-regist tab-tb">
								<div class="data">
									<div class="data-left">
										<div class="tit-regist">Персональная информация риелтора</div>
										<div class="bl-regist">
											<div class="field-pass">
												<label>Имя</label>
												<input type="text" />
											</div><!--field-pass-->
											
											<div class="field-pass">
												<label>Отчество</label>
												<input type="text" />
											</div><!--field-pass-->
											
											<div class="field-pass">
												<label>Фамилия</label>
												<input type="text" />
											</div><!--field-pass-->
											
											<div class="field-pass">
												<label>Город</label>
												<div class="sel-on sel-on4">
													<select>
														<option>Город</option>
														<option>Город</option>
														<option>Город</option>
														<option>Город</option>
														<option>Город</option>
													</select>
												</div>
											</div><!--field-pass-->
											
											<div class="field-pass">
												<label>Телефон</label>
												<input type="text" />
											</div><!--field-pass-->
											
											<div class="field-pass">
												<label>E-mail</label>
												<input type="text" />
											</div><!--field-pass-->
											
											<div class="assest-inp assest-inp2 customP">
												<input id="assest3" checked type="checkbox" />
												<label for="assest3">Не показывать на сайте</label>
											</div>
										</div><!--bl-regist-->
									</div><!--data-left-->
									
									<div class="data-right">
										<div class="tit-regist">Данные для входа риелтора</div>
										<div class="bl-regist">
											<div class="field-pass field-pass5">
												<label>Логин</label>
												<input type="text" />
											</div><!--field-pass-->
											
											<div class="field-pass field-pass5">
												<label>Пароль</label>
												<input type="text" />
											</div><!--field-pass-->
											
											<div class="field-pass field-pass5">
												<label>Повторите пароль</label>
												<input type="text" />
											</div><!--field-pass-->
										</div><!--bl-regist-->
									</div><!--data-right-->
								</div><!--data-->
								
								<div class="assest-inp assest-inp3 customP">
									<input id="assest4" checked type="checkbox" />
									<label for="assest4">Я прочитал и принимаю <a href="#">правила публикации</a> объявлений</label>
								</div>
								
								<div class="but-pop but-pop5"><button type="submit">Зарегистрироваться</button></div>
							</div><!--tab-regist-->
						</div><!--cont-tab-regist-->
					</div><!--body-regist-->
				</div><!--block-regist-->
			</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>