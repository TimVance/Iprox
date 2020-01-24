<?use Bitrix\Main\Security\CurrentUser;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
$Return = array();
Bitrix\Main\Loader::includeModule('sale');
Bitrix\Main\Loader::includeModule('catalog');

if (!$USER->IsAuthorized())
{
	$Return["status"] = "error";
	echo json_encode($Return);
}
else 
{	
function getPropertyByCode($propertyCollection, $code)  {
	foreach ($propertyCollection as $property)
	{
		if($property->getField('CODE') == $code)
			return $property;
	}
}
	
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


$shipmentCollection = $order->getShipmentCollection();
$shipment = $shipmentCollection->createItem(
		Bitrix\Sale\Delivery\Services\Manager::getObjectById(2)
);

$shipmentItemCollection = $shipment->getShipmentItemCollection();


/*$rsUserGroups = CUser::GetUserGroupList($USER->GetID());
$arGroupsDuration = array();
while ($arGroup = $rsUserGroups->Fetch()){

	$UserGroups[] = $arGroup["GROUP_ID"];
	if ($arGroup["DATE_ACTIVE_TO"] && $arGroup["DATE_ACTIVE_FROM"])
	{
		$arGroupsDuration[$arGroup["GROUP_ID"]] = array("DATE_ACTIVE_FROM"=>$arGroup["DATE_ACTIVE_FROM"],"DATE_ACTIVE_TO"=>$arGroup["DATE_ACTIVE_TO"]);
	}

}
$TimeToSave = array();
foreach ($arBasketItems as $BasketPocket)
{
	$rsGroups = CGroup::GetList($by1 = "c_sort", $order1 = "asc",array("STRING_ID"=>$BasketPocket));
	if ($arGroup = $rsGroups->GetNext())
	{
		if ($arGroupsDuration[$arGroup["ID"]] && is_array($arGroupsDuration[$arGroup["ID"]]))
		{
			$CurTime = time();
			
			$arTempTime = array(
					"FROM" => MakeTimeStamp($arGroupsDuration[$arGroup["ID"]]["DATE_ACTIVE_FROM"]),
					"TO"   => MakeTimeStamp($arGroupsDuration[$arGroup["ID"]]["DATE_ACTIVE_TO"]),
					);
			if ($CurTime <= $arTempTime["TO"] && $CurTime >= $arTempTime["FROM"])
			{
				$TimeToSave[$arGroup["ID"]] = array(
							'DATE_ACTIVE_FROM' => $arGroupsDuration[$arGroup["ID"]]["DATE_ACTIVE_FROM"],
							"DATE_ACTIVE_TO"   => $arGroupsDuration[$arGroup["ID"]]["DATE_ACTIVE_TO"]
						);
				$PocketRes = CIBlockElement::GetList(array(),array("ACTIVE"=>"Y","IBLOCK_ID"=>6, "ID"=>$BasketPocket),false,false,array("IBLOCK_ID","NAME","ID","PROPERTY_long_month"));
				if ($aritemPocket = $PocketRes->GetNext())
				{
					$SetTimeTo = AddToTimeStamp(array("MM"=>$aritemPocket["PROPERTY_LONG_MONTH_VALUE"]),$arTempTime["TO"]);
					$TimeToSave[$arGroup["ID"]]["DATE_ACTIVE_TO"] = ConvertTimeStamp($SetTimeTo,"FULL");
				}
				$Return["TIME_TO_SAVE"] = $TimeToSave;
			}
		}
		
	}
}
*/

foreach ($basket as $basketItem)
{

	$item = $shipmentItemCollection->createItem($basketItem);
	$item->setQuantity($basketItem->getQuantity());
}

$paymentCollection = $order->getPaymentCollection();
$payment = $paymentCollection->createItem(
		Bitrix\Sale\PaySystem\Manager::getObjectById(1)
);
$payment->setField("SUM", $order->getPrice());
$payment->setField("CURRENCY", $order->getCurrency());


$propertyCollection = $order->getPropertyCollection();

$packetProperty = getPropertyByCode($propertyCollection, 'packet');
$packetProperty->setValue($arBasketItems[0]);


/*$PocketRes = CIBlockElement::GetList(array(),array("ACTIVE"=>"Y","IBLOCK_ID"=>6,"PROPERTY_ONLY_REG"=>false, "ID"=>$arBasketItems[0]),false,false,array("IBLOCK_ID","NAME","ID","PROPERTY_long_month"));
if ($aritemPocket = $PocketRes->GetNext())
{
	$timeProperty = getPropertyByCode($propertyCollection, 'time_end');
	$SetTimeTo = \MorealtySale\Packets::getCurTime($aritemPocket["PROPERTY_LONG_MONTH_VALUE"]);
	$timeProperty->setValue($SetTimeTo);
}*/




$result = $order->save();


$Return["ITEMS"] = $arBasketItems;
if (!$result->isSuccess())
{
	$Return["status"] = "error";
	$Return["debug_info"] = $result->getErrors();
	$Return["response"] = Loc::getMessage("ORDER_ERROR");
}
else {
	$OrderId = $result->getId();
	$return = CSaleOrder::PayOrder($OrderId,"Y",true,false);
	if (!$return)
	{
		CSaleOrder::Delete($OrderId);
	}
	else {
		\MorealtySale\Packets::UpdateActivePockets($USER->GetID());
	}
	$Return["status"] = "ok";
	$Return["debug_info"] = $return;
	$Return["response"] = ($return)? Loc::getMessage("ORDER_PAYED") : Loc::getMessage("ORDER_NOT_ENOUGH_BALANCE");
	/**
	 * Оказывается не длительноть складывается, а количество в пакете
	 * 
	 * if (count($TimeToSave) > 0 && $return)
	{
		$newGroups = $UserGroups;
		foreach ($newGroups as &$newGroup)
		{
			if ($TimeToSave[$newGroup] && is_array($TimeToSave[$newGroup]))
			{
				$newGroup = array("GROUP_ID"=>$newGroup,"DATE_ACTIVE_FROM"=>$TimeToSave[$newGroup]["DATE_ACTIVE_FROM"],"DATE_ACTIVE_TO"=>$TimeToSave[$newGroup]["DATE_ACTIVE_TO"]);
			}
		}
		if (count($newGroups) > 0)
		{
			CUser::SetUserGroup($USER->GetID(), $newGroups);
		}
		$Return["GROUPS"] = $newGroups;
	}*/
}
//CSaleBasket::DeleteAll(CSaleBasket::GetBasketUserID());
echo json_encode($Return);
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>