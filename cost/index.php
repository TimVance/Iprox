<?
require( $_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php" );
$APPLICATION->SetTitle("Онлайн оценка квартиры в Сочи");
?>

<? $APPLICATION->IncludeComponent('wexpert:includer', 'cost_form', array()) ?>

<? require( $_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php" ); ?>