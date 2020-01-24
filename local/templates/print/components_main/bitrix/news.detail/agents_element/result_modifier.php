<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

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

ob_start();

?>

<?

$arResult["USERS"] = $APPLICATION->IncludeComponent(
	"morealty:realtors.list",
	"agent_page",
	Array(
		"ITEMS_PER_PAGE" => false,
		"FILTER" => array("UF_AGENT_NAME" => $arResult["ID"])
	),
	$this->__component
);?>
<?
$arResult["USERS_HTML"] = ob_get_clean();
if ($arResult["USERS"] && count($arResult["USERS"]) > 0)
{
	$Filter = array("PROPERTY_REALTOR" => $arResult["USERS"], "ACTIVE" => "Y", "!PROPERTY_IS_ACCEPTED"=>false);
	
	$rs = CIBlockElement::GetList(array(), $Filter, array("ACTIVE"));
	if ($arGroup = $rs->Fetch())
	{
		$arResult["TOTAL_COUNT"] = $arGroup["CNT"];
	}
}

$this->__component->setResultCacheKeys(array("USERS", "USERS_HTML", "TOTAL_COUNT"));
