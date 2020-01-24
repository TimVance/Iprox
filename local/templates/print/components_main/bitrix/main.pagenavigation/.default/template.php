<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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


?>
<div class="pages">
	
		
<?if($arResult["REVERSED_PAGES"] === true):?>

	<?if ($arResult["CURRENT_PAGE"] < $arResult["PAGE_COUNT"]):?>
		<?if (($arResult["CURRENT_PAGE"]+1) == $arResult["PAGE_COUNT"]):?>
			<p><a class="modern-page-previous" href="<?=htmlspecialcharsbx($arResult["URL"])?>"><?=GetMessage("nav_prev2")?></a></li>
		<?else:?>
			<p><a class="modern-page-previous" href="<?=htmlspecialcharsbx($component->replaceUrlTemplate($arResult["CURRENT_PAGE"]+1))?>"><?=GetMessage("nav_prev2")?></a></li>
		<?endif?>
	<?endif?>
	<ul>
	<?if ($arResult["CURRENT_PAGE"] < $arResult["PAGE_COUNT"]):?>
			<li class=""><a href="<?=htmlspecialcharsbx($arResult["URL"])?>"><span>1</span></a></li>
	<?else:?>
			<li class="modern-page-current"><span>1</span></li>
	<?endif?>
	<?
	$page = $arResult["START_PAGE"] - 1;
	while($page >= $arResult["END_PAGE"] + 1):
	?>
		<?if ($page == $arResult["CURRENT_PAGE"]):?>
			<li class="modern-page-current"><span><?=($arResult["PAGE_COUNT"] - $page + 1)?></span></li>
		<?else:?>
			<li class=""><a href="<?=htmlspecialcharsbx($component->replaceUrlTemplate($page))?>"><?=($arResult["PAGE_COUNT"] - $page + 1)?></a></li>
		<?endif?>

		<?$page--?>
	<?endwhile?>
	<?if ($arResult["CURRENT_PAGE"] > 1):?>
		<?if($arResult["PAGE_COUNT"] > 1):?>
			<li class=""><a href="<?=htmlspecialcharsbx($component->replaceUrlTemplate(1))?>"><span><?=$arResult["PAGE_COUNT"]?></span></a></li>
		<?endif?>
	<?else:?>
		<?if($arResult["PAGE_COUNT"] > 1):?>
			<li class="modern-page-current"><span><?=$arResult["PAGE_COUNT"]?></span></li>
		<?endif?>
	<?endif?>
	</ul>

	<?if ($arResult["CURRENT_PAGE"] > 1):?>
			<p><a class="modern-page-next" href="<?=htmlspecialcharsbx($component->replaceUrlTemplate($arResult["CURRENT_PAGE"]-1))?>"><?echo GetMessage("nav_next2")?></a></li>
	<?endif?>

<?else:?>

	<?if ($arResult["CURRENT_PAGE"] > 1):?>
		<?if ($arResult["CURRENT_PAGE"] > 2):?>
			<p><a class="modern-page-previous" href="<?=htmlspecialcharsbx($component->replaceUrlTemplate($arResult["CURRENT_PAGE"]-1))?>"><?echo GetMessage("nav_prev2")?></a></li>
		<?else:?>
			<p><a class="modern-page-previous" href="<?=htmlspecialcharsbx($arResult["URL"])?>"><?echo GetMessage("nav_prev2")?></a></li>
		<?endif?>
	<?endif?>
	<ul>
	<?if ($arResult["CURRENT_PAGE"] > 1):?>
			<li class=""><a href="<?=htmlspecialcharsbx($arResult["URL"])?>">1</a></li>
	<?else:?>
			<li class="modern-page-current"><span>1</span></li>
	<?endif?>
	<?
	$page = $arResult["START_PAGE"] + 1;
	while($page <= $arResult["END_PAGE"]-1):
	?>
		<?if ($page == $arResult["CURRENT_PAGE"]):?>
			<li class="modern-page-current"><span><?=$page?></span></li>
		<?else:?>
			<li class=""><a href="<?=htmlspecialcharsbx($component->replaceUrlTemplate($page))?>"><?=$page?></a></li>
		<?endif?>
		<?$page++?>
	<?endwhile?>
	<?if($arResult["CURRENT_PAGE"] < $arResult["PAGE_COUNT"]):?>
		<?if($arResult["PAGE_COUNT"] > 1):?>
			<li class=""><a href="<?=htmlspecialcharsbx($component->replaceUrlTemplate($arResult["PAGE_COUNT"]))?>"><?=$arResult["PAGE_COUNT"]?></a></li>
		<?endif?>
	<?else:?>
		<?if($arResult["PAGE_COUNT"] > 1):?>
			<li class="modern-page-current"><span><?=$arResult["PAGE_COUNT"]?></span></li>
		<?endif?>
	<?endif?>
	</ul>
	<?if($arResult["CURRENT_PAGE"] < $arResult["PAGE_COUNT"]):?>
		<p><a class="modern-page-next" href="<?=htmlspecialcharsbx($component->replaceUrlTemplate($arResult["CURRENT_PAGE"]+1))?>"><?echo GetMessage("nav_next2")?></a></li>
	<?endif?>
<?endif?>

<?if ($arResult["SHOW_ALL"] && false):?>
	<?if ($arResult["ALL_RECORDS"]):?>
			<li class="bx-pag-all"><a href="<?=htmlspecialcharsbx($arResult["URL"])?>" rel="nofollow"><span><?echo GetMessage("round_nav_pages")?></span></a></li>
	<?else:?>
			<li class="bx-pag-all"><a href="<?=htmlspecialcharsbx($component->replaceUrlTemplate("all"))?>" rel="nofollow"><?echo GetMessage("round_nav_all")?></a></li>
	<?endif?>
<?endif?>
		
		<div style="clear:both"></div>
</div>

