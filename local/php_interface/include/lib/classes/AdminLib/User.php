<?
namespace AdminLib;

class User 
{
	
	/**
	 * 
	 * 
	 * 
	 * @param string $inputName название инпута (также будет id селетка)
	 * @param string $formName название родительской формы
	 * @param string $value текущее значение
	 * @param boolean|string $defaultOption (CU - текущий пользователь, SU - выбрать пользователя)
	 */
	public static function UserSelector(string $inputName,string $formName,$value = "",$defaultOption = false)
	{
		echo(\CIBlockPropertyUserID::GetPropertyFieldHtml(array(),array("VALUE"=>$value),array("VALUE"=>$inputName,"FORM_NAME"=>$formName)));
		if ($defaultOption && ($defaultOption == "CU" || $defaultOption == "SU"))
		{
			if (!$value || strlen(trim($value)) <= 0)
			{
			?>
			<script>
			setTimeout(function(){
				var el = document.getElementById("SELECT<?=$inputName?>");
				el.value = "<?=$defaultOption?>";
				el.onchange();
			},100)
			</script>
			<?
			}
		}
	}
	
	public static function UserSelectorMultiple(string $inputName,string $formName,$arValues = array(),$arSettings = array())
	{
		if (!isset($arSettings["DISABLE_ADD"]))
		{
			$arSettings["DISABLE_ADD"] = false;
		}
		if (!isset($arSettings["DEFAULT_STATE"]))
		{
			$arSettings["DEFAULT_STATE"] = "none";
		}
		if (!isset($arSettings["INPUT_NUMS"]))
		{
			$arSettings["INPUT_NUMS"] = 4;
		}
		if (!isset($arSettings["INPUT_NUMS_TO_ADD"]))
		{
			$arSettings["INPUT_NUMS_TO_ADD"] = 1;
		}
		echo("<table id=\"table-".md5($inputName)."\">");
		$arValues = array_values(array_filter($arValues));
		if (count($arValues) > 0)
		{
			
			$i = 0;
			foreach ($arValues as $Value)
			{
				echo("<tr><td>");
				static::UserSelector($inputName."[$i]", $formName,$Value,$arSettings["DEFAULT_STATE"]);
				echo("</td></tr>");
			}
		}
		for($i = 0; $i < $arSettings["INPUT_NUMS"]; $i++)
		{
			echo("<tr><td>");
			static::UserSelector($inputName."[n$i]", $formName,"",$arSettings["DEFAULT_STATE"]);
			echo("</td></tr>");
		}
		if ($arSettings["DISABLE_ADD"] !== true)
		{
			\Bitrix\Main\Page\Asset::getInstance()->addJs('/bitrix/js/iblock/iblock_edit.js');
			echo("<tr><td><input type=\"button\" value=\"Добавить\" onClick=\"addNewRow('table-".md5($inputName)."')\"></td></tr>");
		}
		echo("</table>");
	}
}