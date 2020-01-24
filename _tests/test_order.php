<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");


\Bitrix\main\Loader::includeModule("sale") && \Bitrix\main\Loader::includeModule("catalog");

$res = \CSaleOrder::GetList(
		array(),
		array(
				"ID" => 277,
				"PAYED"	=> "Y",
				"STATUS_ID" => "ST",
				"PROPERTY_VAL_BY_CODE_PACKET"	=> "329"
				
		),
		false,
		false,
		array("*","BASKET_PRODUCT_ID")
);
if ($arProp = $res->GetNext())
{
	my_print_r($arProp);
}