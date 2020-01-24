<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>



<div class="list-agents">
<?foreach($arResult["ITEMS"] as $currentItem):?>
	<?
	$realtors = array();
	$rsUsers = CUser::GetList(($by=""), ($order="desc"), $filter, array('SELECT' => array('UF_AGENT_NAME'))); // выбираем пользователей
	while($ob = $rsUsers->Fetch()):
		if($ob['UF_AGENT_NAME'] == $currentItem['ID']) {
			$realtors[] = $ob['ID'];
		}
	endwhile;


	$offers = array();
	if(count($realtors) > 0) {
		$arSelect = Array("ID", "IBLOCK_ID", "NAME");
		$arFilter = Array("IBLOCK_ID" => array(7, 8, 10, 11, 12, 13), "PROPERTY_realtor" => $realtors, "ACTIVE"=>"Y");
		$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
		while($ob = $res->GetNextElement()){
			$offers[] = $ob;
		} //ok
	}

	?>
	<div class="item-agents">
		<div class="img-agent"><a href="<?=$currentItem["DETAIL_PAGE_URL"]?>">
				<img
					class="preview_picture"
					border="0"
					src="<?=$currentItem['PREVIEW_PICTURE']["SRC"]?>"
					alt="<?=$currentItem["PREVIEW_PICTURE"]["ALT"]?>"
					title="<?=$currentItem["PREVIEW_PICTURE"]["TITLE"]?>"
				/>
		</div>
		<div class="desc-agents">
			<div class="t-agents"><a href="<?=$currentItem["DETAIL_PAGE_URL"]?>"><?=$currentItem["NAME"]?></a></div>
			<div class="adress-agent"><?=$currentItem["PROPERTIES"]["ADDRESS"]["VALUE"]?></div>
			<div class="info-agents">
				<div class="func-agents">
					<div class="contacts-agents">
						<ul>
							<li><span><?=$currentItem["PROPERTIES"]["PHONE_NUMBER"]["VALUE"]?></span></li>
							<li><a href="mailto:<?=$currentItem["PROPERTIES"]["EMAIL"]["VALUE"]?>"><?=$currentItem["PROPERTIES"]["EMAIL"]["VALUE"]?></a></li>
						</ul>
					</div><!--contacts-agents-->
					<div class="nums-contacts">
						<ul>
							<li>Сотрудников <span><?=count($realtors)?></span></li>
							<li>Всего предложений <span><?=count($offers)?></span></li>
						</ul>
					</div>
				</div><!--func-agents-->

				<div class="text-agents">
					<p><?=$currentItem["PREVIEW_TEXT"]?></p>
					<div class="more-but more-but3"><a href="<?=$currentItem["DETAIL_PAGE_URL"]?>">Подробнее</a></div>
				</div><!--text-agents-->
			</div><!--info-agents-->
		</div><!--desc-agents-->
	</div><!--item-agents-->
<?endforeach;?>
</div><!--list-agents-->
<?=$arResult["NAV_STRING"]?>