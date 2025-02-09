<?php

namespace Yandex\Market\Export\PromoGift;

use Yandex\Market;

class Model extends Market\Export\PromoProduct\Model
{
	/**
	 * �������� ������ �������
	 *
	 * @return Table
	 */
	public static function getDataClass()
	{
		return Table::getClassName();
	}

	public function getTagDescriptionList()
	{
		$result = [
			[
				'TAG' => 'promo-gift',
				'VALUE' => null,
				'ATTRIBUTES' => [
					'offer-id' => [
						'TYPE' => Market\Export\Entity\Manager::TYPE_IBLOCK_OFFER_FIELD,
						'FIELD' => 'ID'
					],
					'gift-id' => [
						'TYPE' => Market\Export\Entity\Manager::TYPE_IBLOCK_OFFER_FIELD,
						'FIELD' => 'ID'
					]
				],
				'SETTINGS' => null
			]
		];

		return $result;
	}

	public function getSourceSelect()
    {
        $context = $this->getContext(true);
        $fieldSource = (
            $context['HAS_OFFER']
                ? Market\Export\Entity\Manager::TYPE_IBLOCK_OFFER_FIELD
                : Market\Export\Entity\Manager::TYPE_IBLOCK_ELEMENT_FIELD
        );

        return [
            $fieldSource => [
                'NAME',
                'PREVIEW_PICTURE',
                'DETAIL_PICTURE'
            ]
        ];
    }

    public function getContext($isOnlySelf = false)
    {
        $result = parent::getContext($isOnlySelf);

        $result['EXPORT_GIFT'] = $this->isExportExternalGift($result);

        return $result;
    }

    /**
     * ��������� ������, ������� �� ������ � ��������
     *
     * @param $context
     *
     * @return bool
     */
    protected function isExportExternalGift(array $context)
    {
        if ($this->discount !== null)
        {
            $result = (bool)$this->discount->isExportExternalGift($context);
        }
        else
        {
            $result = ((string)$this->getField('EXPORT_GIFT') !== Table::BOOLEAN_N);
        }

        return $result;
    }

    protected function getDiscountProductFilterList($context)
    {
        $result = [];

        if ($this->discount !== null)
        {
            $result = $this->discount->getGiftFilterList($context);
        }

        return $result;
    }
}