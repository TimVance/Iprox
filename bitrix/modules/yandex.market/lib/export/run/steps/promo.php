<?php

namespace Yandex\Market\Export\Run\Steps;

use Bitrix\Main;
use Yandex\Market;

class Promo extends Base
{
    public function getName()
    {
        return Market\Export\Run\Manager::STEP_PROMO;
    }

    public function run($action, $offset = null)
    {
        $result = new Market\Result\Step();
        $setup = $this->getSetup();
        $context = $setup->getContext();
        $promoCollection = $setup->getPromoCollection();
        $promoCount = count($promoCollection);
        $promoIndex = 0;
        $readyCount = 0;
        $tagDescriptionList = null;
        $sourceValueList = [];
        $elementList = [];
        $flushLimit = $this->getFlushLimit();
        $promoChangedMap = null;

        $this->setRunAction($action);

        $result->setTotal($promoCount);
        $result->setTotalCount($promoCount);

        if ($action === 'change')
        {
            $changes = $this->getChanges();
            $isOnlyPromoChanged = false;
			$promoChangedMap = [];

            if (isset($changes[Market\Export\Run\Manager::ENTITY_TYPE_PROMO])) // has only changes in promo
            {
				$isOnlyPromoChanged = (count($changes) === 1);

                $promoChangedMap += array_flip($changes[Market\Export\Run\Manager::ENTITY_TYPE_PROMO]);
            }

            if (!$isOnlyPromoChanged)
            {
                $promoChangedMap += $this->getStorageInvalidList($context);
            }
        }

        /** @var Market\Export\Promo\Model $promo */
        foreach ($promoCollection as $promo)
        {
            $promoId = $promo->getInternalId();

            if ($offset !== null && $offset > $promoIndex)
            {
                // is out of offset
            }
            else if ($promoChangedMap !== null && !isset($promoChangedMap[$promoId]))
            {
                // is not changed
            }
            else if (!$promo->isActive() || !$promo->isActiveDate()) // is inactive
            {
                // nothing
            }
            else
            {
                if ($tagDescriptionList === null)
                {
                    $tagDescriptionList = $promo->getTagDescriptionList();
                }

                $promoFields = $promo->getPromoFields();
                $exportPromoProductList = $this->getExportPromoProductList($promo, $context);
                $exportPromoGiftList = null;

                if ($promo->isSupportGift())
                {
                    $exportPromoGiftList = $this->getExportPromoGiftList($promo, $context);
                }

                $elementList[$promoId] = $promoFields;

                $sourceValueList[$promoId] = [
                    'TYPE' => $promo->getPromoType(),
                    'PROMO' => $promoFields,
                    'PRODUCT' => [
                        'CONTENTS' => implode('', $exportPromoProductList)
                    ],
                    'GIFT' => [
                        'CONTENTS' => $exportPromoGiftList !== null ? implode('', $exportPromoGiftList) : null
                    ]
                ];
            }

            $promoIndex++;
            $readyCount++;

            $isTimeExpired = $this->getProcessor()->isTimeExpired();

            if (!empty($sourceValueList) && ($isTimeExpired || count($sourceValueList) >= $flushLimit))
            {
                $tagValuesList = $this->buildTagValuesList($tagDescriptionList, $sourceValueList, $context);
                $this->writeData($tagValuesList, $elementList, $context);

                $sourceValueList = [];
                $elementList = [];

                if ($isTimeExpired)
                {
                    $result->setOffset($readyCount);
                    break;
                }
            }
        }

        if (!empty($sourceValueList))
        {
            $tagValuesList = $this->buildTagValuesList($tagDescriptionList, $sourceValueList, $context);
            $this->writeData($tagValuesList, $elementList, $context);
        }

        $result->setProgress($readyCount);
        $result->setReadyCount($readyCount);

        return $result;
    }

    public function isTypedTag()
    {
        return true;
    }

    public function getFormatTag(Market\Export\Xml\Format\Reference\Base $format, $type = null)
    {
        return $format->getPromo($type);
    }

    public function getFormatTagParentName(Market\Export\Xml\Format\Reference\Base $format)
    {
        return $format->getPromoParentName();
    }

    protected function getDataLogEntityType()
    {
        return Market\Logger\Table::ENTITY_TYPE_EXPORT_RUN_PROMO;
    }

    protected function getStorageDataClass()
    {
        return Market\Export\Run\Storage\PromoTable::getClassName();
    }

    protected function getStorageRuntime()
    {
        return [
            new Main\Entity\ReferenceField('EXPORT_PROMO_PRODUCT', Market\Export\Run\Storage\PromoProductTable::getClassName(), [
                '=this.SETUP_ID' => 'ref.SETUP_ID',
                '=this.ELEMENT_ID' => 'ref.PROMO_ID'
            ]),
            new Main\Entity\ReferenceField('EXPORT_PROMO_GIFT', Market\Export\Run\Storage\PromoGiftTable::getClassName(), [
                '=this.SETUP_ID' => 'ref.SETUP_ID',
                '=this.ELEMENT_ID' => 'ref.PROMO_ID'
            ]),
        ];
    }

    protected function getStorageChangesFilter($changes, $context)
    {
        $isNeedCheckProduct = false;
        $result = [];

        if (!empty($changes))
        {
            $ignoredTypeList = $this->getIgnoredTypeChanges();

            foreach ($changes as $changeType => $entityIds)
            {
                if (isset($ignoredTypeList[$changeType])) { continue; }

                switch ($changeType)
                {
                    case Market\Export\Run\Manager::ENTITY_TYPE_PROMO:
                        $result[] = [
                            '=ELEMENT_ID' => $entityIds
                        ];
                    break;

                    default:
                        $isNeedCheckProduct = true;
                    break;
                }
            }
        }

        if ($isNeedCheckProduct)
        {
            $result[] = [
                '>=EXPORT_PROMO_PRODUCT.TIMESTAMP_X' => $this->getParameter('initTime')
            ];

            $result[] = [
                '>=EXPORT_PROMO_GIFT.TIMESTAMP_X' => $this->getParameter('initTime')
            ];
        }

        if (empty($result))
        {
            $result = null;
        }
        else if (count($result) > 1)
        {
            $result['LOGIC'] = 'OR';
        }

        return $result;
    }

    protected function getQueryExcludeFilterPrimary($queryContext)
    {
        return 0; // equal for all
    }

    protected function getStorageInvalidList($context)
    {
        $dataClass = $this->getStorageDataClass();
        $result = [];

        if ($dataClass)
        {
            $query = $dataClass::getList([
                'filter' => [
                    '=SETUP_ID' => $context['SETUP_ID'],
                    '=STATUS' => static::STORAGE_STATUS_INVALID
                ],
                'select' => [
                    'ELEMENT_ID'
                ]
            ]);

            while ($exportPromo = $query->fetch())
            {
                $result[$exportPromo['ELEMENT_ID']] = $exportPromo;
            }
        }
        return $result;
    }

    /**
     * �������� ����������� ����������� ������� ��� promo
     *
     * @param Market\Export\Promo\Model $promo
     * @param $context
     *
     * @return array
     */
    protected function getExportPromoProductList(Market\Export\Promo\Model $promo, $context)
    {
        $result = [];

        $queryExportProductList = Market\Export\Run\Storage\PromoProductTable::getList([
            'filter' => [
                '=SETUP_ID' => $context['SETUP_ID'],
                '=PROMO_ID' => $promo->getId(),
                '=STATUS' => static::STORAGE_STATUS_SUCCESS
            ],
            'select' => [
                'ELEMENT_ID',
                'CONTENTS'
            ]
        ]);

        while ($exportProduct = $queryExportProductList->fetch())
        {
            $result[$exportProduct['ELEMENT_ID']] = $exportProduct['CONTENTS'];
        }

        return $result;
    }

    /**
     * �������� ���������� ����������� �������� ��� promo
     *
     * @param Market\Export\Promo\Model $promo
     * @param $context
     *
     * @return array
     *
     * @throws Main\ArgumentException
     * @throws Main\SystemException
     */
    protected function getExportPromoGiftList(Market\Export\Promo\Model $promo, $context)
    {
        $result = [];

        $queryExportProductList = Market\Export\Run\Storage\PromoGiftTable::getList([
            'filter' => [
                '=SETUP_ID' => $context['SETUP_ID'],
                '=PROMO_ID' => $promo->getId(),
                '=STATUS' => static::STORAGE_STATUS_SUCCESS,
                [
                    'LOGIC' => 'OR',
                    [ '=ELEMENT_TYPE' => Market\Export\PromoGift\Table::PROMO_GIFT_TYPE_OFFER ],
                    [
                        '=ELEMENT_TYPE' => Market\Export\PromoGift\Table::PROMO_GIFT_TYPE_GIFT,
                        '=GIFT_EXPORT.STATUS' => static::STORAGE_STATUS_SUCCESS
                    ]
                ]
            ],
            'select' => [
                'ELEMENT_ID',
                'CONTENTS'
            ],
            'runtime' => [
                new Main\Entity\ReferenceField('GIFT_EXPORT', Market\Export\Run\Storage\GiftTable::getClassName(), [
                    '=this.SETUP_ID' => 'ref.SETUP_ID',
                    '=this.ELEMENT_ID' => 'ref.ELEMENT_ID'
                ])
            ]
        ]);

        while ($exportProduct = $queryExportProductList->fetch())
        {
            $result[$exportProduct['ELEMENT_ID']] = $exportProduct['CONTENTS'];
        }

        return $result;
    }

    /**
     * ���������� ������� promo, ����� ������� ���������� ��������� ������ � ����
     *
     * @return int
     */
    protected function getFlushLimit()
    {
        return (int)($this->getParameter('promoPageSize') ?: Market\Config::getOption('export_run_promo_page_size') ?: 20);
    }

    protected function isAllowDeleteParent()
    {
        return true;
    }

    protected function isAllowPublicDelete()
    {
        return true;
    }
}