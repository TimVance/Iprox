<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? 
global $APPLICATION;

	$CurState  = $APPLICATION->GetPageProperty("show_apartaments","N");
	if (count($arResult["ITEMS"]) <= 0)
	{
		if ($CurState == "N")
		{
			$APPLICATION->SetPageProperty("show_apartaments","N");
		}
	}
	else 
	{
		$APPLICATION->SetPageProperty("show_apartaments","Y");
	}

?>