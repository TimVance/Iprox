<? 
namespace SiteTemplates;

/**
 * 
 * Класс для шустрой работы с риелторами
 * 
 * @author Wexpert
 *
 */
class Realtor 
{
	/**
	 * Ид застройщика по которому работаем
	 * 
	 * @var int
	 */
	private $RealtorId;
	
	
	public function getInstance($ID)
	{
		return (intval($ID) > 0)? new self($ID) : false;
	}
	
	private function __construct($ID)
	{
		$this->RealtorId = intval($ID);
	}
	
	
	/**
	 * Выводим короткую инфу о риелторе
	 * 
	 */
	public function shortInfo($params = false)
	{
		global $APPLICATION;
		$componentParams = Array(
		"ID"           => $this->RealtorId,
		"GET_PROPERTY" => "Y",
		"CACHE_GROUPS" => "Y",
		"CACHE_TYPE"   => "Y",
		"CACHE_TIME"    => 3600, //вернуть
	);
	if ($params && is_array($params))
	{
		$componentParams = array_merge($componentParams,$params);
	}
	$APPLICATION->IncludeComponent("morealty:realtors.detail", "short_realtor", $componentParams);
	}
	
	/**
	 * Имя и телефон риелтора
	 * 
	 * @param array $params
	 */
	public function supershortInfo($params = false)
	{
		global $APPLICATION;
		$componentParams = Array(
				"ID"           => $this->RealtorId,
				"GET_PROPERTY" => "Y",
				"CACHE_GROUPS" => "Y",
				"CACHE_TYPE"   => "Y",
				"CACHE_TIME"    => 3600, //вернуть
		);
		if ($params && is_array($params))
		{
			$componentParams = array_merge($componentParams,$params);
		}
		$APPLICATION->IncludeComponent("morealty:realtors.detail", "super_short_realtor", $componentParams);
	}
}
?>