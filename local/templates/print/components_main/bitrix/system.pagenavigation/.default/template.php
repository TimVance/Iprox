<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/* @var $this CBitrixComponentTemplate */

if (!$arResult["NavShowAlways"]) {
	if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false)) {
		return;
	}
}

?>
<div class="pages">
	<?
	$CurPageParams = "PAGEN_".$arResult["NavNum"];
	$arResult["NavQueryString"] = htmlspecialcharsbx(DeleteParam(array(
		"PAGEN_1",
		"SIZEN_1",
		"PHPSESSID",
		"clear_cache",
		"bitrix_include_areas",
		"catalog",
		"ID",
		$CurPageParams
	)));

	$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
	$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");

	?>
	<?
	$bFirst = true;
	if (!$arResult["NavShowAll"])
	{
		if ($arResult["NavPageNomer"] > 1):
		if($arResult["bSavePage"]):
		?>
				<p><a class="modern-page-previous" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>"><?=GetMessage("nav_prev2")?></a></p>
				<?
				$pager_link_prev = $arResult["sUrlPath"] . '?' . $strNavQueryString . 'PAGEN_' . $arResult["NavNum"] . '=' . ($arResult["NavPageNomer"]-1);
			else:
				if ($arResult["NavPageNomer"] > 2):
					?>
					<p><a class="modern-page-previous" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>"><?=GetMessage("nav_prev2")?></a></p>
					<?
					$pager_link_prev = $arResult["sUrlPath"] . '?' . $strNavQueryString . 'PAGEN_' . $arResult["NavNum"] . '=' . ($arResult["NavPageNomer"]-1);
				else:
					?>
					<p><a class="modern-page-previous" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=GetMessage("nav_prev2")?></a></p>
					<?
					$pager_link_prev = $arResult["sUrlPath"] . $strNavQueryStringFull;
				endif;
			endif;
	
			$this->SetViewTarget('pager_link_prev');
				?>
				<link rel="prev" href="<?=$pager_link_prev?>">
				<?
				unset($pager_link_prev);
			$this->EndViewTarget();
	
			if ($arResult["nStartPage"] > 1):
				$bFirst = false;
				if($arResult["bSavePage"]):
					?>
					<li><a class="modern-page-first" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=1">1</a></li>
					<?
				else:
					?>
					<li><a class="modern-page-first" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>">1</a></li>
					<?
				endif;
				if ($arResult["nStartPage"] > 2):
					/*?>
								<span class="modern-page-dots">...</span>
					<?*/
					?>
					<li><a class="modern-page-dots" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=round($arResult["nStartPage"] / 2)?>">...</a></li>
					<?
				endif;
			endif;
		endif;
	
		do
		{
			if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):
				?>
				<li><span class="<?=($bFirst ? "modern-page-first " : "")?>modern-page-current"><?=$arResult["nStartPage"]?></span></li>
				<?
			elseif($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):
				?>
				<li><a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>" class="<?=($bFirst ? "modern-page-first" : "")?>"><?=$arResult["nStartPage"]?></a></li>
				<?
			else:
				?>
				<li><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"<?
				?> class="<?=($bFirst ? "modern-page-first" : "")?>"><?=$arResult["nStartPage"]?></a></li>
				<?
			endif;
			$arResult["nStartPage"]++;
			$bFirst = false;
		} while($arResult["nStartPage"] <= $arResult["nEndPage"]);
	
	
	
		if($arResult["NavPageNomer"] < $arResult["NavPageCount"]):
			if ($arResult["nEndPage"] < $arResult["NavPageCount"]):
				if ($arResult["nEndPage"] < ($arResult["NavPageCount"] - 1)):
					/*?>
							<span class="modern-page-dots">...</span>
					<?*/
					?>
					<li><a class="modern-page-dots" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=round($arResult["nEndPage"] + ($arResult["NavPageCount"] - $arResult["nEndPage"]) / 2)?>">...</a></li>
					<?
				endif;
				?>
				<li><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["NavPageCount"]?>"><?=$arResult["NavPageCount"]?></a></li>
				<?
			endif;
			?>
			<p><a class="modern-page-next" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>"><?=GetMessage("nav_next2")?></a></p>
			<?
	
			$this->SetViewTarget('pager_link_next');
				?>
				<link rel="next" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>">
				<?
			$this->EndViewTarget();
		endif;
	}
		

	if ($arResult["bShowAll"]):
		if ($arResult["NavShowAll"]):
			?>
			<li><a class="modern-page-pagen full_width_element" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>SHOWALL_<?=$arResult["NavNum"]?>=0"><?=GetMessage("nav_paged")?></a></li>
			<?
		else:
			?>
			<li><a class="modern-page-all" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>SHOWALL_<?=$arResult["NavNum"]?>=1"><?=GetMessage("nav_all")?></a></li>
			<?
		endif;
	endif
	?>
</div>
