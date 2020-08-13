<? if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die(); ?>

<?
$arReturn = array();
if (intval($_REQUEST["iblock_id"]) > 0)
{
	$arReturn["IBLOCK_ID"] = intval($_REQUEST["iblock_id"]);
}

if (trim($_REQUEST["rooms_number"]))
{
	//room_number
	$arReturn["PROPERTY_room_number"] = preg_split("/[\s,]+/", trim($_REQUEST["rooms_number"]));
}
if (intval($_REQUEST["buy_type1"] == 1))
{
	$arReturn["PROPERTY_estate_type_VALUE"][] = "Вторичка";
}
if (intval($_REQUEST["buy_type2"] == 1))
{
	$arReturn["!PROPERTY_newbuilding"][] = false;
}
if (intval($_REQUEST["buy_type3"] == 1))
{
	$arReturn["PROPERTY_estate_type_VALUE"][] = "Ипотека";
}

	

if (intval($_REQUEST["price_from"]) > 0 || intval($_REQUEST["price_to"]) > 0)
{
	if (intval($_REQUEST["price_from"]) <= intval($_REQUEST["price_to"]))
	{
		if (trim($_REQUEST["currency"]))
		{
			$arReturn["PROPERTY_currency_VALUE"] = trim(strtoupper($_REQUEST["currency"]));
		}
		if (intval($_REQUEST["price_from"]) > 0)
		{
			$arReturn[">=PROPERTY_price"] = intval($_REQUEST["price_from"]);
		}
		if (intval($_REQUEST["price_to"]) > 0)
		{
			$arReturn["<=PROPERTY_price"] = intval($_REQUEST["price_to"]);
		}
	}
}
if (intval($_REQUEST["square_from"]) <= intval($_REQUEST["square_to"]))
{
	if (intval($_REQUEST["square_from"]) > 0)
	{
		$arReturn[">=PROPERTY_square"] = intval($_REQUEST["square_from"]);
	}
	if (intval($_REQUEST["square_to"]) > 0)
	{
		$arReturn["<=PROPERTY_square"] = intval($_REQUEST["square_to"]);
	}
}

if ($_REQUEST["is_shop"])
{
	$arReturn["PROPERTY_type"][] = "296";
}
if ($_REQUEST["is_office"])
{
	$arReturn["PROPERTY_type"][] = "295";
}
return $arReturn;
?>
