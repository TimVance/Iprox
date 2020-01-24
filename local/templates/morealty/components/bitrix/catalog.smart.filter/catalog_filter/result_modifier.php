<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();




if ($arParams["ADDITION_FIELDS"])
{
	$propsToLoad = array();
	$arLoadedProps = array();
	
	foreach ($arParams["ADDITION_FIELDS"] as $arAdditionField)
	{
		if ($arAdditionField["FIELD_TYPE"] == "P")
		{
			if ($arAdditionField["TYPE"] == "N")
			{
				if ($arAdditionField["MIN"])
				{
					$propsToLoad[] = $arAdditionField["MIN"];
				}
				if ($arAdditionField["MAX"])
				{
					$propsToLoad[] = $arAdditionField["MAX"];
				}
			}
		}
	}
	

	if ($propsToLoad && count($propsToLoad) > 0)
	{
		foreach ($propsToLoad as $PropCode)
		{
			if (!$PropCode)
				continue;
			$propsRes = CIBlockProperty::GetList(array(), array("ACTIVE"=>"Y", "IBLOCK_ID" => $arParams["IBLOCK_ID"], "CODE" => $PropCode));
			if ($arProp = $propsRes->Fetch())
			{
				$arLoadedProps[$arProp["CODE"]] = $arProp;
			}
		}
		
	}
	
	
	
	foreach ($arParams["ADDITION_FIELDS"] as $arAdditionField)
	{
		if ($arAdditionField["FIELD_TYPE"] == "P")
		{
			if ($arAdditionField["TYPE"] == "N" && $arAdditionField["MIN"] && $arAdditionField["MAX"])
			{
				$OptionProps = array();
				if ($arLoadedProps[$arAdditionField["MIN"]])
				{
					$OptionProps["MIN"] = $arLoadedProps[$arAdditionField["MIN"]];
				}
				if ($arLoadedProps[$arAdditionField["MAX"]])
				{
					$OptionProps["MAX"] = $arLoadedProps[$arAdditionField["MAX"]];
				}
				$arValues = array();
				foreach ($OptionProps as $type => $OptionCase)
				{
					$id = $arParams["FILTER_NAME"]."_".$OptionCase["ID"];
					$arValues[$type] = array(
							"CONTROL_ID" => $arParams["FILTER_NAME"]."_".$OptionProps[$type]["ID"],
							"CONTROL_NAME" => $arParams["FILTER_NAME"]."_".$OptionProps[$type]["ID"],
							"VALUE" => 0
					);
					if (isset($_REQUEST[$arParams["FILTER_NAME"]."_".$OptionProps[$type]["ID"]]) && $_REQUEST["del_filter"] != "Y")
					{
						$arValues[$type]["HTML_VALUE"] = $_REQUEST[$arParams["FILTER_NAME"]."_".$OptionProps[$type]["ID"]];
					}
					$resElements = CIBlockElement::GetList(
							array("PROPERTY_".$OptionCase['ID'] => ($type == "MIN")? "asc" : "desc"),
							array(
									"IBLOCK_ID" => $arParams["IBLOCK_ID"],
									"ACTIVE" => "Y"
							),
							array("PROPERTY_".$OptionCase['ID']),
							array(
									"nTopCount" => 1
							)
					);
					while ($arDbCase = $resElements->Fetch())
					{
						$arValues[$type]["VALUE"] = intval($arDbCase["PROPERTY_".$OptionProps[$type]["ID"]."_VALUE"]);
					}
				}
				/*$arValues = array(
						"MIN" => array(
								"CONTROL_ID" => "arrFilter_".$OptionProps["MIN"]["ID"],
								"CONTROL_NAME" => "arrFilter_".$OptionProps["MIN"]["ID"],
								"VALUE" =>
						)
				)*/
				
				$arResult["ITEMS"][$arAdditionField["MIN"]."_".$arAdditionField["MAX"]] = array(
						"ID" => $arAdditionField["MIN"]."_".$arAdditionField["MAX"],
						"IBLOCK_ID" => $arParams["IBLOCK_ID"],
						"CODE" => $arAdditionField["MIN"]."_".$arAdditionField["MAX"],
						"SORT" => $arAdditionField["SORT"],
						"NAME" => $arAdditionField["NAME"],
						"FIELD_CLASS" => $arAdditionField["FIELD_CLASS"],
						"POSTFIX" => $arAdditionField["POSTFIX"],
						"PROPERTY_TYPE" => "N",
						"DISPLAY_TYPE" => "B",
						"PROPERTY" => $OptionProps,
						"VALUES" => $arValues,
						"CUSTOM_OPTION" => "Y"
				);
			}
		}
		else if ($arAdditionField["FIELD_TYPE"] == "F")
		{
			if ($arAdditionField["TYPE"] == "S" && $arAdditionField["FIELD_CODE"])
			{
				$CurrentValue = "";
				$fieldName = $arParams["FILTER_NAME"]."_".$arAdditionField["FIELD_CODE"];
				if (isset($_REQUEST[$fieldName]) && trim($_REQUEST[$fieldName]) != "" && $_REQUEST["del_filter"] != "Y")
				{
					$CurrentValue = trim($_REQUEST[$fieldName]);
				}
				$arResult["ITEMS"][$arAdditionField["FIELD_CODE"]] = array(
						"ID" => $arAdditionField["FIELD_CODE"],
						"IBLOCK_ID" => $arParams["IBLOCK_ID"],
						"CODE" => $arAdditionField["FIELD_CODE"],
						"SORT" => $arAdditionField["SORT"],
						"NAME" => $arAdditionField["NAME"],
						"FIELD_NAME" => $fieldName,
						"FIELD_ID" => $fieldName,
						"HTML_VALUE" => $CurrentValue,
						"DISPLAY_TYPE" => "CS",
						"PROPERTY_TYPE" => "FS",
						"CUSTOM_OPTION" => "Y"
				);
			}
		}
	}
	foreach ($arResult["ITEMS"] as $key => $arItem)
	{
		if (!$arItem["SORT"] && !$arItem["PRICE"] && $arItem["ID"])
		{
			$resProp = CIBlockProperty::GetByID($arItem["ID"]);
			if ($arPropInfo = $resProp->Fetch())
			{
				if ($arPropInfo["SORT"])
				{
					$arResult["ITEMS"][$key]["SORT"] = $arPropInfo["SORT"];
				}
			}
		}
	}
	usort($arResult["ITEMS"], function($a, $b)
	{
		return $b["SORT"] < $a["SORT"];
	});
}


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

$this->__component->SetResultCacheKeys(array("PROCCESS_PROPS", "ITEMS"));


$arParams["FILTER_VIEW_MODE"] = (isset($arParams["FILTER_VIEW_MODE"]) && toUpper($arParams["FILTER_VIEW_MODE"]) == "HORIZONTAL") ? "HORIZONTAL" : "VERTICAL";
$arParams["POPUP_POSITION"] = (isset($arParams["POPUP_POSITION"]) && in_array($arParams["POPUP_POSITION"], array("left", "right"))) ? $arParams["POPUP_POSITION"] : "left";
