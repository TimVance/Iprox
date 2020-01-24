<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Удалить объекты");

exit;
$elements = '29361,29359';
$arr_elements = explode(",", $elements);
foreach ($arr_elements as $el) {
    if (CIBlockElement::Delete($el)) echo '<br /> Объект '.$el.' удален';
        else echo '<br /> Объект '.$el.' не удален';
}





require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
