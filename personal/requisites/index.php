<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Реквизиты");
$displayRequisites = $USER->IsAdmin() || in_array(7, $USER->GetUserGroupArray());
?>

<?
if($displayRequisites) {
	$APPLICATION->IncludeComponent("bitrix:main.profile","requisites",Array(
			"USER_PROPERTY_NAME" => "",
			"SET_TITLE" => "N",
			"AJAX_MODE" => "N",
			"USER_PROPERTY" => Array("UF_FULL_NAME",
			                         "UF_ABBREVIATION",
			                         "UF_LEGAL_ADDRESS",
			                         "UF_OGRN",
			                         "UF_INN",
			                         "UF_KPP",
			                         "UF_DIRECTOR_FULL_NAM",
			                         "UF_CHECKING_ACCOUNT",
			                         "UF_BANK_NAME",
			                         "UF_COR_ACCOUNT",
			                         "UF_BIC"),
			"SEND_INFO" => "Y",
			"CHECK_RIGHTS" => "N",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"AJAX_OPTION_HISTORY" => "N"
		)
	);
} else {
	LocalRedirect('/personal/myobjects/');
}

?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>