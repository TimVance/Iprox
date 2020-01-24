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

$write = new writeInfo();
$id_iblock_item = writeInfo::writeDop($request->getPostList()->toArray());

print_r($id_iblock_item);
print_r($_POST);

?>

<div class="page ipoteka-wrap">

    <div class="container">

        <div class="white-box">
            <div class="title-h2">Заявка успешно отправлена!</div>

            <div class="forms">
                <div class="row">
                    <div class="col-12">
                        <div class="text-field__label">Спасибо! Мы приняли вашу заявку, скоро с вами свяжется наш менеджер!</div>
                    </div>
                </div>
            </div>
            <br />
            <br />
        </div>

    </div>

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
