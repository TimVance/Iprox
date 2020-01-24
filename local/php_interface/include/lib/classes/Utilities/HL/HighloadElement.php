<?
namespace Utilities\HL;

class HighloadElement

{
	
	protected $fields = null;
	
	protected $__fields_sup_data = null;
	
	
	
	
	function __construct($fields)
	{
		$this->fields = $fields;
	}
	
	public function getField($field)
	{
		return $this->fields[$field];
	}
	public static function getInstance(array $fields)
	{
		return new static($fields);
	}
	
	public static function getInstanceByAr($fields)
	{
		return new static($fields);
	}
}