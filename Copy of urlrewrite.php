<?
$arUrlRewrite = array(
	array(
		"CONDITION" => "#^/personal/favorites/([0-9a-zA-Z_-]+)/?\\??#",
		"RULE" => "catalog=\$1&ID=\$2&",
		"ID" => "",
		"PATH" => "/personal/favorites/index.php",
	),
	array(
		"CONDITION" => "#^/online/([\\.\\-0-9a-zA-Z]+)(/?)([^/]*)#",
		"RULE" => "alias=\$1",
		"ID" => "",
		"PATH" => "/desktop_app/router.php",
	),
	array(
		"CONDITION" => "#^/newbuildings/([\\d]+)/questions/\\??#",
		"RULE" => "ID=\$1&PAGE=questions&",
		"ID" => "",
		"PATH" => "/newbuildings/detail.php",
	),
	array(
		"CONDITION" => "#^/newbuildings/([\\d]+)/plan/\\??#",
		"RULE" => "ID=\$1&PAGE=plan&",
		"ID" => "",
		"PATH" => "/newbuildings/detail.php",
	),
	array(
		"CONDITION" => "#^/newbuildings/([\\d]+)/plan/\$#",
		"RULE" => "ID=\$1&PAGE=plan&",
		"ID" => "",
		"PATH" => "/newbuildings/detail.php",
	),
	array(
		"CONDITION" => "#^/newbuildings/([\\d]+)/\\??#",
		"RULE" => "ID=\$1&",
		"ID" => "",
		"PATH" => "/newbuildings/detail.php",
	),
	array(
		"CONDITION" => "#^/sell/([^/]+)/([^/]+)/\\??#",
		"RULE" => "catalog=\$1&ID=\$2&",
		"ID" => "",
		"PATH" => "/sell/detail.php",
	),
	array(
		"CONDITION" => "#^/arend/([^/]+)/([^/]+)/\$#",
		"RULE" => "catalog=\$1&ID=\$2&",
		"ID" => "",
		"PATH" => "/arend/detail.php",
	),
	array(
		"CONDITION" => "#^/sell/([^/]+)/([^/]+)/\$#",
		"RULE" => "catalog=\$1&ID=\$2&",
		"ID" => "",
		"PATH" => "/sell/detail.php",
	),
	array(
		"CONDITION" => "#^/realtors/([\\d]+)/\\??#",
		"RULE" => "ID=\$1&",
		"ID" => "",
		"PATH" => "/realtors/detail.php",
	),
	array(
		"CONDITION" => "#^/sell/map/([^/]+)/\\??#",
		"RULE" => "CODE=\$1&",
		"ID" => "",
		"PATH" => "/sell/map/custom-map.php",
	),
	array(
		"CONDITION" => "#^/builders/([\\d]+)/\\??#",
		"RULE" => "ID=\$1&",
		"ID" => "",
		"PATH" => "/builders/detail.php",
	),
	array(
		"CONDITION" => "#^/online/(/?)([^/]*)#",
		"RULE" => "",
		"ID" => "",
		"PATH" => "/desktop_app/router.php",
	),
	array(
		"CONDITION" => "#^/agents/([\\d]+)/\\??#",
		"RULE" => "ID=\$1&",
		"ID" => "",
		"PATH" => "/agents/detail.php",
	),
	array(
		"CONDITION" => "#^/arend/([^/]+)/\\??#",
		"RULE" => "catalog=\$1&ID=\$2&",
		"ID" => "",
		"PATH" => "/arend/index.php",
	),
	array(
		"CONDITION" => "#^/sell/([^/]+)/\\??#",
		"RULE" => "catalog=\$1&ID=\$2&",
		"ID" => "",
		"PATH" => "/sell/index.php",
	),
	array(
		"CONDITION" => "#^/arend/([^/]+)/\$#",
		"RULE" => "catalog=\$1&ID=\$2&",
		"ID" => "",
		"PATH" => "/arend/index.php",
	),
	array(
		"CONDITION" => "#^/sell/([^/]+)/\$#",
		"RULE" => "catalog=\$1&ID=\$2&",
		"ID" => "",
		"PATH" => "/sell/index.php",
	),
	array(
		"CONDITION" => "#^/info/news/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/info/news/index.php",
	),
	array(
		"CONDITION" => "#^/contacts/#",
		"RULE" => "",
		"ID" => "bitrix:form",
		"PATH" => "/contacts/index.php",
	),
);

?>