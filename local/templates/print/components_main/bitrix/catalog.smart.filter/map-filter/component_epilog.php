<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


$addFilter = array();


global $USER;
if ($_REQUEST["set_filter"] == "Y")
{
	foreach ($arResult["PROCCESS_PROPS"] as $arProp)
	{
		if ($arProp["ACTIVE"] != "Y")
			continue;
		if ($arProp["PROPERTY_TYPE"] == "L")
		{
			foreach ($arProp["VALUES"] as $arValue)
			{
				if (!$_REQUEST[$arValue["CONTROL_NAME_ALT"]])
					break;
				else if ($arProp["CODE"] == "currency")
				{
					$isPrice = strlen($_REQUEST["arrFilter_143_MIN"]) > 0 || strlen($_REQUEST["arrFilter_88_MIN"]) > 0 || strlen($_REQUEST["arrFilter_140_MIN"]) > 0 || strlen($_REQUEST["arrFilter_141_MIN"]) > 0;
					if (!$isPrice)
					{
						break;
					}
					if ($_REQUEST[$arValue["CONTROL_NAME_ALT"]] == $arValue["HTML_VALUE_ALT"])
					{
						$addFilter["=PROPERTY_".$arProp["ID"]][] = $arValue["REAL_ID"];
						break;
					}
				}
				else if ($_REQUEST[$arValue["CONTROL_NAME_ALT"]] == $arValue["HTML_VALUE_ALT"])
				{
					$addFilter["=PROPERTY_".$arProp["ID"]][] = $arValue["REAL_ID"];
					break;
				}
			}
		}
	}
}
if ($addFilter && count($addFilter) > 0)
{
	foreach ($addFilter as $key=> $value)
	{
		$GLOBALS[$arParams["FILTER_NAME"]][$key] = $value;
	}
}