<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>

<?php

if ($arResult["add"] == 'success') {
    echo '<div class="success">
        <h2>Заявка успешно добавлена!</h2>
        <p>Наш менеджер скоро свяжется с вами</p>
    </div>';
}
else {
echo '<div class="errors">'.$arResult["add"].'</div>';
?>
<form class="sendValuationForm" name="sendValuationForm" method="post" enctype="multipart/form-data">
<?
    foreach ($arResult["form"] as $item):
        if ($item["PROPERTY_TYPE"] == "S"):
            if ($item["CODE"] == "email"): ?>
                <div class="field-wrap">
                    <input
                        <?=($item["IS_REQUIRED"] == 'Y' ? 'required ' : '')?>
                        type="email" placeholder="<?=$item["NAME"].($item["IS_REQUIRED"] == 'Y' ? '*' : '')?>"
                        name="<?=$item["CODE"]?>"
                    >
                 </div>
            <?
            elseif($item["CODE"] == "comment" || $item["CODE"] == "target") : ?>
                <div class="field-wrap textarea">
                    <textarea
                        <?=($item["IS_REQUIRED"] == 'Y' ? 'required ' : '')?>
                        type="phone" placeholder="<?=$item["NAME"].($item["IS_REQUIRED"] == 'Y' ? '*' : '')?>"
                        name="<?=$item["CODE"]?>"
                    ></textarea>
                </div>
            <? else : ?>
                <div class="field-wrap">
                    <input
                        <?=($item["IS_REQUIRED"] == 'Y' ? 'required ' : '')?>
                        type="text" placeholder="<?=$item["NAME"].($item["IS_REQUIRED"] == 'Y' ? '*' : '')?>"
                        name="<?=$item["CODE"]?>"
                    >
                </div>
            <? endif;
        elseif ($item["PROPERTY_TYPE"] == "L") : ?>
            <div class="field-wrap">
                <select <?=($item["IS_REQUIRED"] == 'Y' ? 'required ' : '')?>name="<?=$item["CODE"]?>">
                    <?
                    echo '<option selected disabled>'.$item["NAME"].'</option>';
                    foreach ($item["SELECT"] as $option) {
                        echo '<option value="'.$option["ID"].'">'.$option["VALUE"].'</option>';
                    }
                    ?>
                </select>
            </div>
        <? elseif ($item["PROPERTY_TYPE"] == "F"): ?>
            <div class="field-wrap">
                <label><input name="<?=$item["CODE"]?>[]" type="file" multiple></label>
            </div>
        <? endif;
    endforeach;
?>
    <input type="submit" value="Отправить" class="btn">
</form>
<?php } ?>