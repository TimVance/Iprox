<? if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();?>
<? 
if (count($arResult["ITEMS"]) > 0)
{
	?>
	<div class="spec-propos">
		<? 
		if ($arParams["~BLOCK_TITLE"])
		{
			?>
				<div class="tit-spec"><?=$arParams["~BLOCK_TITLE"]?></div>
			<?
		}
		?>

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
			<div class="bl-spec">
				<div class="head-spec">
					<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a>
					<span><?=ImplodeArrguments(", ",$arItem["PROPERTIES"]['city']['MODIFIED_VALUE'],$arItem["PROPERTIES"]['district']['MODIFIED_VALUE'],$arItem["PROPERTIES"]['microdistrict']['MODIFIED_VALUE'],$arItem["PROPERTIES"]['street']['VALUE'])?></span>
				</div>
	
				<div class="body-spec">
					<? 
					$img = ($arItem["PROPERTIES"]["photo_gallery"][0]["VALUE"])? AddWaterMarkResized($arItem["PROPERTIES"]["photo_gallery"][0]["VALUE"],234,156,Cimg::M_CROP) : false;
					?>
					<div class="img-body-spec">
					<? if ($img)
					{
						?><img src="<?=$img?>" alt="<?=$arItem["NAME"]?>" /><?
					}
					else
					{
						?><div class="img-grey-rielter-offer"></div><?
					}?>
					
					</div>
					<div class="desc-body-spec">
						<? 
						if ($arProperties['price']['VALUE'])
						{
							?>
								<div class="price-spec"><?=MoneyOutPut($arProperties['price']['VALUE'])?> <?=$arProperties['currency']['VALUE_XML_ID']?></div>
							<?
						}
						?>
						<ul>
						<? if ($arProperties["floor"]["VALUE"])
						{
							?>
							<li>
								<span><?=($arProperties["newbuilding"]["ELEM"]["PROPERTY_FLOORS_VALUE"])? "Этаж/этажей:" : "Этаж";?></span> 
								<strong><?=$arProperties["floor"]["VALUE"]?><?=($arProperties["newbuilding"]["ELEM"]["PROPERTY_FLOORS_VALUE"])? "/".$arProperties["newbuilding"]["ELEM"]["PROPERTY_FLOORS_VALUE"] : "";?></strong>
							</li>
							<?
						}?>
						<?=\SiteTemplates\Object::buildPropsShort($arProperties, 5);?>
						</ul>
						<?/* ?><ul>
						<? 
						if ($arProperties['square']['VALUE'])
						{
							?>
								<li><span>Общая площадь:</span> <strong><?=$arProperties['square']['VALUE']?> м<sup>2</sup></strong></li>
							<?
						}
						
						?>
						<? if ($arProperties["floor"]["VALUE"])
						{
							?>
							<li>
								<span><?=($arProperties["newbuilding"]["ELEM"]["PROPERTY_FLOORS_VALUE"])? "Этаж/этажей:" : "Этаж";?></span> 
								<strong><?=$arProperties["floor"]["VALUE"]?><?=($arProperties["newbuilding"]["ELEM"]["PROPERTY_FLOORS_VALUE"])? "/".$arProperties["newbuilding"]["ELEM"]["PROPERTY_FLOORS_VALUE"] : "";?></strong>
							</li>
							<?
						}?>

						</ul><? */?>
					</div>
				</div>
			</div>
			<?
		}
		?>

	</div>
	<?
}
?>
