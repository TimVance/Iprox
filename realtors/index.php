<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Риэлторы Сочи");
?>
<div class="rielters"><a href="/agents/">Агентства</a></div>

<?$APPLICATION->IncludeComponent('wexpert:includer', 'search_in_realtors')?>
<?/*$APPLICATION->IncludeComponent('wexpert:includer', 'sort_in_catalog')*/?>

<?$APPLICATION->IncludeComponent('wexpert:includer', 'sort_realty', array("ORDER" => \Morealty\Realtor::getCurrentSort()))?>
<?$APPLICATION->IncludeComponent(
	"morealty:realtors.list",
	"",
	Array(
		"SORT_BY"						=> \Morealty\Realtor::getCurrentSortBy(),
		"SORT_ORDER"					=> \Morealty\Realtor::getCurrentSortOrder(),
		"SEARCH_BY"						=> $_GET['search'],
	),
	false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>