<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();


CModule::IncludeModule('iblock');

$_GET['cost'] = $_REQUEST['cost'];
$_GET['sessid'] = $_REQUEST['sessid'];

$arFilter = array("IBLOCK_ID" => 14, "ACTIVE" => "Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, array('ID', 'NAME'));
$locations = array();
while ($ob = $res->GetNextElement()) {
    $locations[] = $ob->GetFields();
}

$square = intval($_REQUEST['cost']['square']);
$square = intval($_REQUEST['cost']['square']);
$square_from = intval($_REQUEST['cost']['square'] * 0.8);
$square_to = intval($_REQUEST['cost']['square'] * 1.2);

$arFilter = array(
    "IBLOCK_ID" => 7,
    "ACTIVE" => "Y",
    //"><PROPERTY_square"    => array(intval($square_from), intval($square_to)),
    "PROPERTY_room_number" => $_REQUEST['cost']['rooms'],
    "PROPERTY_district" => $_REQUEST['cost']['region'],
    "PROPERTY_floor" => $_REQUEST['cost']['stage']
);

$arResult['SIMILAR_IDS'] = array();
$props = array('ID', 'NAME', 'IBLOCK_ID', 'PROPERTY_*');
$min_price_m2 = 999999999999;
$max_price_m2 = 0;
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $props);
while ($ob = $res->GetNextElement()) {
    $item = $ob->GetFields();
    $item['PROPERTIES'] = $ob->GetProperties();
    if ($item['PROPERTIES']['price_1m']['VALUE'] < $min_price_m2) {
        //$min_price = $item['PROPERTIES']['price']['VALUE'];
        $min_price_m2 = $item['PROPERTIES']['price_1m']['VALUE'];
    }
    if ($item['PROPERTIES']['price_1m']['VALUE'] > $max_price_m2) {
        //$max_price = $item['PROPERTIES']['price']['VALUE'];
        $max_price_m2 = $item['PROPERTIES']['price_1m']['VALUE'];
    }
    if ($item['PROPERTIES']['square']['VALUE'] >= intval($square_from) && $item['PROPERTIES']['square']['VALUE'] < intval($square_to)) {
        $arResult['SIMILAR_IDS'][] = $item['ID'];
        $flats[] = $item;
    }
    if (!$min_price)
    {
    	$min_price = $item['PROPERTIES']['price']['VALUE'];
    }
    else if ($min_price > $item['PROPERTIES']['price']['VALUE'])
    {
    	$min_price = $item['PROPERTIES']['price']['VALUE'];
    }
    if (!$max_price)
    {
    	$max_price = $item['PROPERTIES']['price']['VALUE'];
    }
    else if ($max_price < $item['PROPERTIES']['price']['VALUE'])
    {
    	$max_price = $item['PROPERTIES']['price']['VALUE'];
    }
}
$resultPrice = $min_price_m2 * $square;

//DELETE
?>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script><?
if (count($_POST) > 0 && check_bitrix_sessid()) {
    // выбрать квартиры из района с такой же примерно площадью (инфоблок 7)

    //my_print_r($_POST);
}


$arResult['CALC_TOTAL'] = array(
    'PRICE' => $resultPrice,
    'FROM' => array(
        'PRICE_M2' => $min_price_m2,
        'PRICE' => $min_price
    ),
    'TO' => array(
        'PRICE_M2' => $max_price_m2,
        'PRICE' => $max_price
    )
);

?>
<form method="post" id="cost_request_form">
    <?= bitrix_sessid_post() ?>
    <input type="hidden" name="check" value="y">
    <div class="pad-empty">
        <div class="block-calculate">
            <div class="online-cost">
                <div class="t-online-cost"><span>Расположение квартиры</span></div>
                <div class="online-fields">
                    <div class="sel-on sel-on1">
                        <label>Район</label>
                        <select name="cost[region]">
                            <? foreach ($locations as $location): ?>
                                <option value="<?= $location['ID'] ?>"
                                        <? if ($_REQUEST['cost']['region'] == $location['ID']) { ?>selected<? } ?>><?= $location['NAME'] ?></option>
                            <? endforeach; ?>
                        </select>
                    </div><!--sel-on-->

                    <div class="field-on">
                        <label>Улица</label>
                        <div class="field-die">
                            <input type="text" name="cost[street]"
                                   value="<?= htmlspecialcharsbx($_REQUEST['cost']['street']) ?>"/>
                            <? /*<div class="die-f">
								<ul>
									<li>Неверовского</li>
									<li>Невская</li>
									<li>Неверная</li>
								</ul>
							</div>*/ ?>
                        </div>
                    </div><!--field-on-->
                </div><!--online-fields-->
            </div><!--online-cost-->

            <div class="online-cost">
                <div class="t-online-cost"><span>Информация о квартире</span></div>
                <div class="online-fields">
                    <div class="sel-on sel-on2">
                        <label>Комнат</label>
                        <select name="cost[rooms]">
                            <? for ($i = 1; $i <= 15; $i++) { ?>
                                <option
                                    <? if ($_REQUEST['cost']['rooms'] == $i) { ?>selected<? } ?>><?= $i ?></option>
                            <? } ?>
                        </select>
                    </div><!--sel-on-->

                    <div class="field-on field-on2">
                        <label>Площадь</label>
                        <input type="text" name="cost[square]"
                               value="<?= htmlspecialcharsbx($_REQUEST['cost']['square']) ?>"/>
                        <label>м<sup>2</sup></label>
                    </div><!--field-on-->

                    <? /*<div class="sel-on sel-on2">
						<label>Этаж</label>
						<select>
							<?for ($i = 1; $i <= 100; $i++) {?>
								<option><?=$i?></option>
							<?}?>
						</select>
					</div><!--sel-on-->*/ ?>

                    <div class="sel-on sel-on2 sel-on3">
                        <label>Этаж</label>
                        <select name="cost[stage]">
                            <? for ($i = 1; $i <= 100; $i++) { ?>
                                <option
                                    <? if ($_REQUEST['cost']['stage'] == $i) { ?>selected<? } ?>><?= $i ?></option>
                            <? } ?>
                        </select>
                    </div><!--sel-on-->
                </div><!--online-fields-->
            </div><!--online-cost-->

            <div class="but-pop but-pop4">
                <button type="submit">Оценить</button>
            </div>
        </div><!--block-calculate-->
    </div>
</form>

<? if (count($arResult['CALC_TOTAL']) > 0 && count($arResult['SIMILAR_IDS']) > 0) { ?>
    <div class="calculate-cost">
        <div class="t-online-calc">
            Ориентировочная стоимость Вашей квартиры
            <div class="ico-info ico-info2">
                <i>i</i>
                <div class="die-benef">
                    Стоимость рассчитывается как средняя на квартиры с аналогичными параметрами в указанном районе
                </div><!--die-benef-->
            </div>
        </div>

        <div class="all-cost"><?= number_format($arResult['CALC_TOTAL']['PRICE'], 0, '.', ' ') ?> руб.</div>

        <div class="slider-cost">
            <div class="value-cost" style="display: none">
                <input type="text" id="amount" readonly>
                <label>руб.</label>
            </div>

            <div id="slider-range-min"></div>
            <div class="scale">
                <div class="star-sc">
                    <div class="ot">От</div>
                    <div class="value-scale">
                        <span><?= number_format($arResult['CALC_TOTAL']['FROM']['PRICE'], 0, '.', ' ') ?> руб.</span>
                        <?= number_format($arResult['CALC_TOTAL']['FROM']['PRICE_M2'], 0, '.', ' ') ?>
                        руб./м<sup>2</sup>
                    </div>
                </div>

                <div class="end-sc">
                    <div class="ot">до</div>
                    <div class="value-scale">
                        <span><?= number_format($arResult['CALC_TOTAL']['TO']['PRICE'], 0, '.', ' ') ?> руб.</span>
                        <?= number_format($arResult['CALC_TOTAL']['TO']['PRICE_M2'], 0, '.', ' ') ?> руб./м<sup>2</sup>
                    </div>
                </div>
            </div><!--scale-->
        </div><!--slider-cost-->

        <div class="bottom-cost">
            <div class="but-pop but-addv">
                <button type="submit" class="inline" href="#form">Отправить заявку</button>
            </div>
            <div class="link-advertise"><a href="/personal/myobjects/add/">Разместить объявление</a></div>
        </div><!--bottom-cost-->
    </div><!--calculate-cost-->




    <div style="display: none">
        <div class="pop pop6" id="form">
            <div class="close"></div>
            <div class="t-pop t-pop4">Отправить заявку</div>
            <span class="err_cont"></span>
            <form id="send_form">
                <div class="field-pop field-pop4 ">
                    <input name="name" class="" placeholder="Ваше Имя *" value="" type="text">
                </div>

                <div class="field-pop field-pop4 ">
                    <input name="email" class="mail-input" placeholder="Ваша почта *" value="" type="text">
                </div>

                <div class="but-pop but-pop3">
                    <button type="submit" value="Отправить" class="send-form">Отправить</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        $('.send-form').on('click', function () {
            var form_search = $('#cost_request_form');
            var form_contact = $('#send_form');
            var error = false;
            form_contact.find('input').each(function () {
                if ($(this).val() == '') {
                    error = true;
                    $('.err_cont').text('Заполните, пожалуйста, все поля');
                }
            });
            var data_search = form_search.serializeArray();
            var data_contact = form_contact.serializeArray();
            var data = data_search.concat(data_contact);
            if (!error){
                $.ajax({
                    url: '/local/templates/morealty/ajax_php/cost_form.php',
                    data: data,
                    type: 'POST',
                    dataType: 'json',
                    success: function (data) {
                        if (data){
                            form_contact.hide();
                            $('.err_cont').text('Заявка № ' + data + ' успешно отправлена');
                        }else{
                            $('.err_cont').text('Ошибка отправки');
                        }
                        $.colorbox.resize();
                    },
                    error: function () {
                        console.log('error');
                    }
                });
            }
            return false;
        });
    </script>






    <script>
        $(function () {
            //slider cost
            if ($("#slider-range-min").html() != undefined) {
                $("#slider-range-min").slider({
                    range: "min",
                    value: <?=(int)$arResult['CALC_TOTAL']['PRICE']?>,
                    min: <?=(int)$arResult['CALC_TOTAL']['FROM']['PRICE']?>,
                    max: <?=(int)$arResult['CALC_TOTAL']['TO']['PRICE']?>,
                    slide: function (event, ui) {
                        $("#amount").val(ui.value);
                    }
                });
                $("#amount").val($("#slider-range-min").slider("value"));
            }

            setTimeout(function () {
                $('.value-cost').appendTo('.slider-cost .ui-slider-handle');
            }, 50);
        });
    </script>


    <div class="about-ruvit">
        <div class="img-ruvit"><img src="<?= SITE_TEMPLATE_PATH ?>/images/logo-ruvit.png" alt=""/></div>

        <div class="desc-ruvit">
            <p>Точную оценку вашей недвижимости вы можете получить у нашего партнёра-эксперта, компании РуВит.</p>

            <div class="cont-ruvit">
                Телефон: <span>8 988-233-98-80</span> E-mail: <span
                        class="mailme">price AT ruvit-sochi DOT ru</span></a>
                Сайт: <a target="_blank" href="http://www.ruvit-sochi.ru" rel="nofollow">www.ruvit-sochi.ru</a>
            </div>
        </div><!--desc-ruvit-->
    </div><!--about-ruvit-->


    <? if (count($arResult['SIMILAR_IDS']) > 0) {
        // подключить компонент списка, чтобы по SIMILAR_IDS вывести квартиры
        ?>

        <div class="b-propos b-propos2">
            <div class="t-emploe">Похожие предложения</div>


            <?


            $APPLICATION->IncludeComponent('wexpert:includer', "sort_in_sell",
                array('FILTER_PARAMS' => $_REQUEST['cost']),
                false
            );


            $APPLICATION->IncludeComponent("wexpert:iblock.list", "sell", Array(
                "ORDER" => array($_GET["SORT_BY"] => $_GET["SORT_ORDER"]),
                "FILTER" => array($GLOBALS['FILTER_PROPERTY'] => $GLOBALS['FILTER_VALUES'], 'ID' => $arResult['SIMILAR_IDS']),
                "IBLOCK_ID" => $arr,
                "PAGESIZE" => 2,
                "SELECT" => "DATE_CREATE",
                "GET_PROPERTY" => "Y",
                "CACHE_TIME" => 0
            ));
            ?>

        </div><!--b-propos-->

    <? } ?>

<? } else if ($_REQUEST['check'] != 'y') { ?>
<? } else { ?>

    <div class="calculate-cost">


        <p class="no-results">По вашему запросу похожих предложений не найдено..<br></p>


        <!--slider-cost-->

        <div class="bottom-cost">

            <div class="link-advertise"><a href="/personal/myobjects/add/">Разместить объявление</a></div>
        </div><!--bottom-cost-->
    </div>
<? } ?>




