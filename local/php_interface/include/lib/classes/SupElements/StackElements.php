<?


namespace SupElements;
use _CIBElement;

/**
 * 
 * @version 0.1
 *
 */
Class StackElements
{
	/**
	 * 
	 * 
	 * @var Element[]
	 */
	public $stack = null;
	
	private $StackClass = "Element";
	
	private $Iterator = null;
	
	
	
	public function createEmptyStack()
	{
		return new static;
	}
	
	/**
	 *
	 * @return Element|false
	 */
	public function GetNext()
	{
		
		if (is_null($this->Iterator))
			$this->Iterator = 0;
		$return = $this->__getStackElement($this->Iterator);
		if (count($this->stack) >= $this->Iterator + 1)
			$this->Iterator++;
		
		return $return;
	}
		
	/**
	 * 
	 * @param int $i
	 * @return Element|false
	 */
	private function __getStackElement(int $i)
	{
		if ($this->stack[$i])
			return $this->stack[$i];
		return false;
	}
	
	/**
	 * 
	 * Сбрасываем итератор
	 */
	public function resetIterator()
	{
		$this->Iterator = null;
	}
	/**
	 * 
	 * Колчество элементов в стаке
	 * @return number
	 */
	public function getStackCount()
	{
		return count($this->stack);
	}
	
	public function getStackSize()
	{
		return $this->getStackCount();
	}
	

	/**
	 * Добавленме элемента в класс
	 * Поддерживается 3 вида параметра
	 * Экземпляр класса Element
	 * Экземпляр класса \_CIBElement (результат $res->GetNextElement())
	 * Массив с свойствами в ключе (PROPERTIES)
	 * 
	 * @param Element|\_CIBElement|array $ele
	 */
	public function addElementToStack($ele)
	{
		if ($ele instanceof Element)
		{
			$this->__addElementToStackByElement($ele);
		}
		else if ($ele instanceof \_CIBElement)
		{
			$this->__addElementToStackByCIBEL($ele);
		}
		else if (is_array($ele))
		{
			$this->__addElementToStackByArray($ele);
		}
	}
	

	/**
	 * Создаем стак по запросу
	 * @param array $arSort
	 * @param array $arFilter
	 * @param boolean $arGroup
	 * @param boolean $arNavStartParams
	 * @param array $arSelectFields
	 * @return \SupElements\StackElements
	 */
	public static function createStack($arSort = array(),$arFilter = array(),$arGroup = false,$arNavStartParams = false, array $arSelectFields = array())
	{
		return static::createStackByDBResult(
				\CIBlockElement::GetList(
					$arSort,
					$arFilter,
					$arGroup,
					$arNavStartParams,
					$arSelectFields
				)
		);
	}
	
	/**
	 * Создаем стак элементов из запроса
	 * 
	 * @param \CIBlockResult $res
	 * @return \SupElements\StackElements
	 */
	public static function createStackByDBResult(\CIBlockResult $res)
	{
		
		$obj = new static();
		//var_dump($res->AffectedRowsCount());
		while ($ele = $res->GetNextElement())
		{
			$obj->addElementToStack($ele);
		}
		return $obj;
	}
	
	
	/**
	 * Добавляем элемент в стак по экзепляру класса Element
	 * @param Element $ele
	 */
	public function __addElementToStackByElement(Element $ele)
	{
		$this->stack[] = $ele;
	}
	
	/**
	 *
	 * Добавляем элемент в стак по массиву
	 * @param array $ele
	 */
	public function __addElementToStackByArray(array $ele)
	{
		$this->stack[] = Element::getInstanceByAr($ele);
	}
	/**
	 *
	 * Добавляем элемент в стакт по экземпляру класса \_CIBElement
	 * @param \_CIBElement $ele
	 */
	public function __addElementToStackByCIBEL(\_CIBElement $ele)
	{
		$this->stack[] = Element::getByCIBEL($ele);
	}
	
	/**
	 * Получаем колонку значений по ключу
	 * 
	 * @param string|int $Column
	 */
	public function getColumnFields($Column)
	{
		$return = array();
		foreach ($this->stack as $key=>$objItem)
		{
			$val = $objItem->getField($Column);
			if ($val)
				$return[$key] = $val;
		}
		return $return;
	}
	
	public function getFilteredStack(callable $func)
	{
		$o = $this->createEmptyStack();
		while ($Item = $this->GetNext())
		{
			if (call_user_func_array($func, array($Item)))
			{
				$o->addElementToStack($Item);
			}
			
		}
		$this->resetIterator();
		return $o;
	}
	
}