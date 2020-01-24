<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>

<?
//view-list
$itemsCount = count($arResult['ITEMS']);
$IBLOCK_ID = $arParams['IBLOCK_ID'];
//$link_added_part = ($IBLOCK_ID == 19) ? '' : '/sell';
$link_added_part = "";
?>

<?if(count($arResult['ITEMS'])  > 0):?>
<div class="b-propos b-propos2">
    <div class="t-emploe">Специальные предложения</div>
    <? $APPLICATION->IncludeComponent('wexpert:includer', "sort_in_sell", array('OK' => 'no',"NOT_RELOAD_ON_CHANGE_TILE"=>"Y"), false); ?>

<div id="special-offers">
<? 
if ($_REQUEST["ajax"] == "Y")
{
	$APPLICATION->RestartBuffer();
}
?>
    <div class="list-square">
        <? $counter = 0; ?>
        <? foreach($arResult['ITEMS'] as $currentItem): ?>
            <?
            $arProperties = $currentItem['PROPERTIES'];
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
            //apartment_complex
            $res = CIBlockElement::GetByID($arProperties['apartment_complex']['VALUE']);
            if($ar_res = $res->GetNext()){
                $arProperties['apartment_complex']['MODIFIED_VALUE'] = $ar_res['NAME'];
            }
            //currency
            switch($arProperties['currency']['VALUE_XML_ID']) {
                case 'rubles' : $arProperties['currency']['VALUE_XML_ID'] = 'Руб.'; break;
                case 'dollars': $arProperties['currency']['VALUE_XML_ID'] = '$'; break;
                case 'euro'   : $arProperties['currency']['VALUE_XML_ID'] = 'Евро'; break;
                default: ; // ok
            }
            //price view
            $price = (string) $arProperties['price']['VALUE'];
            $priceLen = strlen($price);
            if($priceLen >= 5) {
                switch($priceLen) {
                    case 5: $price = substr($price, 0, 2) . ' ' . substr($price, 2, 3); break;
                    case 6: $price = substr($price, 0, 3) . ' ' . substr($price, 3, 3); break;
                    case 7: $price = substr($price, 0, 1) . ' ' . substr($price, 1, 3) . ' ' . substr($price, 4, 3); break; //
                    case 8: $price = substr($price, 0, 2) . ' ' . substr($price, 2, 3) . ' ' . substr($price, 5, 3); break;
                    case 9: $price = substr($price, 0, 3) . ' ' . substr($price, 3, 3) . ' ' . substr($price, 6, 3); break;
                    case 10: $price = substr($price, 0, 1) . ' ' . substr($price, 1, 3) . ' ' . substr($price, 4, 3) . ' ' . substr($price, 7, 3); break;
                    default: break;
                }
                $arProperties['price']['VALUE'] = $price;
            }
            //price1m view
            $price1m = (string) $arProperties['price_1m']['VALUE'];
            $priceLen = strlen($price1m);
            if($priceLen >= 4) {
                switch($priceLen) {
                    case 4: $price1m = substr($price1m, 0, 1) . ' ' . substr($price1m, 1, 3); break;
                    case 5: $price1m = substr($price1m, 0, 2) . ' ' . substr($price1m, 2, 3); break;
                    case 6: $price1m = substr($price1m, 0, 3) . ' ' . substr($price1m, 3, 3); break;
                    default: break;
                }
                $arProperties['price_1m']['VALUE'] = $price1m;
            }
            //ID
            $currentItemID = $currentItem['ID'];
            //favorites IDs
            $arSort = array('SORT' => 'ASC');
            $arFilter = array('USER_ID' => $USER->GetID());
            $rsItems = CFavorites::GetList($arSort, $arFilter);
            $arID = array();
            while ($Res = $rsItems->Fetch()) {
                $arID[] = $Res['URL'];
            }
            ?>
            <div class="item-propos">
                <input type="hidden" id="item-id" value="<?=$currentItem['ID']?>">

                <div class="top-propos">
                    <div class="img-propos">
                        <a href="javascript:void()">
                            <?if(count($arProperties['photo_gallery']) > 0):?>
                                <? 	$photoLink = AddWaterMarkResized($arProperties['photo_gallery'][0]['VALUE'], 322, 242 , CImg::M_CROP); ?>
                                <img src="<?=$photoLink?>" alt="" />
                            <?else:?>
                                <span class="img-grey"></span>
                            <?endif;?>
                        </a>
                    </div>
                    <div class="t-propos"><p><a href="<?=$link_added_part . $currentItem['DETAIL_PAGE_URL']?>"><?=$currentItem['NAME']?></a></p></div>
                </div><!--top-propos-->

                <div class="bot-propos">
                    <div class="adress-p">
                        <?=$arProperties['city']['MODIFIED_VALUE']?>,
                        <?=$arProperties['district']['MODIFIED_VALUE']?>,
                        <?=$arProperties['microdistrict']['MODIFIED_VALUE']?>,
                        <?=$arProperties['street']['VALUE']?>
                    </div>
					<? 
					if ($arProperties['price']['VALUE'] && $arProperties['currency']['VALUE_XML_ID'])
					{
						?><div class="price-p">
	                        <?=$arProperties['price']['VALUE']?> <?=$arProperties['currency']['VALUE_XML_ID']?>
	                    </div>
						<?
					}
					?>


                    <div class="params-p">
                        <ul>
                            <li>Общая площадь: <strong><?=$arProperties['square']['VALUE']?></strong> м<sup>2</sup></li>
                            <li>Этаж: <strong><?=$arProperties['floor']['VALUE']?></strong></li>
                        </ul>
                    </div>

                    <div class="compl-prop"><?=$arProperties['apartment_complex']['MODIFIED_VALUE']?></div>
                </div><!--bot-propos-->
            </div><!--item-propos-->
            <? $counter++; ?>
        <? endforeach; ?>
    </div>

    <?=$arResult["NAV_STRING"]?>
    <? 
if ($_REQUEST["ajax"] == "Y")
{
	die();
}
?>
  </div>

    <?endif;?>
</div>
