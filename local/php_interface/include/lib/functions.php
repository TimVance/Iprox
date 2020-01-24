<?
/**
 * Функции для первого уровня. Наиболее удобные в работе.
 *
 * @version 1.0
 * @author Wexpert Framework
 */


function addWatermark($v)
{
	$Method = false;
	if (intval($v) > 0)
	{
		$FileAr = CFile::GetByID($v)->Getnext();
			if ($FileAr["HEIGHT"] > $FileAr["WIDTH"])
			{
				$Method = Cimg::M_FULL;
			}
	}

	$r = CImg::Resize($v, 1024, 768,$Method);
	return $r;
}

function AddWatermarkAndResize($v,$w,$h,$m)
{
	//return CImg::Overlay(CImg::Resize(addWatermark($v), $w, $h, $m),CImg::Resize(addWatermark($_SERVER['DOCUMENT_ROOT']."/upload/medialibrary/952/morealty_watermark_new2.png"), $w, $h),"cc");
	//return CImg::Resize(addWatermark($v), $w, $h, $m);
	if (!$m)
	{
		$m = false;
		if (intval($v) > 0)
		{
			$FileAr = CFile::GetByID($v)->Getnext();
			if ($FileAr["HEIGHT"] > $FileAr["WIDTH"])
			{
				$m = Cimg::M_FULL;
			}
			else
			{
				$m = Cimg::M_CROP;
			}
		}
	}
	
	return CImg::Overlay(CImg::Resize($v, $w, $h,$m), $_SERVER['DOCUMENT_ROOT']."/upload/medialibrary/952/iprox_watermark.png","cc");
}

function AddWaterMarkResized($v,$w,$h,$m)
{

	if (!$m)
	{
		$m = false;
		if (intval($v) > 0)
		{
			$FileAr = CFile::GetByID($v)->Getnext();
			if ($FileAr["HEIGHT"] > $FileAr["WIDTH"])
			{
				$m = Cimg::M_FULL;
			}
			else
			{
				$m = Cimg::M_CROP;
			}
		}
	}
	$WaterMarkResized = Cimg::Resize($_SERVER['DOCUMENT_ROOT']."/upload/medialibrary/952/iprox_watermark.png", $w, $h, $m);
	return CImg::Resize($v, $w, $h,$m);
	
}

function ImplodeArrguments ($glue,$param1,$param2)
{
	$i = 0;
	$arArg = array();
	foreach (func_get_args() as $param) {
		if ($i == 0)
		{
			$i++;
			continue;
		}
		$arArg[] = $param;
	}
	$arArg = array_filter($arArg, function($value) { return trim($value) !== ""; });
	return implode($glue, $arArg);
}

function MoneyOutPut($sum,$decim = 0)
{
	return number_format($sum,$decim," "," ");
}

function priceDigit($price)
{
	return MoneyOutPut($price);
	$priceLen = strlen($price);
	if ($priceLen >= 5) {
		switch ($priceLen) {
			case 5:
				$price = substr($price, 0, 2) . ' ' . substr($price, 2, 3);
				break;
			case 6:
				$price = substr($price, 0, 3) . ' ' . substr($price, 3, 3);
				break;
			case 7:
				$price = substr($price, 0, 1) . ' ' . substr($price, 1, 3) . ' ' . substr($price, 4, 3);
				break; //
			case 8:
				$price = substr($price, 0, 2) . ' ' . substr($price, 2, 3) . ' ' . substr($price, 5, 3);
				break;
			case 9:
				$price = substr($price, 0, 3) . ' ' . substr($price, 3, 3) . ' ' . substr($price, 6, 3);
				break;
			case 10:
				$price = substr($price, 0, 1) . ' ' . substr($price, 1, 3) . ' ' . substr($price, 4, 3) . ' ' . substr($price, 7, 3);
				break;
			default:
				break;
		}
	}
	return $price;
}

function getSellSections()
{
	return  \Wexpert\BitrixUtils\PhpCacher::returnCacheDataAndSave('getSellSections', 3600 * 24 * 5, function () {
		if(CModule::IncludeModule('iblock')) {
			$arSelect = Array('ID', 'NAME');
			$arFilter = Array('TYPE'=> 'catalog', 'ACTIVE'=>'Y', 'GLOBAL_ACTIVE'=>'Y');
			$res = CIBlock::GetList(Array('SORT'=>'ASC'), $arFilter, false, false, $arSelect);
			while($item = $res->GetNext()) {
				$ob[] = $item;
			}
		}

		$myarr = array();
		foreach($ob as $object) {
			$newObject = '';
			$newObject[0] = $object['NAME'];
			$newObject[1] = '/sell/' . $object['CODE'] . '/';
			$newObject[2] = Array();
			$newObject[3] = Array();
			$newObject[4] = "";
			if(!empty($object['NAME'])) {
				array_push($myarr, $newObject);
			}
		}
		return $myarr;
	});
}

function getArendSections()
{
	return  \Wexpert\BitrixUtils\PhpCacher::returnCacheDataAndSave('getArendSections', 3600 * 24 * 5, function () {
		if(CModule::IncludeModule('iblock')) {
			$arSelect = Array('ID', 'NAME');
			$arFilter = Array('TYPE'=> 'catalog', 'ACTIVE'=>'Y', 'GLOBAL_ACTIVE'=>'Y');
			$res = CIBlock::GetList(Array('SORT'=>'ASC'), $arFilter, false, false, $arSelect);
			while($item = $res->GetNext()) {
				$ob[] = $item;
			}
		}

		//формируем массив $aMenuLinks
		$myarr = array();
		foreach($ob as $object) {
			$newObject = '';
			$newObject[0] = $object['NAME'];
			$newObject[1] = '/arend/' . $object['CODE'] . '/';
			$newObject[2] = Array();
			$newObject[3] = Array();
			$newObject[4] = "";
			if(!empty($object['NAME'])) {

				$arFilter = Array('IBLOCK_ID'=>$object['ID'], 'GLOBAL_ACTIVE'=>'Y');
				$db_list = CIBlockSection::GetList(Array('SORT'=>'ASC'), $arFilter, false, false, false);
				while($ar_result = $db_list->GetNext()) {
					if(!empty($ar_result) && $ar_result['NAME'] == 'Аренда') {
						array_push($myarr, $newObject);
					}
				}

			}
		}
		return $myarr;
	});
}

function getEnding($number) {
	if ($number > 10 and $number < 15) {
		$term = "ий";
	} else {
		if ($number > 16) {
			$number = (int)substr($number, -1);
			if ($number == 0) {
				$term = "ий";
			}
			if ($number == 1) {
				$term = "ие";
			}
			if ($number > 1) {
				$term = "ия";
			}
			if ($number > 4) {
				$term = "ий";
			}
		} else {
			if ($number == 0) {
				$term = "ий";
			}
			if ($number == 1) {
				$term = "ие";
			}
			if ($number > 1) {
				$term = "ия";
			}
			if ($number > 4) {
				$term = "ий";
			}
		}
	}
	return $term;
}

function rus_date()
{
	// Перевод
	$translate = array(
		"am" => "дп",
		"pm" => "пп",
		"AM" => "ДП",
		"PM" => "ПП",
		"Monday" => "Понедельник",
		"Mon" => "Пн",
		"Tuesday" => "Вторник",
		"Tue" => "Вт",
		"Wednesday" => "Среда",
		"Wed" => "Ср",
		"Thursday" => "Четверг",
		"Thu" => "Чт",
		"Friday" => "Пятница",
		"Fri" => "Пт",
		"Saturday" => "Суббота",
		"Sat" => "Сб",
		"Sunday" => "Воскресенье",
		"Sun" => "Вс",
		"January" => "Января",
		"Jan" => "Янв",
		"February" => "Февраля",
		"Feb" => "Фев",
		"March" => "Марта",
		"Mar" => "Мар",
		"April" => "Апреля",
		"Apr" => "Апр",
		"May" => "Мая",
		"May" => "Мая",
		"June" => "Июня",
		"Jun" => "Июн",
		"July" => "Июля",
		"Jul" => "Июл",
		"August" => "Августа",
		"Aug" => "Авг",
		"September" => "Сентября",
		"Sep" => "Сен",
		"October" => "Октября",
		"Oct" => "Окт",
		"November" => "Ноября",
		"Nov" => "Ноя",
		"December" => "Декабря",
		"Dec" => "Дек",
		"st" => "ое",
		"nd" => "ое",
		"rd" => "е",
		"th" => "ое"
	);
	// если передали дату, то переводим ее
	if (func_num_args() > 1) {
		$timestamp = func_get_arg(1);
		return strtr(date(func_get_arg(0), $timestamp), $translate);
	} else {
	// иначе текущую дату
		return strtr(date(func_get_arg(0)), $translate);
	}
}

/**
 * Дампит переменную в браузер
 *
 * @param 	mixed 	$what переменная для дампа
 * @param 	bool 	$in_browser = true выводить ли результат на экран,
 * 					либо скрыть в HTML-комментарий
 * @param 	bool 	$check_admin = true проверять на админа, если вывод прямо в браузер
 * @return 	void
 *
 * @example
 * <pre>my_print_r($ar); //выведет только для админа, для всех остальных HTML-комментарий-заглушка
 * my_print_r($ar, false); //выведет всем в виде HTML-комментария
 * my_print_r($ar, true, false); //выведет всем на экран (не рекомендуется)</pre>
 */

function my_print_r($what, $in_browser = true, $check_admin = false)
{
	if ($in_browser && $check_admin && !$GLOBALS['USER']->IsAdmin()) {
		echo "<!-- my_print_r admin need! -->";
		return;
	}

	/*$backtrace = debug_backtrace();
	echo '<h4>' . $backtrace[0]["file"] . ', ' . $backtrace[0]["line"] . '</h4>';*/

	echo ($in_browser) ? "<pre>" : "<!--";
	if ( is_array($what) )  {
		print_r($what);
	} else {
		var_dump($what);
	}
	echo ($in_browser) ? "</pre>" : "-->";
}

/**
 * Удобная обертка для получения в разных местах параметров в произвольных настройкаx сайта
 *
 * Важно! Сперва необходимо установить параметры через
 * Wexpert\BitrixUtils\HiBlock::installCustomSettingsHiBlock();
 *
 * @param string $paramCode
 * @return mixed
 */
function __cs($paramCode)
{
	$params = Wexpert\BitrixUtils\HiBlock::getCustomSettingsList('CustomSettings', 0);
	return $params[ $paramCode ];
}

/**
 * Получить сущность DataManager HL-инфоблока
 *
 * @param int|string $hiBlockName числовой или символьный код HL-инфоблока
 * @param bool $staticCache = true сохранять в локальном кеше функции возвращаемые сущности
 * @return \Bitrix\Main\Entity\DataManager
 */
function __getHl($hiBlockName, $staticCache = true)
{
	static $addedBlocks;

	$hiBlockName = trim($hiBlockName);
	if ($hiBlockName == '') {
		return false;
	}

	if (! isset($addedBlocks[$hiBlockName]) || !$staticCache) {
		if (is_numeric($hiBlockName)) {
			$addedBlocks[$hiBlockName] = Wexpert\BitrixUtils\HiBlock::getDataManagerByHiId($hiBlockName);
		} else {
			$addedBlocks[$hiBlockName] = Wexpert\BitrixUtils\HiBlock::getDataManagerByHiCode($hiBlockName);
		}
	}

	return $addedBlocks[$hiBlockName];
}


/**
 * Возвращает слово с правильным суффиксом
 * @param (int) $n - количество
 * @param (array|string) $str - строка 'один|два|несколько' или 'слово|слова|слов' или массив с такойже историей
 * @version 2.0
 */
function Suffix($n, $forms)
{
	if (is_string($forms)) {
		$forms = explode('|', $forms);
	}
	if ((int)$n != $n) {
		return $forms[1];
	}

	$n = abs($n) % 100;
	$n1 = $n % 10;

	if ($n > 10 && $n < 20) {
		return $forms[2];
	}
	if ($n1 > 1 && $n1 < 5) {
		return $forms[1];
	}
	if ($n1 == 1) {
		return $forms[0];
	}
	return $forms[2];
}

if (! function_exists('Number2Word_RusLib')) {
	function Number2Word_RusLib($source, $IS_MONEY = "Y", $currency = "")
	{
		$result = '';

		$IS_MONEY = ((string)($IS_MONEY) == 'Y' ? 'Y' : 'N');
		$currency = (string)$currency;
		if ($currency == '' || $currency == 'RUR')
			$currency = 'RUB';
		if ($IS_MONEY == 'Y') {
			if ($currency != 'RUB' && $currency != 'UAH')
				return $result;
		}

		$arNumericLang = array(
			"RUB" => array(
				"zero" => "ноль",
				"1c" => "сто ",
				"2c" => "двести ",
				"3c" => "триста ",
				"4c" => "четыреста ",
				"5c" => "пятьсот ",
				"6c" => "шестьсот ",
				"7c" => "семьсот ",
				"8c" => "восемьсот ",
				"9c" => "девятьсот ",
				"1d0e" => "десять ",
				"1d1e" => "одиннадцать ",
				"1d2e" => "двенадцать ",
				"1d3e" => "тринадцать ",
				"1d4e" => "четырнадцать ",
				"1d5e" => "пятнадцать ",
				"1d6e" => "шестнадцать ",
				"1d7e" => "семнадцать ",
				"1d8e" => "восемнадцать ",
				"1d9e" => "девятнадцать ",
				"2d" => "двадцать ",
				"3d" => "тридцать ",
				"4d" => "сорок ",
				"5d" => "пятьдесят ",
				"6d" => "шестьдесят ",
				"7d" => "семьдесят ",
				"8d" => "восемьдесят ",
				"9d" => "девяносто ",
				"5e" => "пять ",
				"6e" => "шесть ",
				"7e" => "семь ",
				"8e" => "восемь ",
				"9e" => "девять ",
				"1et" => "одна тысяча ",
				"2et" => "две тысячи ",
				"3et" => "три тысячи ",
				"4et" => "четыре тысячи ",
				"1em" => "один миллион ",
				"2em" => "два миллиона ",
				"3em" => "три миллиона ",
				"4em" => "четыре миллиона ",
				"1eb" => "один миллиард ",
				"2eb" => "два миллиарда ",
				"3eb" => "три миллиарда ",
				"4eb" => "четыре миллиарда ",
				"1e." => "один рубль ",
				"2e." => "два рубля ",
				"3e." => "три рубля ",
				"4e." => "четыре рубля ",
				"1e" => "один ",
				"2e" => "два ",
				"3e" => "три ",
				"4e" => "четыре ",
				"11k" => "11 копеек",
				"12k" => "12 копеек",
				"13k" => "13 копеек",
				"14k" => "14 копеек",
				"1k" => "1 копейка",
				"2k" => "2 копейки",
				"3k" => "3 копейки",
				"4k" => "4 копейки",
				"." => "рублей ",
				"t" => "тысяч ",
				"m" => "миллионов ",
				"b" => "миллиардов ",
				"k" => " копеек",
			),
			"UAH" => array(
				"zero" => "нyль",
				"1c" => "сто ",
				"2c" => "двісті ",
				"3c" => "триста ",
				"4c" => "чотириста ",
				"5c" => "п'ятсот ",
				"6c" => "шістсот ",
				"7c" => "сімсот ",
				"8c" => "вісімсот ",
				"9c" => "дев'ятьсот ",
				"1d0e" => "десять ",
				"1d1e" => "одинадцять ",
				"1d2e" => "дванадцять ",
				"1d3e" => "тринадцять ",
				"1d4e" => "чотирнадцять ",
				"1d5e" => "п'ятнадцять ",
				"1d6e" => "шістнадцять ",
				"1d7e" => "сімнадцять ",
				"1d8e" => "вісімнадцять ",
				"1d9e" => "дев'ятнадцять ",
				"2d" => "двадцять ",
				"3d" => "тридцять ",
				"4d" => "сорок ",
				"5d" => "п'ятдесят ",
				"6d" => "шістдесят ",
				"7d" => "сімдесят ",
				"8d" => "вісімдесят ",
				"9d" => "дев'яносто ",
				"5e" => "п'ять ",
				"6e" => "шість ",
				"7e" => "сім ",
				"8e" => "вісім ",
				"9e" => "дев'ять ",
				"1e." => "один гривня ",
				"2e." => "два гривні ",
				"3e." => "три гривні ",
				"4e." => "чотири гривні ",
				"1e" => "один ",
				"2e" => "два ",
				"3e" => "три ",
				"4e" => "чотири ",
				"1et" => "одна тисяча ",
				"2et" => "дві тисячі ",
				"3et" => "три тисячі ",
				"4et" => "чотири тисячі ",
				"1em" => "один мільйон ",
				"2em" => "два мільйона ",
				"3em" => "три мільйона ",
				"4em" => "чотири мільйона ",
				"1eb" => "один мільярд ",
				"2eb" => "два мільярда ",
				"3eb" => "три мільярда ",
				"4eb" => "чотири мільярда ",
				"11k" => "11 копійок",
				"12k" => "12 копійок",
				"13k" => "13 копійок",
				"14k" => "14 копійок",
				"1k" => "1 копійка",
				"2k" => "2 копійки",
				"3k" => "3 копійки",
				"4k" => "4 копійки",
				"." => "гривень ",
				"t" => "тисяч ",
				"m" => "мільйонів ",
				"b" => "мільярдів ",
				"k" => " копійок",
			)
		);

		// k - penny
		if ($IS_MONEY == "Y") {
			$source = (string)((float)$source);
			$dotpos = strpos($source, ".");
			if ($dotpos === false) {
				$ipart = $source;
				$fpart = '';
			} else {
				$ipart = substr($source, 0, $dotpos);
				$fpart = substr($source, $dotpos + 1);
				if ($fpart === false)
					$fpart = '';
			};
			if (strlen($fpart) > 2) {
				$fpart = substr($fpart, 0, 2);
				if ($fpart === false)
					$fpart = '';
			}
			$fillLen = 2 - strlen($fpart);
			if ($fillLen > 0)
				$fpart .= str_repeat('0', $fillLen);
			unset($fillLen);
		} else {
			$ipart = (string)((int)$source);
			$fpart = '';
		}

		if (is_string($ipart)) {
			$ipart = preg_replace('/^[0]+/', '', $ipart);
		}

		$ipart1 = strrev($ipart);
		$ipart1Len = strlen($ipart1);
		$ipart = "";
		$i = 0;
		while ($i < $ipart1Len) {
			$ipart_tmp = substr($ipart1, $i, 1);
			// t - thousands; m - millions; b - billions;
			// e - units; d - scores; c - hundreds;
			if ($i % 3 == 0) {
				if ($i == 0) $ipart_tmp .= "e";
				elseif ($i == 3) $ipart_tmp .= "et";
				elseif ($i == 6) $ipart_tmp .= "em";
				elseif ($i == 9) $ipart_tmp .= "eb";
				else $ipart_tmp .= "x";
			} elseif ($i % 3 == 1) $ipart_tmp .= "d";
			elseif ($i % 3 == 2) $ipart_tmp .= "c";
			$ipart = $ipart_tmp . $ipart;
			$i++;
		}

		if ($IS_MONEY == "Y") {
			$result = $ipart . "." . $fpart . "k";
		} else {
			$result = $ipart;
			if ($result == '')
				$result = $arNumericLang[$currency]['zero'];
		}

		if (substr($result, 0, 1) == ".")
			$result = $arNumericLang[$currency]['zero'] . " " . $result;

		$result = str_replace("0c0d0et", "", $result);
		$result = str_replace("0c0d0em", "", $result);
		$result = str_replace("0c0d0eb", "", $result);

		$result = str_replace("0c", "", $result);
		$result = str_replace("1c", $arNumericLang[$currency]["1c"], $result);
		$result = str_replace("2c", $arNumericLang[$currency]["2c"], $result);
		$result = str_replace("3c", $arNumericLang[$currency]["3c"], $result);
		$result = str_replace("4c", $arNumericLang[$currency]["4c"], $result);
		$result = str_replace("5c", $arNumericLang[$currency]["5c"], $result);
		$result = str_replace("6c", $arNumericLang[$currency]["6c"], $result);
		$result = str_replace("7c", $arNumericLang[$currency]["7c"], $result);
		$result = str_replace("8c", $arNumericLang[$currency]["8c"], $result);
		$result = str_replace("9c", $arNumericLang[$currency]["9c"], $result);

		$result = str_replace("1d0e", $arNumericLang[$currency]["1d0e"], $result);
		$result = str_replace("1d1e", $arNumericLang[$currency]["1d1e"], $result);
		$result = str_replace("1d2e", $arNumericLang[$currency]["1d2e"], $result);
		$result = str_replace("1d3e", $arNumericLang[$currency]["1d3e"], $result);
		$result = str_replace("1d4e", $arNumericLang[$currency]["1d4e"], $result);
		$result = str_replace("1d5e", $arNumericLang[$currency]["1d5e"], $result);
		$result = str_replace("1d6e", $arNumericLang[$currency]["1d6e"], $result);
		$result = str_replace("1d7e", $arNumericLang[$currency]["1d7e"], $result);
		$result = str_replace("1d8e", $arNumericLang[$currency]["1d8e"], $result);
		$result = str_replace("1d9e", $arNumericLang[$currency]["1d9e"], $result);

		$result = str_replace("0d", "", $result);
		$result = str_replace("2d", $arNumericLang[$currency]["2d"], $result);
		$result = str_replace("3d", $arNumericLang[$currency]["3d"], $result);
		$result = str_replace("4d", $arNumericLang[$currency]["4d"], $result);
		$result = str_replace("5d", $arNumericLang[$currency]["5d"], $result);
		$result = str_replace("6d", $arNumericLang[$currency]["6d"], $result);
		$result = str_replace("7d", $arNumericLang[$currency]["7d"], $result);
		$result = str_replace("8d", $arNumericLang[$currency]["8d"], $result);
		$result = str_replace("9d", $arNumericLang[$currency]["9d"], $result);

		$result = str_replace("0e", "", $result);
		$result = str_replace("5e", $arNumericLang[$currency]["5e"], $result);
		$result = str_replace("6e", $arNumericLang[$currency]["6e"], $result);
		$result = str_replace("7e", $arNumericLang[$currency]["7e"], $result);
		$result = str_replace("8e", $arNumericLang[$currency]["8e"], $result);
		$result = str_replace("9e", $arNumericLang[$currency]["9e"], $result);

		$result = str_replace("1et", $arNumericLang[$currency]["1et"], $result);
		$result = str_replace("2et", $arNumericLang[$currency]["2et"], $result);
		$result = str_replace("3et", $arNumericLang[$currency]["3et"], $result);
		$result = str_replace("4et", $arNumericLang[$currency]["4et"], $result);
		$result = str_replace("1em", $arNumericLang[$currency]["1em"], $result);
		$result = str_replace("2em", $arNumericLang[$currency]["2em"], $result);
		$result = str_replace("3em", $arNumericLang[$currency]["3em"], $result);
		$result = str_replace("4em", $arNumericLang[$currency]["4em"], $result);
		$result = str_replace("1eb", $arNumericLang[$currency]["1eb"], $result);
		$result = str_replace("2eb", $arNumericLang[$currency]["2eb"], $result);
		$result = str_replace("3eb", $arNumericLang[$currency]["3eb"], $result);
		$result = str_replace("4eb", $arNumericLang[$currency]["4eb"], $result);

		if ($IS_MONEY == "Y") {
			$result = str_replace("1e.", $arNumericLang[$currency]["1e."], $result);
			$result = str_replace("2e.", $arNumericLang[$currency]["2e."], $result);
			$result = str_replace("3e.", $arNumericLang[$currency]["3e."], $result);
			$result = str_replace("4e.", $arNumericLang[$currency]["4e."], $result);
		} else {
			$result = str_replace("1e", $arNumericLang[$currency]["1e"], $result);
			$result = str_replace("2e", $arNumericLang[$currency]["2e"], $result);
			$result = str_replace("3e", $arNumericLang[$currency]["3e"], $result);
			$result = str_replace("4e", $arNumericLang[$currency]["4e"], $result);
		}

		if ($IS_MONEY == "Y") {
			$result = str_replace("11k", $arNumericLang[$currency]["11k"], $result);
			$result = str_replace("12k", $arNumericLang[$currency]["12k"], $result);
			$result = str_replace("13k", $arNumericLang[$currency]["13k"], $result);
			$result = str_replace("14k", $arNumericLang[$currency]["14k"], $result);
			$result = str_replace("1k", $arNumericLang[$currency]["1k"], $result);
			$result = str_replace("2k", $arNumericLang[$currency]["2k"], $result);
			$result = str_replace("3k", $arNumericLang[$currency]["3k"], $result);
			$result = str_replace("4k", $arNumericLang[$currency]["4k"], $result);
		}

		if ($IS_MONEY == "Y") {
			if (substr($result, 0, 1) == ".")
				$result = $arNumericLang[$currency]['zero'] . " " . $result;

			$result = str_replace(".", $arNumericLang[$currency]["."], $result);
		}

		$result = str_replace("t", $arNumericLang[$currency]["t"], $result);
		$result = str_replace("m", $arNumericLang[$currency]["m"], $result);
		$result = str_replace("b", $arNumericLang[$currency]["b"], $result);

		if ($IS_MONEY == "Y")
			$result = str_replace("k", $arNumericLang[$currency]["k"], $result);

		return (ToUpper(substr($result, 0, 1)) . substr($result, 1));
	}
}
function IsFavoriteElement($ID)
{
	global $USER;
	if ($USER->IsAuthorized())
	{
		if (intval($ID) > 0)
		{
			$res = CFavorites::GetList(array(),array("USER_ID"=>$USER->GetID(),"URL"=>intval($ID)));
			return ($res->AffectedRowsCount() > 0)? true : false;
		}
	}
	else {
		return false;
	}

}

function isMeRedactor()
{
	return CSite::InGroup(array("1","6"));
	
}

function GetIbByID($ID)
{
	return \Wexpert\BitrixUtils\PhpCacher::returnCacheDataAndSave('morealty_ib_data_'.$ID, 3600 * 24 * 5, function ($params) {
		CModule::IncludeModule('iblock');
		$res = CIBlock::GetList(array('SORT'=>'ASC'), array('TYPE'=> 'catalog','ID'=> $params["ID"]), false);
		if($ob = $res->GetNext()) {
			$arIBL = $ob;
		}
		return $arIBL;
	},array("ID"=>$ID));
}

function GetIbByCode($CODE)
{
	return \Wexpert\BitrixUtils\PhpCacher::returnCacheDataAndSave('morealty_ib_data_'.$CODE, 3600 * 24 * 5, function ($params) {
		CModule::IncludeModule('iblock');
		$res = CIBlock::GetList(array('SORT'=>'ASC'), array('TYPE'=> 'catalog','CODE'=> $params["CODE"]), false);
		if($ob = $res->GetNext()) {
			$arIBL = $ob;
		}
		return $arIBL;
	},array("CODE"=>$CODE));
}

function CheckUnInformedObjectsAdmin()
{
	$Ib_IDS = \Wexpert\BitrixUtils\PhpCacher::returnCacheDataAndSave('catalog_iblock_without_new', 3600 * 24 * 5, function () {
		CModule::IncludeModule('iblock');
		$res = CIBlock::GetList(array('SORT'=>'ASC'), array('TYPE'=> 'catalog',"!ID"=>"19"), false, false, array('ID', 'NAME'));
		while ($ob = $res->GetNext()) {
			$arID[] = $ob['ID'];
		}
		return $arID;
	});
	//IS_INFORMED_ADMIN
	/**
	 * 
	 * 
	 * Информирует админа о добавленных объектах, ждущих модерацию
	 */
	if (CModule::IncludeModule("iblock"))
	{
		$Text = "<ul>";
		$arTextElements = array();
		$newEle = new CIBlockElement;
		$res = CIBlockElement::GetList(array(),array("IBLOCK_ID"=>$Ib_IDS,"PROPERTY_IS_ACCEPTED"=>false,"PROPERTY_IS_INFORMED_ADMIN"=>false),false,false,array("ID","IBLOCK_ID","CREATED_BY","ACTIVE","DETAIL_PAGE_URL","NAME"));
		
		while ($arItem = $res->GetNext())
		{
			$newEle->SetPropertyValuesEx($arItem["ID"], $arItem["IBLOCK_ID"], array("IS_INFORMED_ADMIN"=>"Y"));
			$arFields["NAME"] = $arItem["NAME"];
			if ($arItem["CREATED_BY"])
			{
				$rsUser = CUser::GetByID($arItem["CREATED_BY"]);
				if ($arUser = $rsUser->GetNext())
				{
					$arFields["USER"] = ($arUser["NAME"] || $arUser["LAST_NAME"])? implode(" ", array($arUser["NAME"],$arUser["LAST_NAME"])): $arUser["LOGIN"];
				}
			}
			$linkTemplate = "//morealty.dev.mnwb.com/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=#IB_ID#&type=catalog&ID=#ELE_ID#&lang=ru&find_section_section=-1&WF=Y";
			$arFields["LINK"] = str_replace("#ELE_ID#",$arItem["ID"],str_replace("#IB_ID#", $arItem["IBLOCK_ID"], $linkTemplate));
			$arTextElements[] = $arFields;
		}
		foreach ($arTextElements as $curField)
		{
			$Text.= "<li>";
			$Text.='<a href="'.$curField["LINK"].'">'.$curField["NAME"]."</a> ";
			if($curField["USER"])
			{
				$Text.=" Пользователь: ".$curField["USER"];
			}
			$Text.="</li>";
		}
		$Text.="</ul>";
		if ($res->AffectedRowsCount() > 0)
		{
			CEvent::Send("ADDED_OBJECT", SITE_ID, array("TEXT"=>$Text));
		}
		
		
	}
	return "CheckUnInformedObjectsAdmin();";
}


function CheckUnInformedObjects()
{
	$Ib_IDS = \Wexpert\BitrixUtils\PhpCacher::returnCacheDataAndSave('catalog_iblock_without_new', 3600 * 24 * 5, function () {
		CModule::IncludeModule('iblock');
		$res = CIBlock::GetList(array('SORT'=>'ASC'), array('TYPE'=> 'catalog',"!ID"=>"19"), false, false, array('ID', 'NAME'));
		while ($ob = $res->GetNext()) {
			$arID[] = $ob['ID'];
		}
		return $arID;
	});
	if (CModule::IncludeModule("iblock"))
	{
		
		$newEle = new CIBlockElement;
		$res = CIBlockElement::GetList(array(),array("IBLOCK_ID"=>$Ib_IDS,"!PROPERTY_IS_ACCEPTED"=>false,"PROPERTY_IS_INFORMED"=>false),false,false,array("ID","IBLOCK_ID","CREATED_BY","ACTIVE","DETAIL_PAGE_URL","NAME"));
		while ($arItem = $res->GetNext())
		{
			
			$newEle->SetPropertyValuesEx($arItem["ID"], $arItem["IBLOCK_ID"], array("IS_INFORMED"=>"Y"));
			$arFields = array();
			if ($arItem["CREATED_BY"])
			{
				$rsUser = CUser::GetByID($arItem["CREATED_BY"]);
				if ($arUser = $rsUser->GetNext())
				{
					if ($arUser["EMAIL"])
					{
						$arFields["MAIL"] = $arUser["EMAIL"];
					}
					else {
						continue;
					}
					$arFields["USER"] = ($arUser["NAME"] || $arUser["LAST_NAME"])? implode(" ", array($arUser["NAME"],$arUser["LAST_NAME"])): $arUser["LOGIN"];
				}
			}
			$arFields["NAME"] = '"'.$arItem["NAME"].'"';
			$arFields["LINK"] = SITE_SERVER_NAME.$arItem["DETAIL_PAGE_URL"];
			//CEvent::Send("OBJECT_MODERATED", SITE_ID, $arFields);
		}
		
	}
	return "CheckUnInformedObjects();";
}

/**
 * Возвращает ID раздела Аренды в инфоблоке
 * @param int $IBLOCK_ID 
 */


function GetArendSectionAtIB ($IBLOCK_ID)
{
	return  \Wexpert\BitrixUtils\PhpCacher::returnCacheDataAndSave('getArendSection_'.intval($IBLOCK_ID), 3600 * 24 * 5, function ($params) {
		if(CModule::IncludeModule('iblock')) {
			$arSelect = Array('ID', 'NAME');
			$arFilter = Array('TYPE'=> 'catalog', 'ACTIVE'=>'Y', 'GLOBAL_ACTIVE'=>'Y');
			$res = CIBlockSection::GetList(array(),array("NAME"=>"Аренда","DEPTH_LEVEL"=>1,"IBLOCK_ID"=>$params["IBLOCK_ID"]),false,array("ID","NAME"));
			$return = 0;
			if ($arSection = $res->GetNext())
			{
				$return = $arSection["ID"];
			}
		}
		return $return;
	},array("IBLOCK_ID"=>$IBLOCK_ID));
}
function GetSellSectionAtIB ($IBLOCK_ID)
{
	return  \Wexpert\BitrixUtils\PhpCacher::returnCacheDataAndSave('getSellSection_'.intval($IBLOCK_ID), 3600 * 24 * 5, function ($params) {
		if(CModule::IncludeModule('iblock')) {
			$arSelect = Array('ID', 'NAME');
			$arFilter = Array('TYPE'=> 'catalog', 'ACTIVE'=>'Y', 'GLOBAL_ACTIVE'=>'Y');
			$res = CIBlockSection::GetList(array(),array("NAME"=>"Продажа","DEPTH_LEVEL"=>1,"IBLOCK_ID"=>$params["IBLOCK_ID"]),false,array("ID","NAME"));
			$return = 0;
			if ($arSection = $res->GetNext())
			{
				$return = $arSection["ID"];
			}
		}
		return $return;
	},array("IBLOCK_ID"=>$IBLOCK_ID));
}

function LogOutUserById ($USER_ID)
{
	global $USER;
	$USER_ID = intval($USER_ID);
	if ($USER_ID > 0)
	{
		$USER->Update($USER_ID, array("UF_LOGOUTUSER"=>true));
	}
}

function percentFromNumber($number,$percent)
{
	return $number/100*$percent;
}


function propsToORFilter($arProps,$itemProps,$Settings)
{
	$return = array();
	
	$arPropFilter = array();
	$ANDFilter = array();
	foreach ($arProps as $Prop)
	{
		$arProp = $itemProps[$Prop];
		if ($arProps)
		{
			
			if (!$arProp["VALUE"])
				continue;
			
			$prefix = "";
			$type = $arProp["TYPE"];
			
			if (!in_array($Prop, $Settings["PRICE"]) && !in_array($Prop, $Settings["SQUARE"]))
				$val = ($type == "L")? $arProp["VALUE_ENUM_ID"] : $arProp["VALUE"];
			else
			{
				$isSquare = in_array($Prop, $Settings["SQUARE"]);
				$isPrice = in_array($Prop, $Settings["PRICE"]);
				if ($isSquare)
				{
					$minPercent = 90;
					$maxPercent = 110;
				}
				else if ($isPrice)
				{
					$minPercent = 90;
					$maxPercent = 110;
				}
				$arProp["VALUE"] = floatval(str_replace(" ", "", $arProp["VALUE"]));
				$prefix = "><";
				$val = array(intval(percentFromNumber($arProp["VALUE"], $minPercent)),intval(percentFromNumber($arProp["VALUE"], $maxPercent)));
			}
			
			if (!in_array($Prop,$Settings["MAIN"]))
				$arPropFilter[$prefix."PROPERTY_$Prop"] = $val;
			else 
			{
				$ANDFilter[$prefix."PROPERTY_$Prop"] = $val;
			}
			
			
			
		}
	}
	if ($arPropFilter && count($arPropFilter) > 0)
	{
		$return = array(array_merge(array("LOGIC"=>"OR"),$arPropFilter));
	}
	if ($ANDFilter && count($ANDFilter) > 0)
	{
		$return = array_merge($return,$ANDFilter);
	}
	return $return;
	
}