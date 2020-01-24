<?
$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__)."/../../..");
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
define("CRON_SCRIPT", true);
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS",true); 
define('CHK_EVENT', true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

@set_time_limit(0);
@ignore_user_abort(true);


function getPropertyByCode($propertyCollection, $code)  {
	foreach ($propertyCollection as $property)
	{
		if($property->getField('CODE') == $code)
			return $property;
	}
}

if (CModule::IncludeModule("sale") && CModule::IncludeModule("catalog"))
{
	$USER->Logout();
	$DM = \PacketAgent\AgentHL::getDataObject();
	$res = \PacketAgent\AgentHL::getAgentDBResult();
	while ($arData = $res->fetch())
	{
		if ($arData["ID"] && $arData["UF_PA_USER"] && $arData["UF_PA_PACKET"] && $arData["UF_PA_COUNT"])
		{
			if ($USER->GetID() != $arData["UF_PA_USER"])
			{
				$USER->Authorize($arData["UF_PA_USER"]);
			}
			$USER_ID = $USER->GetID();
			\CSaleBasket::DeleteAll();

			Add2BasketByProductID(intval($arData["UF_PA_PACKET"]),$arData["UF_PA_COUNT"]);
			
			
			$basket = \Bitrix\Sale\Basket::loadItemsForFUser(
					
					//Получение ID покупателя (НЕ ID пользователя!)
					\Bitrix\Sale\Fuser::getId(),
					
					//Текущий сайт
					\Bitrix\Main\Context::getCurrent()->getSite()
					);
			
			$basketItems = $basket->getBasketItems();
			foreach ($basketItems as $objBasketItem)
			{
				$arBasketItems[] = $objBasketItem->getProductId();
			}
			$order = Bitrix\Sale\Order::create(SITE_ID, $USER->GetID());
			$order->setPersonTypeId(1);
			$order->setBasket($basket);
			$order->setField("LID", "s1");
			
			$shipmentCollection = $order->getShipmentCollection();
			$shipment = $shipmentCollection->createItem(
					Bitrix\Sale\Delivery\Services\Manager::getObjectById(2)
					);
			
			$shipmentItemCollection = $shipment->getShipmentItemCollection();
			
			
			foreach ($basket as $basketItem)
			{
				
				$item = $shipmentItemCollection->createItem($basketItem);
				$item->setQuantity($basketItem->getQuantity());
			}
			
			$paymentCollection = $order->getPaymentCollection();
			$payment = $paymentCollection->createItem(
					\Bitrix\Sale\PaySystem\Manager::getObjectById(1)
					);
			$payment->setField("SUM", $order->getPrice());
			$payment->setField("CURRENCY", $order->getCurrency());
			
			\CSaleUserAccount::UpdateAccount($USER_ID, "+".$order->getPrice(), "RUB","Выдача пакета");
			$retur2 = $payment->setPaid("Y");
			/*var_dump($retur2);
			if ($APPLICATION->GetException())
			{
				var_dump($APPLICATION->GetException()->__toString());
			}*/
			
			
			
			
			$propertyCollection = $order->getPropertyCollection();
			
			$packetProperty = getPropertyByCode($propertyCollection, 'packet');
			$packetProperty->setValue($arData["UF_PA_PACKET"]);
			
			
			$PocketRes = CIBlockElement::GetList(array(),array("ACTIVE"=>"Y","IBLOCK_ID"=>6,"PROPERTY_ONLY_REG"=>false, "ID"=>$arBasketItems[0]),false,false,array("IBLOCK_ID","NAME","ID","PROPERTY_long_month"));
			if ($aritemPocket = $PocketRes->GetNext())
			{
				$timeProperty = getPropertyByCode($propertyCollection, 'time_end');
				$SetTimeTo = \MorealtySale\Packets::getCurTime($aritemPocket["PROPERTY_LONG_MONTH_VALUE"]);
				$timeProperty->setValue($SetTimeTo);
			}
			
			
			
			$shipment->setField("STATUS_ID", "ST");
			$order->setField("STATUS_ID", "ST");
			$result = $order->save();
			
			if ($result->isSuccess())
			{
				\MorealtySale\Packets::UpdateActivePockets($USER->GetID());
				/*var_dump($return);
				if ($APPLICATION->GetException())
				{
					var_dump($APPLICATION->GetException()->GetString());
				}*/
				/*$OrderId = $result->getId();
				
				$return = \CSaleOrder::PayOrder($OrderId,"Y",true,false);
				
				\MorealtySale\Packets::UpdateActivePockets($USER->GetID());
				
				if (!$return)
				{
					\Bitrix\Sale\Order::delete($OrderId);
				}*/
			}
			$DM::delete($arData["ID"]);
		}
		
		//Add2BasketByProductID(intval($_REQUEST["prod_id"]),$Quant);
	}
	$USER->Logout();
}