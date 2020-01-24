<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
$curDir = $APPLICATION->GetCurPage(false);
?>
<div class="menu-cab">
	<ul>
	<?foreach ($arResult as $arItem):?>
	<?
	if ($arItem["PERMISSION"] == "D")
		continue;
	?>
			<?if ($curDir == $arItem["LINK"]):?>
				<li class="active"><span><i><?=$arItem["TEXT"]?></i></span>
			<?elseif ($arItem["SELECTED"]):?>
				<li><a href="<?=$arItem["LINK"];?>"><i><b><?=$arItem["TEXT"];?></b></i></a>
			<?else:?>
				<li><a href="<?=$arItem["LINK"];?>"><i><?=$arItem["TEXT"];?></i></a>
			<?endif?>
		</li>
	<?endforeach;?>
	</ul>
</div><!--manu-cab-->
