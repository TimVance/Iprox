<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>

<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/local/components/morealty/show_application_link/templates/.default/Mobile_Detect.php");

$detect = new Mobile_Detect();

if( $detect->isiOS() ) {
    $arResult["os"]  = 'ios';
}
elseif ( $detect->isAndroidOS() ) {
    $arResult["os"]  = 'android';
}

$this->includeComponentTemplate();

?>