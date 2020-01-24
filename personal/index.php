<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Финансы");
?>

<? if ($_REQUEST['DOC']) { ?>

	<?
	$APPLICATION->IncludeComponent('morealty:personal.bill', '', array('DOC_ID' => $_REQUEST['DOC']));
	exit;
	?>

<? } else { ?>

	<? $APPLICATION->IncludeComponent('morealty:personal.account', 'in_personal', array("CACHE_TYPE" => "N", "CACHE_TIME" => 0)) ?>

<? } ?>


<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>