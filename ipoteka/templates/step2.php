<div class="page ipoteka-wrap step2" style="display: none">
    <h2>Шаг 2 из 3</h2>
    <div class="white-box">
        <div class="title-h2">Общие сведения</div>

        <div class="forms">
            <div class="row">
                <div class="col-12">
                    <div class="text-field">
                        <div class="text-field__label">ФИО*</div>
                        <input required name="fio" type="text" value="" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="text-field icon-calend">
                        <div class="text-field__label">Дата рождения*</div>
                        <input required name="born_date" type="text" value="" />
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="text-field">
                        <div class="text-field__label">Мобильный телефон*</div>
                        <input required name="phone" type="text" value="" class="phone-mask" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="text-field">
                        <div class="text-field__label">Email (необязательно)</div>
                        <input name="email" type="email" value="" />
                    </div>
                </div>
                <div class="col-md-6">
                    Пол*
                    <select name="sex" class="js-select2">
                        <?
                        foreach ($arrOptions["sex"] as $item) {
                            echo '<option value="'.$item["ID"].'">'.$item["VALUE"].'</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="col-12">
                    <div class="spacer"></div>
                </div>

                <div class="col-12">
                    <div class="title-h3">Паспортные данные <span>(необязательно)</span></div>
                </div>

                <div class="col-12">
                    <div class="text-field">
                        <div class="text-field__label">Серия и номер паспорта</div>
                        <input name="serial_number" type="text" value="" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="text-field icon-calend">
                        <div class="text-field__label">Дата выдачи паспорта</div>
                        <input name="date_of_issue" type="text" value="" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="text-field">
                        <div class="text-field__label">Код подразделения</div>
                        <input name="code_subdivision" type="text" value="" />
                    </div>
                </div>
                <div style="display: none" class="col-md-12">
                    <div class="text-field">
                        <div class="text-field__label">Наименование подразделения</div>
                        <input type="text" value="" />
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="text-field">
                        <div class="text-field__label">Место рождения</div>
                        <input name="born_place" type="text" value="" />
                    </div>
                </div>

                <div style="display: none" class="col-md-12">
                    <div class="gray-box">
                        <div class="row">
                            <div class="col-12">
                                <label class="switch-box">
                                    <div class="switch-box__wrap">
                                        <button class="switcher js-toggle-id" data-id="pasport-box"></button>
                                        <span>Меняли паспорт?</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="toggle-box" id="pasport-box">
                            <div class="row">
                                <div class="col-12">
                                    <div class="text-field">
                                        <div class="text-field__label">Серия и номер паспорта</div>
                                        <input type="text" value="" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-field icon-calend">
                                        <div class="text-field__label">Дата выдачи паспорта</div>
                                        <input type="text" value="" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-field">
                                        <div class="text-field__label">Код подразделения</div>
                                        <input type="text" value="" />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="button button--white button--sm">Добавить старый паспорт</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div style="display: none" class="col-md-12">
                    <div class="gray-box">
                        <div class="row">
                            <div class="col-12">
                                <label class="switch-box">
                                    <div class="switch-box__wrap">
                                        <button class="switcher js-toggle-id" data-id="old-fio"></button>
                                        <span>Меняли фамилию, имя или отчество?</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="toggle-box" id="old-fio">
                            <div class="row">
                                <div class="col-12">
                                    <div class="text-field">
                                        <div class="text-field__label">Предыдущая фамилия</div>
                                        <input type="text" value="" />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="text-field">
                                        <div class="text-field__label">Предыдущее имя</div>
                                        <input type="text" value="" />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="text-field">
                                        <div class="text-field__label">Предыдущее отчество</div>
                                        <input type="text" value="" />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="button button--white button--sm">Добавить предыдущее ФИО</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-md-6">
                    <div class="text-field">
                        <div class="text-field__label">СНИЛС</div>
                        <input name="snils" type="text" value="" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="text-field">
                        <div class="text-field__label">ИНН</div>
                        <input name="inn" type="text" value="" />
                    </div>
                </div>
                <div class="col-12">
                    <div class="text-field">
                        <div class="text-field__label">Адрес постоянной регистрации</div>
                        <input name="address" type="text" value="" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6"></div>
        <div class="col-md-6">
            <input type="button" class="button button--next w100p" value="Далее">
        </div>
    </div>

</div>
