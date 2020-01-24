<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Ипотечный калькулятор");
?>

<?php

include_once("lib/getOptions.php");
include_once("lib/writeInfo.php");
use Bitrix\Main\Context;

$options         = new getOptions();
$family         = $options->getList("family");
$education         = $options->getList("education");
$employment_form         = $options->getList("employment_form");
$income_proof         = $options->getList("income_proof");
$experience         = $options->getList("experience");

// Обработки запроса
$request = Context::getCurrent()->getRequest();
$request_program = intval($request["program"]);
$request_type = intval($request["type"]);
$request_sum = intval(str_replace(' ', "", $request["sum"]));
$request_first_pay = intval(str_replace(' ', "", $request["first_pay"]));
$request_time = intval(str_replace(' ', "", $request["time"]));
$request_comment = $request["comment"];
$request_bank = $request["bank"];

$write = new writeInfo();
$id_iblock_item = writeInfo::writeBase($request->getPostList()->toArray());

?>

<div class="page ipoteka-wrap">

    <form class="container" method="post" action="step4.php">

        <input type="hidden" value="<?=$request_program?>" name="program">

        <div class="white-box">
            <div class="title-h2">Адрес</div>

            <div class="forms">
                <div class="row">
                    <div class="col-12">
                        <div class="text-field">
                            <div class="text-field__label">Адрес постоянной регистрации</div>
                            <input required name="address" type="text" value="" />
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="white-box">
            <div class="title-h2">Дополнительная информация</div>

            <div class="forms">
                <div class="row">
                    <div class="col-md-6">
                        Семейное положение
                        <select name="family" class="js-select2">
                            <?
                            foreach ($family as $item) {
                                echo '<option value="'.$item["ID"].'">'.$item["VALUE"].'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        Образование
                        <select name="education" class="js-select2">
                            <?
                            foreach ($education as $item) {
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
                            foreach ($employment_form as $item) {
                                echo '<option value="'.$item["ID"].'">'.$item["VALUE"].'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        Подтверждение дохода
                        <select name="income_proof" class="js-select2">
                            <?
                            foreach ($income_proof as $item) {
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
                            foreach ($experience as $item) {
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


<link rel="stylesheet" href="css/style.css?v=1">
<link rel="stylesheet" href="css/select2.min.css?v=1">
<script src="js/common.js?v=1"></script>
<script src="js/jquery.maskedinput.min.js?v=1"></script>
<script src="js/select2.min.js?v=1"></script>
<script>
    $(".js-select2").select2();
</script>


<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
