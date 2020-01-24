<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?// COOL!! OVERLAY!!!?>
<div class="ov"></div>

<!--noindex-->
<div style="display: none;">
	<div id="pop1" class="pop">
		<div class="close"></div>
		<div class="t-pop">Пополнение счёта</div>
		<div class="field-pop field-pop3">
			<label>Сумма</label>
			<input type="text" />
			<span>руб.</span>
		</div>
		<div class="buts">
			<div class="but-pop but-pop2"><button type="submit">Запросить счёт</button></div>
			<div class="but-pop but-pop2"><button type="submit">Оплатить картой</button></div>
		</div>
	</div><!--pop-->

	<div id="pop2" class="pop pop2">
		<div class="close"></div>
		<div class="t-pop t-pop2">Счет на оплату</div>

		<div class="price-pop"><span>90 000</span> руб.</div>

		<div class="score-pop">
			Ваш счёт готов! Вы можете загрузить его по ссылке:<br>
			<a href="#">№ 15465 АН 4654</a>
		</div>

		<div class="history-score">Счёт для загрузки всегда доступен в <a href="#">«Истории платежей»</a></div>

		<div class="but-pop"><button type="submit">Готово</button></div>
	</div><!--pop-->

	<div id="pop3" class="pop pop2">
		<div class="close"></div>
		<div class="t-pop t-pop3">Внимание</div>
		<div class="rekviz-pop">
			<p>Мы не можем выставить Вам счёт, поскольку вы не указали реквизиты.</p>
			<a href="#">Заполнить реквизиты</a>
		</div><!--rekviz-pop-->
		<div class="but-pop"><button type="submit">Готово</button></div>
	</div><!--pop-->

	<div id="pop4" class="pop pop2">
		<div class="close"></div>
		<div class="t-pop t-pop4">Пополнение счёта картой</div>
		<div class="pay-p">
			<p>Платёж выполнен успешно.</p>
		</div><!--pay-p-->
		<div class="but-pop"><button type="submit">Готово</button></div>
	</div><!--pop-->

	<div id="pop5" class="pop pop3">
		<div class="close"></div>
		<div class="t-pop t-pop2">Пакет «Комбо 100»</div>
		<div class="property-pop">
			<ul>
				<li><span>25</span> квартир</li>
				<li><span>25</span> участков</li>
				<li><span>25</span> домов</li>
				<li><span>25</span> коммерческая недвижимость</li>
			</ul>
		</div>
		<div class="big-price">90 000 руб.</div>
		<div class="acts">Действует 1 месяц с момента покупки</div>
		<div class="but-pop"><button type="submit">Купить</button></div>
	</div><!--pop-->

	<div id="pop6" class="pop pop4">
		<div class="close"></div>
		<div class="t-pop t-pop5">Первичный рынок недвижимости</div>
		<p>Рынок, на котором продаются договоры долевого участия в строительстве новых домов или объекты недвижимости, еще не оформленные в собственность (как правило, речь идет о строящихся или недавно построенных домах). </p>
		<p>П.р.н. формируется предложением различных застройщиков. Когда идет речь о предложении на П.р.н., то под этим понимается весь объем предложений на рынке новостроек. По мнению риэлторов, основным достоинством первичного рынка является отсутствие у квартиры какой-либо истории, т.е. ее юридическая чистота.</p>
	</div><!--pop-->

	<div id="pop7" class="pop pop5">
		<div class="close"></div>
		<div class="t-pop t-pop4">Добавить объявление</div>

		<div class="tabs-pop tabs-tb">
			<div class="nav-tabs-pop nav-tb">
				<ul>
					<li><a href="#">Продажа</a></li>
					<li><a href="#">Аренда</a></li>
				</ul>
			</div>

			<div class="cont-tabs-pop cont-tb">
				<div class="tab-pop tab-tb">
					<ul>
						<li><a href="add?type=sell&IBLOCK_ID=7">Квартиры</a></li>
						<li><a href="add?type=sell&IBLOCK_ID=8">Коттеджные поселки и комплексы таунхаусов, </a></li>
						<li><a href="add?type=sell&IBLOCK_ID=10">Дома, коттеджи, таунхаусы </a></li>
						<li><a href="add?type=sell&IBLOCK_ID=11">Коммерческая недвижимость</a></li>
						<li><a href="add?type=sell&IBLOCK_ID=12">Торговые и бизнес-центры</a></li>
						<li><a href="add?type=sell&IBLOCK_ID=9">Инвестиционные проекты</a></li>
						<li><a href="add?type=sell&IBLOCK_ID=19">Новостройки</a></li>
					</ul>
				</div><!--tab-pop-->

				<div class="tab-pop tab-tb">
					<ul>
						<li><a href="add?type=arend&IBLOCK_ID=7">Квартиры</a></li>
						<li><a href="add?type=arend&IBLOCK_ID=8">Коттеджные поселки и комплексы таунхаусов, </a></li>
						<li><a href="add?type=arend&IBLOCK_ID=10">Дома, коттеджи, таунхаусы </a></li>
						<li><a href="add?type=arend&IBLOCK_ID=11">Коммерческая недвижимость</a></li>
						<li><a href="add?type=arend&IBLOCK_ID=12">Торговые и бизнес-центры</a></li>
						<li><a href="add?type=arend&IBLOCK_ID=9">Инвестиционные проекты</a></li>
						<li><a href="add?type=arend&IBLOCK_ID=19">Новостройки</a></li>
					</ul>
				</div><!--tab-pop-->
			</div><!--cont-tabs-pop-->
		</div><!--tabs-pop-->
	</div><!--pop-->

	<div id="pop8" class="pop pop6">
		<div class="close"></div>
		<div class="t-pop t-pop4">Отправка сообщения</div>

		<form>
			<div class="textarea-pop"><textarea placeholder="Текст сообщения *"></textarea></div>
			<div class="field-pop field-pop4"><input type="text" placeholder="Как к вам обращаться? *" /></div>
			<div class="field-pop field-pop4"><input type="text" placeholder="Куда вам ответить?  *" /></div>
			<div class="but-pop but-pop3"><button type="submit">Отправить</button></div>
		</form>
	</div><!--pop-->

	<div id="pop9" class="pop pop7">
		<div class="close"></div>
		<div class="t-pop t-pop4">Пополнение счёта картой</div>

		<div class="head-card">
			<div class="total-card">Сумма к оплате: <span>90 000 руб.</span></div>
			<div class="ico-pay">
				<ul>
					<li><img src="<?=SITE_TEMPLATE_PATH?>/images/ico-pay1.png" /></li>
					<li><img src="<?=SITE_TEMPLATE_PATH?>/images/ico-pay2.png" /></li>
					<li><img src="<?=SITE_TEMPLATE_PATH?>/images/ico-pay3.png" /></li>
				</ul>
			</div>
		</div><!--head-card-->

		<div class="body-card">
			<div class="card card1">
				<div class="field-card"><input type="text" placeholder="Номер карты" /></div>

				<div class="fields-card">
					<label>Cardholder</label>

					<div class="fiels">
						<div class="field-card"><input type="text" placeholder="Имя" /></div>
						<div class="field-card"><input type="text" placeholder="Фамилия" /></div>
					</div><!--fiels-->
				</div><!--fields-card-->

				<div class="fields-card">
					<label>Valid/Thru</label>

					<div class="fiels fiels2">
						<div class="field-card"><input type="text" placeholder="ММ" /></div>
						<span>/</span>
						<div class="field-card"><input type="text" placeholder="ГГ" /></div>
					</div><!--fiels-->
				</div><!--fields-card-->
			</div><!--card-->

			<div class="card card2">
				<div class="field-cvc">
					<input type="text" placeholder="CVC" />
					<label>3 цифры с оборотной стороны</label>
				</div>
			</div><!--card-->
		</div><!--body-card-->

		<div class="but-pop but-pop3"><button type="submit">Оплатить</button></div>
	</div><!--pop-->
	<div id="pop11" class="pop pop6">
		<div class="close"></div>

		<form>
			<div class="field-pop field-pop4"><span class="question-span dop-span">Ваш вопрос будет показан после проверки модератором.</span></div>
		</form>
	</div><!--pop-->
	<div id="pop12" class="pop pop6">
		<div class="close"></div>
		<a class='hidden inline cBoxElement' href="#pop12"></a>
		<form>
			<div class="field-pop field-pop4"><span class="dop-span">Небходима <a href="/auth/">авторизация</a>.</span></div>
		</form>
	</div><!--pop-->
	<div id="pop13" class="pop pop6">
		<div class="close"></div>
		<div class="t-pop t-pop4">Поделиться страницей:</div>
		<div class="special-wrapper">
			<script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
			<script src="//yastatic.net/share2/share.js"></script>
			<div class="ya-share2" data-services="collections,vkontakte,facebook,odnoklassniki,moimir"></div>
		</div>
	</div><!--pop-->


	<?$APPLICATION->IncludeComponent(
		"bitrix:form.result.new",
		"send_message_to_realtor",
		Array(
			"CACHE_TIME" => "3600",
			"CACHE_TYPE" => "A",
			"IGNORE_CUSTOM_TEMPLATE" => "N",
			"SEF_MODE" => "N",
			"USE_EXTENDED_ERRORS" => "N",
			"WEB_FORM_ID" => 1
		)
	);?>


</div>
<!--/noindex-->