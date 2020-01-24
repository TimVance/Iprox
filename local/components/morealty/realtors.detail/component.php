<? if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die(); ?>

<?
$USER_ID = $arParams['ID'];


if ($this->StartResultCache(false)) {
	$rsUser = CUser::GetByID($USER_ID);
	$arUser = $rsUser->Fetch();

	if ($arUser['ID']) {
		$arResult = $arUser;

		$arResult['FULL_NAME'] = $arResult['NAME'] . ' ' . $arResult['SECOND_NAME'] . ' ' . $arResult['LAST_NAME'];
		$IBLOCK_ID = 3;

		CModule::IncludeModule("iblock");
		if ($arResult["UF_AGENT_NAME"])
		{
			$Res = CIBlockElement::GetList(array(),array("ID"=>$arResult["UF_AGENT_NAME"],"ACTIVE"=>"Y","GLOBAL_ACTIVE"=>"Y"));
			//$Res = CIBlockElement::GetByID($arResult["UF_AGENT_NAME"]);
			if ($objItem = $Res->GetNextElement()) {
				$ar_props = $objItem->GetFields();
				$ar_props["PROPERTIES"] = $objItem->GetProperties();
				$arResult["UF_AGENT_NAME"] = $ar_props["NAME"];
				$arResult["UF_AGENT_ID"] = $ar_props["ID"];
				$arResult["UF_AGENT_PHOTO_ID"] = $ar_props["PREVIEW_PICTURE"];
				$arResult["AGENTS_INFO"] = $ar_props;
			}
			$arResult["UF_AGENT_PHOTO"] = 'http://' . $_SERVER['HTTP_HOST'] . '/' . CFile::GetPath($arResult["UF_AGENT_PHOTO_ID"]);
		}

		if(!empty($arResult["PERSONAL_PHOTO"])) {
			$arResult["PERSONAL_PHOTO_ID"] = $arResult["PERSONAL_PHOTO"];
			$arResult["PERSONAL_PHOTO"] = 'http://' . $_SERVER['HTTP_HOST'] . '/' . CFile::GetPath($arResult["PERSONAL_PHOTO"]);
		}
		$db_props = CIBlockElement::GetProperty($IBLOCK_ID, $arResult["UF_AGENT_ID"], "sort", "asc", array('CODE' => 'SITE_ADDRESS'));
		while($arProps= $db_props->Fetch()) {
			$arResult['UF_SITE_ADDRESS'] = $arProps['VALUE'];
		}

		$this->IncludeComponentTemplate();
	} else {
		$this->AbortResultCache();
		@include_once($_SERVER["DOCUMENT_ROOT"] . "/404_inc.php");
	}
}
?>