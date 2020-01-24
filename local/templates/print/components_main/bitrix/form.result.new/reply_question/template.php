<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>

<div id="reply_question-form">
    <?if ($arResult["isFormNote"] != "Y") {?>


        <?
        /***********************************************************************************
        form header
         ***********************************************************************************/
        if ($arResult["isFormTitle"]) {?>
        <? } ?>

        <form name="SIMPLE_FORM_<?=$arParams['WEB_FORM_ID']?>">
            <input type="hidden" name="WEB_FORM_ID" value="<?=$arParams['WEB_FORM_ID']?>" />
            <input type="hidden" name="TPL" value="reply_question" />
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
                        <? if($name == 'form_text_9'): ?>
                            <div class="field-pop field-pop4 hidden"><input type="text" name="<?=$name?>" placeholder="<?=$placeholder?>" value="<?=$arParams['REALTOR_ID']?>" /></div>
                        <? elseif($name == 'form_text_10'): ?>
                            <div class="field-pop field-pop4 hidden"><input type="text" name="<?=$name?>" placeholder="<?=$placeholder?>" value="<?=$arParams['ELEMENT_ID']?>" /></div>
                        <? elseif($name == 'form_text_11'): ?>
                            <div class="field-pop field-pop4 hidden"><input type="text" name="<?=$name?>" placeholder="<?=$placeholder?>" value="<?=$arParams['QUESTION_ID']?>" /></div>
                        <? elseif($name == 'form_text_12'): ?>
                            <div class="field-pop field-pop4 hidden"><input type="text" name="<?=$name?>" placeholder="<?=$placeholder?>" value="<?=$arParams['REALTOR_EMAIL']?>" /></div>
                        <? else: ?>
                            <div class="field-pop field-pop4"><input type="text" name="<?=$name?>" placeholder="<?=$placeholder?>" value="<?=$value?>" /></div>
                        <? endif; ?>
                    <?}?>


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
   
</script>