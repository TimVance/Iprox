<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

?>
<?
if ($arResult['ITEMS'] && count($arResult["ITEMS"]) > 0)
{
	?>
	<div class="siblings-obj tiles_new_style">
		<div class="t-emploe">Похожие объекты</div>

		<div class="slier-objects not-hidden">
			<div class="slider1">
				<?
				foreach ($arResult["ITEMS"] as $arItem)
				{
					$arProperties = $arItem["PROPERTIES"];
					switch($arProperties['currency']['VALUE_XML_ID']) {
						case 'rubles' : $arProperties['currency']['VALUE_XML_ID'] = 'Руб.'; break;
						case 'dollars': $arProperties['currency']['VALUE_XML_ID'] = '$'; break;
						case 'euro'   : $arProperties['currency']['VALUE_XML_ID'] = 'Евро'; break;
						default: ; // ok
					}
					?>
					<div class="slide">
						<div class="item-propos">
							<div class="top-propos">
								<div class="img-propos last-objectsblock">
								<?if(count($arProperties['photo_gallery']) > 0):?>
									<div class="slider-agent2">
										
										<? foreach($arProperties['photo_gallery'] as $photo): ?>
												
											<? 	$photoLink = AddWaterMarkResized($photo["VALUE"],235,161,CImg::M_PROPORTIONAL); ?>
											<div class="slide"><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><img src="<?=$photoLink?>" alt="" /></a></div><!--slide-->
										<? endforeach; ?>
										
									</div><!--slider-agent-->
								<?else:?>
									<div class="slide img-grey"><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><div class="img-grey"></div></a></div><!--slide-->
								<?endif;?>
									<div class="nums-photo"><span><?=count($arProperties['photo_gallery'])?></span></div>
								</div>
								<div class="t-propos"><p><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a></p>
								</div>
							</div>
	
							<div class="bot-propos">
							<?
							$arProperties["district"]["VALUE"] = false;
							?>
								<div class="adress-p"><?=\SiteTemplates\Object::buildAddress($arProperties);?></div>
	
								<div class="price-p"><?=MoneyOutPut($arProperties['price']['VALUE'])?> <?=$arProperties['currency']['VALUE_XML_ID']?></div>
	
								<?//=\SiteTemplates\Object::tilesGenerateProps($arProperties);?>
							</div>
						</div>
					</div>
					<?
				}
				?>

				
			</div>
		</div>
	</div>
	<?
}
?>


<?