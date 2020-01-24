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

$GLOBALS['FILTER_PROPERTY'] = $filterProperty;
$GLOBALS['FILTER_VALUES'] = $filterValues;
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
					<div class="sec">
						<div class="search"></div>
					</div>
					<div class="sec">
						<div class="sec-head">
							<span>Цена<? if($filterValuesCount == 2 && $filterProperty == '><PROPERTY_price'): ?>:<strong>&nbsp;от <?=$filterValues[0]?> до <?=$filterValues[1]?></strong><? endif; ?></span>
						</div>
						<div class="form slide-pop">
							<span class="label">Цена</span>
							<div class="field">
								<input type="number" min="10" name="from" placeholder="От" value="0">
								<input type="number" min="10" name="to" placeholder="До" value="0">
							</div>
							<span>м<sup>2</sup></span>
							<a class="pick" href="javascript:void(0);" value="><PROPERTY_price">Подобрать</a>
						</div>
					</div>
					<div class="sec">
						<div class="sec-head">
							<span>Тип квартиры<? if($filterValuesCount == 1 && $filterProperty == 'PROPERTY_type_VALUE'): ?>:<strong>&nbsp;<?=$filterValues[0]?></strong><? endif; ?></span>
						</div>
						<div class="form slide-pop">
							<span class="label">Тип</span>
							<div class="field">
								<select name="type" id="type">
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
								</select>
							</div>
							<a class="pick select" href="javascript:void(0);" value="PROPERTY_type_VALUE">Подобрать</a>
						</div>
					</div>
					<div class="sec">
						<div class="sec-head">
							<span>Площадь<? if($filterValuesCount == 2 && $filterProperty == '><PROPERTY_square'): ?>:<strong>&nbsp;от <?=$filterValues[0]?> до <?=$filterValues[1]?></strong><? endif; ?></span>
						</div>
						<div class="form slide-pop">
							<span class="label">Площадь</span>
							<div class="field">
								<input type="number" min="10" name="from" placeholder="От" value="0">
								<input type="number" min="10" name="to" placeholder="До" value="0">
							</div>
							<span>м<sup>2</sup></span>
							<a class="pick" href="javascript:void(0);" value="><PROPERTY_square">Подобрать</a>
						</div>
					</div>
					<div class="sec">
						<div class="sec-head">
							<span>Расположение<? if($filterValuesCount == 1 && $filterProperty == 'PROPERTY_location_VALUE'): ?>:<strong>&nbsp;<?=$filterValues[0]?></strong><? endif; ?></span>
						</div>
						<div class="form slide-pop">
							<span class="label">Расположение</span>
							<div class="field">
								<select name="location" id="location">
									<? foreach($locations as $location):?>
										<option value="<?=$location['NAME']?>"><?=$location['NAME']?></option>
									<? endforeach; ?>
								</select>
							</div>
							<a class="pick" href="javascript:void(0);" value="PROPERTY_location_VALUE">Подобрать</a>
						</div>
					</div>
				</div>
				<a href="#" class="more">Еще детали</a>
			</div>
		</div>
	</div>
	<div class="f-toggle slide-pop">
		<div class="sub_wrap"></div>
	</div>
</div>