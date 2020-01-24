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
?>
<div class="b-news">
	<div class="title"><?=$arParams["PAGER_TITLE"]?></div>
		<div class="list-news">
			<ul>
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$url = weImg::Resize($arItem['PREVIEW_PICTURE']["SRC"], 235, 176, 'STRETCH');
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<li class="news-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
		<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
				<div class="img-news"><a href="<?=$arItem["DETAIL_PAGE_URL"]?><?=$arItem["CODE"]?>/"><img
						class="preview_picture"
						border="0"
						src="<?=$url?>"
						alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
						title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
						style="float:left"
						/></a></div>
		<?endif?>
		<?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
			<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
				<div class="tit-news"><a href="<?echo $arItem["DETAIL_PAGE_URL"]?><?=$arItem["CODE"]?>/"><?echo $arItem["NAME"]?></a></div>
			<?else:?>
				<div class="tit-news"><?echo $arItem["NAME"]?></div>
			<?endif;?>
		<?endif;?>
		<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
			<p><?echo $arItem["PREVIEW_TEXT"];?></p>
		<?endif;?>
		<?if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
			<div class="date-n"><?echo $arItem["DISPLAY_ACTIVE_FROM"]?></div>
		<?endif?>
	</li>
<?endforeach;?>
			</ul>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
		<div class="more"><a href="/info/news/">Все новости</a></div><!--more-->
<?endif;?>

		</div><!--list-news-->
</div>
