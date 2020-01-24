<? if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die(); ?>
<?
//Коммерческая недвижимость
$arProperties = $arResult;

?>

<div class="adress-obj commect_element_props">
<!--    <div class="t-adress">Коммерческая недвижимость</div>-->
<?
$arPropertiesCodes = array(
	array("Тип коммерции", "commerc_type", "select", "REQ" => true),
	array("Площадь", "square", "text", "REQ" => true, "postfix"=>"<i>м<sup>2</sup></i>"),
	array("separator" => true),
	array("Площадь участка ", "sector_square", "text"),
	array("", "dimension", "select",""),
	array("separator" => true),
	array("Расстояние до моря", "distance_to_sea", "text", "REQ" => true),
	array("", "dimension_distance_to_sea", "select", "REQ" => true),
	//sector_square
);
?>
    <?foreach($arPropertiesCodes as $property):?>
    	<?
    	if ($property['separator'] === true)
    	{
    		?>
    			<div style="clear:both"></div>
    		<?
    	}
    	?>
        <?if($property[2] == "text"):?>
            <div class="line-form text_field_<?=strtolower($property[1])?>">
                <div class="line-field">
                    <label><?=$property[0]?> <?=$property["REQ"]? "<span>*</span>" : ""?></label>
                    <input type="text" name="<?=$property[1]?>" value="<?=$arProperties[$property[1]]['VALUE']?>" />
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
                	<?
                	if ($property[0])
                	{
                		?>
                			<label><?=$property[0]?> <?=$property["REQ"]? "<span>*</span>" : ""?></label>
                		<?
                	}
                	?>
                    <div class="sel-on sel-line sel-line4">
                        <select name="<?=$property[1]?>">
                            <option value="">Выберите из списка</option>
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