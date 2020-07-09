<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?><?

use Bitrix\Sale;
use \Bitrix\Sale\Order;

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

$entityId = $request->get("OrderId");

if (!empty($entityId)) {
    $order = Sale\Order::loadByAccountNumber($entityId);
    if($order) {
        $paymentCollection = $order->getPaymentCollection();
        $onePayment = $paymentCollection[0];
        $onePayment->setPaid("Y");
        if ($order->save()) echo '<h3>Ваш платеж прошел успешно!</h3>';
    }
}
