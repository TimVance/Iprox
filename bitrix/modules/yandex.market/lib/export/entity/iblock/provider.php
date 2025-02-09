<?php

namespace Yandex\Market\Export\Entity\Iblock;

use Bitrix\Main;
use Yandex\Market;

class Provider
{
	protected static $contextCache = [];

	public static function getContext($iblockId)
	{
		$iblockId = (int)$iblockId;

		if (isset(static::$contextCache[$iblockId]))
		{
			$result = static::$contextCache[$iblockId];
		}
		else
		{
			$result = static::loadContext($iblockId);

			static::$contextCache[$iblockId] = $result;
		}

		return $result;
	}

	protected static function loadContext($iblockId)
	{
		$iblockId = (int)$iblockId;
		$result = [
			'IBLOCK_ID' => $iblockId,
			'IBLOCK_NAME' => null,
			'SITE_ID' => null,
			'HAS_CATALOG' => false,
			'HAS_OFFER' => false
		];

		// load fields data

		if ($iblockId > 0 && Main\Loader::includeModule('iblock'))
		{
			$queryIblockFields = \CIBlock::GetList([], [ 'ID' => $iblockId, 'CHECK_PERMISSIONS' => 'N' ]);

			if ($iblock = $queryIblockFields->Fetch())
			{
				$result['IBLOCK_NAME'] = $iblock['NAME'];
				$result['SITE_ID'] = $iblock['LID'];
			}
		}

		// load catalog data

		if ($iblockId > 0 && Main\Loader::includeModule('catalog'))
		{
			$catalogData = \CCatalogSku::GetInfoByIBlock($iblockId);

			if (!empty($catalogData))
			{
				$result['HAS_CATALOG'] = true;
				$hasOffers = (
					!empty($catalogData['CATALOG_TYPE'])
					&& (
						$catalogData['CATALOG_TYPE'] === \CCatalogSku::TYPE_PRODUCT
						|| $catalogData['CATALOG_TYPE'] === \CCatalogSku::TYPE_FULL
					)
				);

				if ($hasOffers)
				{
					$result['HAS_OFFER'] = true;
					$result['OFFER_ONLY'] = ($catalogData['CATALOG_TYPE'] === \CCatalogSku::TYPE_PRODUCT);
					$result['OFFER_IBLOCK_ID'] = (int)$catalogData['IBLOCK_ID'];
					$result['OFFER_PROPERTY_ID'] = (int)$catalogData['SKU_PROPERTY_ID'];
				}
			}
		}

		return $result;
	}
}