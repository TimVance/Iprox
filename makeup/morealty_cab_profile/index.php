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
				<div class="data prof">
					<div class="data-left">
						<div class="tit-regist tit-regist2">Персональная информация</div>
						<div class="bl-regist">
							<div class="field-pass">
								<label>Имя<span>*</span></label>
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
								<label>Телефон<span>*</span></label>
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
							
							<div class="field-pass field-skype">
								<label>Skype</label>
								<input type="text" />
							</div><!--field-pass-->
						</div><!--bl-regist-->
					</div><!--data-left-->
					
					<div class="data-right">
						<div class="tit-regist tit-regist2">Фото</div>
						<div class="down-photo">
							<div class="cont-photo"><img src="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" alt="" /></div>
							
							<div class="func-photo">
								<div class="link-photo"><a href="#">Добавить фото</a></div>
								<p>Объявление с фото риэлтора вызывает больший интерес и доверие у посетителей сайта.</p>
							</div><!--func-photo-->
						</div><!--down-photo-->
						
						<div class="textar-about">
							<label>Обо мне</label>
							<textarea></textarea>
						</div>
					</div><!--data-right-->
				</div><!--data-->
				
				<div class="block-assest">
					<div class="assest-inp assest-inp4 customP">
						<input id="assest2" checked type="checkbox" />
						<label for="assest2">Не получать в личном кабинете сообщения от посетителей сайта</label>
					</div>
					
					<div class="assest-inp assest-inp4 customP">
						<input id="assest3" checked type="checkbox" />
						<label for="assest3">Не получать e-mail уведомлений о сообщениях</label>
					</div>
				</div>
				
				<div class="but-pop but-pop4"><button type="submit">Сохранить</button></div>
			</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>