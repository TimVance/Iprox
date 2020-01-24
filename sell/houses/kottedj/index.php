<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$_REQUEST["catalog"] = "houses";
$_REQUEST["SECTION_ID"] = 67;
require($_SERVER["DOCUMENT_ROOT"]."/sell/section.php");
$APPLICATION->SetPageProperty("title", "Купить коттедж в Сочи, цена: стоимость продажи на портале «Мореалти»");
$APPLICATION->SetPageProperty("description", "Купить коттедж в Сочи по доступной цене. Продажа и стоимость коттеджей на портале недвижимости «Мореалти». Узнайте, сколько стоит дом на сайте.");
$APPLICATION->SetTitle("Продажа коттеджей в Сочи");?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>