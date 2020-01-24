<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $APPLICATION;

if (isset($templateData[0]["CNT"]))
{
	
	$APPLICATION->AddViewContent("all_count",$templateData[0]["CNT"]);
}
$bAjax = ( $_REQUEST["ajax"] === "Y" ) && ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' );

if ($bAjax)
{
	$APPLICATION->RestartBuffer();
	echo(json_encode($templateData));
	
	die();
}