<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if (!\Bitrix\Main\Loader::includeModule("iblock"))
	die();

	
$newEle = new CIBlockElement;

$data = json_decode(file_get_contents("data.json"), true);
my_print_r($data);

foreach ($data as $Element)
{
	$newEle->SetPropertyValuesEx($Element["ID"], 19, array(
			"price_flat_min" => $Element["PRICE"]
	));
}