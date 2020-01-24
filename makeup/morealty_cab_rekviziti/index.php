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
			
			<div class="b-pass">
				<div class="change-pass b-rekviz">
					<div class="bl-rekviz">
						<div class="t-rekviz">Информация о юридическом лице</div>
						<div class="field-pass field-pass2">
							<label>Полное название <span>*</span></label>
							<input type="text" />
						</div><!--field-pass-->
						
						<div class="field-pass field-pass2">
							<label>Сокращённое название</label>
							<input type="text" />
						</div><!--field-pass-->
						
						<div class="field-pass field-pass2">
							<label>Юридический адрес <span>*</span></label>
							<input type="text" />
						</div><!--field-pass-->
						
						<div class="field-pass field-pass3">
							<label>ОГРН <span>*</span></label>
							<input type="text" />
						</div><!--field-pass-->
						
						<div class="field-pass field-pass3">
							<label>ИНН <span>*</span></label>
							<input type="text" />
						</div><!--field-pass-->
						
						<div class="field-pass field-pass3">
							<label>КПП <span>*</span></label>
							<input type="text" />
						</div><!--field-pass-->
						
						<div class="field-pass field-pass2">
							<label>ФИО директора <span>*</span></label>
							<input type="text" />
						</div><!--field-pass-->
					</div><!--bl-rekviz-->
					
					<div class="bl-rekviz">
						<div class="t-rekviz">Банковские реквизиты</div>
						<div class="field-pass field-pass4">
							<label>Расчетный счёт <span>*</span></label>
							<input type="text" />
						</div><!--field-pass-->
						
						<div class="field-pass field-pass2">
							<label>Наименование банка <span>*</span></label>
							<input type="text" />
						</div><!--field-pass-->
						
						<div class="field-pass field-pass3">
							<label>Кор. счёт <span>*</span></label>
							<input type="text" />
						</div><!--field-pass-->
						
						<div class="field-pass field-pass3">
							<label>БИК <span>*</span></label>
							<input type="text" />
						</div><!--field-pass-->
					</div><!--bl-rekviz-->
				</div><!--change-pass-->
				
				<div class="but-pop but-pop4"><button type="submit">Сохранить</button></div>
			</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>