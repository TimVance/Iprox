<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Context;

$APPLICATION->RestartBuffer();

if(empty($_POST["program"])) exit();

$request = Context::getCurrent()->getRequest();
$post = $request->getPostList();

include_once("writeInfo.php");
$write = new writeInfo;
echo $write->writeBase($post);