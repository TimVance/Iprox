<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Ипотечный калькулятор");
?>

<?php

include_once("lib/getOptions.php");
use Bitrix\Main\Context;

$options         = new getOptions();
$sex         = $options->getList("sex");

// Обработки запроса
$request = Context::getCurrent()->getRequest();
$request_program = intval($request["program"]);
$request_type = intval($request["type"]);
$request_sum = intval(str_replace(' ', "", $request["sum"]));
$request_first_pay = intval(str_replace(' ', "", $request["first_pay"]));
$request_time = intval(str_replace(' ', "", $request["time"]));
$request_comment = $request["comment"];
$request_bank = $request["bank"];

?>

<div class="page ipoteka-wrap">

    <form class="container" method="post" action="step3.php">

        <input type="hidden" value="<?=$request_program?>" name="program">
        <input type="hidden" value="<?=$request_type?>" name="type">
        <input type="hidden" value="<?=$request_sum?>" name="sum">
        <input type="hidden" value="<?=$request_first_pay?>" name="first_pay">
        <input type="hidden" value="<?=$request_time?>" name="time">
        <input type="hidden" value="<?=$request_comment?>" name="comment">
        <input type="hidden" value="<?=$request_bank?>" name="bank">

        <div class="white-box">
            <div class="title-h2">Общие сведения</div>

            <div class="forms">
                <div class="row">
                    <div class="col-12">
                        <div class="text-field">
                            <div class="text-field__label">Фамилия</div>
                            <input required name="second_name" type="text" value="" />
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="text-field">
                            <div class="text-field__label">Имя</div>
                            <input required name="first_name" type="text" value="" />
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="text-field">
                            <div class="text-field__label">Отчество</div>
                            <input required name="patronymic" type="text" value="" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-field icon-calend">
                            <div class="text-field__label">Дата рождения</div>
                            <input required name="born_date" type="text" value="" />
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="text-field">
                            <div class="text-field__label">Мобильный телефон</div>
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
                        <div class="text-field">
                            <div class="text-field__label">Доп. мобильный телефон</div>
                            <input required name="additional_phone" type="text" value="" class="phone-mask" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        Пол
                        <select name="sex" class="js-select2">
                            <?
                            foreach ($sex as $item) {
                                echo '<option value="'.$item["ID"].'">'.$item["VALUE"].'</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-12">
                        <div class="spacer"></div>
                    </div>

                    <div class="col-12">
                        <div class="title-h3">Паспортные данные</div>
                    </div>

                    <div class="col-12">
                        <div class="text-field">
                            <div class="text-field__label">Серия и номер паспорта</div>
                            <input required name="serial_number" type="text" value="" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-field icon-calend">
                            <div class="text-field__label">Дата выдачи паспорта</div>
                            <input required name="date_of_issue" type="text" value="" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-field">
                            <div class="text-field__label">Код подразделения</div>
                            <input required name="code_subdivision" type="text" value="" />
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
                            <input required name="born_place" type="text" value="" />
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
                            <input required name="snils" type="text" value="" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-field">
                            <div class="text-field__label">ИНН</div>
                            <input required name="inn" type="text" value="" />
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


<link rel="stylesheet" href="css/style.css?v=1">
<link rel="stylesheet" href="css/select2.min.css?v=1">
<script src="js/common.js?v=1"></script>
<script src="js/jquery.maskedinput.min.js?v=1"></script>
<script src="js/select2.min.js?v=1"></script>
<script>
    $('input[name="born_date"], input[name="date_of_issue"]').mask('99.99.9999');
    $('input[name="serial_number"]').mask('99-99 999999');
    $('input[name="code_subdivision"]').mask('999-999');
    $('input[name="inn"]').mask('999999999999');
    $('input[name="snils"]').mask('999-999-999 99');
    $(".js-select2").select2();
</script>


<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
