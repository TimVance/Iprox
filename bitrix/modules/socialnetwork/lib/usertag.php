<?php
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage socialnetwork
 * @copyright 2001-2019 Bitrix
 */
namespace Bitrix\Socialnetwork;

use Bitrix\Main\Entity;
use Bitrix\Main\NotImplementedException;


/**
 * Class UserTagTable
 *
 * Fields:
 * <ul>
 * <li> USER_ID int mandatory
 * <li> USER reference to {@link \Bitrix\Main\UserTable}
 * <li> NAME varchar mandatory
 * </ul>
 *
 * @package Bitrix\Socialnetwork
 */
class UserTagTable extends Entity\DataManager
{
	public static function getTableName()
	{
		return 'b_sonet_user_tag';
	}

	public static function getMap()
	{
		$fieldsMap = array(
			'USER_ID' => array(
				'data_type' => 'integer',
				'primary' => true
			),
			'USER' => array(
				'data_type' => 'Bitrix\Main\UserTable',
				'reference' => array('=this.USER_ID' => 'ref.ID'),
			),
			'NAME' => array(
				'data_type' => 'string',
				'primary' => true,
				'save_data_modification' => function() {
					return array(
						function ($text)
						{
							return ToLower($text);
						}
					);
				},
				'fetch_data_modification' => function() {
					return array(
						function ($text)
						{
							return ToLower($text);
						}
					);
				}
			)
		);

		return $fieldsMap;
	}

	public static function add(array $data)
	{
		try
		{
			$result = parent::add($data);
		}
		catch(\Exception $e)
		{
			$result = false;
		}

		return $result;
	}

	public static function getUserTagCountData($params = array())
	{
		$connection = \Bitrix\Main\HttpApplication::getConnection();

		$result = array();

		$userId = (
			!empty($params['userId'])
				? intval($params['userId'])
				: false
		);

		$tagName = (
			(is_array($params['tagName']) && !empty($params['tagName']))
			|| (!is_array($params['tagName']) && strlen($params['tagName']) > 0)
				? $params['tagName']
				: false
		);

		$nameFilter = (
			$tagName !== false
				? (is_array($tagName)) ? " IN (".implode(',', array_map(function($val) { return "'".$val."'"; }, $tagName)).")" : " = '".$tagName."'"
				: ($userId ? ' IN (SELECT NAME FROM '.self::getTableName().' WHERE USER_ID = '.$userId.')' : '')
		);

		$whereClause = (!empty($nameFilter) ? 'WHERE NAME '.$nameFilter : '');

		$sql = 'SELECT COUNT(USER_ID) AS CNT, NAME FROM '.self::getTableName().' '.$whereClause.' GROUP BY NAME';

		$res = $connection->query($sql);
		while ($tagData = $res->fetch())
		{
			$result[$tagData['NAME']] = $tagData['CNT'];
		}

		return $result;
	}

	public static function getUserTagTopData($params = array())
	{
		global $USER, $DB;

		$result = array();

		$userId = (
			!empty($params['userId'])
				? intval($params['userId'])
				: false
		);

		$topCount = (
			isset($params['topCount'])
				? intval($params['topCount'])
				: 0
		);

		if ($topCount <= 0)
		{
			$topCount = 2;
		}

		if ($topCount > 5)
		{
			$topCount = 5;
		}

		$avatarSize = (
			isset($params['avatarSize'])
				? intval($params['avatarSize'])
				: 100
		);

		$connection = \Bitrix\Main\Application::getConnection();
		$connection->queryExecute('SET @user_rank = 0');
		$connection->queryExecute('SET @current_entity_id = 0');

		$tagName = (
			(is_array($params['tagName']) && !empty($params['tagName']))
			|| (!is_array($params['tagName']) && strlen($params['tagName']) > 0)
				? $params['tagName']
				: false
		);

		$nameFilter = (
			$tagName !== false
				? (is_array($tagName)) ? " NAME IN (".implode(',', array_map(function($val) use ($DB) { return "'".$DB->forSql($val)."'"; }, $tagName)).")" : " NAME = '".$tagName."'"
				: ($userId ? " USER_ID = ".$userId : "")
		);

		$whereClause = (!empty($nameFilter) ? 'WHERE '.$nameFilter : '');

		$tagsSql = 'SELECT NAME FROM '.self::getTableName().' '.$whereClause;

		if (\Bitrix\Main\ModuleManager::isModuleInstalled('intranet'))
		{
			$ratingId = \CRatings::getAuthorityRating();
			if (intval($ratingId) <= 0)
			{
				return $result;
			}

			$res = $connection->query("SELECT
				@user_rank := IF(
					@current_name = tmp.NAME,
					@user_rank + 1,
					1
				) as USER_RANK,
				@current_name := tmp.NAME,
				tmp.USER_ID as USER_ID,
				tmp.NAME as NAME,
				tmp.WEIGHT as WEIGHT
			FROM (
				SELECT
					@rownum := @rownum + 1 as ROWNUM,
					RS1.ENTITY_ID as USER_ID,
					UT1.NAME as NAME,
					MAX(RS1.VOTES) as WEIGHT
				FROM
					b_rating_subordinate RS1,
					".self::getTableName()." UT1
				WHERE
					RS1.RATING_ID = ".intval($ratingId)."
					AND RS1.ENTITY_ID = UT1.USER_ID
					AND UT1.NAME IN (".$tagsSql.")
				GROUP BY
					UT1.NAME, RS1.ENTITY_ID
				ORDER BY
					UT1.NAME,
					WEIGHT DESC
			) tmp");
		}
		else
		{
			$res = $connection->query("SELECT
				@user_rank := IF(
					@current_name = tmp.NAME,
					@user_rank + 1,
					1
				) as USER_RANK,
				tmp.USER_ID as USER_ID,
				tmp.NAME as NAME,
				1 as WEIGHT
			FROM (
				SELECT
					@rownum := @rownum + 1 as ROWNUM,
					UT1.USER_ID as USER_ID,
					UT1.NAME as NAME
				FROM
					".self::getTableName()." UT1
				WHERE
					UT1.NAME IN (".$tagsSql.")
				ORDER BY
					UT1.NAME
			) tmp");
		}

		$userWeightData = $tagUserData = array();

		$currentTagName = false;
		$hasMine = false;

		while ($resultFields = $res->fetch())
		{
			if (
				!$hasMine
				&& $resultFields['USER_ID'] == $USER->getId()
			)
			{
				$hasMine = true;
			}

			if ($resultFields['NAME'] != $currentTagName)
			{
				$cnt = 0;
				$hasMine = false;
				$tagUserData[$resultFields['NAME']] = array();
			}

			$currentTagName = $resultFields['NAME'];
			$cnt++;

			if ($cnt > ($hasMine ? $topCount+1 : $topCount))
			{
				continue;
			}

			$tagUserData[$resultFields['NAME']][] = $resultFields['USER_ID'];
			if (!isset($userWeightData[$resultFields['USER_ID']]))
			{
				$userWeightData[$resultFields['USER_ID']] = floatval($resultFields['WEIGHT']);
			}
		}

		$userData = \Bitrix\Socialnetwork\Item\UserTag::getUserData([
			'userIdList' => array_keys($userWeightData),
			'avatarSize' => $avatarSize
		]);

		foreach($tagUserData as $tagName => $userIdList)
		{
			$result[$tagName] = array();

			foreach($userIdList as $userId)
			{
				$result[$tagName][] = array(
					'ID' => $userId,
					'NAME_FORMATTED' => $userData[$userId]['NAME_FORMATTED'],
					'PERSONAL_PHOTO' => $userData[$userId]['PERSONAL_PHOTO']['ID'],
					'PERSONAL_PHOTO_SRC' => $userData[$userId]['PERSONAL_PHOTO']['SRC'],
					'PERSONAL_GENDER' => $userData[$userId]['PERSONAL_GENDER'],
					'WEIGHT' => $userWeightData[$userId]
				);
			}
		}

		foreach($result as $tagName => $data)
		{
			usort(
				$data,
				function($a, $b)
				{
					if ($a['WEIGHT'] == $b['WEIGHT'])
					{
						return 0;
					}
					return ($a['WEIGHT'] > $b['WEIGHT']) ? -1 : 1;
				}
			);
			$result[$tagName] = $data;
		}

		return $result;
	}

	public static function update($primary, array $data)
	{
		throw new NotImplementedException("Use add() method of the class.");
	}
}
