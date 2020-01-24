<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>

<div id="ask_question-form">
    <?if ($arResult["isFormNote"] != "Y") {?>


        <?
        /***********************************************************************************
        form header
         ***********************************************************************************/
        if ($arResult["isFormTitle"]) {?>
            <div class="t-pop t-pop4"><?=$arResult["FORM_TITLE"]?></div>
            <? } ?>

        <form name="SIMPLE_FORM_<?=$arParams['WEB_FORM_ID']?>">
            <input type="hidden" name="WEB_FORM_ID" value="<?=$arParams['WEB_FORM_ID']?>" />
            <input type="hidden" name="TPL" value="ask_question" />
            <?=bitrix_sessid_post()?>
            <?
            foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion) {
                if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden') {
                    echo $arQuestion["HTML_CODE"];
                } else {
                    $type = $arQuestion['STRUCTURE'][0]['FIELD_TYPE'];
                    $id = $arQuestion['STRUCTURE'][0]['ID'];
                    $name = 'form_'.$type.'_'.$id;
                    $value = $arResult['arrVALUES'][$name];
                    if($name == 'form_text_4') {
                        //in local\templates\morealty\components\wexpert\iblock.detail\sell_catalog_item\template.php
                        $value = $GLOBALS['REALTOR_EMAILS'];
                    }
                    $placeholder = (($arQuestion['REQUIRED']=='Y')?'*':'').$arQuestion['CAPTION'];
                    if($arQuestion['REQUIRED'] == 'Y') {
                        $placeholder = substr($placeholder, 1);
                        $placeholder .= ' *';
                    }
                    if ($type == 'textarea') {?>
                        <div class="textarea-pop"><textarea name="<?=$name?>" placeholder="<?=$placeholder?>"><?=$value?></textarea></div>
                    <?} else {?>
                        <? if($name == 'form_text_8'): ?>
                            <div class="field-pop field-pop4 hidden"><input type="text" name="<?=$name?>" placeholder="<?=$placeholder?>" value="<?=$arParams['elementID']?>" /></div>
                        <? else: ?>
                            <div class="field-pop field-pop4"><input type="text" name="<?=$name?>" placeholder="<?=$placeholder?>" value="<?=$value?>" /></div>
                        <? endif; ?>                    <?}?>
                    <?
                }
            } //endwhile
            ?>
            <div class="but-pop but-pop3">
                <?$btnVal = htmlspecialcharsbx(strlen(trim($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]);?>
                <button <?=(intval($arResult["F_RIGHT"]) < 10 ? "disabled=\"disabled\"" : "");?> type="submit" name="web_form_submit" value="<?=$btnVal?>"><?=$btnVal?></button>
            </div>
        </form>

        <?
    } //endif (isFormNote)
    ?>
</div>
<script>
    
    var $data = null;

    //$('input[name="form_text_6"]').inputmask("+7 (999) 999-99-99");
</script>