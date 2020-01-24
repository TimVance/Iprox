<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? 

$arProperties = $arResult["PROPERTIES"];
?>
<div class="b-investor builder-<?=$arResult["ID"]?>">
	<div class="t-investor">Застройщик/Инвестор</div>
	<div class="link-investor">
		<a href="<?=$arResult["DETAIL_PAGE_URL"]?>"><?=$arResult['NAME']?></a>
		<span><?= $arProperties['ADDRESS']['VALUE'] ?></span>
	</div>

	<div class="view-ph">
		<div class="view-phone">
			<a href="javascript:void(0);" data-builder="<?=$arResult["ID"]?>">Показать телефон</a>
			<div class="phone-v"><span></span></div>
		</div>
		<div class="send-mess"><a class="inline inline-message" data-user="<?=$arResult["ID"]?>" href="#send_message_to_realtor-form">Оставить сообщение</a></div>
	</div>
</div>
<? 
if ($arParams["AJAX"] == "Y")
{
	?>
	<script>
		window.initPhones(".builder-<?=$arResult["ID"]?>");
		$(".builder-<?=$arResult["ID"]?> .inline").colorbox({inline: true, closeButton: true, escKey: true});
	</script>
	<?
}
?>
