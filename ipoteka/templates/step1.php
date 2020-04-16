<div class="page ipoteka-wrap main-wrap step1">

    <div class="white-box">

        <div class="forms">
            <div class="row">
                <div class="col-md-6">
                    Программа
                    <select name="program" class="js-select2">
                        <?
                        foreach ($arrOptions["program"] as $item) {
                            echo '<option value="'.$item["ID"].'">'.$item["VALUE"].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-6">
                    Тип клиента и потверждение дохода
                    <select name="type" class="js-select2">
                        <?
                        foreach ($arrOptions["type"] as $item) {
                            echo '<option value="'.$item["ID"].'">'.$item["VALUE"].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="bank-name col-md-12">
                    <div class="row">
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            <div class="text-field">
                                <div class="text-field__label">Введите название банка</div>
                                <input name="bank" type="text" value="" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="display:none" class="row">
                <div class="col-12">
                    <div class="box-heaedr">
                        <div>Дополнительные параметры</div>
                        <span class="toggle-link js-toggle-id" data-id="dop-param" data-toggle-text="Свернуть">Развернуть</span>
                    </div>
                </div>

                <div class="disp-none" id="dop-param">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="forms__row">
                                    <div class="forms__field">
                                        <div class="el-box">
                                            <label class="forms-check">
                                                <input type="checkbox" value=""/>
                                                <span>
															Страхование жизни

															<div class="drop-list">
																<div class="drop-list__title">Агентское</div>

																<ul class="drop-list__dropdown">
																	<li class="active"><span>Агентское</span></li>
																	<li><span>Коллективное</span></li>
																</ul>
															</div>
														</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="forms__field">
                                        <div class="el-box">
                                            <label class="forms-check">
                                                <input type="checkbox" value=""/>
                                                <span>
															Flex Price

															<div class="drop-list">
																<div class="drop-list__title">0%</div>

																<ul class="drop-list__dropdown">
																	<li class="active"><span>0%</span></li>
																	<li><span>0,25%</span></li>
																	<li><span>0,5%</span></li>
																</ul>
															</div>
														</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="forms__field">
                                        <div class="el-box">
                                            <label class="forms-check disabled">
                                                <input type="checkbox" value="" disabled/>
                                                <span>Нестандартная надбавка <input type="text" value="0.00"
                                                                                    class="inner-procent-input"
                                                                                    disabled> %</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="forms__row">
                                    <div class="forms__field">
                                        <div class="el-box">
                                            <label class="forms-check">
                                                <input type="checkbox" value=""/>
                                                <span>
															Абсолютная ставка

															<div class="drop-list">
																<div class="drop-list__title">0%</div>

																<ul class="drop-list__dropdown">
																	<li class="active"><span>0%</span></li>
																	<li><span>0,5%</span></li>
																</ul>
															</div>
														</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="forms__field">
                                        <div class="el-box">
                                            <label class="forms-check">
                                                <input type="checkbox" value=""/>
                                                <span>Материнский капитал</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="forms__field">
                                        <div class="el-box">
                                            <label class="forms-check disabled">
                                                <input type="checkbox" value="" disabled/>
                                                <span>Нестандартная заявка</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="hr"></div>
                </div>
            </div>

            <div class="row" style="display: none">
                <div class="col-12">
                    <div class="gray-box">
                        <div class="row">
                            <div class="col-12">
                                <div class="box-heaedr">
                                    <div>
                                        Материнский капитал

                                        <div class="drop-list">
                                            <div class="drop-list__title">В счёт ЧДП</div>

                                            <ul class="drop-list__dropdown">
                                                <li><span>В счёт оплаты продавцу</span></li>
                                                <li class="active"><span>В счёт ЧДП</span></li>
                                            </ul>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="text-field active">
                                    <div class="text-field__label">Размер субсидии</div>
                                    <input type="text" value="453 026" class="format-money"/>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="title-h4">Расчет на первые 6 мес. с учетом материнского капитала</div>
                                <div class="hr"></div>
                            </div>

                            <div class="col-12">
                                <div class="forms__row">
                                    <div class="forms__field w20p">
                                        <div class="text-field active lock">
                                            <div class="text-field__label">Сумма кредита</div>
                                            <input type="text" value="6 453 026" class="format-money"/>
                                        </div>
                                    </div>
                                    <div class="forms__field w20p">
                                        <div class="text-field active lock">
                                            <div class="text-field__label">ПВ</div>
                                            <div class="text-field__info">13.97%</div>
                                            <input type="text" value="1 047 974" class="format-money"/>
                                        </div>
                                    </div>
                                    <div class="forms__field w20p">
                                        <div class="text-field active lock">
                                            <div class="text-field__label">Срок, мес</div>
                                            <input type="text" value="360"/>
                                        </div>
                                    </div>
                                    <div class="forms__field w20p">
                                        <div class="text-field active lock">
                                            <div class="text-field__label">Платеж в мес</div>
                                            <input type="text" value="60 237" disabled/>
                                        </div>
                                    </div>
                                    <div class="forms__field w20p">
                                        <div class="text-field active lock">
                                            <div class="text-field__label">Ставка</div>
                                            <input type="text" value="10.75" disabled/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="box-heaedr">
                        <div class="title-h3">Расчет</div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="hr"></div>
                    <div class="errors"></div>
                </div>

                <div class="col-12" id="dop-param">
                    <div class="forms__row">
                        <div class="forms__field w25p">
                            <div class="text-field active">
                                <div class="text-field__label">Сумма кредита</div>
                                <input name="sum" type="text" value="6 000 000" class="format-money credit-sum"/>
                            </div>
                            <div class="field-hint">max 50 000 000</div>
                        </div>
                        <div class="forms__field w25p">
                            <div class="text-field active">
                                <div class="text-field__label">ПВ</div>
                                <div class="text-field__info">13.97%</div>
                                <input name="first_pay" type="text" value="1 501 000" class="format-money credit-pv"/>
                            </div>
                            <div class="field-hint">min 20.01%</div>
                        </div>
                        <div class="forms__field w15p">
                            <div class="text-field active">
                                <div class="text-field__label">Срок, мес</div>
                                <input name="time" type="text" class="credit-time" max="360" value="360"/>
                            </div>
                            <div class="field-hint">max 360</div>
                        </div>
                        <div class="forms__field w15p">
                            <div class="text-field active lock">
                                <div class="text-field__label">Платеж в мес</div>
                                <input name="price_per_month" type="text" class="format-money credit-pay" value="51 549" disabled/>
                            </div>
                        </div>
                        <div class="forms__field w20p">
                            <div class="text-field active lock">
                                <div class="text-field__label">Стоимость объекта</div>
                                <input name="price" type="text" class="format-money credit-amount" value="7 500 000" disabled/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="hr"></div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="box-heaedr">
                        <span class="comment-button js-toggle-id" data-id="comment"></span>
                        <div class="total-procent">9.75%</div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="hr"></div>
                </div>

                <div class="col-12 disp-none" id="comment">
                    <textarea name="comment" placeholder="Комментарий"></textarea>
                </div>
            </div>


        </div>
    </div>

    <div class="row">
        <div class="col-md-6"></div>
        <div class="col-md-6">
            <input type="submit" class="button button--next w100p" value="Далее">
        </div>
    </div>

</div>