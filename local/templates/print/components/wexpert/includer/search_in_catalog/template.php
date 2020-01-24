<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="block-search">
	<form action="" name="SEARCH_FORM" method="GET">
		<input type="text" name="search" value="<?=$_GET["search"]?>" placeholder="Название агентства, адрес" />
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