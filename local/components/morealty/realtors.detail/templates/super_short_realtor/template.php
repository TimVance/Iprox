<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();?>
<? 

$PersonalPhoto = ($arResult["PERSONAL_PHOTO_ID"])? weImg::Resize($arResult["PERSONAL_PHOTO_ID"],100,100,weImg::M_CROP) : false;
?>
<div class="info-cont-rielt">
	<div class="in-emp">
		<div class="img-emp"><img src="<?=$PersonalPhoto?>" alt="<?=$arResult["FULL_NAME"] ?>" /></div>
		<div class="desc-emploe">
			<div class="name-emploe">
				<?= $arResult["FULL_NAME"] ?>
				<span><?=$arResult['UF_PERSON_POST']?></span>
			</div>
			<div class="view-rielt-phone">
				<input type="hidden" id="realtor_id" value="<?=$arResult['ID']?>">
				<a href="javascript:void(0);">Показать телефон</a>
				<span></span>
			</div>
		</div>
	</div>
</div>