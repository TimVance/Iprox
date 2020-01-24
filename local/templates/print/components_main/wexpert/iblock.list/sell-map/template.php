<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);?>
<?

$arMapData = array();
$bAjax = ( $_REQUEST["ajax"] === "Y" ) && ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' );
$count = 0;
foreach ($arResult["OBJECTS"] as $arItem)
{
	//if (count($arItem["OBJ"]) <= 0) continue;
	
	$arPosition = preg_split("/[\s,]+/", $arItem["PROPERTIES"]["yandex_map"]["VALUE"]);
	if (!$arItem["PROPERTIES"]["yandex_map"]["VALUE"])
		continue;
	$arPointData = array(
			"ID" => $arItem["ID"],
			"LAT" => $arPosition[0],
			"LON" => $arPosition[1],
			"NAME" => count($arItem["OBJ"]),
	);
	if ($arItem["MIN_PRICE"])
	{

		$arPointData["TITLE"] = number_format($arItem["MIN_PRICE"],0,' ',' ');

		if ($arItem["MAX_PRICE"] != $arItem["MIN_PRICE"])
		{
			$arPointData["TITLE"].= " - ".number_format($arItem["MAX_PRICE"],0,' ',' ');
		}
		$arPointData["TITLE"].= " руб.";
	}
	else
	{
		$arPointData["TITLE"] = $arItem["NAME"];
	}
		ob_start();
		?>
			<div class="info_pop" style="display: block;">
				<div class="close"></div>
				<div class="ip_top">
				<?
				//my_print_r($arItem['PROPERTIES']["photo_gallery"]);
				?>
					<?if ($arItem["PROPERTIES"]["photo_gallery"]["VALUE"][0])
					{
						$img = weImg::Resize($arItem["PROPERTIES"]["photo_gallery"]["VALUE"][0],90,60,weImg::M_CROP);
						if ($img)
						{
							?>
								<div class="photo"><img src="<?=$img?>" alt="<?=$arItem["NAME"]?>"></div>
							<?
						}

					}?>
					<?$adressArray = array($arItem["PROPERTY_CITY_NAME"],$arItem["PROPERTY_DISTRICT_NAME"],$arItem["PROPERTY_MICRODISTRICT_NAME"],$arItem["PROPERTIES"]["street"]["VALUE"])?>
					<? 
					$adressArray = array_filter($adressArray, function($value) { return $value !== ''; })
					?>
					<div class="txt">
						<div class="m_title"><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a></div>
						<span class="m_address"><?=implode(", ", $adressArray)?></span>
					</div>
				</div>
				<div class="table-wrapper">
					<table class="ip_info_t">
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
		$cnt = count($arMapData["PLACEMARKS"]);
		$templateData = array_merge(array(array("CNT" => $cnt." ".Suffix($cnt, "Объект|Объекта|Объектов"))), $arMapData["PLACEMARKS"]);

		//my_print_r($arMapData,true,false);?>
		<?$this->SetViewTarget('sell-map-map');?>
		<? 
		$templateMapByUser = ($USER->GetLogin() == "vadim")?  "sell-map-2.1" : "sell-map";
		?>
		<?$APPLICATION->IncludeComponent(
				"bitrix:map.yandex.view",
				"sell-map-2.1",
				array(
						"INIT_MAP_TYPE" => "MAP",
						"MAP_DATA" => serialize($arMapData),
						"MAP_WIDTH" => "auto",
						"MAP_HEIGHT" => "600",
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
						"COMPONENT_TEMPLATE" => "sell-map-2.1",
						"ONMAPREADY"=> "EventMapReady",
						"CACHE_TYPE" => "N",
						"CACHE_TIME" => 0
				),
				$component
		);?>
		<?$this->EndViewTarget();?>
		<? 
		//IsFavoriteElement
		//my_print_r($arResult["OBJECTS"],true,false);
		?>
<?/*$this->SetViewTarget('sell-map-lent');?>
	<div class="options">
		<a href="#" class="picker clear" id="hide_map_list">Очистить</a>
		<div class="opts_wrap">
		<? foreach ($arResult["ITEMS"] as $arObject)
		{
			$isFavourite = IsFavoriteElement($arObject["ID"]);
			$adressArray = array($arItem["PROPERTY_CITY_NAME"],$arItem["PROPERTIES"]["district"]["VALUE"],$arItem["PROPERTIES"]["microdistrict"]["VALUE"],$arItem["PROPERTIES"]["street"]["VALUE"]);
			$adressArray = array_filter($adressArray, function($value) { return $value !== ''; });
			$image = ($arObject["PROPERTIES"]["photo_gallery"][0]["VALUE"])? weImg::Resize($arObject["PROPERTIES"]["photo_gallery"][0]["VALUE"],134,100,weImg::M_CROP): false;
			?>
			<div class="oneopt">
			<? if ($image)
			{
				?>
					<div class="photo"><a href="<?=$arObject["DETAIL_PAGE_URL"]?>"><img src="<?=$image?>" alt="<?=$arObject["NAME"]?>"></a></div>
				<?
			}?>
				
				<div class="info">
					<div class="opt_title"><a href="<?=$arObject["DETAIL_PAGE_URL"]?>"><?=$arObject["NAME"]?><? if ($arObject["PROPERTY_NEWBUILDING_NAME"]) {?> в <?=$arObject["PROPERTY_NEWBUILDING_NAME"]?><?}?></a></div>
					<span class="opt_address"><?=implode(", ", $adressArray)?></span>
					<span class="opt_price"><? if ($arObject["PROPERTY_PRICE_VALUE"]){echo(number_format($arObject["PROPERTY_PRICE_VALUE"],0,' ',' ')); ?> руб.<?}?> </span>
					<div class="favor_status new-type <? if ($isFavourite){echo("active");}?>" data-element="<?=$arObject["ID"]?>"></div>
				</div>
			</div>
			<?
		}?>
		</div>
	</div>
<?$this->EndViewTarget();*/?>