<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Панель колл-центра");
?>

<?php


/* Обработка формы */

if (!empty($_POST)) {

    $block_id = (!empty($_GET["block_id"]) ? $_GET["block_id"] : '7'); // ID инфлоблока Новостроек

    // Удление фотографии
    if (!empty($_POST["delete_photo"])) {

        $ELEMENT_ID          = $_POST["id_product"];
        $file                = $_POST["file"];
        $arFile["MODULE_ID"] = "iblock";
        $arFile["del"]       = "Y";

        CIBlockElement::SetPropertyValueCode($ELEMENT_ID, "photo_gallery", array($_POST["delete_photo"] => array("VALUE" => $arFile)));
        CFile::Delete($file);
    }

    // Запись параметров
    if (!empty($_POST["id"])) {

        // Удаление объекта
        if (!empty($_POST["delete"])) {
            if (CIBlock::GetPermission($block_id) >= 'W') {
                $DB->StartTransaction();
                if (!CIBlockElement::Delete($_POST["id"])) {
                    $strWarning .= 'Error!';
                    $DB->Rollback();
                } else {
                    $DB->Commit();
                    echo '<div style="color: green;">Лот №' . $_POST["id"] . ' удален</div>';
                }
            }
        } else {
            $id                = intval($_POST["id"]);
            $date              = htmlspecialchars($_POST["date"]);
            $tel               = htmlspecialchars($_POST["tel"]);
            $street            = htmlspecialchars($_POST["street"]);
            $system_price_from = intval($_POST["system_price_from"]);
            $comment           = htmlspecialchars($_POST["comment"]);
            $active            = (!empty($_POST["active"]) ? 'Y' : 'N');
            $status            = $_POST["status"];

            if ($block_id == 19 || $block_id == 9) {
                $price_m2_ot = $_POST["price_m2_ot"];
                $square_ot   = $_POST["square_ot"];
            }

            $el = new CIBlockElement;

            $images = [];

            // Запись файлов
            if (!empty($_FILES["files"]['type'][0])) {

                for ($i = 0; $i < count($_FILES["files"]['type']); $i++) {
                    $image_type = explode("/", $_FILES["files"]['type'][$i]);
                    $uploaddir  = '../upload/call/';
                    $uploadfile = $uploaddir . rand(1, 1000) . '_' . time() . '.' . $image_type[1];


                    if (move_uploaded_file($_FILES["files"]['tmp_name'][$i], $uploadfile)) {
                        echo "<div>Загрузка изображения прошла успешно.</div>";
                    } else {
                        echo "<div>Ошибка загрузки изображения.</div>";
                    }
                    $images[]["VALUE"] = CFile::MakeFileArray($uploadfile);
                }
            }

            if ($block_id != 19 && $block_id != 9) {
                CIBlockElement::SetPropertyValuesEx(
                    $id, //ID обновляемого элемента
                    $block_id, //ID инфоблока
                    array(
                        'comment'     => $comment,
                        'street'      => $street,
                        'call_status' => $status,
                        'price'       => $system_price_from,
                        'tel'         => $tel,
                    )
                );
            } else {
                CIBlockElement::SetPropertyValuesEx(
                    $id, //ID обновляемого элемента
                    $block_id, //ID инфоблока
                    array(
                        'comment'        => $comment,
                        'street'         => $street,
                        'call_status'    => $status,
                        'rieltor_phone'  => $tel,
                        'price_flat_min' => $system_price_from,
                        'price_m2_ot'    => $price_m2_ot,
                        'square_ot'      => $square_ot
                    )
                );
            }


            CIBlockElement::SetPropertyValueCode($id, "photo_gallery", $images);

            $arLoadProductArray = array(
                "MODIFIED_BY" => $USER->GetID(), // элемент изменен текущим пользователем
                "ACTIVE"      => $active,
            );

            $res = $el->Update($id, $arLoadProductArray);
            if (!empty($res)) echo '<div style="color: green;">Лот №' . $id . ' изменен</div>';
        }
        unset($_POST);
        unset($_FILES);
    }
}

/* Обработка формы */

?>


<?

$block_id = (!empty($_GET["block_id"]) ? $_GET["block_id"] : '7'); // ID инфлоблока Новостроек
$start    = (!empty($request->get("start")) ? $request->get("start") : date('Y-m-d', time())) . ' 00:00:00';
$end      = (!empty($request->get("end")) ? $request->get("end") : date('Y-m-d', time())) . ' 23:59:59';
$count    = 5;
$page     = (!empty($_GET["page"]) ? $_GET["page"] : 1);
$status   = (!empty($_GET["status"]) ? $_GET["status"] : "");
$public   = (!empty($_GET["only_active"]) ? "Y" : "");
$accept   = (!empty($_GET["only_active"]) ? "Да" : "");

switch ($block_id) {
    case 7:
        $APPLICATION->SetTitle("Работа с вторичкой");
        break;
    case 8:
        $APPLICATION->SetTitle("Работа с домами");
        break;
    case 19:
        $APPLICATION->SetTitle("Работа с новостройками");
        break;
    case 10:
        $APPLICATION->SetTitle("Работа с участками");
        break;
    case 9:
        $APPLICATION->SetTitle("Коттеджные поселки и комплексы таунхаусов");
        break;
}

$Select_filter["IBLOCK_ID"] = $block_id;
if ($public == "Y") {
    $Select_filter["ACTIVE"]                     = "Y";
    $Select_filter["PROPERTY_IS_ACCEPTED_VALUE"] = "Да";
}

$Select_filter["PROPERTY_call_status"] = $status;
$Select_filter["!IBLOCK_SECTION_ID"]   = array("2", "155");
$Select_filter[]                       = array(
    "LOGIC"         => "AND",
    ">=DATE_CREATE" => ConvertTimeStamp(strtotime($start), 'FULL'),
    "<=DATE_CREATE" => ConvertTimeStamp(strtotime($end), 'FULL'),
);

$Sort_filter = array("TIMESTAMP_X" => "ASC");
if ($block_id == 19) $Sort_filter = array("PROPERTY_price_flat_min" => "ASC");

// Получаем объекты
$arResults = CIBlockElement::GetList(
    $Sort_filter,
    $Select_filter,
    false,
    array("nPageSize" => $count, "iNumPage" => $page)
);

//Сколько всего товаров
$all_count = CIBlockElement::GetList(
    $Sort_filter,
    $Select_filter,
    array(),
    false,
    array()
);

// Собираем статусы
if (CModule::IncludeModule("iblock")):
    $property_enums = CIBlockPropertyEnum::GetList(array("ID" => "ASC", "SORT" => "ASC"), array("IBLOCK_ID" => $block_id, "CODE" => "call_status"));
    while ($enum_fields = $property_enums->GetNext()) {
        $status_content[$enum_fields["ID"]] = $enum_fields["VALUE"];
    }
endif;


// Города
$cityes_content = array();
if (CModule::IncludeModule("iblock")):
    $arFilter = array(
        'IBLOCK_ID' => 5,
    );
    $res      = CIBlockElement::GetList(false, $arFilter, array('IBLOCK_ID', 'ID', 'NAME'));
    while ($el = $res->GetNext()):
        $cityes_content[$el['ID']] = $el['NAME'];
    endwhile;
endif;


//Формируем таблицу
echo '<div id="horizontal-scroller" class="call-center">';
echo '<form method="get">';
echo '<div>Дата создания</div><span><input type="date" class="date start" name="start" value="' . substr($start, 0, 10) . '">-';
echo '<input type="date" class="date end" name="end" value="' . substr($end, 0, 10) . '"></span>';

echo '<select style="width:140px" name="block_id">';
echo '<option ' . ($block_id == 7 ? 'selected ' : '') . 'value="7">Квартиры</option>';
echo '<option ' . ($block_id == 8 ? 'selected ' : '') . 'value="8">Дома, коттеджи, таунхаусы</option>';
echo '<option ' . ($block_id == 19 ? 'selected ' : '') . 'value="19">Новостройки</option>';
echo '<option ' . ($block_id == 10 ? 'selected ' : '') . 'value="10">Участки</option>';
echo '<option ' . ($block_id == 9 ? 'selected ' : '') . 'value="9">Коттеджные поселки и комплексы таунхаусов</option>';
echo '</select>';

if (!empty($block_id)) {
    echo '<select style="width:140px" name="status">';
    echo '<option value="">Статус...</option>';
    foreach ($status_content as $id_status => $name_status) {
        echo '<option ' . ($status == $id_status ? 'selected ' : '') . 'value="' . $id_status . '">' . $name_status . '</option>';
    }
    echo '</select>';
}

echo '<div style="margin-right: 10px;display: inline-block;"><input type="checkbox" ' . (!empty($public) ? "checked" : '') . ' value="ACTIVE" name="only_active"> Опубликованные</div>';

echo '<input type="submit" value="Получить"><br /><br />';
echo '</form>';

$arResult = array();
while ($item = $arResults->GetNext()) {
    $arResult[] = $item;
    echo '<form enctype="multipart/form-data" name="product_' . $item["ID"] . '" method="post" id="form_product' . $item["ID"] . '"></form>';
}

// Форма для удаления фото
echo '
        <form style="display: none;" method="post" class="delete_form">
            <input type="text" name="delete_photo" value="42360">
            <input type="text" name="id_product" value="312">
            <input type="submit">
        </form>
    ';

echo '<div class="table">';
echo '<div class="row th">';
echo '<div>Фото</div>';
echo '<div>Дата создания</div>';
echo '<div>Дата изменения</div>';
echo '<div>Лот</div>';
echo '<div>Наименование</div>';
echo '<div>Телефон</div>';
echo '<div class="city">Город</div>';
echo '<div>Адрес</div>';
if ($block_id != 19 && $block_id != 9) echo '<div>Цена</div>';
else echo '<div>Мин стоимость квартиры</div>';
if ($block_id == 19 || $block_id == 9) echo '<div>Цена м² от</div>';
if ($block_id == 19 || $block_id == 9) echo '<div>Площадь квартир от</div>';
echo '<div>Комментарий</div>';
echo '<div>Статус</div>';
echo '<div style="display:none">Удалить объект</div>';
echo '<div>Активен</div>';
echo '<div>Отправить</div>';
echo '</div>';
$total = 0;


foreach ($arResult as $item) {

    $properties = [];
    $arProperty = CIBlockElement::GetProperty($block_id, $item["ID"], "sort", "asc", array());
    while ($property_item = $arProperty->GetNext()) {
        $properties[$property_item["CODE"]][] = $property_item;
    }
    echo '<div class="row td" ' . ($item["ACTIVE"] == "N" ? 'style="opacity:.5"' : '') . '>';
    echo '<div class="photos">
                    <div class="photos-wrap">';
    foreach ($properties["photo_gallery"] as $photo) {
        if (empty($photo["VALUE"])) continue;
        $thumb = CFile::ResizeImageGet($photo["VALUE"], array('width' => 64, 'height' => 64), BX_RESIZE_IMAGE_PROPORTIONAL, true);
        $image = CFile::GetPath($photo["VALUE"]);
        echo '<a file="' . $photo["VALUE"] . '" photo="' . $photo["PROPERTY_VALUE_ID"] . '" product="' . $item["ID"] . '" data-fancybox="gallery' . $item["ID"] . '" class="image-wrap" href="' . $image . '"><img width="' . $thumb["width"] . '" height="' . $thumb["height"] . '" src="' . $thumb["src"] . '"><i title="Удалить фотографию" class="delete_photo">x</i></a>';
    }
    echo '  
                    <button title="Добавить фотографию" class="add-photo">+</button>
                    <input form="form_product' . $item["ID"] . '" class="upload-photo" type="file" name="files[]" multiple>
                </div>
            </div>';
    echo '<div class="date">
                <input form="form_product' . $item["ID"] . '" name="id" type="hidden" value="' . $item["ID"] . '">
                <input form="form_product' . $item["ID"] . '" name="block_id" type="hidden" value="' . $item["BLOCK_ID"] . '">
                <!--<input form="form_product' . $item["ID"] . '" type="text" name="date" value="' . date("d-m-Y", $item["DATE_CREATE_UNIX"]) . '">-->
                ' . date("d-m-Y", $item["DATE_CREATE_UNIX"]) . '
            </div>';
    echo '<div class="date">' . $item["TIMESTAMP_X"] . '</div>';
    echo '<div class="id">' . $item["ID"] . '</div>';
    echo '<div class="name">' . $item["NAME"] . '</div>';
    echo '<div class="phone">';
    if ($block_id != 19 && $block_id != 9) echo '<input form="form_product' . $item["ID"] . '" type="text" name="tel" value="' . $properties["tel"][0]["VALUE"] . '">';
    else echo '<input form="form_product' . $item["ID"] . '" type="text" name="tel" value="' . $properties["rieltor_phone"][0]["VALUE"] . '">';
    echo '</div>';
    echo '<div class="city">' . $cityes_content[$properties["city"][0]["VALUE"]] . '</div>';
    echo '<div class="address">
                <input form="form_product' . $item["ID"] . '" type="text" name="street" value="' . $properties["street"][0]["VALUE"] . '">
            </div>';
    echo '<div class="price">';
    if ($block_id != 19 && $block_id != 9) echo '<input form="form_product' . $item["ID"] . '" type="text" name="system_price_from" value="' . $properties["price"][0]["VALUE"] . '">';
    else echo '<input form="form_product' . $item["ID"] . '" type="text" name="system_price_from" value="' . $properties["price_flat_min"][0]["VALUE"] . '">';
    echo '</div>';

    // Параметры для Новостроек
    if ($block_id == 19 || $block_id == 9) {

        echo '<div class="price_m2_ot">';
        echo '<input form="form_product' . $item["ID"] . '" type="text" name="price_m2_ot" value="' . $properties["price_m2_ot"][0]["VALUE"] . '">';;
        echo '</div>';

        echo '<div class="square_ot">';
        echo '<input form="form_product' . $item["ID"] . '" type="text" name="square_ot" value="' . $properties["square_ot"][0]["VALUE"] . '">';;
        echo '</div>';

    }

    echo '<div class="comment">
                <input form="form_product' . $item["ID"] . '" type="text" name="comment" placeholder="Комметарий к объекту" value="' . $properties["comment"][0]["VALUE"] . '">
            </div>';
    echo '<div class="status">';
    echo '<select form="form_product' . $item["ID"] . '" name="status">';
    echo '<option>Выбрать...</option>';
    foreach ($status_content as $id_status => $name_status) {
        echo '<option ' . ($properties["call_status"][0]["VALUE_ENUM"] == $name_status ? 'selected ' : '') . 'value="' . $id_status . '">' . $name_status . '</option>';
    }
    echo '<select>';
    echo '</div>';
    echo '<div style="display: none" class="delete">
                <input form="form_product' . $item["ID"] . '" type="checkbox" name="delete">
            </div>';
    echo '<div class="active">
                <input form="form_product' . $item["ID"] . '" type="checkbox" name="active" ' . ($item["ACTIVE"] == "Y" ? 'checked' : '') . '>
            </div>';
    echo '<div class="save">
                <input form="form_product' . $item["ID"] . '" type="submit" value="Отправить">
            </div>';
    echo '</div>';
    $total++;
}
echo '</div>';
echo '</div>';
echo '<div class="total">' . $total . ' записей | Страница ' . $page . '</div>';
echo '<div class="total">Всего ' . $all_count . ' записей</div>';
echo '<div  class="pagination">';

for ($i = 1; $i <= ($all_count / 5); $i++) {
    echo '<a ' . ($page == $i ? 'class="active" ' : '') . 'href="' . $APPLICATION->GetCurPageParam("page=" . $i, array('page')) . '">' . $i . '</a>';
}
if ($all_count % 5 > 0) echo '<a ' . ($page == $i ? 'class="active" ' : '') . 'href="' . $APPLICATION->GetCurPageParam("page=" . $i, array('page')) . '">' . $i . '</a>';
echo '</div>';


if ($block_id == 7) {


    // Получаем список свойств для вторички
    $parametrs_for_select = array("type_building", "decoration");
    if (CModule::IncludeModule("iblock")):
        foreach ($parametrs_for_select as $paramter_code) {
            $property_enums = CIBlockPropertyEnum::GetList(array("ID" => "ASC", "SORT" => "ASC"), array("IBLOCK_ID" => $block_id, "CODE" => $paramter_code));
            while ($enum_fields = $property_enums->GetNext()) {
                $param_for_addform[$enum_fields["PROPERTY_CODE"]][$enum_fields["ID"]] = $enum_fields["VALUE"];
            }
        }
    endif;


    // Добавить новый объект
    if ($_POST["action"] == "add_new_item") {


        $el = new CIBlockElement;

        $PROP                  = array();
        $PROP["room_number"]   = $_POST["room_number"];
        $PROP["type_building"] = array('VALUE' => $_POST["type_building"]);
        $PROP["floor"]         = $_POST["floor"];
        $PROP["floors"]        = $_POST["floors"];
        $PROP["tel"]           = $_POST["tel"];
        $PROP["square"]        = $_POST["square"];
        $PROP["square_lived"]  = $_POST["square_lived"];
        $PROP["kitchen"]       = $_POST["kitchen"];
        $PROP["decoration"]    = array('VALUE' => $_POST["decoration"]);
        $PROP["price"]         = $_POST["price"];
        $PROP["price_1m"]      = $_POST["price_1m"];


        $arLoadProductArray = array(
            "MODIFIED_BY"       => $USER->GetID(),
            "IBLOCK_SECTION_ID" => false,
            "IBLOCK_ID"         => $block_id,
            "PROPERTY_VALUES"   => $PROP,
            "NAME"              => $_POST["name"],
            "ACTIVE"            => "N",
            "DETAIL_TEXT"       => $_POST["text"],
        );

        if ($PRODUCT_ID = $el->Add($arLoadProductArray))
            echo "Объект успешно добавлен, ему присвоин номер: " . $PRODUCT_ID;
        else
            echo "Error: " . $el->LAST_ERROR;
    }

    echo '<div class="add_new_item">
        <h2>Добавить квартиру</h2>';
    echo '
        <form name="add_new_item" method="post">
            <input type="hidden" name="action" value="add_new_item">
            <div class="input-wrap">
                <span>Наименование объекта</span>
                <input type="text" name="name" required>
            </div>
            <div class="input-wrap">
                <span>Колличество комнат</span>
                <input type="number" name="room_number">
            </div>
            <div class="input-wrap">
                <span>Тип строения</span>
                <select name="type_building">';
    foreach ($param_for_addform["type_building"] as $key => $value) {
        echo '<option value="' . $key . '">' . $value . '</option>';
    }
    echo '
                </select>
            </div>
            <div class="input-wrap">
                <span>Этаж</span>
                <input type="text" name="floor">
            </div>
            <div class="input-wrap">
                <span>Этажей</span>
                <input type="text" name="floors">
            </div>
            <div class="input-wrap">
                <span>Телефон собственника</span>
                <input type="text" name="tel">
            </div>
            <div class="input-wrap">
                <span>Площадь</span>
                <input type="number" name="square">
            </div>
            <div class="input-wrap">
                <span>Жилая площадь</span>
                <input type="number" name="square_lived">
            </div>
            <div class="input-wrap">
                <span>Кухня</span>
                <input type="number" name="kitchen">
            </div>
            <div class="input-wrap">
                <span>Отделка</span>
                <select name="decoration">';
    foreach ($param_for_addform["decoration"] as $key => $value) {
        echo '<option value="' . $key . '">' . $value . '</option>';
    }
    echo '
                </select>
            </div>
            <div class="input-wrap">
                <span>Цена</span>
                <input type="number" name="price" required>
            </div>
            <div class="input-wrap">
                <span>Стоимость одного метра</span>
                <input type="number" name="price_1m">
            </div>
            <div class="input-wrap full">
                <span>Детальное опсание</span>
                <textarea name="text" cols="30" rows="10"></textarea>
            </div>
            <div class="clear"></div>
            <input type="submit" value="Добавить новый объект">
        </form>
    </div>
    ';
}
if ($block_id == 19) {


    // Получаем список свойств для новостроек
    if (CModule::IncludeModule("iblock")):
        $property_enums = CIBlockElement::GetList(array("ID" => "ASC", "SORT" => "ASC"), array("IBLOCK_ID" => 5));
        while ($enum_fields = $property_enums->GetNext()) {
            $param_for_addform["city"][$enum_fields["ID"]] = $enum_fields["NAME"];
            //print_r($enum_fields);
        }
        $property_enums = CIBlockElement::GetList(array("ID" => "ASC", "SORT" => "ASC"), array("IBLOCK_ID" => 14));
        while ($enum_fields = $property_enums->GetNext()) {
            $param_for_addform["district"][$enum_fields["ID"]] = $enum_fields["NAME"];
            //print_r($enum_fields);
        }
        $property_enums = CIBlockElement::GetList(array("ID" => "ASC", "SORT" => "ASC"), array("IBLOCK_ID" => 15));
        while ($enum_fields = $property_enums->GetNext()) {
            $param_for_addform["microdistrict"][$enum_fields["ID"]] = $enum_fields["NAME"];
            //print_r($enum_fields);
        }
        $parametrs_for_select = array("lift", "class", "type", "parking");
        foreach ($parametrs_for_select as $paramter_code) {
            $property_enums = CIBlockPropertyEnum::GetList(array("ID" => "ASC", "SORT" => "ASC"), array("IBLOCK_ID" => $block_id, "CODE" => $paramter_code));
            while ($enum_fields = $property_enums->GetNext()) {
                $param_for_addform[$enum_fields["PROPERTY_CODE"]][$enum_fields["ID"]] = $enum_fields["VALUE"];
                //print_r($enum_fields);
            }
        }
    endif;


    // Добавить новый объект
    if ($_POST["action"] == "add_new_novostroyka") {


        $el = new CIBlockElement;

        $PROP                    = array();
        $PROP["city"]            = $_POST["city"];
        $PROP["district"]        = $_POST["district"];
        $PROP["microdistrict"]   = $_POST["microdistrict"];
        $PROP["price_m2_ot"]     = $_POST["price_m2_ot"];
        $PROP["price_m2_do"]     = $_POST["price_m2_do"];
        $PROP["deadline"]        = $_POST["deadline"];
        $PROP["price_flat_min"]  = $_POST["price_flat_min"];
        $PROP["square_ot"]       = $_POST["square_ot"];
        $PROP["square_do"]       = $_POST["square_do"];
        $PROP["lift"]            = $_POST["lift"];
        $PROP["class"]           = $_POST["class"];
        $PROP["type"]            = $_POST["type"];
        $PROP["parking"]         = $_POST["parking"];
        $PROP["distance_to_sea"] = $_POST["distance_to_sea"];
        $PROP["ceilings_height"] = $_POST["ceilings_height"];
        $PROP["rieltor_name"]    = $_POST["rieltor_name"];
        $PROP["rieltor_phone"]   = $_POST["rieltor_phone"];


        $arLoadProductArray = array(
            "MODIFIED_BY"       => $USER->GetID(),
            "IBLOCK_SECTION_ID" => false,
            "IBLOCK_ID"         => $block_id,
            "PROPERTY_VALUES"   => $PROP,
            "NAME"              => $_POST["name"],
            "ACTIVE"            => "N",
            "DETAIL_TEXT"       => $_POST["text"],
        );

        if ($PRODUCT_ID = $el->Add($arLoadProductArray))
            echo "Объект успешно добавлен, ему присвоин номер: " . $PRODUCT_ID;
        else
            echo "Error: " . $el->LAST_ERROR;
    }

    echo '<div class="add_new_item">
        <h2>Добавить Новостройку</h2>';
    echo '
        <form name="add_new_novostroyka" method="post">
            <input type="hidden" name="action" value="add_new_novostroyka">
            <div class="input-wrap">
                <span>Наименование объекта</span>
                <input type="text" name="name" required>
            </div>
            <div class="input-wrap">
                <span>Город</span>
                <select name="city">';
    foreach ($param_for_addform["city"] as $key => $value) {
        echo '<option value="' . $key . '">' . $value . '</option>';
    }
    echo '
                </select>
            </div>
            <div class="input-wrap">
                <span>Район</span>
                <select name="district">';
    echo '<option value="">-</option>';
    foreach ($param_for_addform["district"] as $key => $value) {
        echo '<option value="' . $key . '">' . $value . '</option>';
    }
    echo '
                </select>
             </div>
             <div class="input-wrap">
                <span>Микрорайон</span>
                <select name="microdistrict">';
    echo '<option value="">-</option>';
    foreach ($param_for_addform["microdistrict"] as $key => $value) {
        echo '<option value="' . $key . '">' . $value . '</option>';
    }
    echo '
                </select>
            </div>
            <div class="input-wrap">
                <span>Цена м2 от</span>
                <input type="number" name="price_m2_ot">
            </div>
            <div class="input-wrap">
                <span>Цена м2 до</span>
                <input type="number" name="price_m2_do">
            </div>
            <div class="input-wrap">
                <span>Срок сдачи</span>
                <input type="text" name="deadline">
            </div>
            <div class="input-wrap">
                <span>Мин. стоимость за квартиру</span>
                <input type="number" name="price_flat_min">
            </div>
            <div class="input-wrap">
                <span>Площадь квартир от</span>
                <input type="text" name="square_ot">
            </div>
            <div class="input-wrap">
                <span>Площадь квартир до</span>
                <input type="text" name="square_do">
            </div>
            <div class="input-wrap">
                <span>Лифт</span>
                <select name="lift">';
    foreach ($param_for_addform["lift"] as $key => $value) {
        echo '<option value="' . $key . '">' . $value . '</option>';
    }
    echo '
                </select>
            </div>
            <div class="input-wrap">
                <span>Класс</span>
                <select name="class">';
    foreach ($param_for_addform["class"] as $key => $value) {
        echo '<option value="' . $key . '">' . $value . '</option>';
    }
    echo '
                </select>
            </div>
            <div class="input-wrap">
                <span>Тип здания</span>
                <select name="type">';
    foreach ($param_for_addform["type"] as $key => $value) {
        echo '<option value="' . $key . '">' . $value . '</option>';
    }
    echo '
                </select>
            </div>
            <div class="input-wrap">
                <span>Парковка</span>
                <select name="parking">';
    foreach ($param_for_addform["parking"] as $key => $value) {
        echo '<option value="' . $key . '">' . $value . '</option>';
    }
    echo '
                </select>
            </div>
            <div class="input-wrap">
                <span>Расстояние до моря</span>
                <input type="text" name="distance_to_sea">
            </div>
            <div class="input-wrap">
                <span>Высота потолков м</span>
                <input type="text" name="ceilings_height">
            </div>
            <div class="input-wrap">
                <span>Имя риелтора</span>
                <input type="text" name="rieltor_name">
            </div>
            <div class="input-wrap">
                <span>Телефон риелтора</span>
                <input type="text" name="rieltor_phone">
            </div>
            <div class="input-wrap full">
                <span>Детальное описание</span>
                <textarea name="text" cols="30" rows="10"></textarea>
            </div>
            <div class="clear"></div>
            <input type="submit" value="Добавить новый объект">
        </form>
    </div>
    ';
}
if ($block_id == 9) {


    // Получаем список свойств для новостроек
    if (CModule::IncludeModule("iblock")):
        $property_enums = CIBlockElement::GetList(array("ID" => "ASC", "SORT" => "ASC"), array("IBLOCK_ID" => 5));
        while ($enum_fields = $property_enums->GetNext()) {
            $param_for_addform["city"][$enum_fields["ID"]] = $enum_fields["NAME"];
            //print_r($enum_fields);
        }
        $property_enums = CIBlockElement::GetList(array("ID" => "ASC", "SORT" => "ASC"), array("IBLOCK_ID" => 14));
        while ($enum_fields = $property_enums->GetNext()) {
            $param_for_addform["district"][$enum_fields["ID"]] = $enum_fields["NAME"];
            //print_r($enum_fields);
        }
        $property_enums = CIBlockElement::GetList(array("ID" => "ASC", "SORT" => "ASC"), array("IBLOCK_ID" => 15));
        while ($enum_fields = $property_enums->GetNext()) {
            $param_for_addform["microdistrict"][$enum_fields["ID"]] = $enum_fields["NAME"];
            //print_r($enum_fields);
        }
        $parametrs_for_select = array("class", "type", "parking", "territory");
        foreach ($parametrs_for_select as $paramter_code) {
            $property_enums = CIBlockPropertyEnum::GetList(array("ID" => "ASC", "SORT" => "ASC"), array("IBLOCK_ID" => $block_id, "CODE" => $paramter_code));
            while ($enum_fields = $property_enums->GetNext()) {
                $param_for_addform[$enum_fields["PROPERTY_CODE"]][$enum_fields["ID"]] = $enum_fields["VALUE"];
                //print_r($enum_fields);
            }
        }
    endif;


    // Добавить новый объект
    if ($_POST["action"] == "add_new_kottedzh") {


        $el = new CIBlockElement;

        $PROP                    = array();
        $PROP["city"]            = $_POST["city"];
        $PROP["district"]        = $_POST["district"];
        $PROP["microdistrict"]   = $_POST["microdistrict"];
        $PROP["class"]           = $_POST["class"];
        $PROP["type"]            = $_POST["type"];
        $PROP["parking"]         = $_POST["parking"];
        $PROP["territory"]       = $_POST["territory"];
        $PROP["distance_to_sea"] = $_POST["distance_to_sea"];
        $PROP["price_m2_ot"]     = $_POST["price_m2_ot"];
        $PROP["price_m2_do"]     = $_POST["price_m2_do"];
        $PROP["price_flat_min"]  = $_POST["price_flat_min"];
        $PROP["square_ot"]       = $_POST["square_ot"];
        $PROP["square_do"]       = $_POST["square_do"];
        $PROP["deadline"]        = $_POST["deadline"];
        $PROP["rieltor_phone"]   = $_POST["rieltor_phone"];
        $PROP["street"]          = $_POST["street"];


        $arLoadProductArray = array(
            "MODIFIED_BY"       => $USER->GetID(),
            "IBLOCK_SECTION_ID" => false,
            "IBLOCK_ID"         => $block_id,
            "PROPERTY_VALUES"   => $PROP,
            "NAME"              => $_POST["name"],
            "ACTIVE"            => "N",
            "DETAIL_TEXT"       => $_POST["text"],
        );

        if ($PRODUCT_ID = $el->Add($arLoadProductArray))
            echo "Объект успешно добавлен, ему присвоин номер: " . $PRODUCT_ID;
        else
            echo "Error: " . $el->LAST_ERROR;
    }

    echo '<div class="add_new_item">
        <h2>Добавить коттеджные поселки и комплексы таунхаусов</h2>';
    echo '
        <form name="add_new_kottedzh" method="post">
            <input type="hidden" name="action" value="add_new_kottedzh">
            <div class="input-wrap">
                <span>Наименование объекта</span>
                <input type="text" name="name" required>
            </div>
            <div class="input-wrap">
                <span>Город</span>
                <select name="city">';
    foreach ($param_for_addform["city"] as $key => $value) {
        echo '<option value="' . $key . '">' . $value . '</option>';
    }
    echo '
                </select>
            </div>
            <div class="input-wrap">
                <span>Район</span>
                <select name="district">';
    echo '<option value="">-</option>';
    foreach ($param_for_addform["district"] as $key => $value) {
        echo '<option value="' . $key . '">' . $value . '</option>';
    }
    echo '
                </select>
             </div>
             <div class="input-wrap">
                <span>Микрорайон</span>
                <select name="microdistrict">';
    echo '<option value="">-</option>';
    foreach ($param_for_addform["microdistrict"] as $key => $value) {
        echo '<option value="' . $key . '">' . $value . '</option>';
    }
    echo '
                </select>
            </div>
            <div class="input-wrap">
                <span>Класс</span>
                <select name="class">';
    foreach ($param_for_addform["class"] as $key => $value) {
        echo '<option value="' . $key . '">' . $value . '</option>';
    }
    echo '
                </select>
             </div>
                        <div class="input-wrap">
                <span>Тип здания</span>
                <select name="type">';
    foreach ($param_for_addform["type"] as $key => $value) {
        echo '<option value="' . $key . '">' . $value . '</option>';
    }
    echo '
                </select>
            </div>
            <div class="input-wrap">
                <span>Парковка</span>
                <select name="parking">';
    foreach ($param_for_addform["parking"] as $key => $value) {
        echo '<option value="' . $key . '">' . $value . '</option>';
    }
    echo '
                </select>
            </div>
                        <div class="input-wrap">
                <span>Территория</span>
                <select name="territory">';
    foreach ($param_for_addform["territory"] as $key => $value) {
        echo '<option value="' . $key . '">' . $value . '</option>';
    }
    echo '
                </select>
            </div>
            <div class="input-wrap">
                <span>Расстояние до моря</span>
                <input type="text" name="distance_to_sea">
            </div>
            <div class="input-wrap">
                <span>Цена м2 от</span>
                <input type="number" name="price_m2_ot">
            </div>
            <div class="input-wrap">
                <span>Цена м2 до</span>
                <input type="number" name="price_m2_do">
            </div>
            <div class="input-wrap">
                <span>Мин. стоимость за квартиру</span>
                <input type="number" name="price_flat_min">
            </div>
            <div class="input-wrap">
                <span>Площадь квартир от</span>
                <input type="text" name="square_ot">
            </div>
            <div class="input-wrap">
                <span>Площадь квартир до</span>
                <input type="text" name="square_do">
            </div>
            <div class="input-wrap">
                <span>Срок сдачи</span>
                <input type="text" name="deadline">
            </div>
            <div class="input-wrap">
                <span>Телефон риелтора</span>
                <input type="text" name="rieltor_phone">
            </div>
            <div class="input-wrap">
                <span>Улица</span>
                <input type="text" name="street">
            </div>
            <div class="input-wrap full">
                <span>Детальное описание</span>
                <textarea name="text" cols="30" rows="10"></textarea>
            </div>
            <div class="clear"></div>
            <input type="submit" value="Добавить новый объект">
        </form>
    </div>
    ';
}


?>

    <link rel="stylesheet" href="style.css?v=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.css"/>
    <script src="script.js?v=1"></script>
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.js"></script>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>