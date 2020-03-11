<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Обрезка фотографий авито");


//exit;
//$iblock = 7; // Квартиры
$iblock = 8; // Дома

$page = (!empty($_GET["page"]) ? $_GET["page"] : 1);
$arOrder  = Array("ID" => 'DESC');

if ($iblock == 7) {
    $arFilter = array(
        "IBLOCK_ID"                        => $iblock,
        "ACTIVE"                           => "Y",
        "=PROPERTY_source"                 => "538",
        "!PROPERTY_watermark_avito_delete" => "1",
    );
}
elseif ($iblock == 8) {
    $arFilter = array(
        "IBLOCK_ID"                        => $iblock,
        "ACTIVE"                           => "Y",
        "=PROPERTY_source"                 => "542",
        "!PROPERTY_watermark_avito_delete" => "1",
    );
}

$arResult = CIBlockElement::GetList(
    $arOrder,
    $arFilter,
    array("ID", "IBLOCK_ID", "NAME"),
    Array("nPageSize" => 100, "iNumPage" => $page)
);

$all_count = CIBlockElement::GetList(
    $arOrder,
    $arFilter,
    Array(),
    false,
    Array()
);

$i          = 0;
$count_good = 0;
while ($item = $arResult->GetNext()) {
    $arFields = $item;
    $count_good++;
    //if ($count_good > 1) break;
    $PROPERTY_CODE = "photo_gallery";  // код свойства
    $VALUES        = array();
    $VALUES_OLD    = array();

    //Получаем свойство значение св-ва $PROPERTY_CODE
    $res = CIBlockElement::GetProperty($arFields["IBLOCK_ID"], $arFields["ID"], "sort", "asc", array("CODE" => $PROPERTY_CODE));
    while ($ob = $res->GetNext()) {
        $i++;
        $file_path = CFile::GetPath($ob['VALUE']); // Получаем путь к файлу
        if ($file_path) {
            $imsize = getimagesize($_SERVER["DOCUMENT_ROOT"] . $file_path); //Узнаём размер файла
            // Если размер больше установленного минимума
            if ($imsize[0]) {
                // Уменьшаем размер картинки
                $file = CFile::ResizeImageGet($ob['VALUE'], array('width' => $imsize[0], 'height' => ($imsize[1] - ($imsize[1] * 0.1) * 2)), BX_RESIZE_IMAGE_EXACT, true, array(), false, 80);
                // добавляем в массив VALUES новую уменьшенную картинку
                $VALUES[] = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"] . $file["src"]);
            } else {
                // добавляем в массив VALUES старую картинку
                $VALUES[] = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"] . $file_path);
            }
            // Собираем в массив ID старых файлов для их удаления (чтобы не занимали место)
            $VALUES_OLD[] = $ob['VALUE'];
        }

    }

    if (count($VALUES) > 0) {
        $ELEMENT_ID     = $arFields["ID"];  // код элемента
        $PROPERTY_VALUE = $VALUES;  // значение свойства
        // Установим новое значение для данного свойства данного элемента
        CIBlockElement::SetPropertyValuesEx($ELEMENT_ID, $arFields["IBLOCK_ID"], array($PROPERTY_CODE => $PROPERTY_VALUE));
        // Удаляем старые большие изображения
        foreach ($VALUES_OLD as $key => $val) {
            CFile::Delete($val);
        }
    }

    CIBlockElement::SetPropertyValuesEx($arFields["ID"], false, array("watermark_avito_delete" => "1"));
    echo '<div>' . $item["ID"] . ') ' . $item["NAME"] . '</div>';

}
echo '<br /><div>Сколько фотографий обработано: ' . $i . '</div>';
echo '<div>Сколько товаров обработано: ' . $count_good . '</div>';
echo '<div>Сколько всего осталось товаров: ' . $all_count . '</div>';

echo '<div style="
    text-align: center;
    padding: 20px 0;
    max-width: 80%;
    margin: auto;
    font-size: 19px;
    word-break: break-all;
" class="pagination">';
for ($k = 1; $k <= $all_count / 100; $k++) {
    echo '<a style="padding: 5px 10px;border: none;" href="/cut/?page=' . $k . '">' . $k . '</a>';
}
echo '</div>';
echo '<div>Страница - ' . $page . '</div>';

sleep(10);
if ($page <= $all_count / 100) echo "<script>document.location.href='/cut/?page=" . ($page) . "';</script>";

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
