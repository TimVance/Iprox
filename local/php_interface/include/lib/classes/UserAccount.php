<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use \Wexpert\BitrixUtils\IblockUtils as weIU;

class UserAccount
{
	const IBLOCK_ID_ACCOUNT = 23;
	const IBLOCK_ID_USER_PLANS = 26;

	/**
	 * Возвращает счет пользоватля. Создает, если его не было.
	 * 
	 * @param $userId
	 * @return array|bool|\Wexpert\BitrixUtils\multitype
	 */
	public static function getAccount($userId)
	{
		$userId = (int)$userId;
		if ($userId <= 0) {
			return false;
		}

		$acc = weIU::selectSectionsByFilterArray(
			array(),
			array('IBLOCK_ID' => self::IBLOCK_ID_ACCOUNT, 'UF_USER_ID' => $userId),
			false, array('ID', 'NAME', 'IBLOCK_ID', 'UF_*')
		);
		$acc = $acc[0];
		if (! $acc['ID']) {
			$arAddF = array(
				'ACTIVE'        => 'Y',
				'NAME'          => $userId,
				'UF_USER_ID'    => $userId,
				'IBLOCK_ID'     => self::IBLOCK_ID_ACCOUNT
			);
			$res = weIU::addSectionToDb($arAddF);
			if (is_numeric($res['RESULT'])) {
				$arAddF['ID'] = $res['RESULT'];
				$acc = $arAddF;
				$acc['SUMM'] = 0;
			}
		} else {
			$acc['SUMM'] = 0;
			$acc['PAYS'] = weIU::selectElementsByFilterArray(
				array('ID' => 'DESC'),
				array('SECTION_ID' => (int)$acc['ID'], 'IBLOCK_ID' => self::IBLOCK_ID_ACCOUNT, 'ACTIVE' => 'Y'),
				false, false, array('ID', 'IBLOCK_ID', 'NAME', 'TIMESTAMP_X', 'PROPERTY_USER_PACKET.NAME'), true, false
			);

			foreach ($acc['PAYS'] as $pay) {
				if ($pay['PROPERTIES']['STATUS']['VALUE'] == 387) {
					$acc['SUMM'] += $pay['PROPERTIES']['SUMM']['VALUE'];
				} else if ($pay['PROPERTIES']['STATUS']['VALUE'] == 390) {
					$acc['SUMM'] -= $pay['PROPERTIES']['SUMM']['VALUE'];
				}
			}

			$planAllowedCnts = self::getAllowedCountsByPlan($userId);
			$exists = self::getCountsObjectInCatalog($userId, array_keys($planAllowedCnts));
			foreach ($exists as $iblock => $cnt) {
				$planAllowedCnts[$iblock] -= $cnt;
			}

			$acc['userAllowedCounts'] = $planAllowedCnts;
			$acc['userAddedCounts'] = $exists;
		}

		return $acc;
	}

	/**
	 * Добавить движение по счету
	 *
	 * @param $accId
	 * @param $sum
	 * @param int $status 386 - не оплачен, 387 - зачислен, 390 - расход
	 * @param int $packedID = false пакет
	 * @param int $payType = 391 тип оплаты, 391 - счет
	 * @return mixed|null
	 */
	public static function addPay($accId, $sum, $status = 386, $packedID = false, $payType = 391)
	{
		$arD = array(
			'IBLOCK_ID' => self::IBLOCK_ID_ACCOUNT,
			'ACTIVE' => 'Y',
			'NAME'  => randString(),
			'IBLOCK_SECTION_ID' => (int) $accId,
			'PROPERTY_VALUES' => array(
				'SUMM'      => (float) $sum,
				'STATUS'    => $status,
				'USER_PACKET' => $packedID,
				'TYPE' => $payType
			),
		);

		$r = weIU::addElementToDb($arD);

		$arD['STATUS'] = $r['STATUS'];
		$arD['RESULT'] = $r['RESULT'];

		if (is_numeric($r['RESULT'])) {
			$arD['ID'] = $r['RESULT'];
		} else {
			$arD['ID'] = false;
		}

		return $arD;
	}

	public static function getBillFields($userId)
	{
		$userId = (int)$userId;
		if ($userId <= 0) {
			return false;
		}
		$rs = \CUser::GetList($by, $order, array('ID' => $userId), array('SELECT' => array('UF_*'), 'FIELDS' => array('ID')));
		return $rs->Fetch();
	}

	/**
	 * Заполнены ли реквизиты под оплату?
	 *
	 * @param $userId
	 * @return bool
	 */
	public static function isBillFiledsFilled($userId)
	{
		$f = self::getBillFields($userId);
		$r = array(
			'UF_FULL_NAME',
			'UF_LEGAL_ADDRESS',
			'UF_OGRN',
			'UF_INN',
			'UF_KPP',
			'UF_DIRECTOR_FULL_NAM',
			'UF_CHECKING_ACCOUNT',
			'UF_BANK_NAME',
			'UF_COR_ACCOUNT',
			'UF_BIC'
		);
		foreach ($r as $k) {
			if (trim($f[$k]) == '') {
				return false;
			}
		}
		return true;
	}

	/**
	 * Добавить пакет пользователя
	 *
	 * @param array $package
	 * @param array $account
	 * @return array
	 */
	public static function addPayedPackage($package, $account)
	{
		// добавить пакет пользователя
		$dt = new \DateTime('now');
		$from = ConvertTimeStamp($dt->format('U'), 'FULL');
		$dt->modify('+' . (int)$package['PROPERTIES']['long_month']['VALUE'] . 'months');
		$to = ConvertTimeStamp($dt->format('U'), 'FULL');

		$arUsP = array(
			'IBLOCK_ID'     => self::IBLOCK_ID_USER_PLANS,
			'NAME'          => $package['NAME'],
			'DATE_ACTIVE_FROM'  => $from,
			'DATE_ACTIVE_TO'  => $to,
			'PROPERTY_VALUES' => array(
				'owner'      => $account['UF_USER_ID'],
				'package'   => $package['ID']
			)
		);

		$r = weIU::addElementToDb($arUsP);
		if (is_numeric($r['RESULT'])) {
			$arUsP['ID'] = $r['RESULT'];
		} else {
			$arUsP['ID'] = false;
		}

		// добавить расход за этот пакет
		if ($arUsP['ID']) {
			$arUsP['pay'] = self::addPay($account['ID'], $package['PROPERTIES']['price']['VALUE'], 390, $arUsP['ID'], false);

			if (! $arUsP['pay']['ID']) {
				$el = new \CIBlockElement();
				$el->Delete($arUsP['ID']);
			}
		}

		return $arUsP;
	}

	/**
	 * Получить кол-ва разрешенных объявлений для каждого типа каталога
	 * @param $userId
	 * @return array
	 */
	static public function getAllowedCountsByPlan($userId)
	{
		$userAllowedCounts = array();

		$activePackages = weIU::selectElementsByFilterArray(
			array('ID' => 'DESC'),
			array('ACTIVE_DAYE' => 'Y', 'IBLOCK_ID' => self::IBLOCK_ID_USER_PLANS, 'ACTIVE' => 'Y', 'PROPERTY_owner' => $userId),
			false, false, array('ID', 'PROPERTY_package')
		);
		$packIds = array();
		foreach ($activePackages as $p) {
			$packIds[] = $p['PROPERTY_PACKAGE_VALUE'];
		}
		$packIds = array_filter($packIds);
		if (count($packIds) > 0) {
			$activePackages = weIU::selectElementsByFilterArray(
				array('ID' => 'DESC'),
				array('ID' => $packIds), false, false, array('ID', 'IBLOCK_ID'), true
			);
			foreach ($activePackages as $p) {
				foreach ($p['PROPERTIES'] as $k => $v) {
					if (strpos($k, 'count_') === false || (int)$v <= 0) {
						continue;
					}
					$iblock = substr($k, strlen('count_'));
					if (! isset($userAllowedCounts[$iblock])) {
						$userAllowedCounts[$iblock] = 0;
					}
					$userAllowedCounts[$iblock] += $v;
				}
			}
		}
		return $userAllowedCounts;
	}

	static public function getCountsObjectInCatalog($userId, $iblockIds)
	{
		$cnt = array();

		foreach ($iblockIds as $ib) {
			$cnt[$ib] = weIU::selectElementsByFilterArray(array(), array('IBLOCK_ID' => $ib, 'CREATED_BY' => $userId), array());
		}

		return $cnt;
	}

}

?>