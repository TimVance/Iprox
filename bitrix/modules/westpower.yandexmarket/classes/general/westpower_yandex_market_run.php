<?php
IncludeModuleLangFile(__FILE__);
class WestpowerYandexMarketRun {
	
	public $Errors = array();
	
	private $CDate = 0;
	private $Params = array();
	private $AParams = array();
	private	$ProfileId = 0;
	
	private $EData = array();
	private $LData = array();
	
	private $OFile = null;
	
	private $Dir = "/bitrix/catalog_export/";
	
	private $FPath = "";
	private $FTPath = "";
	private $FLPath = "";
	
	function __construct() {
		CModule::IncludeModule("iblock");
		CModule::IncludeModule("catalog");
		$this->CDate = strtotime(date("d.m.Y"));
		$this->Dir = $_SERVER["DOCUMENT_ROOT"].$this->Dir;
	}
	
	public function SetParams($ProfileId = 0, $Params = array()){
		$this->Params = $Params;
		$this->ProfileId = $ProfileId;
		
		$this->FPath = $this->Dir.$this->Params["BASIC_SETTINGS_FILE_NAME"];
		$this->FTPath = $this->FPath.".tmp";
		$this->FLPath = $this->FPath.".log";
		
		$this->AParams["URL"] = "http".($Params["BASIC_SETTINGS_IS_HTTPS"] == "Y" ? "s" : "")."://".$Params["BASIC_SETTINGS_SERVER_NAME"];
		
		return $this;
	}
	
	private function GetLog(){
		$Flag = false;
		$this->LData = json_decode(file_get_contents($this->FLPath),true);		
		if(empty($this->LData)){
			$this->LData = array(
				"FILTER" => array(),
				"IS_OFFERS" => false,
				"OFFERS" => array(),
				"PRICES" => array(),
				"NAV" => array("nPageSize" => $this->Params["BASIC_SETTINGS_MAX_ELM"],"iNumPage" => 1),
				"COUNT_PAGE" => 0
			);
			$Query = CCatalog::GetList(array(),array("ID" => $this->Params["CATALOG_IBLOCK_ID"]));
			if($Answer = $Query->Fetch()) {
				if(isset($Answer["OFFERS_IBLOCK_ID"]) && $Answer["OFFERS_IBLOCK_ID"] > 0){
					$this->LData["IS_OFFERS"] = true;
					$this->LData["OFFERS"]["IBLOCK_ID"] = $Answer["OFFERS_IBLOCK_ID"];
					$this->LData["OFFERS"]["FILTER"] = array("ACTIVE" => "Y","ACTIVE_DATE"=>"Y","IBLOCK_ID" => $Answer["OFFERS_IBLOCK_ID"]);
				}
			}
			$this->LData["FILTER"] = array("ACTIVE" => "Y","ACTIVE_DATE"=>"Y", "IBLOCK_ID" => $this->Params["CATALOG_IBLOCK_ID"]);
			$this->LData["PRICES"] = CIBlockPriceTools::GetCatalogPrices($this->Params["CATALOG_IBLOCK_ID"], $this->Params["PRICES_PRICE_TYPE"]);
			foreach($this->LData["PRICES"] as $key => $value){
				$this->LData["FILTER"]["CATALOG_SHOP_QUANTITY_".$value["ID"]] = 1;
				if(isset($this->LData["OFFERS"]["FILTER"])){
					$this->LData["OFFERS"]["FILTER"]["CATALOG_SHOP_QUANTITY_".$value["ID"]] = 1; 
				}
			}
		}
		
		return $this;
	}
	
	private function SetLog(){
		file_put_contents($this->FLPath,json_encode($this->LData));
		return $this;
	}
	
	public function Init(){
		$Mess = "";
		if($this->Check()){
			$Mess = GetMessage("WESTPOWER_C_G_YMCR_0");
			$Mess = str_replace("#START#",$this->EData["STEP"],$Mess);
			
			if($this->EData["STEP"] == 3){
				$this->OpenFile()->WriteFile("</offers>\n")->WriteFile("</shop>\n")->WriteFile("</yml_catalog>\n")->CloseFile();
				if($this->OpenFile("r")){
					if($NFile = fopen($this->FPath, "w")){
						while (!feof($this->OFile)) {
						    $TmpData = fread($this->OFile, 8192);
						    fwrite($NFile, mb_convert_encoding($TmpData,"windows-1251",LANG_CHARSET));
						}
						fclose($NFile);
					}
				}
				$this->EData["FINAL"] = "Y";
				$this->UpdateEData();
				$Mess = "";
			} elseif($this->EData["STEP"] == 2){
				$this->OpenFile()->GetLog()->WriteOffersFile()->SetLog()->CloseFile();
				if($this->LData["NAV"]["iNumPage"] > $this->LData["COUNT_PAGE"]){
					$this->EData["STEP"] += 1;
					$this->UpdateEData();
				}
				$Mess .= GetMessage("WESTPOWER_C_G_YMCR_1").($this->LData["NAV"]["iNumPage"]-1).GetMessage("WESTPOWER_C_G_YMCR_2").$this->LData["COUNT_PAGE"].".";
			} elseif($this->EData["STEP"] == 1){
				$this->OpenFile()->WriteCategoriesFile()->CloseFile();
				$this->EData["STEP"] += 1;
				$this->UpdateEData();
				$Mess .= GetMessage("WESTPOWER_C_G_YMCR_3");
			} else {
				$this->OpenFile("w")->WriteHeaderFile()->CloseFile();
				$this->EData["STEP"] += 1;
				$this->UpdateEData();
				$Mess .= GetMessage("WESTPOWER_C_G_YMCR_4");
			}
			
			if($Mess != ""){
				$this->Errors[] = str_replace("#END#",$this->EData["MAX_STEP"],$Mess);
			}
		}
	}
	
	private function WriteOffersFile(){
		$Query = CIBlockElement::GetList(array(),$this->LData["FILTER"],false,$this->LData["NAV"]);
		if($this->LData["COUNT_PAGE"] == 0){
			$this->LData["COUNT_PAGE"] = ceil($Query->SelectedRowsCount()/$this->Params["BASIC_SETTINGS_MAX_ELM"]);
			$this->WriteFile("<offers>\n");
		}
		
		while($Answer = $Query->GetNextElement()){
			$Flag = true;
			$Item = $Answer->GetFields();
			if($this->LData["IS_OFFERS"]){
				$Offers = array();
				$Offers = CCatalogSKU::getOffersList(array($Item["ID"]),$Item["IBLOCK_ID"]);
				if(!empty($Offers)){
					$Item["PROPERTIES"] = $Answer->GetProperties();
					$this->LData["OFFERS"]["FILTER"]["ID"] = array_keys($Offers[$Item["ID"]]);
					$OQuery = CIBlockElement::GetList(array(),$this->LData["OFFERS"]["FILTER"]);
					while($OAnswer = $OQuery->GetNextElement()){
						$Item["OFFER"] = array();
						$Item["OFFER"] = $OAnswer->GetFields();
						$Item["OFFER"]["PROPERTIES"] = $OAnswer->GetProperties();
						$Item["OFFER"]["PRICES"] = CIBlockPriceTools::GetItemPrices($Item["OFFER"]["IBLOCK_ID"], $this->LData["PRICES"], $Item["OFFER"]);
						$Item["OFFER"]["MIN_PRICE"] = !empty($Item["OFFER"]['PRICES']) ? CIBlockPriceTools::getMinPriceFromList($Item["OFFER"]["PRICES"]) : array();
						$this->WriteOfferFile($Item,true);
					}
					unset($this->LData["OFFERS"]["FILTER"]["ID"]);
					unset($Offers);
				}
			} 
			
			if($Flag){
				$Item["PROPERTIES"] = $Answer->GetProperties();
				$Item["PRICES"] = CIBlockPriceTools::GetItemPrices($Item["IBLOCK_ID"], $this->LData["PRICES"], $Item);
				$Item["MIN_PRICE"] = !empty($Item['PRICES']) ? CIBlockPriceTools::getMinPriceFromList($Item["PRICES"]) : array();
				$this->WriteOfferFile($Item);
			}
		}
		
		$this->LData["NAV"]["iNumPage"] += 1;
		return $this;
	}
	
	private function WriteOfferFile($Item = array(),$IsOffer = false){
		$Offer = "";
		$IsModel = $IsVendor = $IsVendorCode = false;
		$LProps = !empty($this->Params["PROPERTIES_PARAM"]) ? $this->Params["PROPERTIES_PARAM"] : array();
		if($this->Params["PROPERTIES_VENDOR"] != ""){$IsVendor = true;$LProps[] = $this->Params["PROPERTIES_VENDOR"];}
		if($this->Params["PROPERTIES_VENDOR_CODE"] != ""){$IsVendorCode = true;$LProps[] = $this->Params["PROPERTIES_VENDOR_CODE"];}
		if($this->Params["PROPERTIES_MODEL"] != ""){$IsModel = true;$LProps[] = $this->Params["PROPERTIES_MODEL"];}
		
		if(!empty($Item)){
			if($IsOffer){
				$Item["ID"] = $Item["OFFER"]["ID"];
				$Item["CATALOG_QUANTITY"] = $Item["OFFER"]["CATALOG_QUANTITY"];
				$Item["DETAIL_PAGE_URL"] = $Item["OFFER"]["DETAIL_PAGE_URL"];
				$Item["MIN_PRICE"] = $Item["OFFER"]["MIN_PRICE"];
				
				if($this->Params["CATALOG_COMBINE_PRODUCT_NAMES_AND_OFFERS"] == "Y"){
					$Item["NAME"] = $Item["NAME"]." (".$Item["OFFER"]["NAME"].")";
				} else {
					$Item["NAME"] = $Item["OFFER"]["NAME"];
				}
				
				if($Item["OFFER"]["DETAIL_TEXT"] != ""){
					$Item["DETAIL_TEXT"] = $Item["OFFER"]["DETAIL_TEXT"];
				} elseif($Item["OFFER"]["PREVIEW_TEXT"] != ""){
					$Item["PREVIEW_TEXT"] = $Item["OFFER"]["PREVIEW_TEXT"];
				}
				
				if($Item["OFFER"]["DETAIL_PICTURE"] > 0){
					$Item["DETAIL_PICTURE"] = $Item["OFFER"]["DETAIL_PICTURE"];
				} elseif($Item["OFFER"]["PREVIEW_PICTURE"] > 0){
					$Item["PREVIEW_PICTURE"] = $Item["OFFER"]["PREVIEW_PICTURE"];
				}
				
				foreach($Item["OFFER"]["PROPERTIES"] as $key =>$value){if(!empty($value["VALUE"])){$Item["PROPERTIES"][$key] = $value;}}
				unset($Item["OFFER"]);
			}
			
			if(!empty($this->Params["INVENTORY_CONTROL_WAREHOUSES"])){$Item["CATALOG_QUANTITY"] = $this->GetAmount($Item["ID"]);} 
			if($this->Params["INVENTORY_CONTROL_ACTUAL_BALANCES"] == "Y" && $Item["CATALOG_QUANTITY"] < 0){return false;}
			
			$Offer .= "<offer id=\"".$Item["ID"]."\" available=\"".($Item["CATALOG_QUANTITY"] > 0 ? "true" : "false")."\" >\n";
			$Offer .= "<url>".$this->AParams["URL"].$Item["DETAIL_PAGE_URL"]."</url>\n";
			
			if(!empty($Item["MIN_PRICE"])){
				if($Item["MIN_PRICE"]["DISCOUNT_DIFF"] > 0){
					$Offer .= "<price>".$Item["MIN_PRICE"]["DISCOUNT_VALUE"]."</price>\n";
					$Offer .= "<oldprice>".$Item["MIN_PRICE"]["VALUE"]."</oldprice>\n";
				} else {
					$Offer .= "<price>".$Item["MIN_PRICE"]["VALUE"]."</price>\n";
				}
				$Offer .= "<currencyId>".$Item["MIN_PRICE"]["CURRENCY"]."</currencyId>\n";
			}
			
			$Offer .= "<categoryId>".$Item["IBLOCK_SECTION_ID"]."</categoryId>\n";
			$Offer .= "<name>".$this->YandexText($Item["NAME"], true)."</name>\n";
			
			if($Item["DETAIL_PICTURE"] > 0){
				$Offer .= "<picture>".$this->AParams["URL"].CFile::GetPath($Item["DETAIL_PICTURE"])."</picture>\n";
			} elseif ($Item["PREVIEW_PICTURE"] > 0){
				$Offer .= "<picture>".$this->AParams["URL"].CFile::GetPath($Item["PREVIEW_PICTURE"])."</picture>\n";
			}
			
			if($Item["DETAIL_TEXT"] != ""){
				$Offer .= "<description><![CDATA[ ".$this->YandexText($Item["DETAIL_TEXT"])."  ]]></description>\n";
			} else if($Item["PREVIEW_TEXT"] != ""){
				$Offer .= "<description><![CDATA[ ".$this->YandexText($Item["PREVIEW_TEXT"])." ]]></description>\n";
			}
			
			foreach($LProps as $Prop){
				$Data = array();
				$TagName = "";
				$Attr = "";
				if(isset($Item["PROPERTIES"][$Prop]) && !empty($Item["PROPERTIES"][$Prop]["VALUE"])){
					
					if($IsVendor && $Prop == $this->Params["PROPERTIES_VENDOR"]){
						$TagName = "vendor";
						$IsVendor = false;
					} elseif($IsVendorCode && $Prop == $this->Params["PROPERTIES_VENDOR_CODE"]){
						$TagName = "vendorCode";
						$IsVendorCode = false;
					} elseif($IsModel && $Prop == $this->Params["PROPERTIES_MODEL"]){
						$TagName = "model";
						$IsModel = false;
					} else {
						$TagName = "param";
						$Attr = " name=\"".$this->YandexText($Item["PROPERTIES"][$Prop]["NAME"],true)."\"";
					}
					
					if(is_array($Item["PROPERTIES"][$Prop]["VALUE"])){
						$Data = $Item["PROPERTIES"][$Prop]["VALUE"];
					} else {
						$Data[] = $Item["PROPERTIES"][$Prop]["VALUE"];
					}
					
					foreach($Data as $key => $value){
						if($value == ""){continue;}
						$Offer .= "<".$TagName.$Attr.">".$this->YandexText($value,true)."</".$TagName.">\n";
					}
				}
			}
			$Offer .= "</offer>\n";
			$this->WriteFile($Offer);
		}
	}
	
	private function GetAmount($Id = 0){
		$Result = 0;
		$Filter = array();
		if($Id > 0){
			$Filter["PRODUCT_ID"] = $Id;
			$Filter["STORE_ID"] = $this->Params["INVENTORY_CONTROL_WAREHOUSES"];
			$Query = CCatalogStoreProduct::GetList(array(),$Filter,false,false,array("ID","AMOUNT"));
			while($Answer = $Query->Fetch()){
				$Result += $Answer["AMOUNT"]*1;
			}
		}
		return $Result;
	}
	
	private function WriteCategoriesFile(){
		$Filter = array();
		$Text = "";
		$Filter = array("ACTIVE" => "Y","IBLOCK_ID" => $this->Params["CATALOG_IBLOCK_ID"]);
		if(!empty($this->Params["CATALOG_IBLOCK_SECTION_ID"])){
			$Filter["IBLOCK_SECTION_ID"] = $this->Params["CATALOG_IBLOCK_SECTION_ID"];
		}
		$this->WriteFile("<categories>\n");
		$Query = CIBlockSection::GetList(array("LEFT_MARGIN" => "ASC"),$Filter,false,array("ID", "IBLOCK_SECTION_ID", "NAME"));
		while ($Answer = $Query->Fetch()){
			$Text = "<category id=\"".$Answer['ID']."\"".($Answer['IBLOCK_SECTION_ID'] > 0 ? " parentId=\"".$Answer['IBLOCK_SECTION_ID']."\"" : "").">";
			$Text .= $this->YandexText($Answer["NAME"], true);
			$Text .= "</category>\n";
			$this->WriteFile($Text);
		}
		$this->WriteFile("</categories>\n");
		return $this;
	}
	
	private function WriteHeaderFile(){
		$Text = '<?if (!isset($_GET["referer1"]) || strlen($_GET["referer1"])<=0) $_GET["referer1"] = "yandext"?>';
		$Text .= '<? $strReferer1 = htmlspecialchars($_GET["referer1"]); ?>';
		$Text .= '<?if (!isset($_GET["referer2"]) || strlen($_GET["referer2"]) <= 0) $_GET["referer2"] = "";?>';
		$Text .= '<? $strReferer2 = htmlspecialchars($_GET["referer2"]); ?>';
		$Text .= '<? header("Content-Type: text/xml; charset=windows-1251");?>';
		$Text .= '<? echo "<"."?xml version=\"1.0\" encoding=\"windows-1251\"?".">"?>';
		$Text .= "\n<!DOCTYPE yml_catalog SYSTEM \"shops.dtd\">\n";
		$Text .= "<yml_catalog date=\"".date("Y-m-d H:i")."\">\n";
		$Text .= "<shop>\n";
		if($this->Params["BASIC_SETTINGS_NAME_SHOP"] != ""){
			$Text .= "<name>".$this->YandexText($this->Params["BASIC_SETTINGS_NAME_SHOP"],true)."</name>\n";
		}
		if($this->Params["BASIC_SETTINGS_NAME_COMPANY"] != ""){
			$Text .= "<company>".$this->YandexText($this->Params["BASIC_SETTINGS_NAME_COMPANY"],true)."</company>\n";
		}
		$Text .= "<url>".$this->AParams["URL"]."</url>\n";
		$Text .= "<currencies>\n";
		$Text .= "<currency id=\"".$this->Params["PRICES_CURRENCY"]."\" rate=\"1\"/>\n";
		$Text .= "</currencies>\n";
		$this->WriteFile($Text);
		return $this;
	}
	
	private function YandexText($Text = "", $Hsc = false, $Quot = false){
		if($Hsc){
			$Text = htmlspecialcharsbx($Text);
			if ($Quot){
				$Text = str_replace('&quot;', '"', $Text);
			}
		}
		$Text = preg_replace('/[\x01-\x08\x0B-\x0C\x0E-\x1F]/', "", $Text);
		$Text = str_replace("'", "&apos;", $Text);
		return $Text;
	}
	
	private function WriteFile($Text = ""){
		if($this->OFile){
			fwrite($this->OFile,$Text);
		}
		return $this;
	}
	
	private function CloseFile(){
		if($this->OFile){
			fclose($this->OFile);
		}
		return $this;
	}
	
	private function OpenFile($Mode = "a"){
		if(!$this->OFile = fopen($this->FTPath, $Mode)){
			$this->Errors[] = GetMessage("WESTPOWER_C_G_YMCR_5").$this->FPath;
		}
		return $this;
	}
	
	private function Check(){
		$Result = false;
		$IdDefault = false;
		if($this->ProfileId < 0){
			$this->Errors[] = GetMessage("WESTPOWER_C_G_YMCR_6");
		} elseif($this->Params["BASIC_SETTINGS_MAX_ELM"] < 0){
			$this->Errors[] = GetMessage("WESTPOWER_C_G_YMCR_7");
		} elseif($this->Params["CATALOG_IBLOCK_ID"] < 0){
			$this->Errors[] = GetMessage("WESTPOWER_C_G_YMCR_8");
		} else {
			$this->EData = $this->GetEData();
			if(empty($this->EData)){
				$this->SetLog();
				$this->Errors[] = $this->NewEData() ? GetMessage("WESTPOWER_C_G_YMCR_9") : GetMessage("WESTPOWER_C_G_YMCR_10");
			} else {
				
				$CHash = sha1(serialize($this->Params));
				$DEHash = sha1($this->EData["PARAMS"]);
				
				if($CHash == $DEHash){
					if($this->EData["FINAL"] == "Y"){
						if($this->Params["BASIC_SETTINGS_UNLOAD_ONCE_DAY"] == "Y" && $this->CDate == $this->EData["TIMESTAMP_UNIX"]){
							$this->Errors[] = GetMessage("WESTPOWER_C_G_YMCR_11");
						} else {
							$IdDefault = true;
						}
					} else {
						$Result = true;
					}	
				} else {
					$IdDefault = true;
				}
				
				if($IdDefault){
					$this->SetLog();
					$this->Errors[] = $this->UpdateEData(true) ? GetMessage("WESTPOWER_C_G_YMCR_12") : GetMessage("WESTPOWER_C_G_YMCR_13");
				}
			}
		}
		return $Result;
	}
	
	private function GetEData(){
		global $DB;
		$Result = array();
		$Query = $DB->Query("SELECT * FROM b_westpower_yandex_market WHERE PROFILE_ID=".$DB->ForSQL($this->ProfileId)." LIMIT 1;");
		if($Answer = $Query->Fetch()){
			$Result = $Answer;
		}
		return $Result;
	}
	
	private function UpdateEData($IsDefault = false){
		global $DB;
		
		if($IsDefault){
			$this->EData["TIMESTAMP_UNIX"] = $this->CDate;
			$this->EData["STEP"] = 0;
			$this->EData["MAX_STEP"] = 3;
			$this->EData["PARAMS"] = serialize($this->Params);
			$this->EData["FINAL"] = "N";
		}
		
		return $DB->Query("
			UPDATE
				b_westpower_yandex_market
			SET 
				TIMESTAMP_UNIX = '".$this->EData["TIMESTAMP_UNIX"]."',
				STEP = ".$this->EData["STEP"].",
				MAX_STEP = ".$this->EData["MAX_STEP"].",
				PARAMS = '".$this->EData["PARAMS"]."',
				FINAL = '".$this->EData["FINAL"]."'
			WHERE 
				ID = ".$this->EData["ID"]."
		;");
	}
	
	private function NewEData(){
		global $DB;
		return $DB->Query("INSERT INTO 
			b_westpower_yandex_market 
				(TIMESTAMP_UNIX,PROFILE_ID,STEP,MAX_STEP,PARAMS,FINAL) 
			VALUES
				(
					'".$DB->ForSQL($this->CDate)."',
					'".$DB->ForSQL($this->ProfileId)."',
					'0',
					'3',
					'".$DB->ForSQL(serialize($this->Params))."',
					'N'
				) 
		;");
	}
}
?>