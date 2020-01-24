<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Продажа квартир в Сочи");
?>

			<div class="rielters"><a href="#">Новостройки</a></div>
			<div class="rielters"><a href="#">Вторичка</a></div>
			
			  <div class="filter-line">
				<div class="f-visible">
				  <div class="sub_wrap">
					<div class="float_wrapper">
					  <div class="secs">
						<div class="sec">
						  <div class="search"></div>
						</div>
						<div class="sec">
						  <div class="sec-head">
							<span>Цена <strong>&nbsp;от 4 200 000 до 12 000 000</strong></span>
						  </div>
						  <div class="form slide-pop">
							<span class="label">Площадь</span>
							  <div class="field">
								<input type="number" min="10" name="from" placeholder="От">
								<input type="number" min="10" name="to" placeholder="До">
							  </div>
							  <span>м<sup>2</sup></span>
						  </div>
						</div>
						<div class="sec">
						  <div class="sec-head">
							<span>Тип квартиры</span>
						  </div>
						  <div class="form slide-pop">
							<span class="label">Площадь</span>
							  <div class="field">
								<input type="number" min="10" name="from" placeholder="От">
								<input type="number" min="10" name="to" placeholder="До">
							  </div>
							  <span>м<sup>2</sup></span>
						  </div>
						</div>
						<div class="sec">
						  <div class="sec-head">
							<span>Площадь</span>
						  </div>
						  <div class="form slide-pop">
							<span class="label">Площадь</span>
							  <div class="field">
								<input type="number" min="10" name="from" placeholder="От">
								<input type="number" min="10" name="to" placeholder="До">
							  </div>
							  <span>м<sup>2</sup></span>
						  </div>
						</div>
						<div class="sec">
						  <div class="sec-head">
							<span>Расположение</span>
						  </div>
						  <div class="form slide-pop">
							<span class="label">Площадь</span>
							  <div class="field">
								<input type="number" min="10" name="from" placeholder="От">
								<input type="number" min="10" name="to" placeholder="До">
							  </div>
							  <span>м<sup>2</sup></span>
						  </div>
						</div>
					  </div>
					  <a href="#" class="more">Еще детали</a>
				  </div>
				  </div>
				</div>
				<div class="f-toggle slide-pop">
				  <div class="sub_wrap"></div>
				</div>
			  </div>
			
			  <div class="sort-line">
				<div class="sub_wrap">
				  <div class="float_wrapper">
					<div class="items_wrap">
					  <span class="count">3 589 объектов</span>
					  <div class="items">
						<span class="items_label">Сортировка:</span>
						<ul class="items_list">
						  <li><strong><a href="#">Цена квартиры</a></strong></li>
						  <li><a href="#">Цена м<sup>2</sup></a></li>
						  <li><a href="#">Площадь</a></li>
						  <li><a href="#">Дата добавления</a></li>
						</ul>
					  </div>
					</div>
					<div class="more">
					  <a href="#" id="show_map_list">Список</a>
					</div>
				  </div>
				</div>
			  </div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>