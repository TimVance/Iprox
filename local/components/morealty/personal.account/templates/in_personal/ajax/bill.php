<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if (! $USER->IsAuthorized()) {
	exit;
}

$arResult = array();

$errors = array();

if (floatval($_REQUEST['sum']) > 0) {
	$r = \UserAccount::addPay($GLOBALS['USER_ACCOUNT']['ID'], $_REQUEST['sum']);

	$arResult['price'] = $_REQUEST['sum'];
	$arResult['link'] = '<a target="_blank" href="/personal/?DOC=' . $r['ID'] . '">№ ' . $r['ID'] . '</a>';
	$arResult['status'] = 'OK';

	if (! $r['ID']) {
		$errors[] = 'Ошибка добавления записи: ' . $r['RESULT'];
	}
} else {
	$errors[] = 'Ошибка, не указа сумма!';
}

if (count($errors) > 0) {
	$arResult['status'] = 'ERROR';
	$arResult['html'] = '<span style="color:red">' . implode($errors, '<br>') . '</span>';
}

echo CUtil::PhpToJSObject($arResult);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>