<? 
namespace MorealtySale;

class NewbuildPlan
{
	// Констаты
	/**
	 * Инфоблок планировок
	 * 
	 * @var int
	 */
	const PLANS_IBLOCK = 27;
	
	
	/**
	 * Инфоблок квартир
	 * 
	 * @var int
	 */
	const APARTS_IBLOCK = 7;
	
	
	
	
	//Символьные коды свойств
	
	/**
	 * Код свойства порядкового номера квартиры
	 * 
	 * @var string
	 */
	const PLAN_CODE_APART_NUM = "APART_NUM";
	
	/**
	 * Код поля количества квартир на 1 этаже
	 * 
	 * @var string
	 */
	const PLAN_CODE_APARTS_PER_LEVEL = "UF_PLAN_APARTS";
	/**
	 * Код поля План этажей от 
	 *
	 * @var string
	 */
	const BLOCKPLAN_CODE_LEVEL_FROM = "APART_PLAN_FROM";
	/**
	 * Код поля План этажей до
	 *
	 * @var string
	 */
	const BLOCKPLAN_CODE_LEVEL_TO = "APART_PLAN_TO";
	
	
	/**
	 * 
	 * Код свойства количества квартир на этаже
	 * 
	 * @var string
	 */
	const BLOCKPLAN_CODE_PER_LEVEL = "APART_PER_LEVEL";
	
	
	/**
	 * Код свойства привязки к квартирам у планировок
	 * 
	 * @var string
	 */
	const BLOCKPLAN_CODE_ELEMS = "APART_ELEMS";
	
	/**
	 * Код поля позиции на этаже
	 * 
	 * @var string
	 */
	const APART_CODE_LEVEL = "floor";
	
	
	/**
	 * Свойство привязка к разделу планировок у новостроек
	 * 
	 * @var string
	 */
	const NEWBUILD_CODE_PLAN = "PLAN_SECTION";
	
	
	
	
	
	//Свойства объекта
	/**
	 * ИД разделов планировки
	 * 
	 * @var array
	 */
	public $Plan;
	
	/**
	 * ИД новостройки
	 * 
	 * @var int
	 */
	public static $Newbuilding;
	
	/**
	 * Массив элемента новостройки
	 * 
	 * @var array
	 */
	private $arNewbuilding = false;
	
	
	/**
	 * Массив задействованных квартир
	 * 
	 * @var array
	 */
	private $arAparts = array();
	
	/**
	 * Массив задействованных ID квартир
	 *
	 * @var array
	 */
	private $arIDsAparts = array();
	
	/**
	 * Массив раздела планировки
	 * 
	 * @var array
	 */
	private $PlanSection = false;
	
	
	/**
	 * Массив элементов планировки
	 * 
	 * @var array
	 */
	private $PlanElements = false;
	
	
	/**
	 * Структурированные данные планировок (Разделы => (Элемент1,Элемент2 ...))
	 * 
	 * @var array|bool (false)
	 */
	private $StructurePlans = false;
	

	
	/**
	 * Массив вида номер этажа => какие квартиры на этом этаже 
	 * 
	 * @var array
	 */
	private $LevelAparts = array();
	
	
	/**
	 *
	 * Получаем инстанс по Ид новостройки
	 *
	 * @param int $ID раздела новостройки
	 *
	 * @return obj self| false OnFail
	 */
	public function getInstance($ID)
	{
		if (!\CModule::IncludeModule("iblock"))
		{
			ShowError("Не установлен модуль инфоблоков");
			return false;
		}
		$ParamID = intval($ID);
	
		return ($ParamID > 0 && $ParamID)? new self($ID) : false;
	}
	
	function __construct($newBuild = false) {
		if ($newBuild)
			$this->Newbuilding = $newBuild;
		
		if ($this->Newbuilding) {
			$this->getPlansID();
			$this->loadPlanSection();
			$this->loadPlanElements();
			
		}
		
		
   	}
   	
   	

   	
   	/**
   	 * 
   	 * Получаем поэтажную структуру новостройки
   	 * 
   	 */
   	public function getStructure()
   	{
   		$this->loadAparts();
   		$this->makeStructure();
   		return array("SCHEME"=>$this->StructurePlans,"ITEMS"=>$this->arAparts,"NEWBUILDING"=>$this->arNewbuilding);
   		
   	}
   	
   	
   	private function buildObjectTable()
   	{
   		
   		
   		
   	}
   	
   	/**
   	 * 
   	 * Строим структуру полученных данных
   	 */
   	private function makeStructure()
   	{
   		$int = 1;
   		if (count($this->PlanSection) > 0) {
   			foreach ($this->PlanSection as $ObjectBlock)
   			{
   				$this->StructurePlans[$ObjectBlock["ID"]] = array(
   						"ID"			=>	$ObjectBlock["ID"],
   						"REAL_NAME"		=>	$ObjectBlock["NAME"],
   						"NAME"			=>	"Корпус ".$int,
   						"CHILDS"		=>	$this->makeStructureElems($ObjectBlock["ID"]),
   						);
   				$this->StructurePlans[$ObjectBlock["ID"]]["SCHEME"] = $this->generateBlockScheme($ObjectBlock["ID"]);
   				$int++;
   			}
   		}
   		
   	}
   	
   	
   	/**
   	 * Генерируем схему одного дома
   	 * 
   	 * @param int $SectionID
   	 */
   	private function generateBlockScheme($SectionID)
   	{
   		if (!$SectionID || !$this->StructurePlans[$SectionID] || count($this->StructurePlans[$SectionID]["CHILDS"]) <= 0)
   			return false;
   		
   		$arApartaments = array();
   		
   		$arScheme = array();

   		foreach ($this->StructurePlans[$SectionID]["CHILDS"] as $PlanElement)
   		{
   			
   			$LowLevel = intval($PlanElement["LEVEL_START"]);
   			$HighLevel = intval($PlanElement["LEVEL_END"]);
   			for ($i = intval($PlanElement["LEVEL_START"]);$i<=intval($PlanElement["LEVEL_END"]);$i++)
   			{
   			
   				//$InterSect = array_intersect($this->LevelAparts[$i], $PlanElement["ELEMS"]);
   				$InterSect = array_intersect($PlanElement["ELEMS"],$this->LevelAparts[$i]);
   				
   				

   				$arVal = array();
   				$InnerIndex = 0;
   				if ($InterSect && count($InterSect) > 0)
   				{
   					
   					foreach ($InterSect as $interSectKey=> $Case)
   					{
   						$arVal[$interSectKey] = $Case;
   						$InnerIndex++;
   					}
   				}
				
   				//$arVal = array_merge($arVal,array_fill($InnerIndex, $PlanElement["LEVEL_SIZE"] - $InnerIndex, null));
   				//my_print_r($PlanElement["LEVEL_SIZE"] - $InnerIndex);
   				$arVal = ($arVal + array_fill($InnerIndex, $PlanElement["LEVEL_SIZE"]/* - $InnerIndex*/, null));
   				
   				ksort($arVal);
   				$arScheme[$i] = $arVal;
   			}
   		}
   		return array_reverse($arScheme,true);
   	}
   	
   	/**
   	 * 
   	 * Структурируем данные из элементов планировок
   	 * 
   	 * @param int $SectionID
   	 */
   	private function makeStructureElems($SectionID)
   	{
   		if (!$SectionID || !$this->PlanElements[$SectionID] || count($this->PlanElements[$SectionID]) <= 0)
   			return false;
   		
   		$arPlans = array();
   		
   		foreach ($this->PlanElements[$SectionID] as $PlanElement)
   		{
   			
   			$arPlans[] = $this->makeStructureElem($PlanElement);
   		}
   		return $arPlans;
   	}
   	
   	private function structElementsByNum($elements,$arNum)
   	{
   		if (!$arNum || count($arNum) <= 0)
   			return array_map(array($this,'IntValPropValue'),$elements);
   		$arReturn = array();
   		foreach ($elements as $elementKey=> $Element)
   		{
   			$key = $arNum[$elementKey];
   			if ($key)
   				$arReturn[$key] = $Element;
   			else 
   				$arReturn[] = $Element;
   		}
   		return $arReturn;
   	}
   	
   	private function IntValPropValue($val)
   	{
   		return intval($val);
   	}
   	
   	/**
   	 * Структурируем данные из 1 элемента планировок
   	 * 
   	 */
   	private function makeStructureElem($elem)
   	{
   		if (!$elem)
   			return false;
   		
   		

   		return array(
   				"ID" 			=>	$elem["ID"],
   				"PARENT"		=>	$elem["IBLOCK_SECTION_ID"],
   				"LEVEL_START"	=>	intval($elem["PROPS"][self::BLOCKPLAN_CODE_LEVEL_FROM]["VALUE"]),
   				"LEVEL_END"		=>	($elem["PROPS"][self::BLOCKPLAN_CODE_LEVEL_TO]["VALUE"])? intval($elem["PROPS"][self::BLOCKPLAN_CODE_LEVEL_TO]["VALUE"]) : intval($elem["PROPS"][self::BLOCKPLAN_CODE_LEVEL_FROM]["VALUE"]),
   				"LEVEL_SIZE"	=>	intval($elem["PROPS"][self::BLOCKPLAN_CODE_PER_LEVEL]["VALUE"]),
   				"ELEMS"			=>	$this->structElementsByNum($elem["PROPS"][self::BLOCKPLAN_CODE_ELEMS]["VALUE"], $elem["PROPS"][self::PLAN_CODE_APART_NUM]["VALUE"]),
   				"IMAGE"			=>	$elem["PREVIEW_PICTURE"],//Изображения планировок
   				"TAB_NAME"		=>	$this->generateLevelTabName($elem),
   				//"ELEMS_NUM"		=>	$elem["PROPS"][self::PLAN_CODE_APART_NUM]["VALUE"],// порядковые номера
   				);
   	}
   	
   	/**
   	 * Формируем название вкладки во вкладке планы этажей
   	 * 
   	 * @param массив элемента $Item
   	 * @return string
   	 */
   	private function generateLevelTabName($Item)
   	{
   		if (
   				intval($Item["PROPS"][self::BLOCKPLAN_CODE_LEVEL_FROM]["VALUE"]) > 0 &&
   				intval($Item["PROPS"][self::BLOCKPLAN_CODE_LEVEL_TO]["VALUE"]) > 0
   				&& intval($Item["PROPS"][self::BLOCKPLAN_CODE_LEVEL_FROM]["VALUE"]) == intval($Item["PROPS"][self::BLOCKPLAN_CODE_LEVEL_TO]["VALUE"])
   			)
   		{
   			return $Item["PROPS"][self::BLOCKPLAN_CODE_LEVEL_FROM]["VALUE"]." этаж";
   		}
   		else if (intval($Item["PROPS"][self::BLOCKPLAN_CODE_LEVEL_FROM]["VALUE"]) > 0 && intval($Item["PROPS"][self::BLOCKPLAN_CODE_LEVEL_TO]["VALUE"]) > 0)
   		{
   			return $Item["PROPS"][self::BLOCKPLAN_CODE_LEVEL_FROM]["VALUE"]. " - " .$Item["PROPS"][self::BLOCKPLAN_CODE_LEVEL_TO]["VALUE"]. " этаж";
   		}
   		
   		else if (intval($Item["PROPS"][self::BLOCKPLAN_CODE_LEVEL_FROM]["VALUE"]) > 0 || intval($Item["PROPS"][self::BLOCKPLAN_CODE_LEVEL_TO]["VALUE"]) > 0)
   		{
   			$return =  ($Item["PROPS"][self::BLOCKPLAN_CODE_LEVEL_FROM]["VALUE"])? $Item["PROPS"][self::BLOCKPLAN_CODE_LEVEL_FROM]["VALUE"]: $Item["PROPS"][self::BLOCKPLAN_CODE_LEVEL_TO]["VALUE"];
   			$return.= " Этаж";
   			return $return;
   		}
   		
   	}
   	

   	
   	/**
   	 * 
   	 * загружаем элементы планировки
   	 */
   	private function loadPlanElements()
   	{
   		if (!$this->Plan)
   			return null;
   		
   		if (count($this->getPlanSection()) > 0)
   		{
   			$resEle = \CIBlockElement::GetList(array(),array("IBLOCK_ID"=>self::PLANS_IBLOCK,"SECTION_ID"=>$this->Plan,"ACITVE"=>"Y","GLOBAL_ACTIVE"=>"Y"),false,false,array("NAME","IBLOCK_ID","ID","IBLOCK_SECTION_ID","PREVIEW_PICTURE"));
   			if ($resEle->AffectedRowsCount() > 0)
   			{
   				$this->PlanElements = array();
   			}
   			while ($objItem = $resEle->GetNextElement())
   			{
   				$arItem = $objItem->GetFields();
   				$arItem["PROPS"] = $objItem->GetProperties();
   				$this->PlanElements[$arItem["IBLOCK_SECTION_ID"]][] = $arItem;
   				
   				if ($arItem["PROPS"][self::BLOCKPLAN_CODE_ELEMS]["VALUE"] && count($arItem["PROPS"][self::BLOCKPLAN_CODE_ELEMS]["VALUE"]))
   				{
   					$this->arIDsAparts = array_merge($this->arIDsAparts,$arItem["PROPS"][self::BLOCKPLAN_CODE_ELEMS]["VALUE"]);
   				}
   				
   			}
   			$this->arIDsAparts = array_unique($this->arIDsAparts);
   			unset($res,$arItem);
   		}

   		
   	}
   	
   	
   	/**
   	 * 
   	 * Загружаем квартиры учавствующие в планировке
   	 * 
   	 */
   	private function loadAparts()
   	{
   		if (count($this->arIDsAparts) > 0)
   		{
   			$resAparts = \CIBlockElement::GetList(array(),array("IBLOCK_ID"=>self::APARTS_IBLOCK,"ID"=>$this->arIDsAparts,"ACTIVE"=>"Y","GLOBAL_ACTIVE"=>"Y","!PROPERTY_IS_ACCEPTED"=>false),false,false,array("NAME","IBLOCK_ID","ID","IBLOCK_SECTION_ID","DETAIL_PAGE_URL","PROPERTY_*"));
   			while ($objItem = $resAparts->GetNextElement())
   			{
   				$arItem = $objItem->GetFields();
   				$arItem["PROPS"] = $objItem->GetProperties();
   				if ($arItem["PROPS"][self::APART_CODE_LEVEL]["VALUE"])
   				{
   					
   					if (strpos($arItem["PROPS"][self::APART_CODE_LEVEL]["VALUE"],"-") !== false)
   					{
   						$arFloors = preg_split("/[\s-]+/", $arItem["PROPS"][self::APART_CODE_LEVEL]["VALUE"]);
   						if (!is_null($arFloors[0]) && !is_null($arFloors[1]))
   							$arFloors = range($arFloors[0], $arFloors[1]);
   					}
   					else if (strpos($arItem["PROPS"][self::APART_CODE_LEVEL]["VALUE"],"/") !== false)
   					{
   						$arFloors = preg_split("/[\s\/]+/", $arItem["PROPS"][self::APART_CODE_LEVEL]["VALUE"]);
   						if (!is_null($arFloors[0]) && !is_null($arFloors[1]))
   							$arFloors = range($arFloors[0], $arFloors[1]);
   					}
   					else
						$arFloors = preg_split("/[\s,|\/|-]+/", $arItem["PROPS"][self::APART_CODE_LEVEL]["VALUE"]);
   					foreach ($arFloors as $Type)
   					{
   						$this->LevelAparts[$Type][] = intval($arItem["ID"]);
   					}
   				
   				}
   				$this->arAparts[$arItem["ID"]] = $arItem;
   				

   				//$LevelAparts
   				
   				
   			}
   			unset($ObjItem,$arItem,$resAparts);
   		}
   	
   	}
   	
   	/**
   	 * 
   	 * Загружаем массив разделов планировок
   	 */
   	private function loadPlanSection()
   	{
   		if ($this->PlanSection)
   			return null;
   		
   		$res = \CIBlockSection::GetList(array(),array("IBLOCK_ID"=>$this->PLANS_IBLOCK,"ID"=>$this->Plan),false,array("UF_*"));
   		while ($arSection = $res->GetNext())
   		{
   			$this->PlanSection[$arSection["ID"]] = $arSection;
   		}
   		
   		unset($res);
   	}
   	
   	
   	/**
   	 * 
   	 * Отдаем массив раздела планировки
   	 */
   	public function getPlanSection($ID = false)
   	{
   		
   		return ($ID)? $this->PlanSection[$ID] : $this->PlanSection;
   	}
   	
   	
   	
   	
   	/**
   	 * 
   	 * Получаем ID планировки
   	 * 
   	 */
   	private function getPlansID()
   	{
   		if (count($this->Plan) > 0 || intval($this->Newbuilding) <= 0)
   			return null;
   		
   		if (!$this->arNewbuilding)
   		{
   			$this->getNewbuildEle();
   		}
   		$this->Plan = $this->arNewbuilding["PROPS"][self::NEWBUILD_CODE_PLAN]["VALUE"];

   		return $this->Plan;
   		
   	}
   	
   	
   	
   	/**
   	 * 
   	 * Метод загрузки элемента новостройки 
   	 * 
   	 */
   	private function getNewbuildEle()
   	{
   		if (intval($this->Newbuilding) <= 0)
   			return null;
   		if (!$this->arNewbuilding)
   		{
   			$res = \CIBlockElement::GetList(array(),array("ACTIVE"=>"Y","IBLOCK_ID"=>$GLOBALS["CATALOG_NEWBUILDING"],"ID"=>$this->Newbuilding),false,false,array("NAME","ID","IBLOCK_ID"));
   			if ($objItem = $res->GetNextElement())
   			{
   				$this->arNewbuilding = $objItem->GetFields();
   				$this->arNewbuilding["PROPS"] = $objItem->GetProperties();
   			}
   			unset($res,$objItem);
   		}

   	}

	
}
?>