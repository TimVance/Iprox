<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
$this->setFrameMode(true);?>

<?
$template = "list";
if (in_array(strtolower($arParams["TEMPLATE_THEME"]), array("tiles", "list", "mini-list")))
{
	$template = strtolower($arParams["TEMPLATE_THEME"]);
}
@include $template.".php";
?>
<?=$arResult["NAV_STRING"]?>