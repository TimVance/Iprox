<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<? 
$arMapData = array();
$cnItem = count($arResult["ITEMS"]);
foreach ($arResult["ITEMS"] as $arItem)
{
	//my_print_r($arItem["PREVIEW_PICTURE"]);
	$arPosition = preg_split("/[\s,]+/", $arItem["PROPERTIES"]["YANDEX_MAP"]["VALUE"]);
	$arPointData = array(
			"ID" => $arItem["ID"],
			"LAT" => $arPosition[0],
			"LON" => $arPosition[1],
			//"NAME" => count($arItem["NAME"]),
			"TITLE" => $arItem["NAME"],
	);
	ob_start();
	?>
		<div class="info_pop" style="display: block;">
			<div class="close"></div>
			<div class="ip_top">
					<?if ($arItem["PREVIEW_PICTURE"]["ID"])
					{
						$img = weImg::Resize($arItem["PREVIEW_PICTURE"]["ID"],90,60,weImg::M_PROPORTIONAL);
						if ($img)
						{
							?>
								<div class="photo"><img src="<?=$img?>" alt="<?=$arItem["NAME"]?>"></div>
							<?
						}

					}?>
					<div class="txt">
						<div class="m_title"><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a></div>
						<span class="m_address"><?=$arItem["PROPERTIES"]["ADDRESS"]["VALUE"]?></span>
					</div>
			</div>
			<table class="ip_info_t">
				<p><?=$arItem["PREVIEW_TEXT"]?></p>
				<tbody>
				<? foreach ($arItem["OBJ"] as $arObject)
				{
					?>
						<tr>
							<td class="info_t_type"><a href="<?=$arObject["DETAIL_PAGE_URL"]?>"><? 
							if ($arObject["PROPERTIES"]["room_number"]["VALUE"])
							{
								?><?=$arObject["PROPERTIES"]["room_number"]["VALUE"]?>-комнатная<?
							}?></a></td>
							<td class="info_t_square"><?if ($arObject["PROPERTIES"]["square"]["VALUE"]) ?><?=$arObject["PROPERTIES"]["square"]["VALUE"]?> м<sup>2</sup></td>
							<td class="info_t_price"><? if ($arObject["PROPERTY_PRICE_VALUE"]){echo(number_format($arObject["PROPERTY_PRICE_VALUE"],0,' ',' '));}?> руб.</td>
						</tr>
					<?
				}?>

				</tbody>
			</table>
		</div>
		<?
		$arPointData["INNER_DATA"] = ob_get_clean();
		$arMapData["PLACEMARKS"][] = $arPointData;
}
if ($arParams["MAP_CENTER_POS"])
{
	$CenterPos = preg_split("/[\s,]+/", $arParams["MAP_CENTER_POS"]);
	$arMapData["yandex_lat"] = $CenterPos[0];
	$arMapData["yandex_lon"] = $CenterPos[1];
}
if ($arParams["MAP_ZOOM"])
{
	$arMapData["yandex_scale"] = $arParams["MAP_ZOOM"];
}
?>
<?$this->SetViewTarget('sell-map-map');?>
<?$APPLICATION->IncludeComponent(
		"bitrix:map.yandex.view",
		"sell-map-2.1",
		array(
				"INIT_MAP_TYPE" => "MAP",
				"MAP_DATA" => serialize($arMapData),
				"MAP_WIDTH" => "auto",
				"MAP_HEIGHT" => "945",
				"CONTROLS" => array(
						//0 => "ZOOM",
						//1 => "SMALLZOOM",
						//2 => "SCALELINE",
				),
				"OPTIONS" => array(
						0 => "ENABLE_DBLCLICK_ZOOM",
						1 => "ENABLE_DRAGGING",
				),
				"MAP_ID" => "yam_1",
				"COMPONENT_TEMPLATE" => "sell-map",
				"ONMAPREADY"=> "EventMapReady",
				"AUTO_ZOOM" => ($cnItem > 1)? "Y" : "N"
		),
		$component
);?>
<?$this->EndViewTarget();?>