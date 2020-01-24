<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
$arAgents = array();
if (\Bitrix\Main\Loader::includeModule("iblock"))
{
	
	$rsAgents = CIBlockElement::GetList(
				array(),
				array(
						"IBLOCK_ID" => 3,
						"ACTIVE" => "Y"
				),
				false,
				false,
				array(
						"IBLOCK_ID", 'NAME', "ACTIVE", "ID", "PREVIEW_PICTURE"
				)
			);
	while ($arAgent = $rsAgents->Fetch())
	{
		$arAgents[] = $arAgent;
	}
}
$arResult["AGENTS"] = $arAgents;
$this->__component->setResultCacheKeys("AGENTS");