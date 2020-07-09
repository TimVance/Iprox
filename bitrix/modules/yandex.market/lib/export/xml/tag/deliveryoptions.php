<?php

namespace Yandex\Market\Export\Xml\Tag;

use Yandex\Market;

class DeliveryOptions extends Base
{
	public function getDefaultParameters()
	{
		return [
			'name' => 'delivery-options',
			'value_type' => Market\Type\Manager::TYPE_DELIVERY_OPTIONS
		];
	}

	public function isDefined()
	{
		return !Market\Config::isExpertMode();
	}

	public function getDefaultValue(array $context = [], $siblingsValues = null)
	{
		return !empty($context['DELIVERY_OPTIONS']['delivery']) ? $context['DELIVERY_OPTIONS']['delivery'] : null;
	}

	public function exportNode($value, array $context, \SimpleXMLElement $parent, Market\Result\XmlNode $nodeResult = null, $settings = null)
	{
		$result = $parent->addChild($this->name);

		foreach ($value as $option)
		{
			$optionNode = $result->addChild('option');

			$optionNode->addAttribute('cost', $option['COST']);
			$optionNode->addAttribute('days', $option['DAYS']);

			if (isset($option['ORDER_BEFORE']))
			{
				$optionNode->addAttribute('order-before', $option['ORDER_BEFORE']);
			}
		}

		return $result;
	}
}