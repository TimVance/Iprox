<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
CModule::IncludeModule('iblock');

$arSelect = Array("ID", "NAME");
$arFilter = array("IBLOCK_ID"=>18, "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
$locations = array();
while ($ob = $res->GetNextElement()) {
	$locations[] = $ob->GetFields();
}

$filterValuesCount = count(explode(',', $_GET["FILTER_VALUE"]));
$FILTER_VALUES = explode(',', $_GET["FILTER_VALUE"]);

$filterValues = $FILTER_VALUES;
$filterProperty = '';
if ($FILTER_VALUES != '') {
	$filterProperty = $_GET["FILTER_PROPERTY"];
}
if ($filterValuesCount == 2) {
	$filterValues[0] = $FILTER_VALUES[0];
	$filterValues[1] = $FILTER_VALUES[1];
	$filterProperty = $_GET["FILTER_PROPERTY"];
}
if ($_REQUEST["FILTER_PROPERTY"] == "PROPERTY_cout_rooms" && $_REQUEST["FILTER_VALUES"] == "4+")
{
	$GLOBALS['FILTER_PROPERTY'] = ">=PROPERTY_cout_rooms";
	$GLOBALS['FILTER_VALUES'] = "4";
}
else
{
	$GLOBALS['FILTER_PROPERTY'] = $filterProperty;
	$GLOBALS['FILTER_VALUES'] = $filterValues;
}

if ($_REQUEST["catalog"] == "flat" && $GLOBALS['FILTER_PROPERTY'])
{
	
	if (strpos($GLOBALS['FILTER_PROPERTY'], "PROPERTY_cout_rooms") !== false)
	{
		$GLOBALS['FILTER_PROPERTY'] = str_replace("cout_rooms", "room_number", $GLOBALS['FILTER_PROPERTY']);
	}
}


$arSelect = Array("ID", "NAME");
$arFilter = array("IBLOCK_ID"=>14, "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
$districts = array();
while ($ob = $res->GetNextElement()) {
	$arTemp = $ob->GetFields();
	$districts[$arTemp["ID"]] = $arTemp;
}
if ($_REQUEST["catalog"] == "markets")
{
	$arTypeObject = array();
	$res = CIBlockPropertyEnum::GetList(array("VALUE"=>"ASC"),array("CODE"=>"type","IBLOCK_ID"=>12));
	while ($arProp = $res->GetNext())
	{
		$arTypeObject[$arProp["ID"]] = array("VALUE"=>$arProp["VALUE"],"ID"=>$arProp["ID"]);
	}
}


$RoomSizes = array(1=>1,2=>2,3=>3,"4+"=>"4+");
?>
<div class="filter-line">
	<div class="f-visible">
		<div class="sub_wrap">
			<div class="float_wrapper">
				<div class="secs">
					<form action="" name="FILTER_FORM" method="GET">
						<input type="hidden" name="FILTER_PROPERTY" value="<?=$_GET["FILTER_PROPERTY"]?>">
						<input type="hidden" name="FILTER_VALUE" value="<?=$_GET["FILTER_VALUE"]?>">
						<input type="hidden" name="SORT_BY" value="<?=$_GET["SORT_BY"]?>">
						<input type="hidden" name="SORT_ORDER" value="<?=(!empty($_GET["SORT_ORDER"]) ? $_GET["SORT_ORDER"] : 'DESC')?>">
						<input id="FILTER_FORM_SUBMIT" type="submit" style="display: none;">
					</form>
						<div class="sec sec-form">
							<div class="sec-head search"></div>
							<div class="form slide-pop">
								<span class="label">Название объекта</span>
								<div class="field" id="search">
									<input type="text" name="query" value="<?if($_GET["FILTER_PROPERTY"] == '%NAME') {echo $_GET["FILTER_VALUE"]; } ?>">
									<input type="submit" class="hidden" id="btn-search">
								</div>
								<a class='pick pick-search' href="javascript:void(0);" value="%NAME">Подобрать</a>
							</div>
						</div>
					<? if ($arParams["TIS_NEWBUILD"] == "Y")
					{
						//price_flat_min
						?>
					<div class="sec">
						<div class="sec-head">
							<span>Цена<? if($filterValuesCount == 2 && $filterProperty == '><PROPERTY_price_flat_min'): ?>:<strong>&nbsp;от <?=number_format($filterValues[0],0," "," ")?> до <?=number_format($filterValues[1],0," "," ")?></strong><? endif; ?></span>
						</div>
						<div class="form slide-pop">
							<?/* ?><span class="label">Цена</span><?*/?>
							<div class="field pricefield ">
								<input type="text"  name="from" placeholder="От" value="<? if ($filterProperty == '><PROPERTY_price_flat_min' && $filterValues[0]){echo($filterValues[0]);}else{echo("");}?>">
								<input type="text"  name="to" placeholder="До" value="<? if ($filterProperty == '><PROPERTY_price_flat_min' && $filterValues[1]){echo($filterValues[1]);}else{echo("");}?>">
							</div>
							<span>руб.</span>
							<a class="pick" href="javascript:void(0);" value="><PROPERTY_price_flat_min">Подобрать</a>
						</div>
					</div>
						<?
					}
					else
					{
						?>
						<div class="sec">
							<div class="sec-head">
								<span>Цена<? if($filterValuesCount == 2 && $filterProperty == '><PROPERTY_price'): ?>:<strong>&nbsp;от <?=number_format($filterValues[0],0," "," ")?> до <?=number_format($filterValues[1],0," "," ")?></strong><? endif; ?></span>
							</div>
							<div class="form slide-pop">
								<?/* ?><span class="label">Цена</span><?*/ ?>
								<div class="field pricefield ">
									<input type="text" name="from" placeholder="От" value="<? if ($filterProperty == '><PROPERTY_price' && $filterValues[0]){echo($filterValues[0]);}else{echo("");}?>">
									<input type="text" name="to" placeholder="До" value="<? if ($filterProperty == '><PROPERTY_price' && $filterValues[1]){echo($filterValues[1]);}else{echo("");}?>">
								</div>
								<span>руб.</span>
								<a class="pick" href="javascript:void(0);" value="><PROPERTY_price">Подобрать</a>
							</div>
						</div>
						<?
					}?>

					<?if($_REQUEST['catalog'] == 'flat'):?>
					<div class="sec">
						<div class="sec-head">
							<span>Тип квартиры<? if($filterValuesCount == 1 && $filterProperty == 'PROPERTY_estate_type_VALUE'): ?>:<strong>&nbsp;<?=$filterValues[0]?></strong><? endif; ?></span>
						</div>
						<div class="form slide-pop">
							<?/* ?><span class="label">Тип</span><?*/?>
							<div class="field">
								<select name="type" id="type">
									<option value="Вторичка">Вторичка</option>
									<option value="Недвижимость">Недвижимость</option>
									<option value="Ипотека">Ипотека</option>
								</select>
							</div>
							<a class="pick select" href="javascript:void(0);" value="PROPERTY_estate_type_VALUE">Подобрать</a>
						</div>
					</div>
					<?endif;?>
					<? 
					if ($_REQUEST['catalog'] == 'houses')
					{
					?>
					<div class="sec">
						<div class="sec-head">
							<span>Площадь<? if($filterValuesCount == 2 && $filterProperty == '><PROPERTY_summary_buildings_square'): ?>:<strong>&nbsp;от <?=$filterValues[0]?> до <?=$filterValues[1]?></strong><? endif; ?></span>
						</div>
						<div class="form slide-pop">
							<?/* ?><span class="label">Площадь</span><?*/?>
							<div class="field">
								<input type="number" min="10" name="from" placeholder="От" value="<? if ($filterProperty == '><PROPERTY_summary_buildings_square' && $filterValues[0]){echo($filterValues[0]);}else{echo("0");}?>">
								<input type="number" min="10" name="to" placeholder="До" value="<? if ($filterProperty == '><PROPERTY_summary_buildings_square' && $filterValues[1]){echo($filterValues[1]);}else{echo("0");}?>">
							</div>
							<span>м<sup>2</sup></span>
							<a class="pick" href="javascript:void(0);" value="><PROPERTY_summary_buildings_square">Подобрать</a>
						</div>
					</div>
					<?
					}
					else 
					{
						?>
						
						<div class="sec">
							<div class="sec-head">
								<span>Площадь<? if($filterValuesCount == 2 && $filterProperty == '><PROPERTY_square'): ?>:<strong>&nbsp;от <?=$filterValues[0]?> до <?=$filterValues[1]?></strong><? endif; ?></span>
							</div>
							<div class="form slide-pop">
								<div class="field">
									<input type="number" min="10" name="from" placeholder="От" value="<? if ($filterProperty == '><PROPERTY_square' && $filterValues[0]){echo($filterValues[0]);}else{echo("0");}?>">
									<input type="number" min="10" name="to" placeholder="До" value="<? if ($filterProperty == '><PROPERTY_square' && $filterValues[1]){echo($filterValues[1]);}else{echo("0");}?>">
								</div>
								<span>м<sup>2</sup></span>
								<a class="pick" href="javascript:void(0);" value="><PROPERTY_square">Подобрать</a>
							</div>
						</div>
						<?
					}
					?>
					<div class="sec">
						<div class="sec-head">
							<span>Расположение<? if($filterValuesCount == 1 && $filterProperty == 'PROPERTY_district'): ?>:<strong>&nbsp;<?=$districts[$filterValues[0]]["NAME"]?></strong><? endif; ?></span>
						</div>
						<div class="form slide-pop">
							<span class="label">Район</span>
							<div class="field">
								<select name="district" id="district">
									<option value="">Выберите</option>
									<? foreach($districts as $district):?>
										<option <?if($_REQUEST['FILTER_PROPERTY'] == 'PROPERTY_district' && $_REQUEST['FILTER_VALUE'] == $district['ID']) echo 'selected';?> value="<?=$district['ID']?>"><?=$district['NAME']?></option>
									<? endforeach; ?>
								</select>
							</div>
							<a class="pick" href="javascript:void(0);" value="PROPERTY_district">Подобрать</a>
						</div>
					</div>
					<? if ($_REQUEST["catalog"] == "markets")
					{
						?>
							<div class="sec">
								<div class="sec-head">
									<span>Тип <? if($filterValuesCount == 1 && $filterProperty == 'PROPERTY_type'): ?>:<strong>&nbsp;<?=$arTypeObject[$filterValues[0]]["VALUE"]?></strong><? endif; ?></span>
								</div>
								<div class="form slide-pop">
									<span class="label">Тип</span>
									<div class="field">
										<select name="district" id="district">
											<? foreach($arTypeObject as $Type):?>
												<option <?if($_REQUEST['FILTER_PROPERTY'] == 'PROPERTY_type' && $_REQUEST['FILTER_VALUE'] == $Type['ID']) echo 'selected';?> value="<?=$Type['ID']?>"><?=$Type['VALUE']?></option>
											<? endforeach; ?>
										</select>
									</div>
									<a class="pick" href="javascript:void(0);" value="PROPERTY_type">Подобрать</a>
								</div>
							</div>
						<?
					}?>
					<? if ($_REQUEST["catalog"] == "flat")
					{
						?>
							<div class="sec">
								<div class="sec-head">
									<span>Количество комнат <? if($filterValuesCount == 1 && $filterProperty == 'PROPERTY_cout_rooms'): ?>:<strong>&nbsp;<?=$RoomSizes[$filterValues[0]]?></strong><? endif; ?></span>
								</div>
								<div class="form slide-pop">
									<?/* ?><span class="label">Количество комнат</span><?*/ ?>
									<div class="field">
										<select name="district" id="district">
											<? foreach($RoomSizes as $Size):?>
												<option <?if($_REQUEST['FILTER_PROPERTY'] == 'PROPERTY_cout_rooms' && $_REQUEST['FILTER_VALUE'] == $Size) echo 'selected';?> value="<?=$Size?>"><?=$Size?></option>
											<? endforeach; ?>
										</select>
									</div>
									<a class="pick" href="javascript:void(0);" value="PROPERTY_cout_rooms">Подобрать</a>
								</div>
							</div>
						<?
					}?>
					<? 
					if ($arParams["TIS_NEWBUILD"] == "Y")
					{
						?>
						<div class="sec">
							<div class="sec-head">
								<span>Название новостройки<? if($filterValuesCount == 1 && $filterProperty == 'PROPERTY_name'): ?>:<strong>&nbsp;<?=$_REQUEST["FILTER_VALUE"]?></strong><? endif; ?></span>
							</div>
							<div class="form slide-pop">
								<div class="field">
									<input name="name" id="name" type="text" <? if($filterValuesCount == 1 && $filterProperty == 'PROPERTY_name'): ?> value="<?=$_REQUEST["FILTER_VALUE"]?>" <? endif; ?>>
								</div>
								<a class="pick" href="javascript:void(0);" value="PROPERTY_name">Подобрать</a>
							</div>
						</div>
					<?
					}
					?>
				</div>
				<? 
				if ($filterProperty)
				{
					?>
					<a href="<?=$APPLICATION->GetCurDir()?>" class="more filter-cancel">Сбросить все фильтры</a>
					<?
				}
				?>
				
				<?/* ?><a href="javascript:void(0);" class="more">Еще детали</a><? */?>
			</div>
		</div>
	</div>
	<div class="f-toggle slide-pop">
		<div class="sub_wrap"></div>
	</div>
</div>