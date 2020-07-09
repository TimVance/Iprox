<?
//<title>Westpower Yandex Market</title>
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */
/** @global string $ACTION */
/** @global array $arOldSetupVars */
	
	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

	IncludeModuleLangFile(__FILE__);
	
	global $APPLICATION;
	
	CModule::IncludeModule("westpower.yandexmarket");
	include(__DIR__."/yandex_market_fields.php");
	
	CJSCore::Init(array("jquery"));
	
	if(isset($_POST["AJAX"])){
		$APPLICATION->RestartBuffer();
		
		$Result = array("Status" => false,"Mess" => "","Data" => array());
		
		if(isset($_POST["ACT"])){
			if($_POST["ACT"] == "GET_IBLOCK_DATA"){
				$Ib = htmlspecialchars($_POST["IBLOCK_ID"]);
				if($Ib > 0){
					$Query = CIBlockSection::GetList(array("LEFT_MARGIN"=>"ASC"),array("IBLOCK_ID"=>$Ib),false,array("ID","NAME","DEPTH_LEVEL"));
					while($Answer = $Query->Fetch()){
						for($i = 0; $i < $Answer["DEPTH_LEVEL"];$i++){$Answer["NAME"] = "  .  ".$Answer["NAME"];}
						$Result["Data"]["Sections"][] = array("VALUE" => $Answer["ID"],"TEXT" => $Answer["NAME"]." [".$Answer["ID"]."]");
					}
					$Query = CIBlockProperty::GetList(array("SORT"=>"ASC"), array("ACTIVE"=>"Y", "IBLOCK_ID"=>$Ib));
					while($Answer = $Query->Fetch()){
						$Result["Data"]["Properties"][] = array("VALUE" => $Answer["CODE"],"TEXT" => $Answer["NAME"]." [".$Answer["CODE"]."]");
					}
					$Result["Status"] = true;
				}
			}
			
		}
		
		echo json_encode($Result);
		die();
	}
	
	if($STEP > 1){
		if(isset($_POST["SAVE"])){$FINITE = true;}
	}
	
	$SetupFieldsList = array();
	
	$Ib = 0;
	$Prices = $Sections = $Properties = array();
	
	if(isset($arOldSetupVars["CATALOG_IBLOCK_ID"]) && $arOldSetupVars["CATALOG_IBLOCK_ID"]> 0){
		$Ib = $arOldSetupVars["CATALOG_IBLOCK_ID"];
		$Query = CIBlockSection::GetList(array("LEFT_MARGIN"=>"ASC"),array("IBLOCK_ID"=>$Ib),false,array("ID","NAME","DEPTH_LEVEL"));
		while($Answer = $Query->Fetch()){
			for($i = 0; $i < $Answer["DEPTH_LEVEL"];$i++){$Answer["NAME"] = "  .  ".$Answer["NAME"];}
			$Sections[] = array("VALUE" => $Answer["ID"],"TEXT" => $Answer["NAME"]." [".$Answer["ID"]."]");
		}
		$Query = CIBlockProperty::GetList(array("SORT"=>"ASC"), array("ACTIVE"=>"Y", "IBLOCK_ID"=>$Ib));
		while($Answer = $Query->Fetch()){
			$Properties[] = array("VALUE" => $Answer["CODE"],"TEXT" => $Answer["NAME"]." [".$Answer["CODE"]."]");
		}
	}
	
	$Watcr = new WestpowerAdminTabControlRow();
	
	$ACMenu = new CAdminContextMenu(
		array(
			array(
				"TEXT" => GetMessage("WESTPOWER_YMS_0"),
				"TITLE" => GetMessage("WESTPOWER_YMS_1"),
				"LINK" => "/bitrix/admin/cat_export_setup.php?lang=".LANGUAGE_ID,
				"ICON" => "btn_list",
			)
		)
	);
	$ACMenu->Show();
	
	$Steps = array(
		array("DIV" => "edit1", "TAB" => GetMessage("WESTPOWER_YMS_2"), "ICON" => "store", "TITLE" => GetMessage("WESTPOWER_YMS_2")),
		array("DIV" => "edit2", "TAB" => GetMessage("WESTPOWER_YMS_3"), "ICON" => "store", "TITLE" => GetMessage("WESTPOWER_YMS_3")),
		array("DIV" => "edit3", "TAB" => GetMessage("WESTPOWER_YMS_4"), "ICON" => "store", "TITLE" => GetMessage("WESTPOWER_YMS_4")),
		array("DIV" => "edit4", "TAB" => GetMessage("WESTPOWER_YMS_5"), "ICON" => "store", "TITLE" => GetMessage("WESTPOWER_YMS_5")),
		array("DIV" => "edit5", "TAB" => GetMessage("WESTPOWER_YMS_6"), "ICON" => "store", "TITLE" => GetMessage("WESTPOWER_YMS_6")),
		
	);
	
	$ATControl = new CAdminTabControl("tabControl",$Steps,false,true);
?>
<div class="wp-wy-load-yms">
	<form method="POST" action="<?=$APPLICATION->GetCurPageParam(); ?>" enctype="multipart/form-data" name="dataload">
		<?$ATControl->Begin();?>
			<?foreach($Steps as $key => $value){
				$ATControl->BeginNextTab();
				foreach($YandexMarketFields as $KItem => $VItem){
					if($VItem["STEP"] != $key){continue;}
					
					$Option = "";
					if(isset($VItem["NAME"])){
						$SetupFieldsList[] = $VItem["NAME"];
						if(isset($arOldSetupVars[$VItem["NAME"]]) && $arOldSetupVars[$VItem["NAME"]] != ""){
							if(is_array($arOldSetupVars[$VItem["NAME"]])){
								$Option = $arOldSetupVars[$VItem["NAME"]];
							} else {
								$Option = htmlspecialchars($arOldSetupVars[$VItem["NAME"]]);
							}
						}
						
						if($VItem["NAME"] == "CATALOG_IBLOCK_ID"){
							$Query = CCatalog::GetList(array("NAME" => "ASC"),array(),false,false,array("IBLOCK_ID","NAME"));
							while($Answer = $Query->Fetch()) {
								if(CCatalogSKU::GetInfoByProductIBlock($Answer["IBLOCK_ID"])){
									$VItem["FIELD"]["VALUE"][] = array(
										"VALUE" => $Answer["IBLOCK_ID"],
										"TEXT" => $Answer["NAME"]." [".$Answer["IBLOCK_ID"]."]"
									);
								}
							}
						} elseif($VItem["NAME"] == "INVENTORY_CONTROL_WAREHOUSES"){
							$Query = CCatalogStore::GetList();
							while($Answer = $Query->Fetch()) {
								$VItem["FIELD"]["VALUE"][] = array(
									"VALUE" => $Answer["ID"],
									"TEXT" => $Answer["TITLE"]." [".$Answer["ID"]."]"
								);
							}
						} elseif($VItem["NAME"] == "PRICES_PRICE_TYPE"){
							$Prices = CCatalogIBlockParameters::getPriceTypesList();
							foreach($Prices as $PKey => $PValue){
								$VItem["FIELD"]["VALUE"][] = array("VALUE" => $PKey,"TEXT" => $PValue);
							}
						} elseif($VItem["NAME"] == "PRICES_CURRENCY"){
							$Query = CCurrency::GetList(($by="name"), ($order="asc"), LANGUAGE_ID);
							while($Answer = $Query->Fetch()) {
								$VItem["FIELD"]["VALUE"][] = array(
									"VALUE" => $Answer["CURRENCY"],
									"TEXT" => $Answer["FULL_NAME"]." [".$Answer["CURRENCY"]."]"
								);
							}
						} elseif($VItem["NAME"] == "CATALOG_IBLOCK_SECTION_ID"){
							$VItem["FIELD"]["VALUE"] = $Sections;
						} elseif(in_array($VItem["NAME"],array("PROPERTIES_VENDOR","PROPERTIES_VENDOR_CODE","PROPERTIES_PARAM","PROPERTIES_MODEL"))){
							$VItem["FIELD"]["VALUE"] = $Properties;
						}
					}
					echo $Watcr->GetHtmlBlock($VItem,$Option);
					unset($YandexMarketFields[$KItem]);
				}
				$ATControl->EndTab();
			}?>
			<?$ATControl->Buttons();?>
			<?=bitrix_sessid_post();?>
			<input type="submit" name="SAVE" class="adm-btn-save" value="<?=GetMessage("WESTPOWER_YMS_7")?>">
		<?$ATControl->End();?>
		<?if ($ACTION == "EXPORT_EDIT" || $ACTION == "EXPORT_COPY"):?>
		<input type="hidden" name="PROFILE_ID" value="<?=intval($PROFILE_ID); ?>">
		<?endif;?>
		<input type="hidden" name="lang" value="<?=LANGUAGE_ID?>">
		<input type="hidden" name="ACT_FILE" value="<?=htmlspecialcharsbx($_REQUEST["ACT_FILE"])?>">
		<input type="hidden" name="ACTION" value="<?=htmlspecialcharsbx($ACTION) ?>">
		<input type="hidden" name="STEP" value="<?=intval($STEP) + 1 ?>">
		<input type="hidden" name="SETUP_FIELDS_LIST" value="<?=implode(",",$SetupFieldsList)?>" />
	</form>
</div>
<script>
	$(document).ready(function(){
		$("#CATALOG_IBLOCK_ID").change(function(){
			$("#CATALOG_IBLOCK_SECTION_ID").empty();
			$("#PROPERTIES_VENDOR").empty();
			$("#PROPERTIES_VENDOR_CODE").empty();
			$("#PROPERTIES_PARAM").empty();
			$("#PROPERTIES_MODEL").empty();
			if($(this).val() > 0){
				$.ajax({
					type: "POST",
					dataType: "json",
					data: "AJAX&ACT=GET_IBLOCK_DATA&IBLOCK_ID="+$(this).val(),
					success: function(Result){
						var StrHtml = "<option></option>";
						if(Result.Status){
							$("#PROPERTIES_VENDOR").append(StrHtml);
							$("#PROPERTIES_VENDOR_CODE").append(StrHtml);
							$("#PROPERTIES_MODEL").append(StrHtml);
							
							for(i in Result["Data"]["Sections"]){
								StrHtml = "<option value=\""+Result["Data"]["Sections"][i]["VALUE"]+"\">"+Result["Data"]["Sections"][i]["TEXT"]+"</option>";
								$("#CATALOG_IBLOCK_SECTION_ID").append(StrHtml);
							}
							for(i in Result["Data"]["Properties"]){
								StrHtml = "<option value=\""+Result["Data"]["Properties"][i]["VALUE"]+"\">"+Result["Data"]["Properties"][i]["TEXT"]+"</option>";
								$("#PROPERTIES_VENDOR").append(StrHtml);
								$("#PROPERTIES_VENDOR_CODE").append(StrHtml);
								$("#PROPERTIES_PARAM").append(StrHtml);
								$("#PROPERTIES_MODEL").append(StrHtml);
							}
						}
					}
				});
			}
		});
	})
</script>
<style>
	.wp-wy-load-yms .adm-detail-content-cell-l *,
	.wp-wy-load-yms .adm-detail-content-cell-r * {
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
	}
	.wp-wy-load-yms .adm-detail-content-cell-r,.wp-wy-load-yms .adm-detail-content-cell-l {width: 50%;}
	.wp-wy-load-yms select,
	.wp-wy-load-yms input[type="text"] {
		min-width: 200px;
	}
</style>