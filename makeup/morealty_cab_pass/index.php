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
				<div class="change-pass">
					<div class="field-pass">
						<label>Текущий пароль <span>*</span></label>
						<input type="text" />
					</div><!--field-pass-->
					
					<div class="field-pass">
						<label>Новый пароль <span>*</span></label>
						<input type="text" />
					</div><!--field-pass-->
					
					<div class="field-pass">
						<label>Ещё раз новый <span>*</span></label>
						<input type="text" />
					</div><!--field-pass-->
				</div><!--change-pass-->
				
				<div class="but-pop but-pop4"><button type="submit">Сохранить</button></div>
			</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>