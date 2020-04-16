<div class="page ipoteka-wrap">

    <form class="container" method="post" action="step4.php">

        <div class="white-box" style="display: none">
            <div class="title-h2">Дополнительная информация</div>

            <div class="forms">
                <div class="row">
                    <div class="col-md-6">
                        Семейное положение
                        <select name="family" class="js-select2">
                            <?
                            foreach ($arrOptions["family"] as $item) {
                                echo '<option value="'.$item["ID"].'">'.$item["VALUE"].'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        Образование
                        <select name="education" class="js-select2">
                            <?
                            foreach ($arrOptions["education"] as $item) {
                                echo '<option value="'.$item["ID"].'">'.$item["VALUE"].'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-6">
                        <div class="text-field">
                            <div class="text-field__label">Количество детей (до 18 лет)</div>
                            <input required name="children" type="text" value="" />
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="white-box">
            <div class="title-h2">Основная работа</div>

            <div class="forms">
                <div class="row">
                    <div class="col-md-6">
                        Форма занятости
                        <select name="employment_form" class="js-select2">
                            <?
                            foreach ($arrOptions["employment_form"] as $item) {
                                echo '<option value="'.$item["ID"].'">'.$item["VALUE"].'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        Подтверждение дохода
                        <select name="income_proof" class="js-select2">
                            <?
                            foreach ($arrOptions["income_proof"] as $item) {
                                echo '<option value="'.$item["ID"].'">'.$item["VALUE"].'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <div class="text-field">
                            <div class="text-field__label">Работодатель (Название, ИНН)</div>
                            <input required name="address" type="text" value="" />
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="text-field">
                            <div class="text-field__label">Должность</div>
                            <input required name="position" type="text" value="" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        Стаж на текущем месте
                        <select name="experience" class="js-select2">
                            <?
                            foreach ($arrOptions["experience"] as $item) {
                                echo '<option value="'.$item["ID"].'">'.$item["VALUE"].'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-6">
                        <div class="text-field">
                            <div class="text-field__label">Доход в месяц, руб.</div>
                            <input required name="income" type="text" value="" />
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-field">
                            <div class="text-field__label">Сайт</div>
                            <input required name="address" type="text" value="" />
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-field">
                            <div class="text-field__label">Рабочий телефон</div>
                            <input required name="work_phone" type="text" value="" />
                        </div>
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

    </form>

</div>