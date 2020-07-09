<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use \Wexpert\BitrixUtils\IblockUtils as weIU;

if (! $USER->IsAuthorized()) {
	exit;
}

$arResult = array();

$errors = array();

if (intval($_REQUEST['gid']) > 0) {
	$package = weIU::selectElementsByFilterArray(
		array(), array('ID' => intval($_REQUEST['gid'])), false, false,
		array('ID', 'NAME'), true, false
	);
	$package = $package[0];

	if (! $package['ID']) {
		$errors[] = 'Не найден план.';
	} else if ((float)$GLOBALS['USER_ACCOUNT']["BUDGET"]["CURRENT_BUDGET"] < (float)$package['PROPERTIES']['price']['VALUE']) {
		$errors[] = 'Не достаточно денег на счету. <a href="/personal/">Пополнить счет.</a>';
	} else {
		$arUsP = \UserAccount::addPayedPackage($package, $GLOBALS['USER_ACCOUNT']);

		if (! $arUsP['ID']) {
			$errors[] = 'Ошибка добавления записи: ' . $arUsP['RESULT'];
		}
		if (! $arUsP['pay']['ID']) {
			$errors[] = 'Ошибка добавления записи: ' . $arUsP['pay']['RESULT'];
		}

		if ($arUsP['ID'] && $arUsP['pay']['ID']) {
			$arResult['status'] = 'OK';
			$arResult['html'] = '<strong>Пакет успешно куплен.</strong>';
		}
	}

} else {
	$errors[] = 'Ошибка, не указан пакет!';
}

if (count($errors) > 0) {
	$arResult['status'] = 'ERROR';
	$arResult['html'] = '<span style="color:red">' . implode($errors, '<br>') . '</span>';
}

echo CUtil::PhpToJSObject($arResult);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>