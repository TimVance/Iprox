<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?/* ?>
<div class="nav-func nav-tb">
	<ul>
		<li class="active buy-or-arend" data-type="sell">Купить</li>
		<li class="buy-or-arend" data-type="arend">Снять</li>
	</ul>
</div>
<?*/ ?>
<form id="find_estate" action="/sell/flat/">
	<input type="hidden" name="buy_or_arend" value="sell">
	<input type="hidden" name="estate_type" value="/flat/">
	<input type="hidden" name="iblock_id" value="7">
	<input type="hidden" name="rooms_number" value="1">
	<input type="hidden" name="buy_type1" value="0">
	<input type="hidden" name="buy_type2" value="0">
	<input type="hidden" name="buy_type3" value="0">
	<input type="hidden" name="price_from" value="">
	<input type="hidden" name="price_to" value="">
	<input type="hidden" name="square_from" value="">
	<input type="hidden" name="square_to" value="">
	<input type="hidden" name="currency" value="rub">
	<input type="hidden" name="is_find_estate" value="true">
	<input type="hidden" name="build-type" value="">
	<input type="hidden" name="is_shop" value="">
	<input type="hidden" name="is_office" value="">
	<input type="hidden" name="commerce_info" value="">
	
	<input type="submit" id="find_estate-submit-final" style='display: none;'>
</form>

<div class="cont-func cont-tb fint_estate_block">
	<div class="tab-f tab-tb">
		<div class="tabs-room">
			<div class="nav-rooms">
				<ul>
					<li class="active"><a href="javascript:void(0);" data-link="/flat/" data-id="7" class="change-estate_type">Квартиру</a></li>
					<li><a href="javascript:void(0);" data-link="/houses/" data-id="8" class="change-estate_type">Дом</a></li>
					<li><a href="javascript:void(0);" data-link="/markets/" data-id="12" class="change-estate_type">Коммерция</a></li>
					<li><a href="javascript:void(0);" data-link="/land/" data-id="10" class="change-estate_type">Участок</a></li>
				</ul>
			</div>

			<div class="cont-rooms">
				<div class="tab-room">
					<div class="choice-rooms">
						<div class="radio-rooms">
							<ul>
								<li class="defaultP">
									<input id="num-rooms1" data-rooms="1" name="num-rooms" type="radio" />
									<label for="num-rooms1" data-rooms="1">1</label>
								</li>
								<li class="defaultP">
									<input id="num-rooms2" data-rooms="2" name="num-rooms" type="radio" />
									<label for="num-rooms2" data-rooms="2">2</label>
								</li>
								<li class="defaultP">
									<input id="num-rooms3" data-rooms="3" name="num-rooms" type="radio" />
									<label for="num-rooms3" data-rooms="3">3</label>
								</li>
								<li class="defaultP">
									<input id="num-rooms4" data-rooms="4, 5, 6, 7, 8, 9, 10" name="num-rooms" type="radio" />
									<label for="num-rooms4" data-rooms="4, 5, 6, 7, 8, 9, 10">4+</label>
								</li>
							</ul>
							<p>-комнатную</p>
						</div>

						<div class="check-rooms">
							<ul>
								<li class="customP">
									<input id="check-rooms1" type="checkbox" />
									<label for="check-rooms1" class="lbl-check-rooms1" data-val="1">на вторичке</label>
								</li>
								<li class="customP">
									<input id="check-rooms2" type="checkbox" />
									<label for="check-rooms2" class="lbl-check-rooms2" data-val="1">в новостройке</label>
								</li>
								<li class="customP">
									<input id="check-rooms3" type="checkbox" />
									<label for="check-rooms3" class="lbl-check-rooms3" data-val="1">по ипотеке</label>
								</li>
								
								<?/* ?>
								<li class="customP">
									<input id="check-rooms4" type="checkbox" />
									<label for="check-rooms4" class="lbl-check-rooms4" data-val="1">Офис</label>
								</li>
								<li class="customP">
									<input id="check-rooms5" type="checkbox" />
									<label for="check-rooms5" class="lbl-check-rooms5" data-val="1">Магазин</label>
								</li>
								<?*/ ?>
							</ul>
						</div>
					</div>

					<div class="choice-top-form">
						<label>Цена</label>
						<div class="fields-pr">
							<div class="field-pr"><input type="text" placeholder="от" name="price_from-temp" /></div>
							<div class="field-pr"><input type="text" placeholder="до" name="price_to-temp" /></div>
						</div>
						<div class="sel-pr">
							<select>
								<option value="rub">Руб</option>
								<option value="usd">USD</option>
								<option value="eur">EUR</option>
							</select>
						</div>
					</div>
				</div>
			</div>

			<div class="add-options-forms">
				<div class="choice-top-form choice-top-form2">
					<label>Площадь</label>
					<div class="fields-pr">
						<div class="field-pr"><input type="text" placeholder="от" name="square_from-temp" /></div>
						<div class="field-pr"><input type="text" placeholder="до" name="square_to-temp" /></div>
					</div>
					<div class="sel-pr">
					</div>
				</div>
				<div class="choice-top-form commerce_type">
					<label>Тип</label>
					<div class="sel-pr">
						<select name="commerce_type">
							<option>Выберите тип</option>
							<option value="462">Офис</option>
							<option value="463">Магазин</option>
							<option value="464">Ресторан</option>
							<option value="465">Гостиница</option>
						</select>
					</div>
				</div>
			</div>

			<div class="bot-forms">
				<div class="but-choice"><button type="submit" id="find-estate-submit">Подобрать</button></div>
				<div class="view-map"><a href="javascript:void(0);" data-link="/sell/map/" >Показать на карте</a></div>
				<div class="more-params"><a href="javascript:void(0);">Дополнительные параметры</a></div>
			</div>
		</div>
	</div>

	<div class="tab-f tab-tb" style="display:none">
		<div class="tabs-room">
			<div class="nav-rooms">
				<ul>
					<li class="active"><a href="javascript:void(0);" data-link="/flat/" data-id="7" class="change-estate_type">Квартиру</a></li>
					<li><a href="javascript:void(0);" data-link="/houses/" data-id="8" class="change-estate_type">Дом</a></li>
					<li><a href="javascript:void(0);" data-link="/markets/" data-id="12" class="change-estate_type">Помещение</a></li>
					<li><a href="javascript:void(0);" data-link="/land/" data-id="10" class="change-estate_type">Участок</a></li>
				</ul>
			</div>

			<div class="cont-rooms">
				<div class="tab-room">
					<div class="choice-rooms">
						<div class="radio-rooms">
							<ul>
								<li class="defaultP">
									<input id="num-rooms1-2" checked name="num-rooms-2" type="radio" />
									<label for="num-rooms1-2">1</label>
								</li>
								<li class="defaultP">
									<input id="num-rooms2-2" name="num-rooms-2" type="radio" />
									<label for="num-rooms2-2">2</label>
								</li>
								<li class="defaultP">
									<input id="num-rooms3-2" name="num-rooms-2" type="radio" />
									<label for="num-rooms3-2">3</label>
								</li>
								<li class="defaultP">
									<input id="num-rooms4-2" name="num-rooms-2" type="radio" />
									<label for="num-rooms4-2">4+</label>
								</li>
							</ul>
							<p>-комнатную</p>
						</div>

						<?/* ?><div class="check-rooms">
							<ul>
								<li class="customP">
									<input id="check-rooms1-2" type="checkbox" />
									<label for="check-rooms1-2">на вторичке</label>
								</li>
								<li class="customP">
									<input id="check-rooms2-2" type="checkbox" />
									<label for="check-rooms2-2">в новостройке</label>
								</li>
							</ul>
						</div><? */?>
					</div>

					<div class="choice-top-form">
						<label>Цена</label>
						<div class="fields-pr">
							<div class="field-pr"><input type="text" placeholder="от" /></div>
							<div class="field-pr"><input type="text" placeholder="до" /></div>
						</div>
						<div class="sel-pr">
							<select>
								<option>Руб</option>
								<option>USD</option>
								<option>EUR</option>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="error_arend_class" style="text-align: center;display: none;"><?=ShowError("К сожалению, в этом разделе нет объявлений") ?></div>
			<div class="bot-forms">
				<div class="but-choice try_to_get_arend"><button type="submit">Подобрать</button></div>
				<div class="view-map"><a href="javascript:void(0);" data-link="/sell/map/" >Показать на карте</a></div>
				<div class="more-params"><a href="javascript:void(0);">Дополнительные параметры</a></div>
			</div>
		</div>
	</div>
</div>