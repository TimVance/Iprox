<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
$this->setFrameMode(true);
if ($arResult["TOTAL_COUNT"])
{
	$this->SetViewTarget("agent_page_count_objects");
	?>(<?=$arResult["TOTAL_COUNT"]?>)<?
	$this->EndViewTarget();
}
?>

<?
	$arProperties = $arResult["PROPERTIES"];
	//
	/*$rsUser = CUser::GetList(($by="ID"), ($order="desc"), array('GROUPS_ID' => array(7),"UF_AGENT_NAME"=>$arResult["ID"]), array("SELECT"=>array("UF_*")));
	$users = array();
	$AgentsOffersCount = 0;
	$arCurrentRealtorOffersID = array();
	while($user = $rsUser->Fetch()) {
		$arSelect = Array('ID', 'NAME');
		$arFilter = Array('TYPE'=> 'catalog', 'ACTIVE'=>'Y', 'GLOBAL_ACTIVE'=>'Y');
		$res = CIBlock::GetList(Array('SORT'=>'ASC'), $arFilter, true, $arSelect);
		while($item = $res->GetNext()) {
			$arID[]   = $item['ID'];
			$arNAME[]   = $item['NAME'];
		}

		$arSECTION = array();

		for($i = 0; $i < count($arID) ; $i++) {
			$arSECTION[$arID[$i]] = $arNAME[$i];
		}
		$arSelect = Array('PROPERTY_REALTOR', 'IBLOCK_ID', 'ID');
		$arFilter = Array("IBLOCK_ID"=>$arID, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
		$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
		$currentID = $arResult['ID'];
		$arRealtorOffers = '';
		
		while($ob = $res->GetNextElement()) {
			$arFields = $ob->GetFields();
			if($arFields['PROPERTY_REALTOR_VALUE'] == $currentID) {
				$arCurrentRealtorOffersID[] = $arFields['ID'];
				if(!empty($arRealtorOffers[$arFields['IBLOCK_ID']])) {
					$arRealtorOffers[$arFields['IBLOCK_ID']]++;
				}
				else {
					$arRealtorOffers[$arFields['IBLOCK_ID']] = 1;
				}
			}
		}


		$totalCount = 0;
		foreach($arRealtorOffers as $offer) {
			$totalCount += $offer;
			$AgentsOffersCount+=$offer;
		}
		$user['OFFER_COUNT'] = $totalCount;

		if($user['UF_AGENT_NAME'] == $arResult['ID']) {
			$users[] = $user;
		}
	}*/

$GLOBALS['EMAILS_FOR_FORM'] = $arProperties['EMAIL']['VALUE'];
?>
<div class="adress-agency"><?=$arProperties['ADDRESS']['VALUE']?></div>
<div class="about-agency">
	<div class="about-agency-l">
		<div class="card-agency">
			<?
			if ($arResult['PREVIEW_PICTURE'])
			{
				?>
				<div class="img-card-agency">
					<img
						class="preview_picture"
						border="0"
						src="<?=$arResult['PREVIEW_PICTURE']["SRC"]?>"
						alt="<?=$arResult["PREVIEW_PICTURE"]["ALT"]?>"
						title="<?=$arResult["PREVIEW_PICTURE"]["TITLE"]?>"
					/>
				</div>
				<?
			}
			?>
			
			<div class="desc-card-agency">
				<div class="link-ag">
					<ul>
						<li><a target="_blank" href="//<?=$arProperties['SITE_ADDRESS']['VALUE']?>"><?=$arProperties['SITE_ADDRESS']['VALUE']?></a></li>
						<li><span class="mailme"><?=str_replace(array("@", "."), array(" AT ", " DOT "), $arProperties['EMAIL']['VALUE'])?></span></li>
					</ul>
				</div>
				<div class="work-ag">
					<ul>
						<? foreach($arProperties["TIMETABLE"]["VALUE"] as $itemValue): ?>
							<li>
								<?
								//формируем правильный вывод нулей в часах путем добавления тегов
								$fullString = $itemValue;
								for($i = 0; $i < substr_count($fullString, ':'); $i += 1) { // break
									$pos = strpos($itemValue, ':');
									if($pos > 0) {
										$part1 = substr($itemValue, 0, $pos);
										$part2 = substr($itemValue, $pos + 1, count($itemValue) - 2);
										$part21= substr($itemValue, -1);
										$part2 .= $part21;
										$nextSymbol = is_numeric(substr($part2, 1, 1));
										if($nextSymbol) {
											echo substr($part1, 2) . "<sup>" . substr($part2, 0, 2) . "</sup>";
										}
										else {
											echo $part1;
										}
										$itemValue = $part2;
									}
									else {
										break;
									}
								}
								if(substr_count($fullString, ':') == 0) {
									echo $itemValue;
								}
								?>
							</li>
						<? endforeach; ?>
					</ul>
				</div>

				<div class="phone-ag"><?=$arProperties['PHONE_NUMBER']['VALUE']?></div>

				<div class="mess-ag"><a class="inline" href="#send_message_to_realtor-form">Оставить сообщение</a></div>
			</div>
		</div>

		<div class="text-ab-ag">
			<?
			if ($arResult["DETAIL_TEXT"])
			{
				?>
					<div class="t-ab">Об агентстве</div>
					<div class="text-acoord">
						<?=$arResult["DETAIL_TEXT"]?>
					</div>
					<div class="link-acc"><a href="javascript:void(0);"><span class="open-text">Развернуть</span> <span class="close-text">Свернуть</span></a></div>
				<?
			}
			?>

		</div>
		<div class="accord-card">
			<?
			if ($arProperties["BRANCHES"]["VALUE"])
			{
				?>
				<div class="item-accrod">
					<div class="tit-accord"><span><?=$arProperties["BRANCHES"]["NAME"]?></span></div>
					<div class="text-accrod">
						<ul>
							<? foreach($arProperties["BRANCHES"]["VALUE"] as $itemValue): ?>
								<li><?=$itemValue?></li>
							<? endforeach; ?>
						</ul>
					</div>
				</div>
				<?
			}
			?>
			
			<?
			if ($arProperties["ACTIVITIES"]["VALUE"])
			{
				?>
				<div class="item-accrod">
					<div class="tit-accord"><span><?=$arProperties["ACTIVITIES"]["NAME"]?></span></div>
					<div class="text-accrod">
						<ul>
							<? foreach($arProperties["ACTIVITIES"]["VALUE"] as $itemValue): ?>
								<li><?=$itemValue?></li>
							<? endforeach; ?>
						</ul>
					</div>
				</div>
				<?
			}
			?>
			
		</div>
	</div>

	<div class="about-agency-r">
		<div class="map-agency">

			<? $arProperty = $arResult["PROPERTIES"]; ?>
				<? $arPos = explode(",", $arProperty['YANDEX_MAP']['VALUE']);?>
					<?$APPLICATION->IncludeComponent(
						"bitrix:map.yandex.view",
						"contacts",
						Array(
							"INIT_MAP_TYPE" => "MAP",
							"MAP_DATA" => serialize(array(
								'yandex_lat' => $arPos[0],
								'yandex_lon' => $arPos[1],
								'yandex_scale' => 15,
								'PLACEMARKS' => array (
									array(
										//'TEXT' => $arProperty["YAMAP"]["VALUE"].", ".$arProperty["YANDEX_MAP"]["VALUE"],
										"TEXT" => $arResult["NAME"],
										'LON' => $arPos[1],
										'LAT' => $arPos[0],
									),
								),
							)),
							"MAP_WIDTH" => "100%",
							"MAP_HEIGHT" => "500",
							"CONTROLS" => array(/*"ZOOM", "MINIMAP", "TYPECONTROL", "SCALELINE"*/),
							"OPTIONS" => array("DESABLE_SCROLL_ZOOM", "ENABLE_DBLCLICK_ZOOM", "ENABLE_DRAGGING"),
							"MAP_ID" => "",
							"ONMAPREADY"=> "EventMapReady"
						),
						$component
					);?>
		</div>
		<? if ($arProperties["VIDEO_LINK"]["VALUE"]) {
			?>
			<div class="video-agency">
				<iframe width="100%" height="315" src="<?=$arProperties["VIDEO_LINK"]["VALUE"]?>" frameborder="0" allowfullscreen></iframe>
			</div>
			<?
		}?>

	</div>
</div>

<?=$arResult["USERS_HTML"]?>

<?


//

?>