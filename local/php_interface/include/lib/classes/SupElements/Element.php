<?

namespace SupElements;

use _CIBElement;

/**
 * 
 * Класс для работы с элементами
 * @version 0.1
 */
Class Element {
	
	/**
	 * Поля элемента
	 * 
	 * @var array
	 */
	private $fields = array();
	
	/**
	 * Свойства
	 * 
	 * @var array
	 */
	private $props	= array();
	
	
	public static function getInstanceByAr(array $ele)
	{
		return new static($ele);
	}
	
	public static function getInstanceByID($ID)
	{
		if (!\CModule::IncludeModule("iblock"))
			return false;
		$res = \CIBlockElement::GetList(array(),array("ID"=>intval($ID)));
		if ($obj = $res->GetNextElement())
		{
			return self::getByGetNextElement($obj);
		}
		return false;
	}
	
	
	
	public static function getByGetNextElement(_CIBElement $ele)
	{
		return ($ele)? new static($ele) : false;
	}
	
	public static function getByCIBEL(_CIBElement $ele)
	{
		return ($ele)? new static($ele) : false;
	}
	
	
	function __construct($ele)
	{
		if (is_array($ele))
		{
			$fields = $ele;
			unset($fields["PROPERTIES"]);
			$this->fields = $fields;
			$this->props = $ele["PROPERTIES"];
		}
		else if ($ele instanceof \_CIBElement )
		{
			$this->fields = $ele->GetFields();
			$this->props = $ele->GetProperties();
		}
		$this->__afterConstruct();
	}
	
	/**
	 * 
	 * 
	 */
	function __afterConstruct()
	{
		
	}
	
	
	
	
	public function updateElement(array $Fields)
	{
		if (!\CModule::IncludeModule("iblock"))
			return false;
		
		$ele = new \CIBlockElement;
		$ele->Update($this->getField("ID"), $Fields);
	}
	
	/**
	 * Обновляем свойства элемента
	 * 
	 * @param array $arProps
	 * @return boolean on False
	 */
	public function updateElementProps(array $arProps)
	{
		if (!\CModule::IncludeModule("iblock"))
			return false;
		
		$ele = new \CIBlockElement;
		$ele->SetPropertyValuesEx($this->getField("ID"), $this->getField("IBLOCK_ID"), $arProps);
	}
	
	
	/**
	 * Добавляем во множественное свойство
	 * 
	 * @param string $prop
	 * @param array|string|int $value
	 */
	public function addToMultipleProp($prop,$value)
	{
		if ($value)
		{
			if (!is_array($value))
			{
				$value = array($value);
			}
		}
		$prevVal = $value;

		$CurValues = ($this->getPropType($prop) == "L") ? $this->getPropValueID($prop) : $this->getPropValue($prop);
		/**
		 * 
		 * @todo Привязка в файлам множественная.
		 */
		$value = array_unique(array_merge($value,(array)$CurValues));
		$this->updateElementProps(array(
				$prop => $value
		));
	}
	
	
	/**
	 * Удаляем из свойства значения
	 * 
	 * @param string $prop
	 * @param array|string|int $value
	 */
	public function removeFromFultipleProp($prop,$value)
	{
		if ($value)
		{
			if (!is_array($value))
			{
				$value = array($value);
			}
		}
		$CurValues = ($this->getPropType($prop) == "L") ? $this->getPropValueID($prop) : $this->getPropValue($prop);
		/**
		 *
		 * @todo Привязка в файлам множественная.
		 */
		$value = array_unique(array_diff((array)$CurValues,$value));
		if (!$value || count($value) <= 0)
			$value = false;
		$this->updateElementProps(array(
				$prop => $value
		));
	}
	
	public function isElementActive()
	{
		return $this->getField("ACTIVE") == "Y";
	}
	
	
	
	
	public final function getField($Field)
	{
		return $this->fields[$Field];
	}
	public final function getPropValue($Prop,$tilda = false)
	{
		return $this->props[$Prop][(!$tilda)? "VALUE" : "~VALUE"];
	}
	public final function getPropAr($Prop)
	{
		return $this->props[$Prop];
	}
	public final function getPropValueID($Prop)
	{
		return $this->props[$Prop]["PROPERTY_VALUE_ID"];
	}
	public final function getPropEnumID($Prop)
	{
		return $this->props[$Prop]["VALUE_ENUM_ID"];
	}
	public final function getPropDescr($Prop)
	{
		return $this->props[$Prop]["DESCRIPTION"];
	}
	public final function getPropType($Prop)
	{
		return $this->props[$Prop]["TYPE"];
	}
	
	public final function getID()
	{
		return $this->getField("ID");
	}
	public final function getName()
	{
		return $this->getField("NAME");
	}
	public final function getSort()
	{
		return $this->getField("SORT");
	}
	public final function getTextProp($Prop)
	{
		return $this->getPropValue($Prop,true)["TEXT"];
	}
	
}