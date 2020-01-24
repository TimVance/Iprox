<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

//$APPLICATION->SetTitle("Риэлторы Сочи");
$url = explode('/realtors/', $_SERVER['REQUEST_URI']);
$url =  str_replace('/', '', $url[1]);
if($url != $_REQUEST["ID"] && !strstr($url , '?')) {
	header("HTTP/1.0 404 Not Found\r\n");
	@define("ERROR_404","Y");
}
?>
<?
$APPLICATION->IncludeComponent("morealty:realtors.detail", "",Array(
		"ID" => $_REQUEST["ID"],
		"OBJECTS_TEMPLATE_VIEW" => $_REQUEST["VIEW_TYPE"],
		
	)
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>