<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();?>
<? 
if ($arParams["HIDE_AGENT"] !== "Y")
{
	$PersonalPhoto = ($arResult["PERSONAL_PHOTO_ID"])? weImg::Resize($arResult["PERSONAL_PHOTO_ID"],100,100,weImg::M_CROP) : false;
}
else
{
	$PersonalPhoto = ($arResult["AGENTS_INFO"]["PREVIEW_PICTURE"])? weImg::Resize($arResult["AGENTS_INFO"]["PREVIEW_PICTURE"],100,100,weImg::M_CROP) : false;
}
$offersCount = intval($arResult["UF_REALTOR_OBJECTS"]);
?>
<div class="full-rielt realtor-<?=$arResult["ID"]?>">
	<div class="info-cont-rielt">
		<div class="in-emp">
		<? if ($PersonalPhoto)
		{
			?>
			<div class="img-emp">
				<img src="<?=$PersonalPhoto?>" alt=""/>
			</div>
			<?
		}?>
			
			<div class="desc-emploe">
			<? 
			if ($arResult['AGENTS_INFO'])
			{
				?>
				<div class="<?=($arParams["HIDE_AGENT"] !== "Y") ? "name-rr" : "name-emploe"?>">
					<? $link = 'http://' . $_SERVER['HTTP_HOST'] . '/agents/' . $arResult['AGENTS_INFO']["ID"] . '/'; ?>
					<a href="<?= $link ?>"><?= $arResult['AGENTS_INFO']["NAME"] ?></a>
				</div>
				<?
			}
			?>
			<? 
			if ($arParams["HIDE_AGENT"] !== "Y")
			{
				?>
				<div class="name-emploe">
					<? $link = 'http://' . $_SERVER['HTTP_HOST'] . '/realtors/' . $arResult["ID"] . '/'; ?>
					<a href="<?= $link ?>"><?= $arResult["FULL_NAME"] ?></a>
					<span><?= $arResult['UF_PERSONAL_POST'] ?></span>
				</div>
				<?
			}
			?>
				

				<? if ($offersCount !== false && $arParams["HIDE_AGENT"] !== "Y") {
					?>
						<div class="propos-rr">(<?= $offersCount ?> <?=Suffix($offersCount, array("предложение","предложения","предложений"))?>)</div>
					<?
				}?>
			</div><!--desc-emploe-->
		</div>
	</div>

	<div class="view-ph">
		<?if(!empty($arResult['PERSONAL_MOBILE'])):?>
			<div class="view-phone">
				<a <?if ($arParams["HIDE_AGENT"] !== "Y") {?>data-realtor="<?=$arResult["ID"]?>"<?}else {?>data-agents="<?=$arResult["AGENTS_INFO"]["ID"]?>"<?} ?>  href="javascript:void(0);">Показать телефон</a>
				<div class="phone-v"><span></span></div>
			</div>
		<?endif;?>
		<div class="send-mess"><a class="inline" href="#send_message_to_realtor-form">Оставить сообщение</a>
		</div>
	</div>
</div>
<? 
if ($arParams["AJAX"] == "Y")
{
	?>
	<script>
		window.initPhones(".realtor-<?=$arResult["ID"]?>");
		$(".realtor-<?=$arResult["ID"]?> .inline").colorbox({inline: true, closeButton: true, escKey: true});
	</script>
	<?
}
?>