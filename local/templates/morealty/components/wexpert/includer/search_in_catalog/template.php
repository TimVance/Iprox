<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$formName = ($arParams["FORM_NAME"])? $arParams["FORM_NAME"] : "SEARCH_FORM";
$placeHolder = ($arParams["LABEL"])? $arParams["LABEL"] : "Название застройщика, адрес";
?>
<div class="block-search">
	<form action="" name="<?=$formName?>" method="GET">
		<?
		if ($arParams["IS_MAP"] === "Y")
		{
			?>
				<input type="hidden" name="map" value="Y">
			<?
		}
		?>
		<input type="text" name="search" value="<?=$_GET["search"]?>" placeholder="" />
		<div class="but-s">
			<button type="submit">Найти</button>
		</div>
	</form>
</div>

<?

//поиск по имени или адресу
global $arrFilter;
$arrFilter = array(array(
	                   "LOGIC" => "OR",
	                   "%NAME" => $_GET["search"],
	                   "%PROPERTY_ADDRESS" => $_GET["search"]
                   )); ?>