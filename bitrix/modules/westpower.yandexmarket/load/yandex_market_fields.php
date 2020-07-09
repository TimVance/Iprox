<?php
	
	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
	
	IncludeModuleLangFile(__FILE__);
	
	$YandexMarketFields = array(
		array(
			"NAME" => "SETUP_PROFILE_NAME",
			"TITLE" => GetMessage("WESTPOWER_YMF_0"),
			"STEP" => 0,
			"FIELD" => array("TYPE" => "text"),
		),
		array(
			"NAME" => "BASIC_SETTINGS_SERVER_NAME",
			"TITLE" => GetMessage("WESTPOWER_YMF_1"),
			"STEP" => 0,
			"FIELD" => array("TYPE" => "text","DEFAULT" => $_SERVER["HTTP_HOST"]),
		),
		array(
			"NAME" => "BASIC_SETTINGS_NAME_SHOP",
			"TITLE" => GetMessage("WESTPOWER_YMF_2"),
			"STEP" => 0,
			"FIELD" => array("TYPE" => "text","DEFAULT" => $_SERVER["HTTP_HOST"]),
		),
		array(
			"NAME" => "BASIC_SETTINGS_NAME_COMPANY",
			"TITLE" => GetMessage("WESTPOWER_YMF_3"),
			"STEP" => 0,
			"FIELD" => array("TYPE" => "text","DEFAULT" => $_SERVER["HTTP_HOST"]),
		),
		array(
			"NAME" => "BASIC_SETTINGS_IS_HTTPS",
			"TITLE" => GetMessage("WESTPOWER_YMF_4"),
			"STEP" => 0,
			"FIELD" => array("TYPE" => "checkbox","VALUE" => array(array("VALUE" => "Y"))),
		),
		array(
			"NAME" => "BASIC_SETTINGS_UNLOAD_ONCE_DAY",
			"TITLE" => GetMessage("WESTPOWER_YMF_5"),
			"STEP" => 0,
			"FIELD" => array("TYPE" => "checkbox","VALUE" => array(array("VALUE" => "Y"))),
		),
		array(
			"NAME" => "BASIC_SETTINGS_MAX_ELM",
			"TITLE" => GetMessage("WESTPOWER_YMF_6"),
			"STEP" => 0,
			"FIELD" => array("TYPE" => "text","DEFAULT" => 200),
		),
		array(
			"NAME" => "BASIC_SETTINGS_FILE_NAME",
			"TITLE" => GetMessage("WESTPOWER_YMF_7"),
			"STEP" => 0,
			"FIELD" => array("TYPE" => "text","DEFAULT" => "yandex_".mt_rand(0, 999999).".php"),
		),
		array(
			"NAME" => "CATALOG_COMBINE_PRODUCT_NAMES_AND_OFFERS",
			"TITLE" => GetMessage("WESTPOWER_YMF_8"),
			"STEP" => 1,
			"FIELD" => array("TYPE" => "checkbox","VALUE" => array(array("VALUE" => "Y"))),
		),
		array(
			"NAME" => "CATALOG_IBLOCK_ID",
			"TITLE" => GetMessage("WESTPOWER_YMF_9"),
			"STEP" => 1,
			"FIELD" => array("TYPE" => "select","VALUE" => array())
		),
		array(
			"NAME" => "CATALOG_IBLOCK_SECTION_ID",
			"TITLE" => GetMessage("WESTPOWER_YMF_10"),
			"STEP" => 1,
			"FIELD" => array("TYPE" => "select","MULTIPLE" => true,"SIZE" => 20,"VALUE" => array())
		),
		array(
			"NAME" => "PROPERTIES_MODEL",
			"TITLE" => GetMessage("WESTPOWER_YMF_18"),
			"STEP" => 2,
			"FIELD" => array("TYPE" => "select","VALUE" => array()),
		),
		array(
			"NAME" => "PROPERTIES_VENDOR",
			"TITLE" => GetMessage("WESTPOWER_YMF_11"),
			"STEP" => 2,
			"FIELD" => array("TYPE" => "select","VALUE" => array()),
		),
		array(
			"NAME" => "PROPERTIES_VENDOR_CODE",
			"TITLE" => GetMessage("WESTPOWER_YMF_12"),
			"STEP" => 2,
			"FIELD" => array("TYPE" => "select","VALUE" => array()),
		),
		array(
			"NAME" => "PROPERTIES_PARAM",
			"TITLE" => GetMessage("WESTPOWER_YMF_13"),
			"STEP" => 2,
			"FIELD" => array("TYPE" => "select","VALUE" => array(),"SIZE" => 15,"MULTIPLE" => true),
		),
		array(
			"NAME" => "PRICES_PRICE_TYPE",
			"TITLE" => GetMessage("WESTPOWER_YMF_14"),
			"STEP" => 3,
			"FIELD" => array("TYPE" => "select","VALUE" => array(),"MULTIPLE" => true),
		),
		array(
			"NAME" => "PRICES_CURRENCY",
			"TITLE" => GetMessage("WESTPOWER_YMF_15"),
			"STEP" => 3,
			"FIELD" => array("TYPE" => "select","VALUE" => array()),
		),
		array(
			"NAME" => "INVENTORY_CONTROL_ACTUAL_BALANCES",
			"TITLE" => GetMessage("WESTPOWER_YMF_16"),
			"STEP" => 4,
			"FIELD" => array("TYPE" => "checkbox","VALUE" => array(array("VALUE" => "Y"))),
		),
		array(
			"NAME" => "INVENTORY_CONTROL_WAREHOUSES",
			"TITLE" => GetMessage("WESTPOWER_YMF_17"),
			"STEP" => 4,
			"FIELD" => array("TYPE" => "select","SIZE" => 10,"MULTIPLE" => true,"VALUE" => array()),
		)
	);
?>