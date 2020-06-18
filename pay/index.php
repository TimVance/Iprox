<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Оплата");
?>

<p>Iprox – сервис, который предоставляет доступ к базе недвижимости: вторичное жилье, квартиры в Новостройках, участки и дома.</p>

<h2>Список услуг</h2>

<p>При регистрации активруется демо-доступ на 7 дней, по окончании которого база недвижимости становится недоступна. Чтобы вновь вернуть доступ, необходимо оплатить один из пакетов услуг в личном кабинете:</p>

<?$APPLICATION->IncludeComponent('wexpert:iblock.list', 'pay_packets_info', array(
    'IBLOCK_ID'     => 6,
    'PAGESIZE'      => 8,
    'ORDER'         => array('SORT' => 'ASC', 'NAME' => 'ASC'),
    'SELECT'        => array(),
    'GET_PROPERTY'  => 'Y',
    'CACHE_TIME'    => 0,
    'SET_404'       => 'N'
));?>

<h2>Способы оплаты</h2>
<p><i>Перед покупкой услуг необходимо пополнить баланс пользователя одним из способов ниже:</i></p>
<ul>
    <li>Оплата через PayOnline</li>
    <li>Оплата выставленного счета</li>
</ul>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>