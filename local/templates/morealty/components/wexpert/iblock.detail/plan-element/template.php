<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? 
$arProperties = $arResult['PROPERTIES'];
$res          = CIBlockElement::GetByID($arProperties['city']['VALUE']);
//city
if ($ar_res = $res->GetNext()) {
	$arProperties['city']['MODIFIED_VALUE'] = $ar_res['NAME'];
}
$res = CIBlockElement::GetByID($arProperties['district']['VALUE']);
//district
if ($ar_res = $res->GetNext()) {
	$arProperties['district']['MODIFIED_VALUE'] = $ar_res['NAME'];
}
$res = CIBlockElement::GetByID($arProperties['microdistrict']['VALUE']);
//microdistrict
if ($ar_res = $res->GetNext()) {
	$arProperties['microdistrict']['MODIFIED_VALUE'] = $ar_res['NAME'];
}
//apartment_complex
$res = CIBlockElement::GetByID($arProperties['apartment_complex']['VALUE']);
if ($ar_res = $res->GetNext()) {
	$arProperties['apartment_complex']['MODIFIED_VALUE'] = $ar_res['NAME'];
}
//currency
switch ($arProperties['currency']['VALUE_XML_ID']) {
	case 'rubles' :
		$arProperties['currency']['VALUE_XML_ID'] = 'Руб.';
		break;
	case 'dollars':
		$arProperties['currency']['VALUE_XML_ID'] = '$';
		break;
	case 'euro'   :
		$arProperties['currency']['VALUE_XML_ID'] = 'Евро';
		break;
	default:
		; // ok
}
?>
			<div class="pop pop4 pop8">
				<div class="t-pop t-pop5"><?=$arResult["NAME"]?></div>
				<div class="consist"><? if ($arParams["FLOOR"])
				{
					?>
					<?=$arParams["~FLOOR"]?> этаж, 
					<?
				} ?>
				<?=\SiteTemplates\Object::buildAddress($arProperties);?></div>
				<div class="body-pp">
					<div class="img-pp">
						<? 
						if ($arResult["PROPERTIES"]["layouts_gallery"][0]["VALUE"])
						{
							$img = CImg::Resize($arResult["PROPERTIES"]["layouts_gallery"][0]["VALUE"], 450, 450, Cimg::M_FULL);
							if ($img)
							{
								?><img src="<?=$img?>" alt="<?=$arResult["NAME"]?>"/><?
							}
						}
						?>
					
					</div>
					<div class="desc-pp">
						<div class="price-pp">
							<? 
							if ($arProperties['currency']['VALUE'])
							{
								if ($arProperties['price']['VALUE'])
								{
									?>
									<?=MoneyOutPut($arProperties['price']['VALUE']) . ' ' . $arProperties['currency']['VALUE_XML_ID']?>
									<?
								}
								if ($arProperties['price_1m']['VALUE'])
								{
									?>
									<span><?=MoneyOutPut($arProperties['price']['VALUE']) . ' ' . $arProperties['currency']['VALUE_XML_ID']?>/м<sup>2</sup></span>
									<?
								}
							}
							?>
							
							
						</div>
						<?
						if ($arProperties["newbuilding"]["ELEM"]["PROPERTIES"]["builder"]["VALUE"])
						{
							\SiteTemplates\Builder::getInstance($arProperties["newbuilding"]["ELEM"]["PROPERTIES"]["builder"]["VALUE"])->shortInfo(array("AJAX"=>$arParams["AJAX"]));
						}
						if ($arProperties["newbuilding"]["ELEM"]["PROPERTIES"]["user_realtor"]["VALUE"])
						{
							\SiteTemplates\Realtor::getInstance($arProperties["newbuilding"]["ELEM"]["PROPERTIES"]["user_realtor"]["VALUE"])->shortInfo(array("AJAX"=>$arParams["AJAX"]));
						}
						?>
						
					</div>
				</div>
			</div><!--pop-->

<?