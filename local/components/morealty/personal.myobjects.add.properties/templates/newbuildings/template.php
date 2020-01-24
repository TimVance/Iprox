<? if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die(); ?>
<?
//Новостройки
$arProperties = $arResult;

$arPropertiesCodes = array(
    array("Класс ", "class", "select"),
    array("Тип здания   ", "type", "select"),
    array("Этажей в объекте ", "floors", "text"),
    array("Год постройки ", "builder_year", "text"),
    array("Лифт", "lift", "checkbox"),
    array("Расстояние до моря", "distance_to_sea", "text"),
    array("Размерность расстояния до моря", "dimension_distance_to_sea", "select"),
    array("Срок сдачи ", "deadline", "text"),
        //array("Количество квартир ", "special_purpose", "select"),
        //array("Площадь квартир ", "distance_to_sea", "text"),
    //array("Цена кв.м. от", "distance_to_sea", "text"),
    //array("Квартиры от  ", "dimension_distance_to_sea_", "select"),
    //array("Валюта цен ", "dimension_distance_to_sea_", "select"),
);
?>


<div class="adress-obj">
<!--    <div class="t-adress">Новостройки</div>-->

    <?foreach($arPropertiesCodes as $property):?>
        <?if($property[2] == "text"):?>
            <div class="line-form">
                <div class="line-field">
                    <label><?=$property[0]?>  <span>*</span></label>
                    <input type="text" name="<?=$property[1]?>" value="<?=$arProperties[$property[1]]['VALUE']?>" />
                    <!--                <i>м<sup>2</sup></i>-->
                </div>
            </div>
        <?endif;?>
        <?if($property[2] == "select"):?>
            <div class="line-form">
                <div class="line-select">
                    <label><?=$property[0]?></label>
                    <div class="sel-on sel-line sel-line4">
                        <select name="<?=$property[1]?>">
                            <?foreach($arProperties[$property[1]] as $key => $value):?>
                                <option <?if($value == $arProperties[$property[1]]['VALUE']) echo 'selected=selected';?> value="<?=$key?>"><?=$value?></option>
                            <?endforeach;?>
                        </select>
                    </div>
                </div>
            </div>
        <?endif;?>
        <?if($property[2] == "checkbox"):?>
            <div class="line-check">
                <ul>
                    <li class="customP">
                        <input id="check1" name="<?=$property[1]?>" type="checkbox" <?if($arProperties[$property[1]]['VALUE'] == 'да') echo 'checked'; ?> value="345"/>
                        <label for="check1"><?=$property[0]?></label>
                    </li>
                    <li class="customP">
                        <input id="check2"  name="<?=$property[1]?>" type="checkbox" <?if($arProperties[$property[1]]['VALUE'] == 'да') echo 'checked'; ?> value="346"/>
                        <label for="check2"><?=$property[0]?></label>
                    </li>
                </ul>
            </div>
        <?endif;?>
    <?endforeach;?>
</div><!--adress-obj-->
