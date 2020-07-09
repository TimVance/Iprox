#!/usr/bin/php
<?
##########################################################################
# Name: kreattika.mlexport                                               #
# http://marketplace.1c-bitrix.ru/solutions/kreattika.mlexport/          #
# File: run.php                                                          #
# Version: 1.0.2                                                         #
# (c) 2011-2016 Kreattika, Sedov S.Y.                                    #
# Proprietary licensed                                                   #
# http://kreattika.ru/                                                   #
# mailto:info@kreattika.ru                                               #
##########################################################################
?>
<?
$fileInfo = pathinfo(__FILE__);
$pattern = array("'/bitrix/modules/kreattika.mlexport/run'si");
$_SERVER["DOCUMENT_ROOT"] = preg_replace($pattern, array(""), $fileInfo['dirname']);
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

define('BX_SESSION_ID_CHANGE', false);
define('BX_SKIP_POST_UNQUOTE', true);
define('NO_AGENT_CHECK', true);
define("STATISTIC_SKIP_ACTIVITY_CHECK", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
@set_time_limit(0);

\Bitrix\Main\Loader::IncludeModule("kreattika.mlexport");

$flRun = COption::GetOptionString("kreattika.mlexport", "mlexport_run");

if( $flRun == 'CRON' ):
    CMLExport::Start();
endif;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>