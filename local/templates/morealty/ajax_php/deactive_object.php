<?if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') { die(); }
require ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");?>
<? 
CModule::IncludeModule("iblock");
$arFavor = array();
if (intval($_REQUEST["TARGET"]) > 0)
{
	
	$res = CIBlockElement::GetList(array(),array("ID"=>intval($_REQUEST["TARGET"]),
			
			array(
					"LOGIC" => "OR",
					"PROPERTY_realtor"=>$USER->GetID(),
					"CREATED_BY" => $USER->GetID(),
			),
			
	),false,false,array("IBLOCK_ID","ID","ACTIVE","CREATED_BY"));
	if ($res->AffectedRowsCount() <= 0)
	{
		$arReturn["STATE"] = "error";
	}
	else {
		if ($arTemp = $res->GetNext())
		{
			$newState = ($arTemp["ACTIVE"] == "Y")? "N": "Y";
			$newEle = new CIBlockElement;
			if($newEle->Update($arTemp['ID'], array("ACTIVE"=>$newState,"IBLOCK_ID"=>$arTemp["IBLOCK_ID"])))
			{
				$arReturn["STATE"] = $newState;
				$arReturn["ele"] = $arTemp;
			}
			
		}
	}

}
die(json_encode($arReturn));
?>