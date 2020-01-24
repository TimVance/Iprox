<?
namespace Utilities\Sale;

class Order
{
	
	/**
	 *
	 *
	 * @param \Bitrix\Sale\PropertyValueCollection $propertyCollection
	 * @param string $code
	 * @return \Bitrix\Sale\PropertyValue
	 */
	public static function getPropertyByCode(\Bitrix\Sale\PropertyValueCollection $propertyCollection, $code)  {
		foreach ($propertyCollection as $property)
		{
			if($property->getField('CODE') == $code)
				return $property;
		}
	}
}