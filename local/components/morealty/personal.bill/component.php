<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/* @var $this CBitrixComponent */

use \Wexpert\BitrixUtils\IblockUtils as weIU;

$arResult['arUser'] = CUser::GetByID( (int)$USER->GetID() )->GetNext( (int)$USER->GetID() );
$arResult['BILL_FIELDS_FILLED'] = \UserAccount::isBillFiledsFilled( (int)$USER->GetID() );

$doc = weIU::selectElementsByFilterArray(array(), array(
	'ID' => (int)$arParams['DOC_ID']
), false, false, array('ID', 'IBLOCK_ID', 'IBLOCK_SECTION_ID', 'NAME', 'TIMESTAMP_X'), true, false);
$arResult['doc'] = $doc = $doc[0];

if ($doc['IBLOCK_SECTION_ID'] != $GLOBALS['USER_ACCOUNT']['ID'] || !$doc['ID'] || $doc['PROPERTIES']['TYPE']['VALUE'] != 391) {
	require $_SERVER['DOCUMENT_ROOT'] . "/404_inc.php";
}  else {
	$this->includeComponentTemplate();
}

?>