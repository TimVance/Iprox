<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);?>

<?
if ($arResult["ITEMS"] && count($arResult["ITEMS"]) > 0)
{
	?>
	<ul class="items">
		<?
		foreach ($arResult["ITEMS"] as $arItem)
		{
			$img = false;
			foreach($arItem["PROPERTIES"]['photo_gallery'] as $photo):
				$img = AddWaterMarkResized($photo["VALUE"],65, 40,CImg::M_PROPORTIONAL);
				break;
			endforeach;
			
			?>
			<li>
				
				<div class="search_item_image">
					<?
					if ($img)
					{
						?>
							<img src="<?=$img?>">
						<?
					}
					?>
					
				</div>
				<div class="search_item_info">
					<?
					if ($arItem["IBLOCK_ID"] != 19)
					{
						?>
							<span>Лот: <?=$arItem["ID"]?></span>
						<?
					}
					?>
					
					<p><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a></p>
                    <?
                        if (!empty($arItem["PROPERTIES"]["price"]["VALUE"])) {
                            echo '<p>';
                                echo number_format($arItem["PROPERTIES"]["price"]["VALUE"], 0, '', ' ');
                            echo 'р.</p>';
                        }
                        if (!empty($arItem["PROPERTIES"]["price_flat_min"]["VALUE"])) {
                            echo '<p>от ';
                            echo number_format($arItem["PROPERTIES"]["price_flat_min"]["VALUE"], 0, '', ' ');
                            echo 'р.</p>';
                        }
                    ?>
					<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="to_element"></a>
				</div>
		</li>
			<?
		}
		?>
		
	</ul>
	<?
}
else 
{
	?>
		<script>
			$(function(){
				$(".search_elements").hide();
			});
		</script>
	<? 
}
?>