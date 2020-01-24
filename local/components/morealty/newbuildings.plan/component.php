<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
	die(); ?>
<?
CModule::IncludeModule('iblock');

$arParams["MOTHER_ID"]  = filter_var($arParams["MOTHER_ID"],FILTER_VALIDATE_INT,
		array(
			"options" => array(
						"min_range" => 1,
					)
			));
if (!$arParams["MOTHER_ID"])
{
	ShowError("Неверно указана новостройка");
	return false;
}

if ($this->StartResultCache(false)) {
	$arResult = \MorealtySale\NewbuildPlan::getInstance($arParams["MOTHER_ID"])->getStructure();

	if (count($arResult["SCHEME"]) > 0)
	{
		$this->SetResultCacheKeys(array(
				"NEWBUILDING","SCHEME","ITEMS"
		));
	}
	if ($arParams["JUST_RESULT"] !== "Y")
	{
		$this->IncludeComponentTemplate();
	}

}
return $arResult;
?>