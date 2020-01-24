<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) ?>

<?
$ELEMENT_ID = $_REQUEST['ID'];
//$stages_count = $arResult["NEWBUILDING"]['PROPERTIES']['floors']['VALUE'];

//$FlatsPerStage = 6;//$arResult["NEWBUILDING_SCHEME"]['PROPERTIES']["flats_at_stage_count"]["VALUE"];
// /my_print_r($arResult["SCHEME"]);
?>
<? 

$delayedContent = "";
if (count($arResult["SCHEME"])  > 0)
{
	?>
	<div class="object-selector plans-selector">
		<b>Выберите корпус:</b>
		<ul>
		<? 
		foreach ($arResult["SCHEME"] as $arObject)
		{
			?>
				<li><a href="javascript:void(0);" data-target="object-<?=$arObject["ID"]?>"><?=$arObject["REAL_NAME"]?></a></li>
			<?
		}
		?>
			
		</ul>
	</div>
	<?
}

foreach ($arResult["SCHEME"] as $arObject)
{
	?>
	<div class="sales-map innactive" id="object-<?=$arObject["ID"]?>">
		<div class="sales-polygon">
			<div class="cloth-wrap">
				<div class="tree"></div>
				<table class="cloth">
					<? foreach ($arObject["SCHEME"] as $LevelKey=>$arLevel)
					{
						?>
							<tr>
							<th><?=$LevelKey?></th>
							<? 
							foreach ($arLevel as $Case)
							{
								if (!$Case)
								{
									?>
									<td></td>
									<?
								}
								else
								{
									
									
									$CurElement = $arResult["ITEMS"][$Case];
									?>
										<td class="filled cboxAjaxData" href="javascript:void(0);" data-floor="<?=$LevelKey?>" data-target="<?=$CurElement["ID"]?>">
											<span><?=$CurElement['PROPS']['room_number']['VALUE']?></span>
											<div class="info">
												<span class="item_title"><a href="<?=$CurElement['DETAIL_PAGE_URL']?>"><?=$CurElement['NAME']?></a></span>
												<span class="item_price"><?=MoneyOutPut($CurElement['PROPS']['price']['VALUE']) . ' ' . $CurElement['PROPS']['currency']['VALUE']?></span>
												<span class="item_per_meter"><?=MoneyOutPut($CurElement['PROPS']['price_1m']['VALUE']) . ' ' . $CurElement['PROPS']['currency']['VALUE']?>/м<sup>2</sup></span>
												<? 
												if ($CurElement["PROPS"]["layouts_gallery"]["VALUE"][0])
												{
													$img = AddWatermarkAndResize($CurElement["PROPS"]["layouts_gallery"]["VALUE"][0], 232, 147, Cimg::M_FULL);
													if ($img)
													{	
														?>
															<div class="item_preview"><img src="<?=$img?>" alt=""></div>
														<?
													}
												}
												?>
												
											</div>
										</td>
									<?
								}
								?>
								
								<?
							}
							?>
							</tr>
						<?
					}?>
				</table>
			</div>
			<ul class="exps">
				<li>
					<div class="clr grey"></div>
					<span class="txt">Продано</span>
				</li>
			</ul>
		</div>
		<?/* ?><div class="floorplans <?if(count($arResult["NEWBUILDING_SCHEME"]['PROPERTIES']['stage_plans']['VALUE']) == 0) echo "vishidden";?>">
			<div class="floorplans-in">
				<div class="tabs-tb">
					<div class="nav-regist nav-tb">
						<span class="nav-title">Планы этажей</span>
						<?
						?>
						<ul>
							<?if(count($arResult["NEWBUILDING_SCHEME"]['PROPERTIES']['stage_plans']['VALUE']) == 1):?>
								<li class="active"><a href="javascript:void(0);">1 этаж</a></li>
							<?endif;?>
							<?if(count($arResult["NEWBUILDING_SCHEME"]['PROPERTIES']['stage_plans']['VALUE']) == 2):?>
								<li class="active"><a href="javascript:void(0);">1 этаж</a></li>
								<li><a href="javascript:void(0);">Типовой</a></li>
							<?endif;?>
						</ul>
					</div><!--nav-regist-->
					<div class="cont-tb">
						<? $counter = 0; ?>
						<? foreach ($arResult["NEWBUILDING_SCHEME"]['PROPERTIES']['stage_plans']['VALUE'] as $stage_plan):?>
							<div class="tab-tb">
								<?$img = CFile::GetPath($stage_plan); ?>
								<img src="<?=$img?>" alt="">
							</div>
							<?$counter++?>
						<?endforeach;?>
						<? $counter = 0; ?>
					</div><!--cont-tb-->
				</div><!--tabs-tb-->
			</div>
		</div><? */?>
		
	<div class="floorplans visible">
		<div class="floorplans-in">
			<div class="tabs-tb">
				<div class="nav-regist nav-tb">
					<span class="nav-title">Планы этажей</span>
					<?
					?>
					<div class="floorplans_list_wrapper">
						<ul>
						<? 
	
						foreach ($arObject["CHILDS"] as $arTab)
						{
							?><li><a href="javascript:void(0);"><?=$arTab["TAB_NAME"]?></a></li><?
						}
						?>
						</ul>
					</div>
				</div><!--nav-regist-->
				<div class="cont-tb">
				<? 
				foreach ($arObject["CHILDS"] as $arTab)
				{
					?>
						<div class="tab-tb" style="text-align:center;">
							<?//$img = CFile::GetPath($arTab["IMAGE"]); 
								$img = AddWaterMarkResized($arTab["IMAGE"],400,null,CImg::M_PROPORTIONAL);
								$arFile = CFile::GetByID($arTab["IMAGE"])->GetNext();
								//AddWatermarkAndResize
								$filePath = AddWaterMarkResized($arTab["IMAGE"],$arFile["WIDTH"],$arFile["HEIGHT"]);;
							?>
							<? 
							if ($filePath && $img)
							{
								?>
									<a href="<?=$filePath?>" class="image-viewer">
										<img src="<?=$img?>" alt="<?=$arTab["TAB_NAME"]?>">
									</a>
								<?
							}
							?>

						</div>
					<?
				}
				?>
				</div>
			</div>
		</div>
	</div>
	</div>
	<?
}
//my_print_r($arResult["ITEMS"]);
?>
