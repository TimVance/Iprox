<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>

<? 
CModule::IncludeModule('iblock');

if (count($arParams["SORT_FIELDS"]) <= 0) return array();

if (trim($_REQUEST["SORT_DIRECTION"]) != "")
{
	if (strtolower(trim($_REQUEST["SORT_DIRECTION"])) != "asc" && strtolower(trim($_REQUEST["SORT_DIRECTION"])) != "desc")
	{
		$_REQUEST["SORT_DIRECTION"] = "ASC";
	}
}

$arResult["ITEMS"] = array();
$arResult["CHECKED"] = false;
$arResult["return"] = array();
foreach ($arParams["SORT_FIELDS"] as $FieldCode=>$arField)
{
	$arResult["ITEMS"][] = array("CODE"=>$FieldCode,"TEXT"=>$arParams["~SORT_FIELDS"][$FieldCode],"SELECTED"=>($_REQUEST["SORT_BY"] == $FieldCode)? "Y": "N");
	if($_REQUEST["SORT_BY"] == $FieldCode)
	{
		$arResult["CHECKED"] = array("CODE"=>$FieldCode,"TEXT"=>$arParams["~SORT_FIELDS"][$FieldCode],"DIRECTTION"=>($_REQUEST["SORT_DIRECTION"])? $_REQUEST["SORT_DIRECTION"]: "ASC");
		$arResult["return"] = array($FieldCode=>($_REQUEST["SORT_DIRECTION"])? $_REQUEST["SORT_DIRECTION"]: "ASC");
	}
}
$this->IncludeComponentTemplate();
return $arResult["return"];

?>
