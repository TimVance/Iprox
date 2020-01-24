<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();



if (isset($arParams["TEMPLATE_THEME"]) && !empty($arParams["TEMPLATE_THEME"]))
{
	$arAvailableThemes = array();
	$dir = trim(preg_replace("'[\\\\/]+'", "/", dirname(__FILE__)."/themes/"));
	if (is_dir($dir) && $directory = opendir($dir))
	{
		while (($file = readdir($directory)) !== false)
		{
			if ($file != "." && $file != ".." && is_dir($dir.$file))
				$arAvailableThemes[] = $file;
		}
		closedir($directory);
	}

	if ($arParams["TEMPLATE_THEME"] == "site")
	{
		$solution = COption::GetOptionString("main", "wizard_solution", "", SITE_ID);
		if ($solution == "eshop")
		{
			$templateId = COption::GetOptionString("main", "wizard_template_id", "eshop_bootstrap", SITE_ID);
			$templateId = (preg_match("/^eshop_adapt/", $templateId)) ? "eshop_adapt" : $templateId;
			$theme = COption::GetOptionString("main", "wizard_".$templateId."_theme_id", "blue", SITE_ID);
			$arParams["TEMPLATE_THEME"] = (in_array($theme, $arAvailableThemes)) ? $theme : "blue";
		}
	}
	else
	{
		$arParams["TEMPLATE_THEME"] = (in_array($arParams["TEMPLATE_THEME"], $arAvailableThemes)) ? $arParams["TEMPLATE_THEME"] : "blue";
	}
}
else
{
	$arParams["TEMPLATE_THEME"] = "blue";
}

$arParams["FILTER_VIEW_MODE"] = (isset($arParams["FILTER_VIEW_MODE"]) && toUpper($arParams["FILTER_VIEW_MODE"]) == "HORIZONTAL") ? "HORIZONTAL" : "VERTICAL";
$arParams["POPUP_POSITION"] = (isset($arParams["POPUP_POSITION"]) && in_array($arParams["POPUP_POSITION"], array("left", "right"))) ? $arParams["POPUP_POSITION"] : "left";

$arResult["PROCCESS_PROPS"] = array();
foreach (\Morealty\Catalog::getCatalogAdditionalPropsFromMain() as $PretendentCode)
{
	if (!$PretendentCode)
		continue;
		$propQuery = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => intval($arParams["IBLOCK_ID"]), "CODE" => $PretendentCode));
		if ($arProp = $propQuery->Fetch())
		{
			if ($arProp["PROPERTY_TYPE"] == "L")
			{
				$EnumRes = CIBlockPropertyEnum::GetList(array("ID" => "ASC"), array("IBLOCK_ID" => intval($arParams["IBLOCK_ID"]), "PROPERTY_ID" => $arProp["ID"]));
				while ($arValuesProp = $EnumRes->GetNext())
				{
					$safeValue = abs(crc32($arValuesProp["ID"]));
					$arProp["VALUES"][] = array(
							"CONTROL_ID" => $arParams["FILTER_NAME"]."_".$arProp["ID"]."_".$safeValue,
							"CONTROL_NAME" => $arParams["FILTER_NAME"]."_".$arProp["ID"]."_".$safeValue,
							"CONTROL_NAME_ALT" => $arParams["FILTER_NAME"]."_".$arProp["ID"],
							"HTML_VALUE_ALT" => $safeValue,
							"REAL_ID" => $arValuesProp["ID"],
							"VALUE"	=> $arValuesProp['VALUE'],
							"SORT" => $arValuesProp["SORT"],
					);
				}
			}
			$arResult["PROCCESS_PROPS"][$arProp["ID"]] = $arProp;
		}
}

$this->__component->SetResultCacheKeys(array("PROCCESS_PROPS"));