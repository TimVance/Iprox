<?if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') { die(); }
require ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");?>
<? 
$arFavor = array();
if (count($_REQUEST["favourIDS"]) > 0)
{
	
	foreach ($_REQUEST["favourIDS"] as $Case)
	{
		$arFavor["key".$Case] = "N";
	}
	if ($USER->IsAuthorized()){
		$res = CFavorites::GetList(array(),array('USER_ID' => $USER->GetID(),"URL"=>$_REQUEST["favourIDS"]));
		while ($arTemp = $res->GetNext())
		{
			$arFavor["key".$arTemp["URL"]] = "Y";
		}
	}

}
die(json_encode($arFavor));
?>