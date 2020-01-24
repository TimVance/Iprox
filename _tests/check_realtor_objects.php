<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if (!$USER->IsAdmin())
	die();

	//\Morealty\User::updateUsersObjects();
?>
<form action="" method="post">
	<input type="number" name="realtor" value="<?=$_POST["realtor"]?>">
	<input type="submit" name="send" value="Отправить">
</form>
<?
$curRealtor = intval($_POST["realtor"]);
if ($curRealtor > 0)
{
	if (!\Bitrix\Main\Loader::includeModule("iblock"))
		die();
		$filter = array("IBLOCK_ID" => \Morealty\Catalog::getCatalogIblockIds(), "ACTIVE" => "Y", "PROPERTY_realtor" => $curRealtor, "!PROPERTY_IS_ACCEPTED"=>false);
	$rs = CIBlockElement::GetList(array(), $filter, false, false, array("ID" , "NAME", "IBLOCK_ID"));
	my_print_r($filter, true, false);
	my_print_r($rs->AffectedRowsCount(), true, false);
	while ($arItem = $rs->Fetch())
	{
		my_print_r($arItem, true, false);
	}
}