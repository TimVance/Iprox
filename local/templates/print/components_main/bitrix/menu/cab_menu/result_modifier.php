<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (constant('IS_USER_OWNER_ONE_REALTY') === true) {

	for ($i = 0, $il = count($arResult); $i < $il; $i++) {
		if (in_array($arResult[$i]['LINK'], array('/personal/', '/personal/service/', '/personal/requisites/'))) {
			unset($arResult[$i]);
		}
	}
	
}


?>