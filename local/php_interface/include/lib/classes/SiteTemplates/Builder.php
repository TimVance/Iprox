<? 
namespace SiteTemplates;

/**
 * 
 * Класс для шустрой работы с застройщиками
 * 
 * @author Wexpert
 *
 */
class Builder 
{
	/**
	 * Ид застройщика по которому работаем
	 * 
	 * @var int
	 */
	private $BuilderId;
	
	
	public function getInstance($ID)
	{
		return (intval($ID) > 0)? new self($ID) : false;
	}
	
	private function __construct($ID)
	{
		$this->BuilderId = intval($ID);
	}
	
	
	/**
	 * Выводим короткую инфу о застройщике
	 * 
	 */
	public function shortInfo($params = false)
	{
		global $APPLICATION;
		$componentParams = Array(
		"ID"           => $this->BuilderId,
		"SELECT"       => array('SHOW_COUNTER'),
		"GET_PROPERTY" => "Y",
		"CACHE_GROUPS" => "Y",
		"CACHE_TYPE"   => "Y",
		"CACHE_TIME"    => 3600, //вернуть
		"SHOW_PROPERTY" => array(
			"class", "type", "floors", "ceilings_height",
			"decoration", "parking", "distance_to_sea", "lift", "square_ot", "square_do",
			"price_flat_min", "price_m2_ot", "price_m2_do", "deadline"),
		"SHOW_EMPTY_PROPERTY" => 'Y',
		"SET_404"		=> "N",
		"INCLUDE_SEO"	=> "N",
	);
	if ($params && is_array($params))
	{
		$componentParams = array_merge($componentParams,$params);
	}
	$APPLICATION->IncludeComponent("wexpert:iblock.detail", "builder_element", $componentParams);
	}
}
?>