<? 
namespace MorealtySale;

use Bitrix\Main\Loader;


/**
 * 
 * @deprecated
 * @author sea
 *
 */
class Packets
{
	
	public function getCurTime ($PlusMonth = false)
	{
		if ($PlusMonth == false)
		{
			return FormatDate("Y-m-d H:i:s",time());
		}
		else 
		{
			return FormatDate("Y-m-d H:i:s",strtotime("+ ".$PlusMonth." month"));
		}
		
	}
	
	
	private function getActivePocketsDB ($USER_ID)
	{

		if ($USER_ID <= 0)
			return false;
		
		global $DB;
		$CurrentDateTime = ConvertTimeStamp(time(), "FULL");
		$arOrders = array();
		$arOrdersIds = array();
		$rsOrders = \CSaleOrder::GetList(array("DATE_INSERT"=>"DESC"),array(
				"USER_ID" => $USER_ID,
				//"<=DATE_INSERT" => $CurrentDateTime,
				"STATUS_ID" => "ST",
				'PAYED' => 'Y',
				"!PROPERTY_VAL_BY_CODE_PACKET" => false,
		));
		global $USER;
		while($ar_res = $rsOrders->GetNext())
		{
			
			//$return[] = $ar_res;
			$arOrders[$ar_res["ID"]] = $ar_res;
			$arOrdersIds[] = $ar_res["ID"];
		}
		//my_print_r($arOrders);
		//CSaleOrderPropsValue::GetList
		$arTrain = array();
		$arPocketsIds = array();
		
		
		$BasketOrder = \CSaleBasket::GetList(array(),array("ORDER_ID"=>$arOrdersIds));
		
		
		while ($arBasket = $BasketOrder->GetNext())
		{
			if (!$arTrain[$arBasket["ORDER_ID"]])
			{
				$arTrain[$arBasket["ORDER_ID"]][] = array("CNT"=>$arBasket["QUANTITY"],"PRODUCT"=>$arBasket["PRODUCT_ID"]);
				$arPocketsIds[] = $arBasket["PRODUCT_ID"];
			}
			
		}
		
		$arPocketsIds = array_unique($arPocketsIds);
		
		$rsOrderProps = \CSaleOrderPropsValue::GetList(array(),array("ORDER_ID"=>$arOrdersIds));
		while ($arOrderProps = $rsOrderProps->GetNext())
		{
			
			if ($arOrderProps["CODE"] == "packet")
			{
				$pocketVal = intval($arOrderProps["VALUE"]);
				if ($pocketVal > 0)
				{
					
					$arPocketsIds[] = $pocketVal;
				}
				
			}
			
			$arOrders[$arOrderProps["ORDER_ID"]]["PROPS"][$arOrderProps["CODE"]] = $arOrderProps;
		
		
		}
		
		
		// Получаем данные пакетов
		$arPackets = array();
		if (count($arPocketsIds) > 0)
		{
			$resPocket = \CIBlockElement::GetList(array(),array("ID"=>$arPocketsIds));
			while ($objItem = $resPocket->GetNextElement())
			{
				$arPocket = $objItem->GetFields();
				$arPocket["PROPERTIES"] = $objItem->GetProperties();
				$arPackets[$arPocket["ID"]] = $arPocket;
				foreach ($arOrders as $orderkey=>$TempOrder)
				{
					
					if ($TempOrder["PROPS"]["packet"]["VALUE"] == $arPocket["ID"])
					{
						$arOrders[$orderkey]["PROPS"]["packet"]["PACKET_DATA"] = $arPocket;
					}
					
				}
				
			}
		}
		foreach ($arOrders as $keyOrder => &$arOrder)
		{
			if (count($arTrain[$arOrder["ID"]]) > 0)
			{
				foreach ($arTrain[$arOrder["ID"]] as $TrainItem)
				{
					$arOrder["TRAIN"][] = 
					array_merge(
							$TrainItem,
							$arPackets[$TrainItem["PRODUCT"]],
							array(
									"PACKET_PRICE" => $arOrder["PROPS"]["packet"]["PACKET_DATA"]["PROPERTIES"]["price"]["VALUE"],
									"TIME_END"	=> $arOrder["PROPS"]["time_end"]["VALUE"]
									
							)
					);
					
				}
			}
				
		}
		unset($arPackets,$arTrain);
		return $arOrders;
		
	}
	
	
	/**
	 * Обновляет кэш о текущих пакетах
	 *
	 *
	 * @param int $USER_ID id пользователя. По умолчанию текущий пользователь
	 */
	public function UpdateActivePockets ($USER_ID = false)
	{
		global $USER;
		if ($USER_ID === false)
			$USER_ID = $USER->GetID();
		$USER_ID = intval($USER_ID);
		if ($USER_ID <= 0)
			return false;
		
		return self::getActivePockets($USER_ID,true);
		
		
	}
	
	/**
	 * Возвращает текущие активные пакеты из заказов
	 *
	 *
	 * @param int $USER_ID id пользователя. По умолчанию текущий пользователь
	 * @param bool обновить кэш. По умолчанию нет. Для обновления лучше использовать метов \MorealtySale\Packets::UpdateActivePockets()
	 */
	public function getActivePockets ($USER_ID = false,$updateCache = false)
	{
		global $USER;
		if ($USER_ID === false)
			$USER_ID = $USER->GetID();
		$USER_ID = intval($USER_ID);
		if ($USER_ID <= 0 || !Loader::includeModule("sale"))
			return false;
		
		
		$cache = new \CPHPCache();
		$cache_time = 3600*36;
		$cache_path = 'morealtyAccount';
		$cache_id = $cache_path.$USER_ID;
		if ($updateCache)
		{
			$cache->Clean($cache_id,$cache_path);
		}
		if ($cache_time > 0 && $cache->InitCache($cache_time, $cache_id, $cache_path))
		{
			$res = $cache->GetVars();
			if (is_array($res[$cache_path]) && (count($res[$cache_path]) > 0))
				$return = $res[$cache_path];
		}
		if (!is_array($return))
		{
			$return = self::getActivePocketsDB($USER_ID);
			//////////// end cache /////////
			if ($cache_time > 0)
			{
				$cache->StartDataCache($cache_time, $cache_id, $cache_path);
				$cache->EndDataCache(array($cache_path=>$return));
			}
			unset($rsOrderProps,$arOrderProps,$arOrders,$CurrentDateTime,$arOrdersIds);
		}
		
		return $return;
	}
	
	/**
	 * 
	 * 
	 * @param \Bitrix\Sale\PropertyValueCollection $propertyCollection
	 * @param string $code
	 * @return \Bitrix\Sale\PropertyValue
	 */
	private static function getPropertyByCode(\Bitrix\Sale\PropertyValueCollection $propertyCollection, $code)  {
		foreach ($propertyCollection as $property)
		{
			if($property->getField('CODE') == $code)
				return $property;
		}
	}
	
	public static function givePacketToUser(int $Packet,int $Quantity,int $USER_ID)
	{
		
		\PacketAgent\AgentHL::giveUserPacket($USER_ID, $Packet, $Quantity);
		
		return ;
		
		
		if (\CModule::IncludeModule("sale"))
		{
			global $APPLICATION;
			$Fuser = \Bitrix\Sale\Fuser::getIdByUserId($USER_ID);
			\CSaleBasket::DeleteAll($Fuser);
			$basket = \Bitrix\Sale\Basket::loadItemsForFUser(
					$Fuser,
					\Bitrix\Main\Context::getCurrent()->getSite()
			);
			
			if ($item = $basket->getExistsItem('catalog', $Packet))
			{
				
			}
			else
			{
				$item = $basket->createItem('catalog', $Packet);
				
				$item->setFields([
						'QUANTITY' => $Quantity,
						'CURRENCY' => \Bitrix\Currency\CurrencyManager::getBaseCurrency(),
						'LID' => \Bitrix\Main\Context::getCurrent()->getSite(),
				]);
				
			}
			$basket->save();
			$basketItems = $basket->getBasketItems();
			foreach ($basketItems as $objBasketItem)
			{
				$arBasketItems[] = $objBasketItem->getProductId();
			}
			
			$order = \Bitrix\Sale\Order::create(SITE_ID, $USER_ID);
			$order->setPersonTypeId(1);
			$order->setBasket($basket);
			
			$shipmentCollection = $order->getShipmentCollection();
			$shipment = $shipmentCollection->createItem(
					\Bitrix\Sale\Delivery\Services\Manager::getObjectById(2)
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
			
			$payment->setField("SUM", "1");
			$payment->setField("CURRENCY", $order->getCurrency());
			
			$propertyCollection = $order->getPropertyCollection();
			
			$packetProperty = static::getPropertyByCode($propertyCollection, 'packet');
			$packetProperty->setValue($arBasketItems[0]);
			
			$bFinded = false;
			
			/*$PocketRes = \CIBlockElement::GetList(array(),array("ACTIVE"=>"Y","IBLOCK_ID"=>6,"PROPERTY_ONLY_REG"=>false, "ID"=>$arBasketItems[0]),false,false,array("IBLOCK_ID","NAME","ID","PROPERTY_long_month"));
			if ($aritemPocket = $PocketRes->GetNext())
			{
				$timeProperty = static::getPropertyByCode($propertyCollection, 'time_end');
				$SetTimeTo = \MorealtySale\Packets::getCurTime($aritemPocket["PROPERTY_LONG_MONTH_VALUE"]);
				$timeProperty->setValue($SetTimeTo);
				$bFinded = true;
			}*/
			//my_print_r($arBasketItems[0]);
			if ($bFinded)
			{
				$order->setField("STATUS_ID", "ST");
				$result = $order->save();
				
				//my_print_r($result);
				//my_print_r($APPLICATION->GetException()->__toString());
				if ($result->isSuccess())
				{
					\CSaleUserAccount::UpdateAccount($USER_ID, "+1", "RUB","Выдача пакета");
					$status = \CSaleOrder::PayOrder($result->getId(),"Y",true,false);
					\CSaleUserAccount::UpdateAccount($USER_ID, "-1", "RUB","Выдача пакета");
					if (!$status)
					{
						\CSaleOrder::Delete($result->getId());
					}
					else
					{
						\MorealtySale\Packets::UpdateActivePockets($USER_ID);
					}
				}
			}

		}
	}
	
	public static function removePacketFromUser(int $Packet,int $USER_ID)
	{
		global $APPLICATION;
		\PacketAgent\AgentHL::removeUserPacketsFromQueue($USER_ID, $Packet);
		if (\CModule::IncludeModule("sale") && \CModule::IncludeModule("catalog"))
		{
			$resOrders = \CSaleOrder::GetList(array(),array(
					"USER_ID" => $USER_ID,
					"PAYED"	=> "Y",
					"!STATUS_ID" => "EN",
					"PROPERTY_VAL_BY_CODE_PACKET" => $Packet 
			));
			$ord = new \CSaleOrder;
			while ($arOrder = $resOrders->GetNext())
			{
				$rs = $ord->Update($arOrder["ID"], array(
						"STATUS_ID"	=> "EN",
						
				));
				
				$order = \Bitrix\Sale\Order::load($arOrder["ID"]);
				$order->setField("STATUS_ID", "EN");

				$collection = $order->getShipmentCollection()->current();
				if ($collection)
				{
					$res = $collection->setField("STATUS_ID", "EN");
				}
				$res = $order->save();
				
				
				$PacketID = \Utilities\Sale\Order::getPropertyByCode($order->getPropertyCollection(), "packet")->getValue();
				if ($PacketID)
				{
					$bPayd = $order->isPaid();
					$Time = \MorealtyEntities\Packet::getPacketLifeTime($PacketID);
					
					if ($Time)
					{
						
						$Time = -1 * abs($Time);
						\CustomUsers::addUserActiveTime($Time, $order->getUserId());
					}
				}
			}
		}
	}
	
}

?>