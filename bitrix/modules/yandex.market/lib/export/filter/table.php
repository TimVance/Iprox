<?php

namespace Yandex\Market\Export\Filter;

use Bitrix\Main;
use Yandex\Market;

class Table extends Market\Reference\Storage\Table
{
	const ENTITY_TYPE_IBLOCK_LINK = 'iblock_link';
	const ENTITY_TYPE_PROMO_PRODUCT = 'promo_product';
	const ENTITY_TYPE_PROMO_GIFT = 'promo_gift';

	public static function getTableName()
	{
		return 'yamarket_export_filter';
	}

	public static function createIndexes(Main\DB\Connection $connection)
	{
		$tableName = static::getTableName();

		$connection->createIndex($tableName, 'IX_' . $tableName . '_1', [ 'ENTITY_TYPE', 'ENTITY_ID' ]);
	}

	public static function getUfId()
	{
		return 'YAMARKET_EXPORT_FILTER';
	}

	public static function getMap()
	{
		return [
			new Main\Entity\IntegerField('ID', [
				'autocomplete' => true,
				'primary' => true
			]),
			new Main\Entity\StringField('NAME'),
			new Main\Entity\IntegerField('SORT', [
				'default_value' => 500
			]),
			new Main\Entity\StringField('SALES_NOTES'),
			new Main\Entity\EnumField('ENTITY_TYPE', [
				'default_value' => static::ENTITY_TYPE_IBLOCK_LINK,
				'values' => [
					static::ENTITY_TYPE_IBLOCK_LINK,
					static::ENTITY_TYPE_PROMO_PRODUCT,
					static::ENTITY_TYPE_PROMO_GIFT,
				]
			]),
			new Main\Entity\IntegerField('ENTITY_ID', [
				'required' => true
			]),

			new Main\Entity\ReferenceField('IBLOCK_LINK', Market\Export\IblockLink\Table::getClassName(), [
				'=this.ENTITY_TYPE' => [ '?', static::ENTITY_TYPE_IBLOCK_LINK ],
				'=this.ENTITY_ID' => 'ref.ID',
			]),

			new Main\Entity\ReferenceField('PROMO_PRODUCT', Market\Export\Promo\Table::getClassName(), [
				'=this.ENTITY_TYPE' => [ '?', static::ENTITY_TYPE_PROMO_PRODUCT ],
				'=this.ENTITY_ID' => 'ref.ID',
			]),

			new Main\Entity\ReferenceField('PROMO_GIFT', Market\Export\Promo\Table::getClassName(), [
				'=this.ENTITY_TYPE' => [ '?', static::ENTITY_TYPE_PROMO_GIFT ],
				'=this.ENTITY_ID' => 'ref.ID',
			]),
		];
	}

	public static function getReference($primary = null)
	{
		return [
			'FILTER_CONDITION' => [
				'TABLE' => Market\Export\FilterCondition\Table::getClassName(),
				'LINK_FIELD' => 'FILTER_ID',
				'LINK' => [
					'FILTER_ID' => $primary
				]
			],
			'DELIVERY' => [
				'TABLE' => Market\Export\Delivery\Table::getClassName(),
				'LINK_FIELD' => 'ENTITY_ID',
				'LINK' => [
					'ENTITY_TYPE' => Market\Export\Delivery\Table::ENTITY_TYPE_FILTER,
					'ENTITY_ID' => $primary
				]
			]
		];
	}
}