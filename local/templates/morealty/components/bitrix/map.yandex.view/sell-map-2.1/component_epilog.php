<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
global $APPLICATION;
$APPLICATION->AddHeadScript($templateFolder."/custom_function.js",true);
$APPLICATION->AddHeadScript($templateFolder."/ymapsstyle.js",true);
CJSCore::Init("fx");