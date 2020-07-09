<?
//<title>Westpower Yandex Market</title>
/** @global CUser $USER */
/** @global CMain $APPLICATION */
	
	set_time_limit(0);
	CModule::IncludeModule("westpower.yandexmarket");
	
	if(!isset($PROFILE_ID) && isset($profile_id) && $profile_id > 0){
		$PROFILE_ID = $profile_id;
	}

	$Wymr = new WestpowerYandexMarketRun();
	$Wymr->SetParams($PROFILE_ID,$arSetupVars)->Init();
	
	if(!empty($Wymr->Errors)){
		$strExportErrorMessage = implode(", ",$Wymr->Errors);	
	}
?>