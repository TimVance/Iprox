<? 
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

if (count($arResult["ITEMS"]) > 0)
{
	foreach ($arResult["ITEMS"] as $arItem)
	{
		
		if ($arItem["PROPERTIES"]["FILE"]["VALUE"])
		{
			$isImage = false;
			
			$fileAr = CFile::GetByID($arItem["PROPERTIES"]["FILE"]["VALUE"])->GetNext();
			if ($fileAr)
			{
				$isImage = strpos($fileAr["CONTENT_TYPE"], "image") !== false;
				$Path = CFile::GetPath($fileAr["ID"]);
				if ($Path)
				{
					if ($isImage)
					{
						?>
						<style>
							.main-page-wr-top.wr-top {background : #296398 url(<?=$Path?>) 50% 0 no-repeat;background-size: 100% 100%;}
						</style>
						<?
					}
					else
					{
						?>
						<style>
							.main-page-wr-top.wr-top {background : #fff;}
						</style>
						<?
						if ($USER->GetLogin() == "vadim")
						{
							?>
							<video class="main-page-video" width="320" height="240" autoplay loop>
							  <source src="<?=$Path?>" type="<?=$fileAr["CONTENT_TYPE"]?>">
							</video>
							<?
						}
					}
				}
			}
			//my_print_r($fileAr);
		}
		
	}
}
?>