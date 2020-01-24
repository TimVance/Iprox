<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/* @var $this CBitrixComponentTemplate */
?>
<?
	$currentUserID = $USER->GetID();
	$rsUser = CUser::GetByID($currentUserID);
	$arUser = $rsUser->Fetch();
	$fullName = $arUser['NAME'] . ' ' . $arUser['SECOND_NAME'] . ' ' . $arUser['LAST_NAME'];
	$photoLink = 'http://' . $_SERVER['HTTP_HOST'] . '/' . CFile::GetPath($arUser['PERSONAL_PHOTO']);
?>
<div class="info-lk">
	<ul>
		<li><a href="/personal/profile/"><?=$fullName?></a></li>
		<li><span><?=$arUser['PERSONAL_PHONE']?></span></li>
	</ul>
	<div class="img-lk">
		<a href="/personal/profile/">
			<?if(!empty($arUser['PERSONAL_PHOTO'])) :?>
				<img src="<?=$photoLink?>" alt="" />
			<? else: ?>
				<img src="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" alt="" />
			<? endif; ?>
		</a>
	</div>
</div><!--info-lk-->
