<?
namespace Utilities\HL;

class HighloadStackElements

{
	
	/**
	 *
	 *
	 * @var HighloadElement[]
	 */
	public $stack = null;
	
	private $StackClass = "Element";
	
	protected $Iterator = null;
	
	
	
	public function createEmptyStack()
	{
		return new static;
	}
	
	/**
	 *
	 * @return HighloadElement|false
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
	 * @return HighloadElement|false
	 */
	protected function __getStackElement($i)
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
	 * Добавленме элемента в стак
	 *
	 * @param HighloadElement|array $ele
	 */
	public function addElementToStack($ele)
	{
		if ($ele instanceof HighloadElement)
		{
			$this->__addElementToStackByElement($ele);
		}
		else if (is_array($ele))
		{
			$this->__addElementToStackByArray($ele);
		}
	}
	
	
	
	/**
	 * Добавляем элемент в стак по экзепляру класса Element
	 * @param HighloadElement $ele
	 */
	protected function __addElementToStackByElement(HighloadElement $ele)
	{
		$this->stack[] = $ele;
	}
	
	/**
	 *
	 * Добавляем элемент в стак по массиву
	 * @param array $ele
	 */
	protected function __addElementToStackByArray(array $ele)
	{
		$this->stack[] = HighloadElement::getInstanceByAr($ele);
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
	
	
	public function sortStack(callable $func)
	{
		usort($this->stack, $func);
	}
}