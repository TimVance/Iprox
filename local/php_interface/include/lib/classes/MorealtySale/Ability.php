<? 
namespace MorealtySale;


/**
 * 
 * @deprecated
 * @author sea
 *
 */
class Ability 
{
	
	public function HowMuchUserCanAddTo($to,$USER_ID = false)
	{
		global $USER;
		if (!$to) 
			return false;
		if ($USER_ID === false)
			$USER_ID = $USER->GetID();
		$to = intval($to);
		$PosobData  =  array();
		$CurVal = self::totalHowMuchUserCanAdd($USER_ID,$to);
		
		return $CurVal[$to];
		
	}
	public function totalHowMuchUserCanAdd($USER_ID = false,$IBLOCK_ID = false)
	{
		global $USER;
		if ($USER_ID === false)
			$USER_ID = $USER->GetID();
		$USER_ID = intval($USER_ID);
		
		$OwnObjects = User::currentObjects($USER_ID,$IBLOCK_ID);
		$PosobData = self::packAllTypes($USER_ID);
		
		if ($PosobData === true)
			return true;
		//Тут потом отдельное количество домов, купленное как отдельные дома
		$allPosobData = self::sumAllPossible($PosobData);
		
		return self::getRealPossoblities($allPosobData, $OwnObjects);
	}
	
	private function getRealPossoblities($arPosobs,$arCurrent)
	{
		foreach ($arPosobs as $key=>&$Posob)
		{
			if ($arCurrent[$key])
			{
				$Posob = $Posob-intval($arCurrent[$key]) ;
			}
		}
		return $arPosobs;
	}
	
	private function sumAllPossible($PosobData = array())
	{
		
		$allPosob = array();
		foreach ($PosobData as $PosobArray)
		{
			foreach ($PosobArray as $keyPosob=>$PosobVal)
			{
				$allPosob[$keyPosob] = ($allPosob[$keyPosob])? $allPosob[$keyPosob]+$PosobVal: $PosobVal;
			}
		}
		return $allPosob;
		
	}
	private function packAllTypes($USER_ID)
	{
		
		$res = PacketsReader::getAvalCountSimple($USER_ID);
		if ($res === true)
			return true;
		
		$PosobData[] = $res;
		
		return $PosobData;
	}
	
	
}
?>