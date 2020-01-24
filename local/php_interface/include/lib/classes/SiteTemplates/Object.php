<? 
namespace SiteTemplates;

/**
 * 
 * Класс для шаблонизации и приведения к общему виду, вывода полей\свойств объектов в продаже\аренде\у риелторов
 * 
 * @author Wexpert
 *
 */
class Object 
{
	/**
	 * Шаблон свойств
	 * 
	 * @var array
	 */
	private static $PropsMap = array(
							"square"	=> array("POST_FIX"=>" м<sup>2</sup>","NAME"=>"Общая площадь"),
							"floor"		=> array("NAME"=>"Этаж", "!IBLOCK_ID" => 7),
							"summary_buildings_square" => array("POST_FIX"=>" м<sup>2</sup>","NAME"=>"Общая площадь строений"),
							"number_of_storeys" => array('NAME'=>"Этажность","OUTPUT"=>"VALUE_XML_ID"),
							"sector_square"		=> array("NAME"=>"Площадь участка","OTHER_OPTION"=>array("NAME"=>"Размерность площади участка","CODE"=>"dimension","OUTPUT"=>"VALUE_XML_ID")),
							"number_of_bedrooms"=> array("NAME"=>"Кол-во спален"),
							"garage"	=> array("NAME"=>"Гараж","BOOL"=>true),
							"decoration"=> array("NAME"=>"Отделка","OUTPUT"=>"VALUE_XML_ID"),
							"wc"		=> array("NAME"=>"Санузел","OUTPUT"=>"VALUE_XML_ID"),
							"have_loggia"=> array("NAME"=>"Лоджия","BOOL"=>true),
							"have_balcony"=> array("NAME"=>"Балкон","BOOL"=>true),
							"have_phone"=> array("NAME"=>"Телефон","BOOL"=>true),
							"have_furniture"=> array("NAME"=>"Продается с мебелью","BOOL"=>true),
							"can_mortgage"=> array("NAME"=>"Возможна ипотека","BOOL"=>true),
							
							);
							
	private static $PropsMapShort = array(
			"square"	=> array("POST_FIX"=>" м<sup>2</sup>","NAME"=>"Общая площадь"),
			"summary_buildings_square" => array("POST_FIX"=>" м<sup>2</sup>","NAME"=>"Общая площадь строений"),
			"number_of_storeys" => array('NAME'=>"Этажность","OUTPUT"=>"VALUE_XML_ID"),
			"sector_square"		=> array("NAME"=>"Площадь участка","OTHER_OPTION"=>array("NAME"=>"Размерность площади участка","CODE"=>"dimension","OUTPUT"=>"VALUE_XML_ID")),
			"number_of_bedrooms"=> array("NAME"=>"Количество спален"),
			
				
	);
	
	
	private static $PropsFlatShort = array(
			"square"	=> array("POST_FIX"=>" м<sup>2</sup>","NAME"=>"Общая площадь"),
			"room_number"=> array("NAME"=>"Количество комнат"),
			"floor"		=> array("NAME"=>"Этаж"),
			"floors" => array('NAME'=>"Этажность"),
			"newbuilding" => array("NAME" => "Расстояние до моря", "ELEMENT" => true, "ELEMENT_PROPERTY" => "distance_to_sea", "ELEMENT_PROPERTY_POSTFIX" => "dimension_distance_to_sea")
	);
	
	private static $PropsLandShort = array(
			"sector_square"	=> array("NAME" => "Площадь участка", "OTHER_OPTION"=>array("NAME"=>"Размерность площади участка","CODE"=>"plot_dimension","OUTPUT"=>"VALUE_XML_ID")),
			"distance_to_sea"=> array("NAME" => "Расстояние до моря", "OTHER_OPTION"=>array("NAME"=>"Размерность расстояния до моря","CODE"=>"dimension_distance_to_sea","OUTPUT"=>"VALUE_XML_ID")),
			"rights_to_land" => array('NAME'=>"Право на землю","OUTPUT"=>"VALUE_XML_ID"),
			
	);
	
	
	private static $PropsTiles = array(
			"square"	=> array("POST_FIX"=>" м<sup>2</sup>","NAME"=>"Общая площадь"),
			"floor"		=> array("NAME"=>"Этаж"),
			"summary_buildings_square" => array("POST_FIX"=>" м<sup>2</sup>","NAME"=>"Общая площадь строений"),
			"number_of_storeys" => array('NAME'=>"Этажность","OUTPUT"=>"VALUE_XML_ID"),
	);
							
	/**
	 * 
	 * 
	 * 
	 * @param array $arProps Массив свойств элемента
	 * 
	 * @return string(HTML) 
	 */						
	public function buildProps($arProps)
	{
		ob_start();
		?>
		<div class="list-prm">
			<ul>
				<?
				foreach (self::$PropsMap as $PropCode => $PropOptions)
				{
					if (!$arProps[$PropCode]["VALUE"])
						continue;
					
					if ($PropOptions["!IBLOCK_ID"])
					{
						if ($arProps[$PropCode]["IBLOCK_ID"] == $PropOptions["!IBLOCK_ID"])
						{
							continue;
						}
					}
						
					$StringVal = "";
						
					if ($PropOptions["BOOL"])
					{
						$StringVal = "Да";
				
					}
					else if ($PropOptions["OUTPUT"] == "VALUE_XML_ID")
					{
						$StringVal = $arProps[$PropCode]["VALUE_ENUM"];
					}
					else
					{
						$StringVal = $arProps[$PropCode]["VALUE"];
					}
					if ($PropOptions["OTHER_OPTION"])
					{
						if ($PropOptions["BOOL"])
						{
							$StringVal .= " Да";
				
						}
						else if ($PropOptions["OUTPUT"] == "VALUE_XML_ID" || $PropOptions["OTHER_OPTION"]["OUTPUT"] == "VALUE_XML_ID")
						{
							$StringVal .= " " . $arProps[$PropOptions["OTHER_OPTION"]["CODE"]]["VALUE_ENUM"];
						}
						else
						{
							$StringVal .= " " .$arProps[$PropOptions["OTHER_OPTION"]["CODE"]]["VALUE"];
						}
					}
					?>
					<li><?=$PropOptions["NAME"]?> : <strong><?=$StringVal?> <?=$PropOptions["POST_FIX"]?></strong></li>
					<?
				}
			?>
			</ul>
		</div>
		<? 
		return ob_get_clean();
		
	}
	
	public function buildLandPropsShort($arProps)
	{
		ob_start();
		?>
		<div class="list-prm">
			<ul>
				<?
				foreach (self::$PropsLandShort as $PropCode => $PropOptions)
				{
					if (!$arProps[$PropCode]["VALUE"])
						continue;
						
					$StringVal = "";
						
					if ($PropOptions["BOOL"])
					{
						$StringVal = "Да";
				
					}
					else if ($PropOptions["OUTPUT"] == "VALUE_XML_ID")
					{
						$StringVal = $arProps[$PropCode]["VALUE_ENUM"];
					}
					else if ($PropOptions["ELEMENT"])
					{
						$res = \CIBlockElement::GetByID($arProps[$PropCode]["VALUE"]);
						if ($objItem = $res->GetNextElement())
						{
							$arSubItem = $objItem->GetFields();
							$arSubItem["PROPERTIES"] = $objItem->GetProperties();
							
							if ($PropOptions["ELEMENT_PROPERTY"] && $arSubItem["PROPERTIES"][$PropOptions["ELEMENT_PROPERTY"]]["VALUE"])
							{
								$StringVal.= $arSubItem["PROPERTIES"][$PropOptions["ELEMENT_PROPERTY"]]["VALUE"];
							}
							if ($PropOptions["ELEMENT_PROPERTY_POSTFIX"] && $arSubItem["PROPERTIES"][$PropOptions["ELEMENT_PROPERTY_POSTFIX"]]["VALUE"])
							{
								$StringVal.= " ". $arSubItem["PROPERTIES"][$PropOptions["ELEMENT_PROPERTY_POSTFIX"]]["VALUE"];
							}
						}
					}
					else
					{
						$StringVal = $arProps[$PropCode]["VALUE"];
					}
					if ($PropOptions["OTHER_OPTION"])
					{
						if ($PropOptions["BOOL"])
						{
							$StringVal .= " Да";
				
						}
						else if ($PropOptions["OUTPUT"] == "VALUE_XML_ID" || $PropOptions["OTHER_OPTION"]["OUTPUT"] == "VALUE_XML_ID")
						{
							$StringVal .= " " . $arProps[$PropOptions["OTHER_OPTION"]["CODE"]]["VALUE_ENUM"];
						}
						else
						{
							$StringVal .= " " .$arProps[$PropOptions["OTHER_OPTION"]["CODE"]]["VALUE"];
						}
					}
					?>
					<li><?=$PropOptions["NAME"]?> : <strong><?=$StringVal?> <?=$PropOptions["POST_FIX"]?></strong></li>
					<?
				}
			?>
			</ul>
		</div>
		<? 
		return ob_get_clean();
		
	}
	
	
	public function buildFlatPropsShort($arProps)
	{
		ob_start();
		?>
		<div class="list-prm">
			<ul>
				<?
				foreach (self::$PropsFlatShort as $PropCode => $PropOptions)
				{
					if (!$arProps[$PropCode]["VALUE"])
						continue;
						
					$StringVal = "";
						
					if ($PropOptions["BOOL"])
					{
						$StringVal = "Да";
				
					}
					else if ($PropOptions["OUTPUT"] == "VALUE_XML_ID")
					{
						$StringVal = $arProps[$PropCode]["VALUE_ENUM"];
					}
					else if ($PropOptions["ELEMENT"])
					{
						$res = \CIBlockElement::GetByID($arProps[$PropCode]["VALUE"]);
						if ($objItem = $res->GetNextElement())
						{
							$arSubItem = $objItem->GetFields();
							$arSubItem["PROPERTIES"] = $objItem->GetProperties();
							
							if ($PropOptions["ELEMENT_PROPERTY"] && $arSubItem["PROPERTIES"][$PropOptions["ELEMENT_PROPERTY"]]["VALUE"])
							{
								$StringVal.= $arSubItem["PROPERTIES"][$PropOptions["ELEMENT_PROPERTY"]]["VALUE"];
							}
							if ($PropOptions["ELEMENT_PROPERTY_POSTFIX"] && $arSubItem["PROPERTIES"][$PropOptions["ELEMENT_PROPERTY_POSTFIX"]]["VALUE"])
							{
								$StringVal.= " ". $arSubItem["PROPERTIES"][$PropOptions["ELEMENT_PROPERTY_POSTFIX"]]["VALUE"];
							}
						}
					}
					else
					{
						$StringVal = $arProps[$PropCode]["VALUE"];
					}
					if ($PropOptions["OTHER_OPTION"])
					{
						if ($PropOptions["BOOL"])
						{
							$StringVal .= " Да";
				
						}
						else if ($PropOptions["OUTPUT"] == "VALUE_XML_ID" || $PropOptions["OTHER_OPTION"]["OUTPUT"] == "VALUE_XML_ID")
						{
							$StringVal .= " " . $arProps[$PropOptions["OTHER_OPTION"]["CODE"]]["VALUE_ENUM"];
						}
						else
						{
							$StringVal .= " " .$arProps[$PropOptions["OTHER_OPTION"]["CODE"]]["VALUE"];
						}
					}
					?>
					<li><?=$PropOptions["NAME"]?> : <strong><?=$StringVal?> <?=$PropOptions["POST_FIX"]?></strong></li>
					<?
				}
			?>
			</ul>
		</div>
		<? 
		return ob_get_clean();
		
	}
	
	public function buildPropsShort($arProps, $maximum = 0)
	{
		$bIttering = $maximum > 0;
		$i = 0;
		ob_start();
		?>
			
					<?
					foreach (self::$PropsMap as $PropCode => $PropOptions)
					{
						if (!$arProps[$PropCode]["VALUE"])
							continue;
							
						
						if ($PropOptions["!IBLOCK_ID"])
						{
							if ($arProps[$PropCode]["IBLOCK_ID"] == $PropOptions["!IBLOCK_ID"])
							{
								continue;
							}
						}
						$StringVal = "";
							
						if ($PropOptions["BOOL"])
						{
							$StringVal = "Да";
					
						}
						else if ($PropOptions["OUTPUT"] == "VALUE_XML_ID")
						{
							$StringVal = $arProps[$PropCode]["VALUE_ENUM"];
						}
						else
						{
							$StringVal = $arProps[$PropCode]["VALUE"];
						}
						if ($PropOptions["OTHER_OPTION"])
						{
							if ($PropOptions["BOOL"])
							{
								$StringVal .= " Да";
					
							}
							else if ($PropOptions["OUTPUT"] == "VALUE_XML_ID" || $PropOptions["OTHER_OPTION"]["OUTPUT"] == "VALUE_XML_ID")
							{
								$StringVal .= " " . $arProps[$PropOptions["OTHER_OPTION"]["CODE"]]["VALUE_ENUM"];
							}
							else
							{
								$StringVal .= " " .$arProps[$PropOptions["OTHER_OPTION"]["CODE"]]["VALUE"];
							}
						}
						?>
						<li><span><?=$PropOptions["NAME"]?>:</span><strong><?=$StringVal?> <?=$PropOptions["POST_FIX"]?></strong></li>
						<?
						$i++;
						if ($bIttering && $i >= $maximum)
							break;
					}
				?>
			<? 
			return ob_get_clean();
			
		}
	
	/**
	 * Строит адресс элемента
	 * 
	 * @param array $arProperties Массив свойств
	 */
	public function buildAddress ($arProperties)
	{
		$res = \CIBlockElement::GetByID($arProperties['city']['VALUE']);
		//city
		if($ar_res = $res->GetNext()){
			$arProperties['city']['MODIFIED_VALUE'] = $ar_res['NAME'];
		}
		$res = \CIBlockElement::GetByID($arProperties['district']['VALUE']);
		//district
		if($ar_res = $res->GetNext()){
			$arProperties['district']['MODIFIED_VALUE'] = $ar_res['NAME'];
		}
		$res = \CIBlockElement::GetByID($arProperties['microdistrict']['VALUE']);
		//microdistrict
		if($ar_res = $res->GetNext()){
			$arProperties['microdistrict']['MODIFIED_VALUE'] = $ar_res['NAME'];
		}
		//apartment_complex
		$res = \CIBlockElement::GetByID($arProperties['apartment_complex']['VALUE']);
		if($ar_res = $res->GetNext()){
			$arProperties['apartment_complex']['MODIFIED_VALUE'] = $ar_res['NAME'];
		}
		return ImplodeArrguments(", ", $arProperties['city']['MODIFIED_VALUE'], $arProperties['district']['MODIFIED_VALUE'],$arProperties['microdistrict']['MODIFIED_VALUE'],$arProperties['street']['VALUE']);
	}
	
	
	public function FlatTilesGenerateProps($arProps)
	{
		ob_start();
		?>
		<div class="params-p">
			<ul>
				<?
				foreach (self::$PropsFlatShort as $PropCode => $PropOptions)
				{
					if (!$arProps[$PropCode]["VALUE"])
						continue;
						
					$StringVal = "";
						
					if ($PropOptions["BOOL"])
					{
						$StringVal = "Да";
				
					}
					else if ($PropOptions["OUTPUT"] == "VALUE_XML_ID")
					{
						$StringVal = $arProps[$PropCode]["VALUE_ENUM"];
					}
					else if ($PropOptions["ELEMENT"])
					{
						$res = \CIBlockElement::GetByID($arProps[$PropCode]["VALUE"]);
						if ($objItem = $res->GetNextElement())
						{
							$arSubItem = $objItem->GetFields();
							$arSubItem["PROPERTIES"] = $objItem->GetProperties();
							
							if ($PropOptions["ELEMENT_PROPERTY"] && $arSubItem["PROPERTIES"][$PropOptions["ELEMENT_PROPERTY"]]["VALUE"])
							{
								$StringVal.= $arSubItem["PROPERTIES"][$PropOptions["ELEMENT_PROPERTY"]]["VALUE"];
							}
							if ($PropOptions["ELEMENT_PROPERTY_POSTFIX"] && $arSubItem["PROPERTIES"][$PropOptions["ELEMENT_PROPERTY_POSTFIX"]]["VALUE"])
							{
								$StringVal.= " ". $arSubItem["PROPERTIES"][$PropOptions["ELEMENT_PROPERTY_POSTFIX"]]["VALUE"];
							}
						}
					}
					else
					{
						$StringVal = $arProps[$PropCode]["VALUE"];
					}
					if ($PropOptions["OTHER_OPTION"])
					{
						if ($PropOptions["BOOL"])
						{
							$StringVal .= " Да";
				
						}
						else if ($PropOptions["OUTPUT"] == "VALUE_XML_ID" || $PropOptions["OTHER_OPTION"]["OUTPUT"] == "VALUE_XML_ID")
						{
							$StringVal .= " " . $arProps[$PropOptions["OTHER_OPTION"]["CODE"]]["VALUE_ENUM"];
						}
						else
						{
							$StringVal .= " " .$arProps[$PropOptions["OTHER_OPTION"]["CODE"]]["VALUE"];
						}
					}
					?>
					<li><?=$PropOptions["NAME"]?> : <strong><?=$StringVal?> <?=$PropOptions["POST_FIX"]?></strong></li>
					<?
				}
			?>
			</ul>
		</div>
		<? 
		return ob_get_clean();
	}
	
	
	public function tilesGenerateProps($arProps)
	{
		ob_start();
		?>
		<div class="params-p">
			<ul>
				<?
				foreach (self::$PropsTiles as $PropCode => $PropOptions)
				{
					if (!$arProps[$PropCode]["VALUE"])
						continue;
						
					$StringVal = "";
						
					if ($PropOptions["BOOL"])
					{
						$StringVal = "Да";
				
					}
					else if ($PropOptions["OUTPUT"] == "VALUE_XML_ID")
					{
						$StringVal = $arProps[$PropCode]["VALUE_ENUM"];
					}
					else if ($PropOptions["ELEMENT"])
					{
						$res = \CIBlockElement::GetByID($arProps[$PropCode]["VALUE"]);
						if ($objItem = $res->GetNextElement())
						{
							$arSubItem = $objItem->GetFields();
							$arSubItem["PROPERTIES"] = $objItem->GetProperties();
							
							if ($PropOptions["ELEMENT_PROPERTY"] && $arSubItem["PROPERTIES"][$PropOptions["ELEMENT_PROPERTY"]]["VALUE"])
							{
								$StringVal.= $arSubItem["PROPERTIES"][$PropOptions["ELEMENT_PROPERTY"]]["VALUE"];
							}
							if ($PropOptions["ELEMENT_PROPERTY_POSTFIX"] && $arSubItem["PROPERTIES"][$PropOptions["ELEMENT_PROPERTY_POSTFIX"]]["VALUE"])
							{
								$StringVal.= " ". $arSubItem["PROPERTIES"][$PropOptions["ELEMENT_PROPERTY_POSTFIX"]]["VALUE"];
							}
						}
					}
					else
					{
						$StringVal = $arProps[$PropCode]["VALUE"];
					}
					if ($PropOptions["OTHER_OPTION"])
					{
						if ($PropOptions["BOOL"])
						{
							$StringVal .= " Да";
				
						}
						else if ($PropOptions["OUTPUT"] == "VALUE_XML_ID" || $PropOptions["OTHER_OPTION"]["OUTPUT"] == "VALUE_XML_ID")
						{
							$StringVal .= " " . $arProps[$PropOptions["OTHER_OPTION"]["CODE"]]["VALUE_ENUM"];
						}
						else
						{
							$StringVal .= " " .$arProps[$PropOptions["OTHER_OPTION"]["CODE"]]["VALUE"];
						}
					}
					?>
					<li><?=$PropOptions["NAME"]?> : <strong><?=$StringVal?> <?=$PropOptions["POST_FIX"]?></strong></li>
					<?
				}
			?>
			</ul>
		</div>
		<? 
		return ob_get_clean();
	}
	
}
?>