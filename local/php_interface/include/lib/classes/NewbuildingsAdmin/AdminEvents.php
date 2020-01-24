<? 
namespace NewbuildingsAdmin;

class AdminEvents {
	
	public function onNewBuildChangeAction()
	{
		global $APPLICATION;
		if ($APPLICATION->GetCurPage() == "/bitrix/admin/newbuild_iblock_element_edit.php")
		{
			
			
			$APPLICATION->AddHeadString('
			<script>
				BX.ready(function(){
					var formE = form_element_7_form[0];
					var url = formE.form.action;
					formE.form.action = encodeURI(url.replace("iblock_element_edit.php","newbuild_iblock_element_edit.php"));
				});
			</script>
			');
		}

	}
	
	public function onRedirectCheck(&$url)
	{
		global $APPLICATION;
		if ($APPLICATION->GetCurPage() == "/bitrix/admin/newbuild_iblock_element_edit.php")
		{
			if (strpos($url, "/admin/iblock_element_edit.php") !== false)
			{
				$url = str_replace("/admin/iblock_element_edit.php", "/admin/newbuild_iblock_element_edit.php", $url);
			}
		}
		
	}
	
	public function onApartAddPage(&$form)
	{
		global $APPLICATION;
		if ($APPLICATION->GetCurPage() == "/bitrix/admin/newbuild_iblock_element_edit.php")
		{
			$PropsToShow = array(
					"PROPERTY_34",
					"PROPERTY_41",
					//"PROPERTY_54", //Город
					//"PROPERTY_61", // Район
					//"PROPERTY_69", Микрорайон
					//"PROPERTY_74", // Улица
					//"PROPERTY_82",
					"PROPERTY_84",
					"PROPERTY_85",
					"PROPERTY_88",
					"PROPERTY_89",
					"PROPERTY_157",
					"PROPERTY_188",
					//"PROPERTY_281", // Прошел ли модерацию
					"PROPERTY_331"
			);
			$FieldsToShow = array(
					//"NAME",
					//"CODE",
					"SORT",
					"ACTIVE"
					);
			foreach ($form->tabs as &$arTab)
			{
				foreach ($arTab["FIELDS"] as $key=>$arField)
					
					if (strpos($arField["id"], "PROPERTY_") !== false /*&& !$arField["required"]*/)
					{
						/*if ($arField["required"])
							$arField["required"] = "";*/
						if (!in_array($arField["id"],$PropsToShow))
						{
							unset($arTab["FIELDS"][$key]);
						}
					}
					else 
					{
						if (!in_array($arField["id"],$FieldsToShow))
						{
							unset($arTab["FIELDS"][$key]);
						}
					}
			}
		}
	}
	
	public function onApartAddPageSetProps()
	{
		global $APPLICATION;
		if ($APPLICATION->GetCurPage() == "/bitrix/admin/newbuild_iblock_element_edit.php" && !$_REQUEST["ID"])
		{
			ob_start();
			?>
			<script>
			BX.ready(function(){

			
				<? 
				if ($_REQUEST["newbuilding"])
				{
					?>
					document.getElementById("PROP[188][n0]").value = "<?=$_REQUEST["newbuilding"]?>";
					<?
				}
				if ($_REQUEST["living_block"])
				{
					?>
					document.getElementById("tr_PROPERTY_331").getElementsByTagName("select")[0].value = "<?=$_REQUEST["living_block"]?>";
					<?
				}
				if ($_REQUEST["apart_level"])
				{
					?>
					document.getElementById("tr_PROPERTY_85").getElementsByTagName("input")[0].value = "<?=$_REQUEST["apart_level"]?>";
					<?
				}
				?>
			});
			</script>
			<?
			$APPLICATION->AddHeadString( ob_get_clean() );
		}
	}
}