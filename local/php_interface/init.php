<?
/**
 * Отправная точка входа WE_FRAMEWORK для битрикс
 *
 * @version 1.X
 * @author (c) www.wexpert.ru, 2016
 */

// уже написанный функционал на проекте
if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/bitrix/php_interface/init.php')) {
	require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/php_interface/init.php';
}

// автозагрузка классов
require __DIR__ . '/include/lib/simple_loader.php';

// плавающие функции
require __DIR__ . '/include/lib/functions.php';

// добавление обработчиков (без определения)
require __DIR__ . '/include/lib/handlers_add.php';

require __DIR__ . '/include/lib/classes/webitrixauth.php';

if (Bitrix\Main\Loader::includeModule('artamonov.api')) (new Artamonov\Api\Init)->start();