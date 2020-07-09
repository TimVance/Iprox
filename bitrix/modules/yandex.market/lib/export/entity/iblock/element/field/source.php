<?php

namespace Yandex\Market\Export\Entity\Iblock\Element\Field;

use Yandex\Market;
use Bitrix\Main;

Main\Localization\Loc::loadMessages(__FILE__);

class Source extends Market\Export\Entity\Reference\Source
{
	public function getQuerySelect($select)
	{
		return [
			'ELEMENT' => $select
		];
	}

	public function isFilterable()
	{
		return true;
	}

	public function getQueryFilter($filter, $select)
	{
		return [
			'ELEMENT' => $this->buildQueryFilter($filter)
		];
	}

	public function getOrder()
	{
		return 100;
	}

	public function getElementListValues($elementList, $parentList, $select, $queryContext, $sourceValues)
	{
		$result = [];

		foreach ($elementList as $elementId => $element)
		{
			$parent = null;

			if (!isset($element['PARENT_ID'])) // is not offer
			{
				$parent = $element;
			}
			else if (isset($parentList[$element['PARENT_ID']])) // has parent element
			{
				$parent = $parentList[$element['PARENT_ID']];
			}

			if (isset($parent))
			{
				$result[$elementId] = $this->getFieldValues($parent, $select);
			}
		}

		return $result;
	}

	public function getFields(array $context = [])
	{
		return $this->buildFieldsDescription([
			'ID' => [
				'TYPE' => Market\Export\Entity\Data::TYPE_NUMBER,
                'AUTOCOMPLETE' => true,
                'AUTOCOMPLETE_FIELD' => 'NAME'
			],
			'NAME' => [
				'TYPE' => Market\Export\Entity\Data::TYPE_STRING,
                'AUTOCOMPLETE' => true,
			],
			'IBLOCK_SECTION_ID' => [
				'TYPE' => Market\Export\Entity\Data::TYPE_IBLOCK_SECTION
			],
			'CODE'=> [
				'TYPE' => Market\Export\Entity\Data::TYPE_STRING,
                'AUTOCOMPLETE' => true,
                'AUTOCOMPLETE_FIELD' => 'NAME'
			],
			'PREVIEW_PICTURE' => [
				'TYPE' => Market\Export\Entity\Data::TYPE_FILE
			],
			'PREVIEW_TEXT' => [
				'TYPE' => Market\Export\Entity\Data::TYPE_STRING
			],
			'DETAIL_PICTURE' => [
				'TYPE' => Market\Export\Entity\Data::TYPE_FILE
			],
			'DETAIL_TEXT' => [
				'TYPE' => Market\Export\Entity\Data::TYPE_STRING
			],
			'DETAIL_PAGE_URL' => [
				'TYPE' => Market\Export\Entity\Data::TYPE_URL
			],
			'DATE_CREATE' => [
				'TYPE' => Market\Export\Entity\Data::TYPE_DATE
			],
			'TIMESTAMP_X' => [
				'TYPE' => Market\Export\Entity\Data::TYPE_DATE
			],
			'XML_ID' => [
				'TYPE' => Market\Export\Entity\Data::TYPE_STRING
			]
		]);
	}

	public function getFieldEnum($field, array $context = [])
	{
		$result = null;

		switch ($field['ID'])
		{
			case 'IBLOCK_SECTION_ID':

				if (isset($context['IBLOCK_ID']) && Main\Loader::includeModule('iblock'))
				{
					$result = [];

					$queryEnum = \CIBlockSection::getList(
						[
							'LEFT_MARGIN' => 'ASC'
						],
						[
							'IBLOCK_ID' => $context['IBLOCK_ID'],
							'ACTIVE' => 'Y',
							'CHECK_PERMISSIONS' => 'N'
						],
						false,
						[
							'ID',
							'NAME',
							'DEPTH_LEVEL'
						]
					);

					while ($enum = $queryEnum->fetch())
					{
						$result[] = [
							'ID' => $enum['ID'],
							'VALUE' => str_repeat('.', $enum['DEPTH_LEVEL'] - 1) . $enum['NAME']
						];
					}
				}

			break;

		}

		if ($result === null)
		{
			$result = parent::getFieldEnum($field, $context);
		}

		return $result;
	}

	public function getFieldAutocomplete($field, $query, array $context = [])
    {
        $result = [];
        $query = (string)$query;
        $iblockId = (int)$this->getContextIblockId($context);

        if ($query !== '' && $iblockId > 0 && !empty($field['ID']))
        {
            $select = [ $field['ID'] ];
            $filter = [
                'IBLOCK_ID' => $iblockId,
                'ACTIVE' => 'Y',
                'ACTIVE_DATE' => 'Y'
            ];
            $autocompleteField = isset($field['AUTOCOMPLETE_FIELD'])
                ? $field['AUTOCOMPLETE_FIELD']
                : null;

            if ($autocompleteField !== null)
            {
                $select[] = $autocompleteField;

                $filter[] = [
                    'LOGIC' => 'OR',
                    [ '%' . $field['ID'] => $query ],
                    [ '%' . $autocompleteField => $query ],
                ];
            }
            else
            {
                $filter['%' . $field['ID']] = $query;
            }

            // query

            $queryList = \CIBlockElement::GetList(
                [],
                $filter,
                false,
                [ 'nTopCount' => 20 ],
                $select
            );

            while ($item = $queryList->Fetch())
            {
                $itemValue = isset($item[$field['ID']]) ? trim($item[$field['ID']]) : '';

                if ($itemValue !== '')
                {
                    $itemTitle = $itemValue;

                    if ($autocompleteField !== null && isset($item[$autocompleteField]))
                    {
                        $itemTitle = '[' . $itemValue . '] ' . $item[$autocompleteField];
                    }

                    $result[] = [
                        'ID' => $itemValue,
                        'VALUE' => $itemTitle
                    ];
                }
            }
        }

        return $result;
    }

    public function getFieldDisplayValue($field, $valueList, array $context = [])
    {
        $result = null;
        $iblockId = (int)$this->getContextIblockId($context);

        if ($iblockId > 0 && !empty($field['AUTOCOMPLETE_FIELD']))
        {
            $result = [];

            $queryList = \CIBlockElement::GetList(
                [],
                [
                    'IBLOCK_ID' => $iblockId,
                    '=' . $field['ID'] => $valueList
                ],
                false,
                [ 'nTopCount' => count($valueList) ],
                [
                    $field['ID'],
                    $field['AUTOCOMPLETE_FIELD']
                ]
            );

            while ($item = $queryList->Fetch())
            {
                $itemValue = isset($item[$field['ID']]) ? trim($item[$field['ID']]) : '';
                $itemTitle = isset($item[$field['AUTOCOMPLETE_FIELD']]) ? trim($item[$field['AUTOCOMPLETE_FIELD']]) : '';

                if ($itemValue !== '' && $itemTitle !== '')
                {
                    $result[] = [
                        'ID' => $itemValue,
                        'VALUE' => '[' . $itemValue . '] ' . $itemTitle
                    ];
                }
            }
        }

        return $result;
    }

    protected function buildQueryFilter($filter)
	{
		$result = [];

		foreach ($filter as $filterItem)
		{
			$compare = $filterItem['COMPARE'];
			$field = $filterItem['FIELD'];
			$value = $filterItem['VALUE'];

			if (($field === 'IBLOCK_SECTION_ID' || $field === 'SECTION_ID') && ($compare === '=' || $compare === ''))
			{
				$field = 'SECTION_ID';
				$compare = '';

				if (!empty($value))
				{
					$result['INCLUDE_SUBSECTIONS'] = 'Y';
				}
			}

			$this->pushQueryFilter($result, $compare, $field, $value);
        }

        return $result;
	}

	protected function getContextIblockId(array $context = [])
    {
        return $context['IBLOCK_ID'];
    }

	protected function getFieldValues($element, $select)
	{
		$result = [];

		foreach ($select as $fieldName)
		{
			$fieldValue = null;
			$fieldNameTilda = '~' . $fieldName;

			if (isset($element[$fieldNameTilda]))
			{
				$fieldValue = $element[$fieldNameTilda];
			}
			else if (isset($element[$fieldName]))
			{
				$fieldValue = $element[$fieldName];
			}

			if ($fieldValue !== null)
			{
				switch ($fieldName)
				{
					case 'PREVIEW_PICTURE':
					case 'DETAIL_PICTURE':
						$fieldValue = \CFile::GetPath($fieldValue);
					break;
				}
			}

			$result[$fieldName] = $fieldValue;
		}

		return $result;
	}

	protected function getLangPrefix()
	{
		return 'IBLOCK_ELEMENT_FIELD_';
	}
}
