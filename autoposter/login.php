<?

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Автопостинг на Facebook");


include_once("Facebook/autoload.php");

// App ID и App Secret из настроек приложения
$app_id = "725004367925770";
$app_secret = "5e654ff8997d4d772531a1ed0c607743";

// ссылка на страницу возврата после авторизации
// домен должен совпадать с указанным в настройках приложения
$callback = "https://iprox.ru/autoposter/callback.php";

$fb = new Facebook\Facebook([
    'app_id'  => $app_id,
    'app_secret' => $app_secret,
    'default_graph_version' => 'v2.4',
]);

$helper = $fb->getRedirectLoginHelper();

// для публикации в группах достаточно разрешения publish_actions
// для публикации на страницах нужны все 3 элемента
$permissions = ['publish_actions','manage_pages','publish_pages'];
$loginUrl = $helper->getLoginUrl($callback, $permissions);

echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';




require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>