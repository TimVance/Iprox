<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


$addFilter = array();


global $USER;

if (strtolower($_REQUEST["set_filter"]) == "y")
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
foreach ($arResult["ITEMS"] as $arItem)
{
	
	if ($arItem["CUSTOM_OPTION"] === "Y")
	{
		
		if ($arItem["PROPERTY_TYPE"] == "N")
		{
			foreach ($arItem["VALUES"] as $type => $arValue)
			{
				if (isset($arValue["HTML_VALUE"]))
				{
					$sign = ($type == "MIN")? ">=" : "<=";
					$addFilter[$sign."PROPERTY_".$arItem["PROPERTY"][$type]["ID"]] = $arValue["HTML_VALUE"];
				}
			}
		}
		elseif ($arItem["PROPERTY_TYPE"] == "FS")
		{
			if ($arItem["HTML_VALUE"])
			{
				$addFilter[$arItem["CODE"]] = "%".$arItem["HTML_VALUE"]."%";
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

