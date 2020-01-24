<? 
namespace MorealtySale;

class Offer 
{
	public static $AredtType = "arend";
	public static $SellType = "sell";
	
	
	public function GetOfferType ($OfferId)
	{
		if (!\CModule::IncludeModule("iblock"))
		{
			Throw new Exception("Не подключен модуль инфоблока");
			return false;
		}
		
		$res = \CIBlockElement::GetList(array(),array("ID"=>intval($OfferId)),false,false,array("ID","IBLOCK_ID","IBLOCK_SECTION_ID"));
		if ($arItem = $res->GetNext())
		{
			if ($arItem["IBLOCK_SECTION_ID"])
			{
				$res2 = \CIBlockSection::GetList(array(),array("ID"=>$arItem["IBLOCK_SECTION_ID"]),false,array("ID","IBLOCK_ID","CODE"));
				if ($arSection = $res2->GetNext())
				{
					if (self::isOfferArend($arSection["CODE"]))
						return self::$AredtType;
					else if (self::isOfferSell($arSection["CODE"]))
						return self::$SellType;
					else 
						return false;
				}
				else 
				{
					return false;
				}
				
			}

		}
		else
		{
			return false;
		}
		
	}
	public function getSectionIdByType($type,$IBLOCK_ID)
	{
		$filter = array("CODE"=>$type);
		if ($IBLOCK_ID)
			$filter["IBLOCK_ID"] = $IBLOCK_ID;
		$res2 = \CIBlockSection::GetList(array(),$filter,false,array("ID","IBLOCK_ID","CODE"));
		$section = $res2->GetNext();
		return $section['ID'];
	}
	
	private function isOfferArend($var)
	{
		return $var == self::$AredtType;
	}
	private function isOfferSell($var)
	{
		return $var == self::$SellType;
	}
}
?>