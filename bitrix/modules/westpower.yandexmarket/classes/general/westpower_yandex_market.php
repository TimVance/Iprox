<?php
IncludeModuleLangFile(__FILE__);
class WestpowerYandexMarket {
	
	private $Params = array();
	private $Id = -1;
	private $Step = -1;
	private $MaxStep = -1;
	private $CurentDate;
	private $AdditionalStep = 2;
	private $NavParams = array();
	
	private $OFile;
	
	public $Errors = array();
	
	function __construct() {
		CModule::IncludeModule("iblock");
		$this->CurentDate = strtotime(date("d.m.Y"));
	}
	public function SetParams($Arg0 = array()){
		foreach($Arg0 as $key => $value){
			if($key == "Https"){
				$value = "http".($value == "Y" ? "s" : "")."://";
			}
			$this->Params[$key] = $value;
		}
	}
	public function GetParams(){
		return serialize($this->Params);
	}
	public function Run(){
		$MessagText = GetMessage("WESTPOWER_YMCG_1");
		$this->Check();
		$this->Params["FileName"] = $_SERVER["DOCUMENT_ROOT"].$this->Params["FileName"];
		$this->Params["FileNameTmp"] = $this->Params["FileName"]."tmp";
		if(empty($this->Errors) && $this->GetData()){
			$MessagText = str_replace("#START#",$this->Step,$MessagText);
			if($this->Step == 1){
				if($this->OpenFile("w")){
					$this->SetElements();
					$this->SetNextStep();
					$this->WriteHeaderFile();
					$this->CloseFile();
				}	
			} elseif($this->Step == 2){
				if($this->OpenFile()){
					$this->SetSection();
					$this->SetNextStep();
					$this->CloseFile();
				}
			} else if($this->Step >= 3){
				if($this->OpenFile()){
					$this->SetElements();
					$this->SetNextStep();
					$this->CloseFile();
				}
			}
			$this->Errors[] = str_replace("#END#",$this->MaxStep,$MessagText);
		}
	}
	private function SetSection(){
		$Text = "";
		$this->WriteFile("<categories>\n");
		$Query = CIBlockSection::GetList(
			array("LEFT_MARGIN" => "ASC"),
			array("IBLOCK_ID" => $this->Params["Ib"], "ID" => $this->Params["SectionId"]),
			false,
			array("ID", "IBLOCK_SECTION_ID", "NAME")
		);
		while ($Answer = $Query->Fetch()){
			$Text = "<category id=\"".$Answer['ID']."\"";
			if($Answer['IBLOCK_SECTION_ID'] > 0){
				$Text .= " parentId=\"".$Answer['IBLOCK_SECTION_ID']."\"";
			}
			$Text .= ">".$this->YandexText($Answer["NAME"], true)."</category>\n";
			$this->WriteFile($Text);
		}
		$this->WriteFile("</categories>\n");
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
		$Text .= "<name>".COption::GetOptionString("main", "site_name", "")."</name>\n";
		$Text .= "<company>".COption::GetOptionString("main", "site_name", "")."</company>\n";
		$Text .= "<url>".$this->Params["ServerName"]."</url>\n";
		$Text .= "<platform>1C-Bitrix</platform>\n";
		$Text .= "<currencies>\n";
		$Text .= "<currency id=\"".$this->Params["Currency"]."\" rate=\"1\"/>\n";
		$Text .= "</currencies>\n";
		$this->WriteFile($Text);
	}
	private function YandexText($text, $bHSC = false, $bDblQuote = false){
		global $APPLICATION;

		$bHSC = (true == $bHSC ? true : false);
		$bDblQuote = (true == $bDblQuote ? true: false);

		if ($bHSC)
		{
			$text = htmlspecialcharsbx($text);
			if ($bDblQuote)
				$text = str_replace('&quot;', '"', $text);
		}
		$text = preg_replace('/[\x01-\x08\x0B-\x0C\x0E-\x1F]/', "", $text);
		$text = str_replace("'", "&apos;", $text);
		return $text;
	}
	private function WriteFile($Text = ""){
		fwrite($this->OFile,$Text);
	}
	private function CloseFile(){
		if($this->OFile){
			fclose($this->OFile);
		}
	}
	private function OpenFile($Mode = "a"){
		$Flag = false;
		if($this->OFile = fopen($this->Params["FileNameTmp"], $Mode)){
			$Flag = true;
		} else {
			$this->Errors[] = GetMessage("WESTPOWER_YMCG_2").$this->Params["FileName"];
		}
		return $Flag;
	}
	
	private function SetElements(){
		
		$Page = $this->Step - $this->AdditionalStep;
		$this->NavParams = array("nPageSize" => $this->Params["MaxElm"]);
		
		if($Page >= 1){
			$this->NavParams["iNumPage"] = $Page;
		}
		
		$Query = CIBlockElement::GetList(
			array(),
			array(
				"ACTIVE" => "Y",
				"IBLOCK_ID" => $this->Params["Ib"],
				"SECTION_ID" => $this->Params["SectionId"]
			),
			false,
			$this->NavParams,
			array(
				"ID",
				"IBLOCK_ID",
				"IBLOCK_SECTION_ID",
				"NAME",
				"PREVIEW_PICTURE",
				"PREVIEW_TEXT",
				"DETAIL_PICTURE",
				"DETAIL_PAGE_URL"
			)
		);
		
		if($Page >= 1){
			if($Page == 1){$this->WriteFile("<offers>\n");}
			$this->Params["ServerName"] = $this->Params["Https"].$this->Params["ServerName"];
			$Offers = array("Id" => array(),"Elm" => array(),"List" => array());
			while($Answer = $Query->GetNext()){
				if(CCatalogSKU::IsExistOffers($Answer["ID"],$this->Params["Ib"])){
					$Offers["Id"][] = $Answer["ID"];
					$Offers["Elm"][$Answer["ID"]] = $Answer;
				} else {
					$this->AddProductFromFile($Answer);
				}
			}
			if(!empty($Offers["Id"])){
				$Offers["List"] = CCatalogSKU::getOffersList($Offers["Id"]);
				foreach($Offers["Id"] as $key => $value){
					$ElmUrl = $Offers["Elm"][$value]["DETAIL_PAGE_URL"];
					foreach($Offers["List"][$value] as $keyOff => $valueOff){
						$Offers["Elm"][$value]["ID"] = $valueOff["ID"];
						$Offers["Elm"][$value]["DETAIL_PAGE_URL"] = $ElmUrl."?offer=".$valueOff["ID"];
						$this->AddProductFromFile($Offers["Elm"][$value]);
					}
				}
			}
		} else {
			$this->MaxStep = ceil($Query->SelectedRowsCount()/$this->Params["MaxElm"])+$this->AdditionalStep;
			$this->SetMaxStep();
		}
	}
	
	private function AddProductFromFile($Elm = array()){
		$Flag = true;
		$Text = "";
		$Price = $this->GetPriceElement($Elm["ID"]);
		$Img = -1;
		$Amount = $this->GetAmountElement($Elm["ID"]);
		if($this->Params["ActualAmount"] == "Y" && $Amount == 0){$Flag = false;}
		if($Flag){
			$Text = "<offer id=\"".$Elm["ID"]."\" available=\"".($Amount > 0 ? "true" : "false")."\" >\n";
			$Text .= "<url>".$this->Params["ServerName"].$Elm["DETAIL_PAGE_URL"]."</url>\n";
			
			if($Price["DISCOUNT"] > 0){
				$Text .= "<price>".$Price["DISCOUNT"]."</price>\n";
			} elseif($Price["PRICE"] > 0){
				$Text .= "<price>".$Price["PRICE"]."</price>\n";
			}
			
			$Text .= "<currencyId>".$this->Params["Currency"]."</currencyId>\n";
        	$Text .= "<categoryId>".$Elm["IBLOCK_SECTION_ID"]."</categoryId>\n";
			
			if($Elm["DETAIL_PICTURE"] > 0){
				$Img = $Elm["DETAIL_PICTURE"];
			} elseif($Elm["PREVIEW_PICTURE"] > 0){
				$Img = $Elm["PREVIEW_PICTURE"];
			}
			
			if($Img > 0){
				$Img = CFile::GetByID($Img)->Fetch();
				$Text .= "<picture>";
				$Text .= $this->Params["ServerName"]."/upload/".$Img["SUBDIR"]."/".$Img["FILE_NAME"];
				$Text .= "</picture>\n";
			}
			
			$Text .= "<name>".$this->YandexText($Elm["NAME"], true)."</name>\n";
			$Text .= "<description>".$this->YandexText($Elm["PREVIEW_TEXT"], true)."</description>\n";

			$Text .= "</offer>\n";
			$this->WriteFile($Text);
		}
	}
	
	private function GetPriceElement($Id = -1){
		$Price = array("PRICE" => -1,"DISCOUNT" => -1);
		if($Id > 0){
			$Query = CPrice::GetListEx(
				array(),
		        array(
		        	"PRODUCT_ID" => $Id,
		        	"CATALOG_GROUP_ID" => $this->Params["PriceId"]
		        ),
		        false,
		        false,
		        array("ID", "CATALOG_GROUP_ID", "PRICE", "CURRENCY", "QUANTITY_FROM", "QUANTITY_TO")
			)->Fetch();

			if(!empty($Query)){
				$Price["PRICE"] = $Query["PRICE"];
				
				$OptimalPrice = CCatalogProduct::GetOptimalPrice(
					$Id,
					1,
					array(2),
					"N",
					array(),
					SITE_ID,
					array()
				);
				
				if($Price["PRICE"] > $OptimalPrice["RESULT_PRICE"]["DISCOUNT_PRICE"]){
					$Price["DISCOUNT"] = $OptimalPrice["RESULT_PRICE"]["DISCOUNT_PRICE"];
				}
			}
		}
		return $Price;
	}
	
	private function GetAmountElement($Id = -1){
		$Amount = 0;
		$WarehousesCount = 0;
		if(is_array($this->Params["Warehouses"])){
			$WarehousesCount = count($this->Params["Warehouses"]);
		}
		if($Id > 0){
			if($WarehousesCount > 0){
				$Query = CCatalogStoreProduct::GetList(
					array(),
					array(
						"PRODUCT_ID" => $Id,
						"STORE_ID" => $this->Params["Warehouses"]
					),
					false,
					false,
					array("ID","AMOUNT")
				);
				while($Answer = $Query->Fetch()){
					$Amount += $Answer["AMOUNT"]*1;
				}
			} else {
				$Query =  CIBlockElement::GetList(
					array(),
					array(
						"ID" => $Id
					),
					false,
					array("nPageSize"=>1),
					array("ID","CATALOG_QUANTITY")
				);
				if($Answer = $Query->Fetch()){
					$Amount = $Answer["CATALOG_QUANTITY"];
				}
			}
		}
		return $Amount;
	}
	
	private function Check(){
		if(!($this->Params["ProfileId"] > 0)){
			$this->Errors[] = GetMessage("WESTPOWER_YMCG_3");
		}
		if(!($this->Params["MaxElm"] > 0)){
			$this->Errors[] = GetMessage("WESTPOWER_YMCG_4");
		}
		if(!($this->Params["Ib"] > 0)){
			$this->Errors[] = GetMessage("WESTPOWER_YMCG_5");
		}
		
	}
	private function GetData(){
		global $DB;
		$Flag = false;
		$Query = $DB->Query("
			SELECT
				ID,
				STEP,
				MAX_STEP,
				FINAL
			FROM 
				b_westpower_yandex_market 
			WHERE 
				PROFILE_ID=".$DB->ForSQL($this->Params["ProfileId"])."
			AND 
				TIMESTAMP_UNIX=".$DB->ForSQL($this->CurentDate)."
			LIMIT 1
		;");
		if($Answer = $Query->Fetch()){
			if($Answer["FINAL"] != "Y"){
				$Flag = true;
				$this->Step = ($Answer["STEP"] >= 1 ? $Answer["STEP"] : 1);
				$this->Id = $Answer["ID"];
				$this->MaxStep = ($Answer["MAX_STEP"] >= 1 ? $Answer["MAX_STEP"] : -1);
			}
		} else {
			if($this->NewExport()){
				$this->Errors[] = GetMessage("WESTPOWER_YMCG_6");
			} else {
				$this->Errors[] = GetMessage("WESTPOWER_YMCG_7");
			}
		}
		return $Flag;
	}
	private function SetMaxStep(){
		global $DB;
		if($this->MaxStep >= 1){
			$DB->Query("
				UPDATE
					b_westpower_yandex_market
				SET 
					MAX_STEP = ".$DB->ForSQL($this->MaxStep)." 
				WHERE 
					ID = ".$this->Id."
			;");
		}	
	}
	private function SetNextStep(){
		global $DB;
		$DataFile = "";
		$this->Step += 1;
		
		if($this->MaxStep >= 1){
			$DB->Query("
				UPDATE
					b_westpower_yandex_market
				SET 
					STEP = ".$DB->ForSQL($this->Step)." 
				WHERE 
					ID = ".$this->Id."
			;");
		}
		if($this->Step > $this->MaxStep){
			$DB->Query("
				UPDATE
					b_westpower_yandex_market
				SET 
					FINAL = 'Y' 
				WHERE 
					ID = ".$this->Id."
			;");
			$this->WriteFile("</offers>\n");
			$this->WriteFile("</shop>\n");
			$this->WriteFile("</yml_catalog>\n");
			$this->ConvertFileWin1251();
		}
	}
	
	private function ConvertFileWin1251(){
		$this->CloseFile();
		if($this->OpenFile("r")){
			if($NFile = fopen($this->Params["FileName"], "w")){
				while (!feof($this->OFile)) {
				    $TmpData = fread($this->OFile,  8192);
				    fwrite($NFile, mb_convert_encoding($TmpData,"windows-1251",LANG_CHARSET));
				}
				fclose($NFile);
			}
		}
	}
	
	private function NewExport(){
		global $DB;
		$Result = $DB->Query("INSERT INTO 
			b_westpower_yandex_market 
				(TIMESTAMP_UNIX,PROFILE_ID,STEP,MAX_STEP,PARAMS,FINAL) 
			VALUES
				(
					'".$DB->ForSQL($this->CurentDate)."',
					'".$DB->ForSQL($this->Params["ProfileId"])."',
					'1',
					'-1',
					'".$DB->ForSQL($this->GetParams())."',
					'N'
				) 
		;");
		return $Result;
	}
}
?>