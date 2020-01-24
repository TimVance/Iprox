<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) ?>

<?
$ELEMENT_ID = $_REQUEST['ID'];
/*$arSelect = Array("ID", "NAME", "IBLOCK_ID", "PROPERTY_*");
$arFilter = Array("IBLOCK_ID"=> 20, "PROPERTY_newbuilding" => $ELEMENT_ID, "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while($ob = $res->GetNextElement())
{
	$item = $ob->GetFields();
	$item['PROPERTIES'] = $ob->GetProperties();
	$planItem = $item;
}


$curNewbuilding = $GLOBALS['CURRENT_NEWBUILDING'];
unset($GLOBALS['CURRENT_NEWBUILDING']);
$stages_count = $curNewbuilding['PROPERTIES']['floors']['VALUE'];


$arSelect = Array("ID", "NAME", "IBLOCK_ID", "PROPERTY_*");
$arFilter = Array("IBLOCK_ID"=> 7, "PROPERTY_newbuilding" => $item['PROPERTIES']['stage_structure']['VALUE'], "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while($ob = $res->GetNextElement())
{
	$item = $ob->GetFields();
	$item['PROPERTIES'] = $ob->GetProperties();
	$arItems[ $item['PROPERTIES']['floor']['VALUE'] ][ $item['PROPERTIES']['room_number']['VALUE'] ] = $item;
}
*/
if ($USER->GetLogin() == "vadim")
{
	//my_print_r($arResult);
}
$stages_count = $arResult["NEWBUILDING"]['PROPERTIES']['floors']['VALUE'];

$FlatsPerStage = 6;//$arResult["NEWBUILDING_SCHEME"]['PROPERTIES']["flats_at_stage_count"]["VALUE"];
?>

<div class="sales-map">
	<div class="sales-polygon">
		<div class="cloth-wrap">
			<div class="tree"></div>
			<table class="cloth">
				<?for($i = $stages_count; $i > 0; $i--):?>
				<tr>
					<th><?=$i?></th>
					<?for($j = 0; $j < $FlatsPerStage; $j++):?>
						<?if(!empty($arResult["PLANS"][$i][$j])):?>
							<?
							$curItem = $arResult["PLANS"][$i][$j];
							?>
							<td class="filled">
								<span><?=$curItem['PROPERTIES']['room_number']['VALUE']?></span>
								<div class="info">
									<span class="item_title"><?=$curItem['NAME']?></span>
									<span class="item_price"><?=$curItem['PROPERTIES']['price']['VALUE'] . ' ' . $curItem['PROPERTIES']['currency']['VALUE']?></span>
									<span class="item_per_meter"><?=$curItem['PROPERTIES']['price_1m']['VALUE'] . ' ' . $curItem['PROPERTIES']['currency']['VALUE']?>/м<sup>2</sup></span>
									<div class="item_preview"><img src="<?=SITE_TEMPLATE_PATH?>/images_tmp/sales_poly_item1.jpg" alt=""></div>
								</div>
							</td>
							<?else:?>
							<td></td>
						<?endif;?>
					<?endfor;?>
				</tr>
				<?endfor;?>
			</table>
		</div>
		<ul class="exps">
			<li>
				<div class="clr grey"></div>
				<span class="txt">Продано</span>
			</li>
		</ul>
	</div>
	<div class="floorplans <?if(count($arResult["NEWBUILDING_SCHEME"]['PROPERTIES']['stage_plans']['VALUE']) == 0) echo "vishidden";?>">
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
	</div>
</div>