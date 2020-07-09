<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeModuleLangFile(__FILE__);
class WestpowerAdminTabControlRow {

	public function GetHtmlBlock($Field = array(),$Option = ""){
		$StrHtml = "";
		$RowCssClass = "";
		
		if(isset($Field["PARAMS"]["ROW_CSS_CLASS"]) && isset($Field["PARAMS"]["ROW_CSS_CLASS"]) != ""){
			$RowCssClass = $Field["PARAMS"]["ROW_CSS_CLASS"];
		}
		
		if(!empty($Field)){
			if(isset($Field["HEADER"]) && $Field["HEADER"]){
				$StrHtml .= "<tr class=\"heading\"><td colspan=\"2\">".$Field["HEADER_TEXT"]."</td></tr>";
			}
			$StrHtml .= "<tr class=\"".$RowCssClass."\">";
			if(isset($Field["PARAMS"]["TITLE_POSITION"]) && $Field["PARAMS"]["TITLE_POSITION"] == "top"){
					$StrHtml .= "<td colspan=\"2\">".$Field["TITLE"]."</td>";
				$StrHtml .= "</tr>";
				$StrHtml .= "<tr class=\"".$RowCssClass."\">";
					$StrHtml .= "<td colspan=\"2\">";
			} else {
				$StrHtml .= "<td>".$Field["TITLE"]."</td>";
				$StrHtml .= "<td>";
			}
				if(isset($Field["NAME"]) && $Field["NAME"] != ""){
					$StrHtml .= $this->GetHtmlField($Field["NAME"],$Field["FIELD"],$Option);
				}
				$StrHtml .= "</td>";
			$StrHtml .= "</tr>";
			if(isset($Field["CUSTOM_HTML"])){
				$StrHtml .= "<tr><td colspan=\"2\">".$Field["CUSTOM_HTML"]."</td></tr>";
			}
		}
		return $StrHtml;
	}

	private function GetHtmlField($Name = "",$Field = array(),$Option = ""){
		$StrHtml = "";
		if($Option == "" && isset($Field["DEFAULT"]) && $Field["DEFAULT"] != ""){$Option = $Field["DEFAULT"];}
		
		if($Field["TYPE"] == "select"){
			if(isset($Field["MULTIPLE"]) && $Field["MULTIPLE"]){
				if(!isset($Field["SIZE"]) || $Field["SIZE"] < 0){$Field["SIZE"] = 3;}
				$StrHtml .= "<select id=\"".$Name."\" size=\"".$Field["SIZE"]."\" name=\"".$Name."[]\" multiple=\"multiple\">";
					foreach($Field["VALUE"] as $Item){
						$StrHtml .= "<option";
							$StrHtml .= in_array($Item["VALUE"],$Option) ? " selected=\"\"" : "";
							if(isset($Item["ATTRIBUTES"]) && is_array($Item["ATTRIBUTES"])){
								foreach($Item["ATTRIBUTES"] as $key => $value){
									$StrHtml .= " ".$key."=\"".$value."\"";
								}
							}
							$StrHtml .= " value=\"".$Item["VALUE"]."\">";
							$StrHtml .= $Item["TEXT"];
						$StrHtml .= "</option>";
					}
				$StrHtml .= "</select>";
			} else {
				$StrHtml .= "<select id=\"".$Name."\" name=\"".$Name."\" >";
					$StrHtml .= "<option></option>";
					foreach($Field["VALUE"] as $Item){
						$StrHtml .= "<option";
							$StrHtml .= $Option == $Item["VALUE"] ? " selected=\"\"" : "";
							if(isset($Item["ATTRIBUTES"]) && is_array($Item["ATTRIBUTES"])){
								foreach($Item["ATTRIBUTES"] as $key => $value){
									$StrHtml .= " ".$key."=\"".$value."\"";
								}
							}
							$StrHtml .= " value=\"".$Item["VALUE"]."\">";
							$StrHtml .= $Item["TEXT"];
						$StrHtml .= "</option>";
					}
				$StrHtml .= "</select>";
			}
		} elseif ($Field["TYPE"] == "checkbox"){
			
			foreach($Field["VALUE"] as $key => $value){
				$StrHtml .= "<input type=\"".$Field["TYPE"]."\" name=\"".$Name."\" value=\"".$value["VALUE"]."\"";
				
				if($Option != "" && $Option == $value["VALUE"]){$StrHtml .= " checked=\"checked\" ";}
				$StrHtml .= "/>";
			}
		} elseif($Field["TYPE"] == "button"){
			foreach($Field["VALUE"] as $key => $value){
				$StrHtml .= "<input type=\"".$Field["TYPE"]."\"";
				$StrHtml .= " name=\"".$Name."\"";
				if(isset($Field["ONCLICK"])){
					$StrHtml .= " onclick=\"".$Field["ONCLICK"]."\"";
				}
				$StrHtml .= " value=\"".$value["VALUE"]."\" />";
			}	
		} elseif($Field["TYPE"] == "textarea"){
			if(isset($Field["MULTIPLE"]) && $Field["MULTIPLE"]){
			} else {
				$StrHtml .= "<textarea placeholder=\"".$Field["PLACEHOLDER"]."\" name=\"".$Name."\">".$Option."</textarea>";
			}
		} elseif($Field["TYPE"] == "text"){
			
			if(isset($Field["MULTIPLE"]) && $Field["MULTIPLE"]){
				$Name .= "[]";
				$Values = unserialize($Option);
				
				if(empty($Values)){$Values[] = "";}
				
				$StrHtml .= "<div class=\"wp-wti-o-group wp-wti-o-fit-text\">";
					$StrHtml .= "<div class=\"wp-wti-o-items\">";
					foreach($Values as $Item){
						$StrHtml .= "<div class=\"wp-wti-o-item\">";
							$StrHtml .= "<input";
							$StrHtml .= " type=\"".$Field["TYPE"]."\"";
							$StrHtml .= " name=\"".$Name."\"";
							$StrHtml .= " value=\"".$Item."\"";				
							if(isset($Field["ONCLICK"]) && $Field["ONCLICK"] != ""){
								$StrHtml .= " onclick=\"".$Field["ONCLICK"]."\"";
							}
							$StrHtml .= "/>";
							$StrHtml .= "<a class=\"wp-wti-o-remove\" href=\"javascript:void(0);\">";
								$StrHtml .= GetMessage("WESTPOWER_ATCR_0");
							$StrHtml .= "</a>";
							
						$StrHtml .= "</div>";
					}
					$StrHtml .= "</div>";
					$StrHtml .= "<div class=\"wp-wti-o-bt\">";
						$StrHtml .= "<a class=\"wp-wti-o-add\" href=\"javascript:void(0);\">";
							$StrHtml .= GetMessage("WESTPOWER_ATCR_1");
						$StrHtml .= "</a>";
					$StrHtml .= "</div>";
				$StrHtml .= "</div>";
				
			} else {
				$StrHtml .= "<input placeholder=\"".$Field["PLACEHOLDER"]."\" type=\"".$Field["TYPE"]."\" name=\"".$Name."\" value=\"".$Option."\" />";
			}
		} elseif($Field["TYPE"] == "groups"){
			$Values = unserialize($Option);
			$CountFieldsGroups = count($Field["GROUPS"]);
			if(isset($Field["MULTIPLE"]) && $Field["MULTIPLE"]){
				if(!empty($Values)){
					$NValues = array();
					foreach($Values as $key => $value){
						foreach($value as $Vkey => $Vvalue){
							$NValues[$Vkey][$key] = $Vvalue;
						}
					}
					$Values = $NValues;
				} else {
					$Values[] = array();
				}

				$StrHtml .= "<div class=\"wp-wti-o-group wp-wti-o-ft-groups wp-wti-o-count-fields-".$CountFieldsGroups."\">";
					$StrHtml .= "<div class=\"wp-wti-o-items\">";
					foreach($Values as $key => $value){
						$StrHtml .= "<div class=\"wp-wti-o-item\">";
							$StrHtml .= "<table><tr>";
							foreach($Field["GROUPS"] as $Item){
								$StrHtml .= "<td>";
								$StrHtml .= $this->GetHtmlField(
									$Name."[".$Item["NAME"]."][]",
									$Item,
									(isset($Values[$key][$Item["NAME"]]) ? $Values[$key][$Item["NAME"]] : "")
								);
								$StrHtml .= "</td>";
							}
							$StrHtml .= "<td>";
							$StrHtml .= "<a class=\"wp-wti-o-remove\" href=\"javascript:void(0);\">";
								$StrHtml .= GetMessage("WESTPOWER_ATCR_0");
							$StrHtml .= "</a>";
							$StrHtml .= "</td>";
							$StrHtml .= "</tr></table>";
						$StrHtml .= "</div>";
					}
					$StrHtml .= "</div>";
					$StrHtml .= "<div class=\"wp-wti-o-bt\">";
						$StrHtml .= "<a class=\"wp-wti-o-add\" href=\"javascript:void(0);\">";
							$StrHtml .= GetMessage("WESTPOWER_ATCR_1");
						$StrHtml .= "</a>";
					$StrHtml .= "</div>";
				$StrHtml .= "</div>";
				
			} else {
				foreach($Field["GROUPS"] as $Item){
					$StrHtml .= $this->GetHtmlField(
						$Name."[".$Item["NAME"]."]",
						$Item,
						(isset($Values[$Item["NAME"]]) ? $Values[$Item["NAME"]] : "")
					);
				}
			}
		}
		return $StrHtml;
	}
}
?>