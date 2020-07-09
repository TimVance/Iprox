<?
/**
 * Файл с различными глобальными рубильниками-константами
 */

/**
 * На сайте бета-тестировщик
 * @var bool
 */
define('IS_BETA_TESTER', $USER->IsAdmin());

/**
 * ID формы вывода запроса авторизации
 * @var string
 */
define('AUTHORIZE_FORM_ID', 'pop12');

/**
 * Приоритетная секция для раздела боттом-меню Агентства/застройщики/риелторы
 * @var string
 */
define('MAIN_SECTION', trim(__cs('TYPE_AGENTS_RAJDEL')));

$arID = \Wexpert\BitrixUtils\PhpCacher::returnCacheDataAndSave('catalog_iblock_ids', 3600 * 24 * 5, function () {
	CModule::IncludeModule('iblock');
	$res = CIBlock::GetList(array('SORT'=>'ASC'), array('TYPE'=> 'catalog', 'ACTIVE'=>'Y', 'GLOBAL_ACTIVE'=>'Y'), false, false, array('ID', 'NAME'));
	while ($ob = $res->GetNext()) {
		$arID[] = $ob['ID'];
	}
	return $arID;
});


/**
 * Список инфоблоков каталогов
 * @var string
 */
define('CATALOG_IBLOCKS', implode(',', $arID));

/**
 * Список инфоблоков каталогов
 * @var array
 */
$GLOBALS['CATALOG_IBLOCKS_ARRAY'] = $arID;


/**
 * 
 * 
 * ID ИБ новостроек
 * @var array
 * 
 */
$GLOBALS["CATALOG_NEWBUILDING"] = array(19);
/**
 * 
 * 
 */
$arIDwithoutNew = \Wexpert\BitrixUtils\PhpCacher::returnCacheDataAndSave('catalog_iblock_without_new', 3600 * 24 * 5, function () {
	CModule::IncludeModule('iblock');
	$res = CIBlock::GetList(array('SORT'=>'ASC'), array('TYPE'=> 'catalog',"!ID"=>"19"), false, false, array('ID', 'NAME'));
	while ($ob = $res->GetNext()) {
		$arID[] = $ob['ID'];
	}
	return $arID;
});
$GLOBALS['CATALOG_IB_IDS'] = $arIDwithoutNew;


/**
 * ID текущего раздела
 * @var int
 */
if(!empty($_REQUEST['catalog'])) {
	CModule::IncludeModule('iblock');
	$catalogType = $_REQUEST['catalog'];
	$res = CIBlock::GetList(array('SORT'=>'ASC'), array('TYPE'=> 'catalog', 'CODE' => $catalogType), false, array('ID', 'NAME', 'IBLOCK_ID'));
	if ($ob = $res->GetNext()) {
		$GLOBALS['IBLOCK_ID'] = $ob['ID'];
	}
}

/**
 * На сайте владелец недвижимости?
 * @var int
 */
define('IS_USER_OWNER_ONE_REALTY', \CSite::InGroup(array(8)));
$GLOBALS["OwnerGroups"] = array(8,7);
/**
 * На сайте риэлтор
 * @var int
 */
define('IS_USER_REALTOR', \CSite::InGroup(array(7, 6)) || $USER->IsAdmin());
$GLOBALS["RealtorGroups"] = array(6);

/**
 * ID группы застройщиков
 * 
 * @var int
 */
define("GROUP_ZASTROY", 14);
/**
 * Конфигурация посетителя - счета и оплаты, возможности
 */
if (constant('IS_USER_OWNER_ONE_REALTY') === true) {
}

if (constant('IS_USER_REALTOR') === true) {
	$GLOBALS['USER_ACCOUNT'] = \UserAccount::getAccount( $USER->GetID() );

    CModule::IncludeModule('sale');
    if ($arUsBUDGET = CSaleUserAccount::GetByUserID($USER->GetID(), "RUB")) {
        $GLOBALS['USER_ACCOUNT']["BUDGET"] = $arUsBUDGET;
    }
}


if (!CustomUsers::isUserActive())
{
	define("USER_NOT_ACTIVE", true);
}