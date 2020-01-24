<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$formName = ($arParams["FORM_NAME"])? $arParams["FORM_NAME"] : "SEARCH_FORM";
?>
<div class="block-search">
	<form action="" name="<?=$formName?>" method="GET">
		<input type="text" name="search" value="<?=$_GET["search"]?>" placeholder="Фио, телефон, агентство" />
		<div class="but-s">
			<button type="submit">Найти</button>
		</div>
	</form>
</div>

<?
