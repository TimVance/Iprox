<?
namespace Utilities\HL;

use Bitrix\Highloadblock\HighloadBlockTable as HighLoader;

abstract class Highload

{

	
	/**
	 * 
	 * Получаем класс хайлоада
	 * @return string
	 */
	abstract static function getEntityClass();
	
	/**
	 * Получаем ид таблицы
	 * 
	 * @return int
	 */
	abstract static function getTableID();
	
	
	
	/**
	 * Получаем таблицу
	 *
	 * @return \Bitrix\Main\DB\Result
	 */
	public static function getTable()
	{
		\Bitrix\Main\Loader::includeModule("highloadblock");
		return HighLoader::getById(static::getTableID())->fetch();
	}
	
	
	/**
	 * Получаем скомпилированную таблицу
	 *
	 * @return \Bitrix\Main\Entity\Base
	 */
	public static function getCompiledTable()
	{
		\Bitrix\Main\Loader::includeModule("highloadblock");
		return HighLoader::compileEntity(static::getTable());
	}
	
	
	/**
	 *
	 * Получаем дата менеджет таблицы
	 *
	 * @return \Bitrix\Main\Entity\DataManager
	 */
	public static function getDataObject()
	{
		return static::getCompiledTable()->getDataClass();
	}
	
	
	
	public static function addElement($fields)
	{
		$DM = static::getDataObject();
		
		return $DM::add($fields);
	}
	
	public static function updateElement($id,$fields)
	{
		$DM = static::getDataObject();
		return $DM::update($id, $fields);
	}
	
	public static function removeElement($id)
	{
		$DM = static::getDataObject();
		return $DM::delete($id);
	}
}