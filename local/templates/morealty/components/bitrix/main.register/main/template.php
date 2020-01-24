<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) { die(); }
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<?if($USER->IsAuthorized()):?>

	<p><?echo GetMessage("MAIN_REGISTER_AUTH")?></p>

<?else:?>
	<?
	if (count($arResult["ERRORS"]) > 0):
		foreach ($arResult["ERRORS"] as $key => $error)
			if (intval($key) == 0 && $key !== 0)
				$arResult["ERRORS"][$key] = str_replace("#FIELD_NAME#", "&quot;".GetMessage("REGISTER_FIELD_".$key)."&quot;", $error);

		ShowError(implode("<br />", $arResult["ERRORS"]));

	elseif($arResult["USE_EMAIL_CONFIRMATION"] === "Y" && false):
		?>
		<p><?echo GetMessage("REGISTER_EMAIL_WILL_BE_SENT")?></p>
	<?endif?>

<? $APPLICATION->SetTitle("Регистрация нового пользователя"); ?>
	<div class="pad-empty">
		<div class="block-regist tabs-tb">
			<div class="nav-regist nav-tb">
				<ul>
					<li id="realtor" <?if($_REQUEST['checked_user_type'] == 'realtor') echo 'class="active"';?>><a href="javascript:void(0);">Я - риелтор</a></li>
					<li id="owner" <?if($_REQUEST['checked_user_type'] != 'realtor') echo 'class="active"';?>><a href="javascript:void(0);">Я - собственник</a></li>
					
				</ul>
			</div>
			<?
			if (!$_REQUEST['checked_user_type'])
			{
				?>
				<script>
				$(function(){
					setTimeout(function(){
						$(".nav-regist li:eq(0)").trigger("click");
					},150);
					
				});
				</script>
				<?
			}
			?>
			
			<div class="free-ads <?if($_REQUEST['checked_user_type'] == 'realtor') echo 'offer-hidden';?>">одно объявление бесплатно</div>
				<? if($arResult["BACKURL"] <> ''): ?>
					<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
				<? endif; ?>
				<? $FIELDS = $arResult["SHOW_FIELDS"] ?>
			<?
			?>
			<div class="body-regist">
					<div class="cont-tab-regist cont-tb">
			<div class="tab-regist tab-tb <?if($_REQUEST['checked_user_type'] == 'realtor') {echo 'tab-visible';} else {echo 'tab-hidden';}?>">
			<form method="post" action="<?=POST_FORM_ACTION_URI?>" name="regform" enctype="multipart/form-data">
				<input type="hidden" name="checked_user_type" value="realtor">
				<div class="data">
					<div class="data-left">
						<div class="tit-regist">Персональная информация риелтора</div>
						<div class="bl-regist">
							<div class="field-pass">
								<label>Имя:*</label>
								<div class="ff-pass">
									<input class="req" type="text" name="REGISTER[NAME]"  value="<?=$_REQUEST['REGISTER']['NAME']?>" autocomplete="off" class="bx-auth-input" />
								</div>
							</div>

							<div class="field-pass">
								<label>Фамилия:</label>
								<div class="ff-pass">
									<input class="req" type="text" name="REGISTER[LAST_NAME]" value="<?=$_REQUEST['REGISTER']['LAST_NAME']?>" autocomplete="off" class="bx-auth-input" />
								</div>
							</div>
							
							
							<div class="field-pass">
								<label>Отчество:</label>
								<div class="ff-pass">
									<input type="text" name="REGISTER[SECOND_NAME]" value="<?=$_REQUEST['REGISTER']['SECOND_NAME']?>" autocomplete="off" class="bx-auth-input" />
								</div>
							</div>

							

							<div class="field-pass">
								<label>Город:*</label>
								<div class="sel-on sel-on4">
									<select class="req" name="UF_CITY"  onchange="this.form.elements['REGISTER[PERSONAL_CITY]'].disabled=(this.value != 'N')">
										<option value="13">Сочи</option>
									</select>
								</div>
							</div>
							<?
							if ($arResult["AGENTS"] && count($arResult["AGENTS"]) > 0)
							{
								?>
								<div class="field-pass">
									<label>Агенство:</label>
									<div class="sel-on sel-on4">
										<select name="UF_AGENT_NAME" class="not_selectobx agents-selectbox">
											<option value="">Выберите агенство</option>
											<?
											foreach ($arResult["AGENTS"] as $arAgent)
											{
												$img = $arAgent["PREVIEW_PICTURE"]? weImg::Resize($arAgent["PREVIEW_PICTURE"], 75, 75, weImg::M_CROP) : false;
												?>
													<option <?=($img)? 'data-image="'.$img.'"' : ""?> data-name="<?=htmlspecialchars($arAgent['NAME'])?>" value="<?=$arAgent["ID"]?>"><?=$arAgent['NAME']?></option>
												<?
											}
											?>
										</select>
									</div>
								</div>
								<?
							}
							?>
							

							<div class="field-pass">
								<label>Телефон:*</label>
								<div class="ff-pass">
									<input class="req" type="text" class="personal-mobiles" name="REGISTER[PERSONAL_MOBILE]" value="<?=$_REQUEST['REGISTER']['PERSONAL_MOBILE']?>"  autocomplete="off" class="bx-auth-input" />
								</div>
							</div>

							<div class="field-pass">
								<label>E-mail:*</label>
								<div class="ff-pass">
									<input type="text" class="req" name="REGISTER[EMAIL]" value="<?=$_REQUEST['REGISTER']['EMAIL']?>"  autocomplete="off" class="bx-auth-input" />
								</div>
							</div>


							<div class="field-pass">
								<label>Пароль:*</label>
								<div class="ff-pass">
									<input type="password" class="req" name="REGISTER[PASSWORD]" value="<?=$_REQUEST['REGISTER']['PASSWORD']?>" autocomplete="off" class="bx-auth-input" />
								</div>
							</div>

							<div class="field-pass">
								<label>Повторите пароль:*</label>
								<div class="ff-pass">
									<input type="password" class="req" name="REGISTER[CONFIRM_PASSWORD]" value="<?=$_REQUEST['REGISTER']['CONFIRM_PASSWORD']?>"  autocomplete="off" />
								</div>
							</div>
							<?/* ?><div class="assest-inp assest-inp2 customP">
								<input id="assest" checked type="checkbox" name="UF_ACCEPT_RULES" />
								<label for="assest">Не показывать на сайте</label>
							</div>
							<?*/?>
						</div>

					</div>

					<div class="data-right">
						<div class="tit-regist tit_info">Справка</div>
							<div class="bl-regist">
								<p>После регистрации необходимо потвердить Ваш почтовый адрес (E-mail). Для этого на указанный E-mail придет письмо с потверждением.</p>
								<p>Для входа на сайт используется Ваш номер телефона. При каждом входе на телефон отправляется СМС-сообщение с кодом, который необходимо ввести для доступа к сайту.</p>
							</div>
						<?/* ?>
						
						<div class="tit-regist">Данные для входа риелтора</div>
						<div class="bl-regist">
							<div class="field-pass field-pass5">
								<label>Логин:*</label>
								<div class="ff-pass">
									<input type="text" class="req" name="REGISTER[LOGIN]" value="<?=$_REQUEST['REGISTER']['LOGIN']?>" autocomplete="off" class="bx-auth-input" />
								</div>
							</div>

							<div class="field-pass field-pass5">
								<label>Пароль:*</label>
								<div class="ff-pass">
									<input type="password" class="req" name="REGISTER[PASSWORD]" value="<?=$_REQUEST['REGISTER']['PASSWORD']?>" autocomplete="off" class="bx-auth-input" />
								</div>
							</div>

							<div class="field-pass field-pass5">
								<label>Повторите пароль:*</label>
								<div class="ff-pass">
									<input type="password" class="req" name="REGISTER[CONFIRM_PASSWORD]" value="<?=$_REQUEST['REGISTER']['CONFIRM_PASSWORD']?>"  autocomplete="off" />
								</div>
							</div>
						</div>
						<?*/ ?>
					</div>
				</div>

				<div class="assest-inp assest-inp3 customP">
					<input id="assest2" checked type="checkbox" class="req" name="UF_DISPLAY_PRIVATE"/>
					<label for="assest2">Я прочитал и принимаю <a href="#">правила публикации</a> объявлений.</label>
				</div>


				<div class="but-pop but-pop5">
					<input type="hidden" name="REGISTER[WORK_PROFILE]" value="7">
					<input type="submit" name="register_submit_button" value="Зарегистрироваться" />
				</div>
				</form>
			</div>
				
				<div class="tab-regist tab-tb <?if($_REQUEST['checked_user_type'] == 'owner' || empty($_REQUEST['checked_user_type'])) {echo 'tab-visible';}  else {echo 'tab-hidden';}?>">
					<form method="post" action="<?=POST_FORM_ACTION_URI?>" name="regform" enctype="multipart/form-data">
						<input type="hidden" name="checked_user_type" value="owner">
						<div class="data">
							<div class="data-left">
								<div class="tit-regist">Персональная информация</div>
								<div class="bl-regist">
									<div class="field-pass">
										<label>Имя:*</label>
										<div class="ff-pass">
											<input type="text" class="req" name="REGISTER[NAME]"  value="<?=$_REQUEST['REGISTER']['NAME']?>" autocomplete="off" class="bx-auth-input" />
										</div>
									</div>

									<div class="field-pass">
										<label>Фамилия:</label>
										<div class="ff-pass">
										<?
										if (!$_REQUEST['REGISTER']['LAST_NAME'])
										{
											$_REQUEST['REGISTER']['LAST_NAME'] = " ";
										}
										?>
											<input class="req" type="text" name="REGISTER[LAST_NAME]" value="<?=$_REQUEST['REGISTER']['LAST_NAME']?>" autocomplete="off" class="bx-auth-input" />
										</div>
									</div>
									<div class="field-pass">
										<label>Отчество:</label>
										<div class="ff-pass">
											<input type="text" name="REGISTER[SECOND_NAME]" value="<?=$_REQUEST['REGISTER']['SECOND_NAME']?>" autocomplete="off" class="bx-auth-input" />
										</div>
									</div>

									

									<div class="field-pass">
										<label>Город:*</label>
										<div class="sel-on sel-on4">
											<select class="req" name="UF_CITY"  onchange="this.form.elements['REGISTER[PERSONAL_CITY]'].disabled=(this.value != 'N')">
												<option value="13">Сочи</option>
											</select>
										</div>
									</div>

									<div class="field-pass">
										<label>Телефон:*</label>
										<div class="ff-pass">
											<input type="text" class="req" placeholder="+7(___)___-__-__" class="personal-mobile" name="REGISTER[PERSONAL_MOBILE]" value="<?=$_REQUEST['REGISTER']['PERSONAL_MOBILE']?>"  autocomplete="off" class="bx-auth-input" />
										</div>
										<script>
											$(document).ready(function() {
												$(".personal-mobile").inputmask("+7(999)999-99-99");
											});
										</script>
									</div>


									<div class="field-pass">
										<label>E-mail:*</label>
										<div class="ff-pass">
											<input type="text" class="req" name="REGISTER[EMAIL]" value="<?=$_REQUEST['REGISTER']['EMAIL']?>"  autocomplete="off" class="bx-auth-input" />
										</div>
									</div>

									<div class="field-pass">
										<label>Пароль:*</label>
										<div class="ff-pass">
											<input type="password" class="req" name="REGISTER[PASSWORD]" value="<?=$_REQUEST['REGISTER']['PASSWORD']?>" autocomplete="off" class="bx-auth-input" />
										</div>
									</div>
		
									<div class="field-pass">
										<label>Повторите пароль:*</label>
										<div class="ff-pass">
											<input type="password" class="req" name="REGISTER[CONFIRM_PASSWORD]" value="<?=$_REQUEST['REGISTER']['CONFIRM_PASSWORD']?>"  autocomplete="off" />
										</div>
									</div>
									<?/* ?>
									<div class="assest-inp assest-inp2 customP">
										<input id="assest" checked type="checkbox" name="UF_ACCEPT_RULES" />
										<label for="assest">Не показывать на сайте</label>
									</div>
									<?*/ ?>
								</div>
							</div>

							<div class="data-right">
								<div class="tit-regist tit_info">Справка</div>
									<div class="bl-regist">
										<p>После регистрации необходимо потвердить Ваш почтовый адрес (E-mail). Для этого на указанный E-mail придет письмо с потверждением.</p>
										<p>Для входа на сайт используется Ваш номер телефона. При каждом входе на телефон отправляется СМС-сообщение с кодом, который необходимо ввести для доступа к сайту.</p>
									</div>
								<?/* ?>
								
								<div class="tit-regist">Данные для входа риелтора</div>
								<div class="bl-regist">
									<div class="field-pass field-pass5">
										<label>Логин:*</label>
										<div class="ff-pass">
											<input type="text" class="req" name="REGISTER[LOGIN]" value="<?=$_REQUEST['REGISTER']['LOGIN']?>" autocomplete="off" class="bx-auth-input" />
										</div>
									</div>
		
									<div class="field-pass field-pass5">
										<label>Пароль:*</label>
										<div class="ff-pass">
											<input type="password" class="req" name="REGISTER[PASSWORD]" value="<?=$_REQUEST['REGISTER']['PASSWORD']?>" autocomplete="off" class="bx-auth-input" />
										</div>
									</div>
		
									<div class="field-pass field-pass5">
										<label>Повторите пароль:*</label>
										<div class="ff-pass">
											<input type="password" class="req" name="REGISTER[CONFIRM_PASSWORD]" value="<?=$_REQUEST['REGISTER']['CONFIRM_PASSWORD']?>"  autocomplete="off" />
										</div>
									</div>
								</div>
								<?*/ ?>
							</div>
						</div>

						<div class="assest-inp assest-inp3 customP">
							<input id="assest3" checked type="checkbox" class="req" name="UF_DISPLAY_PRIVATE"/>
							<?/* ?><label for="assest2">Я прочитал и принимаю <a href="#">правила публикации</a> объявлений, а также даю согласие на обработку моих персональных данных</label><?*/ ?>
							<label for="assest3">Я прочитал и принимаю <a href="#">правила публикации</a> объявлений.</label>
						</div>
						<div class="but-pop but-pop5">
							<input type="hidden" name="REGISTER[WORK_PROFILE]" value="8">
							<input type="submit" name="register_submit_button" value="Зарегистрироваться" />
						</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
<?endif; // IsAuthorized ?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>