<?


namespace MorealtyEntities\Stacks;

use \MorealtyEntities\Flat;
use \SupElements\StackElements;
use _CIBElement;
/**
 *
 *
 * Расширение StackElements для акций
 *
 */
Class FlatStack extends StackElements
{
	
	/**
	 *
	 * 
	 * @var Flat[]
	 */
	public $stack = null;
	
	
	private $StackClass = "Flat";
	
	
	
	
	
	
	
	
	
	

	
	
	/**
	 * Добавляем элемент в стак по экзепляру класса Element
	 * @param Flat $ele
	 */
	public function __addElementToStackByElement(Flat $ele)
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
		
		$this->stack[] = Flat::getInstanceByAr($ele);
		
	}
	/**
	 *
	 * Добавляем элемент в стакт по экземпляру класса \_CIBElement
	 * @param \_CIBElement $ele
	 */
	public function __addElementToStackByCIBEL(\_CIBElement $ele)
	{
		$this->stack[] = Flat::getByCIBEL($ele);
	}
	
	

	
	
}