<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die(); ?>
<?
global $APPLICATION;
$IBLOCK_ID = 19;
$ELEMENT_ID = $_REQUEST['ID'];

//get meta
$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($IBLOCK_ID, $ELEMENT_ID);
$arElMetaProp = $ipropValues->getValues();
if(!empty($arElMetaProp['ELEMENT_META_DESCRIPTION']))
	$APPLICATION->SetPageProperty('description', $arElMetaProp['ELEMENT_META_DESCRIPTION']);
if(!empty($arElMetaProp['ELEMENT_META_KEYWORDS']))
	$APPLICATION->SetPageProperty('keywords', $arElMetaProp['ELEMENT_META_KEYWORDS']);
if(!empty($arElMetaProp['ELEMENT_META_TITLE']))
	$APPLICATION->SetPageProperty('title', $arElMetaProp['ELEMENT_META_TITLE']);

$CurState  = $APPLICATION->GetPageProperty("show_apartaments","N");

if ($CurState == "N")
{
	?>
	<script>
		$(".newbuilding-apartamets").hide();
	</script>
	<?
}

?>