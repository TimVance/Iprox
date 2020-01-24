<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$_REQUEST["SECTION_ID"] = 52;
$_REQUEST['catalog'] = "flat";
require($_SERVER["DOCUMENT_ROOT"]."/sell/section.php");
$APPLICATION->SetPageProperty("title", "Элитные апартаменты в Сочи: купить на портале «Мореалти»");
$APPLICATION->SetPageProperty("description", "Купить элитные апартаменты в Сочи. Продажа элитных квартир на портале недвижимости «Мореалти».");
$APPLICATION->SetTitle("Элитные апартаменты");?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>