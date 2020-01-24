<? if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die(); ?>
<?
$arProperties = $arResult['PROPERTIES'];
$isAjax = $_REQUEST["ajax"] == "Y" && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
if ($isAjax)
{
	$APPLICATION->RestartBuffer();
}
?>

<div class="list-agents list-agents2" id="block-agents">
    <?foreach($arResult['ITEMS'] as $currentItem):?>
    <? 
    $currentItem["EDIT_PERSONAL"] =	"/personal/myobjects/add/?PRODUCT_ID=".$currentItem["ID"]."&IBLOCK_ID=".$currentItem["IBLOCK_ID"];
    $Active = ($currentItem["PROPERTY_IS_ACCEPTED_VALUE"])? true: false;
    if (!$Active)
    {
    	$currentItem["DETAIL_PAGE_URL"] = "javascript:void(0);";
    }
    $EleActive = ($currentItem["ACTIVE"] == "Y")? true: false;
    ?>
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
        } ?>
        <div class="item-agents <? if (!$Active){echo(" isModerate ");}if(!$EleActive){echo(" noactive ");}?>" > <!--noactive-->
            <div class="img-agent">
                <div class="gal-agent">
                    <div class="slider-agent">
                        <?if(count($arProperties['photo_gallery']) > 0):?>
                            <? foreach($arProperties['photo_gallery'] as $photo): ?>
                                <? 	$photoLink = AddWaterMarkResized($photo["VALUE"],322,242,CImg::M_PROPORTIONAL); ?>
                                <div class="slide"><img src="<?=$photoLink?>" alt="" /></div><!--slide-->
                            <? endforeach; ?>
                        <?else:?>
                            <div class="slide img-grey"><div class="img-grey"></div></div><!--slide-->
                        <?endif;?>
                    </div><!--slider-agent-->
                    <div class="nums-photo"><span><?=count($arProperties['photo_gallery'])?></span></div>
                </div>
            </div>
            <div class="desc-agents"><?/*='/sell' . $currentItem['DETAIL_PAGE_URL']?>"><?=$currentItem['NAME'] ?>*/?>
                <?/* ?><div class="ico-view"><a href="add/?PRODUCT_ID=<?=$currentItem['ID']?>&IBLOCK_ID=<?=$currentItem['IBLOCK_ID']?>"></a></div><? */?>
               <div class="ico-view" data-element="<?=$currentItem["ID"]?>"><a href="javascript:void(0);"></a></div>
                <div class="t-agents"><a href="<?=$currentItem["DETAIL_PAGE_URL"]?>"><?=$currentItem["NAME"]?></a></div>
                <div class="adress-agent">
                    <?=$arProperties['city']['MODIFIED_VALUE']?>,
                    <?=$arProperties['district']['MODIFIED_VALUE']?>,
                    <?=$arProperties['microdistrict']['MODIFIED_VALUE']?>,
                    <?=$arProperties['street']['VALUE']?>
                </div>
                <div class="info-agents">
                    <div class="params-propos">
                       	<?
						if ($currentItem["IBLOCK_ID"] == 7)
						{
							?>
							<?=\SiteTemplates\Object::buildFlatPropsShort($arProperties);?>
							<?
						}
						else if ($currentItem["IBLOCK_ID"] == 10)
						{
							?>
							<?=\SiteTemplates\Object::buildLandPropsShort($arProperties);?>
							<?
						}
						else 
						{
							?>
								<?=\SiteTemplates\Object::buildProps($arProperties);?>
							<?
						}
						?>
                       	<?/* ?>
                        <div class="list-prm">
                            <ul>
                            
                            <?
                            if ($arProperties['square']["VALUE"])
                            {
                            	?>
                            		<li>Общая площадь: <strong><?=$arProperties['square']['VALUE']?></strong> м<sup>2</sup></li>
                            	<?
                            }
                            else if ($arProperties["summary_apartment_square"]["VALUE"])
                            {
                            	?>
                            		<li>Общая площадь дома: <strong><?=$arProperties['summary_apartment_square']['VALUE']?></strong> м<sup>2</sup></li>
                            	<?
                            }
                            if ($arProperties['floor']['VALUE'])
                            {
                            	?>
                            		<li>Этаж: <strong><?=$arProperties['floor']['VALUE']?></strong></li>
                            	<?
                            }
                            else if ($arProperties["number_of_storeys"]["VALUE"])
                            {
                            	?>
                            		<li>Этажность: <strong><?=$arProperties['number_of_storeys']['VALUE_ENUM']?></strong></li>
                            	<?
                            }
                            ?>
                                
                               
                            </ul>
                        </div><!--list-prm-->
                        <?*/ ?>

                        <div class="name-r">
                            <?=$arProperties['apartment_complex']['MODIFIED_VALUE']?>
                        </div>
                    </div><!--params-propos-->

                    <div class="func-propos">
                       
                       	<?/* ?>
                        <div class="price-propos">
                            <span class="price-inline"><? if ($arProperties['price']['VALUE']){?><?=$arProperties['price']['VALUE']?> <?=$arProperties['currency']['VALUE_XML_ID']?><? }else if ($arProperties['price_1m']['VALUE']) {?><?=$arProperties['price_1m']['VALUE']?><span class="per-price"> <?=$arProperties['currency']['VALUE_XML_ID']?>/м<sup>2</sup></span><? }?></span>
                        </div>
                        
                        <?*/ ?>
                        
                        						<div class="price-propos">
						<? 
						if ($arProperties['price']['VALUE'])
						{
							?>
							<p><span><?=$arProperties['price']['VALUE']?> <?=$arProperties['currency']['VALUE_XML_ID']?></span></p>
							<?
						}
						?>
							
							<? if ($arProperties['price_1m']['VALUE'])
							{
								?>
								<p><?=$arProperties['price_1m']['VALUE']?> <?=$arProperties['currency']['VALUE_XML_ID']?>/м<sup>2</sup></p>
								<?
							}
						
							/*else if ($arParams["IBLOCK_ID"] == "11")
							{
								if($arProperties["summary_apartment_square"]['VALUE'])
								{
									?>
									<?= MoneyOutPut(intval(str_replace(" ","",$arProperties['price']['VALUE']))/intval($arProperties["summary_apartment_square"]['VALUE'])) ?> <?= $arProperties['currency']['VALUE_XML_ID'] ?>
									за м²
									<?
								}
								else if($arProperties["square"]['VALUE'])
								{
									?>
									<?= MoneyOutPut(intval(str_replace(" ","",$arProperties['price']['VALUE']))/intval($arProperties["square"]['VALUE'])) ?> <?= $arProperties['currency']['VALUE_XML_ID'] ?>
									за м²
									<?
								}
								
							}*/

							?>
							
						</div>
                        <div class="more-but"><a href="<?=$currentItem["EDIT_PERSONAL"]?>">Редактировать</a></div>
                    </div><!--func-propos-->
                </div><!--info-agents-->
            </div><!--desc-agents-->
        </div><!--item-agents-->
    <?endforeach;?>
<script>
        $(document).ready(function(){
        	$(".desc-agents .ico-view").bind("click.Objects",function(){
        		var $TisId = $(this).attr("data-element");
        		var $ThisClicked = $(this);
        		if(typeof($TisId) != "undefined")
        		{
        			$.ajax({
        				dataType: "json",
        				data: {TARGET: $TisId},
        				url: '/local/templates/morealty/ajax_php/deactive_object.php',
        				success: function(data) {
        					if(typeof(data) == "object")
        					{
        						if(typeof(data.STATE) != "undefined")
        						{
        							if(data.STATE == 'Y')
        							{
        								$ThisClicked.parents(".item-agents").removeClass("noactive");
        							}
        							else if(data.STATE == 'N')
        							{
        								$ThisClicked.parents(".item-agents").addClass("noactive");
        							}
        						}
        					}
        				}
        			});
        		}
        	});

        		$('.item-agents').hover(function(){
        			if($(this).is(".isModerate"))
        			{
        		        var title = "Объект находится на модерации";
        		        $(this).data('tipText', title).removeAttr('title');
        		        $('<p class="tooltip"></p>')
        		        .text(title)
        		        .appendTo('body')
        		        .fadeIn('slow');
        			}

        		}, function() {
        		        $('.tooltip').remove();
        		}).mousemove(function(e) {
        			if($(this).is(".isModerate"))
        			{
        		        var mousex = e.pageX + 20; //Get X coordinates
        		        var mousey = e.pageY + 10; //Get Y coordinates
        		        $('.tooltip')
        		        .css({ top: mousey, left: mousex })
        			}
        		});
        });
</script>
<?=$arResult["NAV_STRING"]?>
</div><!--list-agents-->


<? 
if ($isAjax)
{
	die();
}
?>