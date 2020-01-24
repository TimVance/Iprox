<? if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die(); ?>
<?
//Дома, коттеджи, таунхаусы
$arProperties = $arResult;


$arPropertiesCodes = array(
	array("Тип строения", "object_type", "select", "REQ" => true),
	array("Общая площадь строений", "summary_buildings_square", "text","postfix"=>"<i>м<sup>2</sup></i>","REQ" => true),
	array("Общая площадь дома ", "summary_apartment_square", "text","postfix"=>"<i>м<sup>2</sup></i>","REQ" => true),
	array("Этажность", "number_of_storeys", "select"),
	array("Площадь участка ", "sector_square", "text","REQ" => true),
	array("Размерность площади участка ", "dimension", "select",""),
	array("Количество спален ", "number_of_bedrooms", "text","REQ" => true),
	array("Отделка ", "decoration", "select"),
	array("Гараж", "garage", "select"),
	array("Целевое назначение ", "special_purpose", "select"),
	array("Год постройки ", "build_year", "date"),
	array("Расстояние до моря", "distance_to_sea", "text","REQ" => false),
	array("Размерность расстояния до моря ", "dimension_distance_to_sea", "select"),
	array("Материал стен","wall_type","select"),
	array("Видео","video_gallery","text","REQ" => false, "placeholder" => "Вставьте ссылку YouTube"),
);

?>

<div class="adress-obj">
<!--    <div class="t-adress">Дома, коттеджи, таунхаусы</div>-->

    <?foreach($arPropertiesCodes as $property):?>
        <?if($property[2] == "text"):?>
            <div class="line-form add-property-<?=$property[1]?>">
                <div class="line-field ">
                    <label><?=$property[0]?>  <?=$property["REQ"]? "<span>*</span>" : ""?></label>
                    <input type="text" name="<?=$property[1]?>" value="<?=$arProperties[$property[1]]['VALUE']?>" placeholder="<?=$property["placeholder"]?>"/>
                  	<?
                  	if ($property["postfix"])
                  	{
                  		echo($property["postfix"]);
                  	}
                  	?>
                </div>
            </div>
        <?endif;?>
        <?if($property[2] == "select"):?>
            <div class="line-form add-property-<?=$property[1]?>">
                <div class="line-select">
                    <label><?=$property[0]?></label>
                    <div class="sel-on sel-line sel-line4">
                        <select name="<?=$property[1]?>">
                            <option value="0">Выберите из списка</option>
                            <? $property_enums = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=> $_REQUEST['IBLOCK_ID'], "CODE" => $property[1]));
                            while($enum_fields = $property_enums->GetNext()): ?>
                                <option <?if($enum_fields['VALUE'] == $arProperties[$property[1]]['VALUE']) echo 'selected=selected';?> value="<?=$enum_fields['ID']?>"><?=$enum_fields['VALUE']?></option>
                            <? endwhile; ?>
                        </select>
                    </div>
                </div>
            </div>
        <?endif;?>
        <?if($property[2] == "checkbox"):?>
            <div class="line-check add-property-<?=$property[1]?>">
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