<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if (!empty($arResult)):?>
		
			<ul>

<?
$previousLevel = 0;
foreach($arResult as $arItem):?>
	<? 
	if ($arItem["PARAMS"]["HARD_LINK"] === "Y")
	{
		if ($APPLICATION->GetCurPage(false) != $arItem["LINK"] && $arItem["SELECTED"])
			$arItem["SELECTED"] = false;
	}
	?>
	<?if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel && $previousLevel != 2):?>
		<?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
	<?else: ?>
		<?=str_repeat("</ul></div></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
	<?endif?>

	<?if ($arItem["IS_PARENT"]):?>

		<?if ($arItem["DEPTH_LEVEL"] == 1):?>
			<li>
			<? if ($arItem["SELECTED"]) {?>
				<a href="<?=$arItem["LINK"]?>" class="root-item-selected"><p><?=$arItem["TEXT"]?></p></a>
			<?}
				else {?>
				<a href="<?=$arItem["LINK"]?>" class="root-item"><p><?=$arItem["TEXT"]?></p></a>
				<?}?>
			<div class="panel-menu">
				<ul>
		<?else:?>
			<li<?if ($arItem["SELECTED"]):?> class="item-selected"<?endif?>><a href="<?=$arItem["LINK"]?>" class="parent"><p><?=$arItem["TEXT"]?></p></a>
					<ul>
		<?endif?>

	<?else:?>

		<?if ($arItem["PERMISSION"] > "D"):?>

			<?if ($arItem["DEPTH_LEVEL"] == 1):?>
				<li>
				<? if ($arItem["SELECTED"]) {?>
					<span><p><?=$arItem["TEXT"]?></p></span>
				<?}
					else {?>
					<a href="<?=$arItem["LINK"]?>"><p><?=$arItem["TEXT"]?></p></a>
					<?}?>
				</li>
			<?else:?>
				<li<?if ($arItem["SELECTED"]):?> class="item-selected"<?endif?>><a href="<?=$arItem["LINK"]?>"><p><?=$arItem["TEXT"]?></p></a></li>
			<?endif?>

		<?else:?>

			<?if ($arItem["DEPTH_LEVEL"] == 1):?>
				<li><a href="" class="<?if ($arItem["SELECTED"]):?>root-item-selected<?else:?>root-item<?endif?>" title="<?=GetMessage("MENU_ITEM_ACCESS_DENIED")?>"><p><?=$arItem["TEXT"]?></p></a></li>
			<?else:?>
				<li><a href="" class="denied" title="<?=GetMessage("MENU_ITEM_ACCESS_DENIED")?>"><p><?=$arItem["TEXT"]?></p></a></li>
			<?endif?>

		<?endif?>

	<?endif?>

	<?$previousLevel = $arItem["DEPTH_LEVEL"];?>

<?endforeach?>

<?if ($previousLevel > 1)://close last item tags?>
	<?=str_repeat("</ul></div></li>", ($previousLevel-1) );?>
<?endif?>

</ul>

<?endif?>