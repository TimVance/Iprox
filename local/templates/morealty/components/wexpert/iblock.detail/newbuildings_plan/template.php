<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
$IBLOCK_ID = 19;
$ELEMENT_ID = $_REQUEST['ID'];
CIBlockElement::CounterInc($arResult['ID']);

$arProperties = $arResult['PROPERTIES'];

$arItems = $arResult['arItems'];
//$arUser = $arResult['arUser'];
$GLOBALS['CURRENT_NEWBUILDING'] = $arResult;
$arResult['PROPERTIES'] = $arProperties;
?>
<?
?>
<div class="adress-agency">
	<?=ImplodeArrguments(",", $arProperties['city']['MODIFIED_VALUE'], $arProperties['district']['MODIFIED_VALUE'], $arProperties['microdistrict']['MODIFIED_VALUE'], $arProperties['street']['VALUE']); ?>
	</div>

<?$PlanResult =  $APPLICATION->IncludeComponent("morealty:newbuildings.plan","", array("MOTHER_ID"=>$arResult['ID'],"CACHE_TIME"    => 3600),$component); ?>

<?
$arIds = array();
foreach ($PlanResult["ITEMS"] as $arItem)
{
	$arIds[] = $arItem["ID"];
}
$arSelect = array('ID', 'NAME', 'IBLOCK_ID', 'PROPERTY_*', 'SHOW_COUNTER', 'TIMESTAMP_X');
$arFilter = Array("IBLOCK_ID" => 7, "ACTIVE" => "Y", "PROPERTY_newbuilding" => $ELEMENT_ID,"ID"=>$arIds);
$res = CIBlockElement::GetList(Array('sort' => 'desc'), $arFilter, false, false, $arSelect);
$arItems = array();
$flatsCount = $res->SelectedRowsCount();
while($ob = $res->GetNextElement()) {
	$item = $ob->GetFields();

	$item['PROPERTIES'] = $ob->GetProperties();
	if($item['PROPERTIES']['currency']['VALUE'] == "RUB") {
		$arItems['rub'][] = $item;
	} else if($item['PROPERTIES']['currency']['VALUE'] == "USD") {
		$arItems['usd'][] = $item;
	} else if($item['PROPERTIES']['currency']['VALUE'] == "EUR") {
		$arItems['eur'][] = $item;
	}
}
$arResult['arItems'] = $arItems;
?>
<? if ((count($arItems['rub']) > 0) || (count($arItems['usd']) > 0) || (count($arItems['eur']) > 0))
{
?>
	<div class="all-propos sm">
		<div class="t-emploe-wrap">
			<div class="t-emploe">Все предложения застройщика/инвестора </div>
			<div class="sel-on">
				<select name="currency-table">
						<? if ((count($arItems['rub']) > 0))
						{
							?>
							<option value="rub">Руб</option>
							<?
						}?>
						<? if ((count($arItems['usd']) > 0))
						{
							?>
							<option value="usd">USD</option>
							<?
						}?>
						<? if ((count($arItems['eur']) > 0))
						{
							?>
							<option value="eur">EUR</option>
							<?
						}?>
					
				</select>
			</div>
		</div>
		<? if (count($arItems['rub']) > 0)
		{
			?>
				<div id="table-rubles" class="table-history table-history2 bh" style="display:none;">
					<table id="tb1">
						<thead>
						<tr>
							<th><a href="javascript:void(0);">Предложение</a>	<i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
							<th><a href="javascript:void(0);">Площадь</a> <i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
							<th><a href="javascript:void(0);">Этаж/этажей</a> <i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
							<th><a href="javascript:void(0);">Цена</a> <i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
							<th><a href="javascript:void(0);">Цена м<sup>2</sup></a> <i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
							<th><a href="javascript:void(0);">Обновлено</a> <i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
							<th><a href="javascript:void(0);">Просмотров</a> <i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
						</tr>
						</thead>
						<tbody>
						<?foreach($arItems['rub'] as $item):?>
							<tr>
								<td><?=$item['PROPERTIES']['room_number']['VALUE']?>-комнатная</td>
								<td><strong><?=$item['PROPERTIES']['square']['VALUE']?></strong></td>
								<td><?=$item['PROPERTIES']['floor']['VALUE']?></td>
								<td><strong><?=$item['PROPERTIES']['price']['VALUE']?></strong></td>
								<td><?=$item['PROPERTIES']['price_1m']['VALUE']?></td>
								<? $day = rus_date("j F Y", strtotime($item['TIMESTAMP_X'])); ?>
								<?//CConsole::log(rus_date("j F Y", strtotime(date("Y-m-d H:i:s")))); ?>
								<td><?=$day?></td>
								<td><?=$item['SHOW_COUNTER']?></td>
							</tr>
						<?endforeach;?>
						</tbody>
					</table>
				</div>
			<?
		}?>

		<? if (count($arItems['usd']) > 0)
		{
			?>
				<div id="table-dollars" class="table-history table-history2 bh" style="display:none;">
					<table id="tb2">
						<thead>
						<tr>
							<th><a href="javascript:void(0);">Предложение</a>	<i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
							<th><a href="javascript:void(0);">Площадь</a> <i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
							<th><a href="javascript:void(0);">Этаж/этажей</a> <i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
							<th><a href="javascript:void(0);">Цена</a> <i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
							<th><a href="javascript:void(0);">Цена м<sup>2</sup></a> <i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
							<th><a href="javascript:void(0);">Обновлено</a> <i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
							<th><a href="javascript:void(0);">Просмотров</a> <i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
						</tr>
						</thead>
						<tbody>
						<?foreach($arItems['usd'] as $item):?>
							<tr>
								<td><?=$item['PROPERTIES']['room_number']['VALUE']?>-комнатная</td>
								<td><strong><?=$item['PROPERTIES']['square']['VALUE']?></strong></td>
								<td><?=$item['PROPERTIES']['floor']['VALUE']?></td>
								<td><strong><?=$item['PROPERTIES']['price']['VALUE']?></strong></td>
								<td><?=$item['PROPERTIES']['price_1m']['VALUE']?></td>
								<? $day = rus_date("j F Y", strtotime($item['TIMESTAMP_X'])); ?>
								<?//CConsole::log(rus_date("j F Y", strtotime(date("Y-m-d H:i:s")))); ?>
								<td><?=$day?></td>
								<td><?=$item['SHOW_COUNTER']?></td>
							</tr>
						<?endforeach;?>
						</tbody>
					</table>
				</div>
			<?
		}?>

		<? if (count($arItems['eur']) > 0)
		{
			?>
				<div id="table-euros" class="table-history table-history2 bh" style="display:none;">
					<table id="tb3">
						<thead>
						<tr>
							<th><a href="javascript:void(0);">Предложение</a>	<i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
							<th><a href="javascript:void(0);">Площадь</a> <i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
							<th><a href="javascript:void(0);">Этаж/этажей</a> <i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
							<th><a href="javascript:void(0);">Цена</a> <i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
							<th><a href="javascript:void(0);">Цена м<sup>2</sup></a> <i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
							<th><a href="javascript:void(0);">Обновлено</a> <i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
							<th><a href="javascript:void(0);">Просмотров</a> <i class="uarr">&uarr;</i><i class="darr">&darr;</i></th>
						</tr>
						</thead>
						<tbody>
						<?foreach($arItems['eur'] as $item):?>
							<tr>
								<td><?=$item['PROPERTIES']['room_number']['VALUE']?>-комнатная</td>
								<td><strong><?=$item['PROPERTIES']['square']['VALUE']?></strong></td>
								<td><?=$item['PROPERTIES']['floor']['VALUE']?></td>
								<td><strong><?=$item['PROPERTIES']['price']['VALUE']?></strong></td>
								<td><?=$item['PROPERTIES']['price_1m']['VALUE']?></td>
								<? $day = rus_date("j F Y", strtotime($item['TIMESTAMP_X'])); ?>
								<?//CConsole::log(rus_date("j F Y", strtotime(date("Y-m-d H:i:s")))); ?>
								<td><?=$day?></td>
								<td><?=$item['SHOW_COUNTER']?></td>
							</tr>
						<?endforeach;?>
						</tbody>
					</table>
				</div>
			<?
		}?>

	</div>
<? }?>
	<div class="block-developer">
		<?
		if ($arProperties["builder"]["VALUE"])
		{
			?><?\SiteTemplates\Builder::getInstance($arProperties["builder"]["VALUE"])->shortInfo()?><?
		}
		?>


			
			<? 
			if ($arProperties['CONTACT_PERSON_ID'])
			{
				?>
				<div class="contacts-face">
					<div class="t-face">Контактные лица</div>
				<?
				\SiteTemplates\Realtor::getInstance($arProperties['CONTACT_PERSON_ID'])->supershortInfo();
				?>
				</div>
				<?
			}
			?>
		
	</div>