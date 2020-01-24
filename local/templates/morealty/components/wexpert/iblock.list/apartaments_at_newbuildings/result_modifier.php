<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? 
$Creaters = array();
foreach ($arResult["ITEMS"] as $arItem)
{
	$Creaters[] = $arItem["CREATED_BY"];
}

$UsersGroups = CustomUsers::GetUsersGourps($Creaters);


$Creaters = array_unique($Creaters);
$arResult["OwnersITEMS"] = array();
$arResult["realtorsITEMS"] = array();

foreach ($arResult["ITEMS"] as $arItem)
{
	if (intval($arItem["CREATED_BY"]) > 0)
	{
		if (array_intersect($UsersGroups[$arItem["CREATED_BY"]], $GLOBALS["OwnerGroups"]))
		{
			$arResult["OwnersITEMS"][] = $arItem;
		}
		else if (array_intersect($UsersGroups[$arItem["CREATED_BY"]], $GLOBALS["RealtorGroups"]))
		{
			$arResult["realtorsITEMS"][] = $arItem;
		}

	}
}
$this->__component->SetResultCacheKeys(array("ITEMS"));
?>