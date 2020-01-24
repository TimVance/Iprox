<? if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die(); ?>
<?

//Квартиры
$arProperties = $arResult;
CConsole::log($arProperties);
?>

<div class="adress-obj not_implements">
<!--    <div class="t-adress">Квартира</div>-->

    <div class="line-form">
        <div class="line-select">
            <label>Количество комнат <span>*</span></label>

            <div class="sel-on sel-line sel-line3">
                <select name="room_number">
                    <option value="all">Выберите из списка</option>
                    <?foreach($arProperties['room_number_list'] as $key => $value):?>
                        <option <?if($value == $arProperties['room_number']['VALUE']) echo 'selected=selected';?> value="<?=$key?>"><?=$value?></option>
                    <?endforeach;?>
                </select>
            </div><!--sel-on-->
        </div><!--line-select-->

        <div class="line-field">
            <label>Этаж <span>*</span></label>
            <input type="text" name="floor" value="<?=$arProperties['floor']['VALUE']?>" />
        </div><!--line-field-->
        
        
        
        <div class="line-field">
            <label>Этажей <span>*</span></label>
            <input type="text" name="floors" value="<?=$arProperties['floors']['VALUE']?>" />
        </div><!--line-field-->
        <?
        //floors
        ?>
    </div><!--line-form-->

    <div class="line-form">
        <div class="line-field">
            <label>Площадь общая <span>*</span></label>
            <input type="text" name="square" value="<?=$arProperties['square']['VALUE']?>" />
            <i>м<sup>2</sup></i>
        </div><!--line-field-->

        <div class="line-field">
            <label>Жилая</label>
            <input type="text" name="square_lived" value="<?=$arProperties['square_lived']['VALUE']?>" />
            <i>м<sup>2</sup></i>
        </div><!--line-field-->

        <div class="line-field">
            <label>Кухня</label>
            <input type="text" name="kitchen" value="<?=$arProperties['kitchen']['VALUE']?>" />
            <i>м<sup>2</sup></i>
        </div><!--line-field-->
    </div><!--line-form-->

    <div class="line-form line-form2">
        <div class="line-select">
            <label>Отделка</label>

            <div class="sel-on sel-line sel-line4">
                <select name="decoration">
                    <option value="all">Выберите из списка</option>
                    <?foreach($arProperties['decoration_list'] as $key => $value):?>
                        <option <?if($key == $arProperties['decoration']['VALUE_ENUM_ID']) echo 'selected=selected';?> value="<?=$key?>"><?=$value?></option>
                    <?endforeach;?>
                </select>
            </div><!--sel-on-->
        </div><!--line-select-->

        <div class="line-select">
            <label>Плита</label>

            <div class="sel-on sel-line sel-line4">
                <select name="stove">
                    <option value="all">Выберите из списка</option>
                    <?foreach($arProperties['stove_list'] as $key => $value):?>
                        <option <?if($key == $arProperties['stove']['VALUE_ENUM_ID']) echo 'selected=selected';?> value="<?=$key?>"><?=$value?></option>
                    <?endforeach;?>
                </select>
            </div><!--sel-on-->
        </div><!--line-select-->

        <div class="line-select">
            <label>Санузел</label>

            <div class="sel-on sel-line sel-line4">
                <select name="wc">
                    <option value="all">Выберите из списка</option>
                    <?foreach($arProperties['wc_list'] as $key => $value):?>
                        <option <?if($key == $arProperties['wc']['VALUE_ENUM_ID']) echo 'selected=selected';?> value="<?=$key?>"><?=$value?></option>
                    <?endforeach;?>
                </select>
            </div><!--sel-on-->
        </div><!--line-select-->
    </div><!--line-form-->


    <div class="line-form line-form2">
         <div class="line-select">
            <label>Статус</label>
            <div class="sel-on sel-line sel-line4">
                <select name="status">
                    <option value="all">Выберите из списка</option>
                    <?foreach($arProperties['STATUS_ITEMS'] as $key => $value):?>
                        <option <?if($key == $arProperties['STATUS']['VALUE_ENUM_ID']) echo 'selected=selected';?> value="<?=$key?>"><?=$value?></option>
                    <?endforeach;?>
                </select>
            </div><!--sel-on-->
        </div><!--line-select-->
    </div><!--line-form-->
    
    
    <div class="line-check">
    
        <ul>
            <li class="customP">
                <input id="check1" name="have_loggia" type="checkbox" <?if($arProperties['have_loggia']['VALUE'] == 'да') echo 'checked'; ?> value="345"/>
                <label for="check1">Лоджия</label>
            </li>
            <li class="customP">
                <input id="check2"  name="have_balcony" type="checkbox" <?if($arProperties['have_balcony']['VALUE'] == 'да') echo 'checked'; ?> value="346"/>
                <label for="check2">Балкон</label>
            </li>
            <?/* ?><li class="customP">
                <input id="check3"  name="have_phone" type="checkbox" <?if($arProperties['have_phone']['VALUE'] == 'да') echo 'checked'; ?>  value="347" />
                <label for="check3">Телефон</label>
            </li><?*/ ?>
        </ul>
    </div>
</div>
