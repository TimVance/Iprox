<?

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Ипотечный калькулятор");


include_once("lib/getOptions.php");

// Get Options
$options         = new getOptions();
$allowOptions = array('program', 'type', 'sex', 'family', 'education','employment_form', 'income_proof', 'experience');
$arrOptions = $options->getList($allowOptions);

// Get templates
include_once("templates/step1.php");
include_once("templates/step2.php");
include_once("templates/step3.php");
include_once("templates/step4.php");
include_once("templates/scripts.php");


require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");

?>
