<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); ?>

<?
CModule::IncludeModule('iblock');

$ID = $_REQUEST['ID'];
$arResult = array();

$arSelect = Array("ID", "IBLOCK_ID", "NAME", 'DETAIL_PAGE_URL');
$arFilter = Array("IBLOCK_ID"=> 19, "ID" => $ID, "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
while($ob = $res->GetNextElement()) {
    $arResult = $ob->GetFields();
    $arResult['PROPERTIES'] = $ob->GetProperties();
}

$arProperties = $arResult['PROPERTIES'];

$res = CIBlockElement::GetByID($arProperties['city']['VALUE']);
//city
if($ar_res = $res->GetNext()){
    $arProperties['city']['MODIFIED_VALUE'] = $ar_res['NAME'];
}
$res = CIBlockElement::GetByID($arProperties['district']['VALUE']);
//district
if($ar_res = $res->GetNext()){
    $arProperties['district']['MODIFIED_VALUE'] = $ar_res['NAME'];
}
$res = CIBlockElement::GetByID($arProperties['microdistrict']['VALUE']);
//microdistrict
if($ar_res = $res->GetNext()){
    $arProperties['microdistrict']['MODIFIED_VALUE'] = $ar_res['NAME'];
}

if($arProperties['lift']['VALUE'] == 'да') {
    $arProperties['lift']['VALUE'] = 'есть';
} else {
    $arProperties['lift']['VALUE'] = 'нет';
}
?>

    <input type="hidden" name="hidden_city" value="<?=$arProperties['city']['VALUE']?>">
    <input type="hidden" name="hidden_district" value="<?=$arProperties['district']['VALUE']?>">
    <input type="hidden" name="hidden_microdistrict" value="<?=$arProperties['microdistrict']['VALUE']?>">
    <input type="hidden" name="hidden_street" value="<?=$arProperties['street']['VALUE']?>">
    <div class="t-adress"><?=$arResult['NAME']?></div>
    <div class="list-agents list-agents2">
    <div class="item-agents">
        <div class="img-agent">
            <div class="gal-agent">
                <div class="slider-agent">
                    <? foreach($arProperties['photo_gallery']['VALUE'] as $photo): ?>
                        <? 	$photoLink = 'http://' . $_SERVER['HTTP_HOST'] . '/' . CFile::GetPath($photo); ?>
                        <div class="slide"><img src="<?=$photoLink?>" alt="" /></div><!--slide-->
                    <? endforeach; ?>
                </div><!--slider-agent-->
                <div class="nums-photo"><span><?=count($arProperties['photo_gallery']['VALUE'])?></span></div>
            </div>
        </div>
        <div class="desc-agents">
            <div class="t-agents"><a href="<?=$arResult['DETAIL_PAGE_URL']?>"><?=$arResult['NAME']?></a></div>
            <div class="adress-agent">
                <?=$arProperties['city']['MODIFIED_VALUE']?>,
                <?=$arProperties['district']['MODIFIED_VALUE']?>,
                <?=$arProperties['microdistrict']['MODIFIED_VALUE']?>,
                <?=$arProperties['street']['VALUE']?>
            </div>

            <div class="info-agents">
                <div class="params-propos">
                    <div class="list-prm">
                        <ul>
                            <li><span>Класс:</span> <strong><?=$arProperties['class']['VALUE']?></strong></li>
                            <li><span>Тип: здания</span> <strong><?=$arProperties['type']['VALUE']?></strong></li>
                            <li><span>Этажей:</span> <strong><?=$arProperties['floors']['VALUE']?></strong></li>
                            <li><span>Парковка:</span> <strong><?=$arProperties['parking']['VALUE']?></strong></li>
                            <li><span>Лифт:</span> <strong><?=$arProperties['lift']['VALUE']?></strong></li>
                            <li><span>Расстояние до моря:</span> <strong><?=$arProperties['distance_to_sea']['VALUE']?></strong></li>
                        </ul>
                    </div><!--list-prm-->
                </div><!--params-propos-->

                <div class="more-but"><a href="<?=$arResult['DETAIL_PAGE_URL']?>">Подробнее об объекте</a></div>
            </div><!--info-agents-->
        </div><!--desc-agents-->
    </div><!--item-agents-->
</div><!--list-agents-->

