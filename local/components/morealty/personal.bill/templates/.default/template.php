<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/* @var $this CBitrixComponentTemplate */

$APPLICATION->RestartBuffer();

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Счет</title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?=LANG_CHARSET?>">
	<style>
		table { border-collapse: collapse; }
		table.acc td { border: 1pt solid #000000; padding: 0pt 3pt; line-height: 21pt; }
		table.it td { border: 1pt solid #000000; padding: 0pt 3pt; }
		table.sign td { font-weight: bold; vertical-align: bottom; }
		table.header td { padding: 0pt; vertical-align: top; }
	</style>
</head>

<?

if ($_REQUEST['BLANK'] == 'Y')
	$blank = true;

$pageWidth  = 595.28;
$pageHeight = 841.89;

$background = '#ffffff';

$margin = array(
	'top' => false,
	'right' => false,
	'bottom' => false,
	'left' => false
);

$width = $pageWidth - $margin['left'] - $margin['right'];

?>

<body style="margin: 0pt; padding: 0pt;"<? if ($_REQUEST['PRINT'] == 'Y') { ?> onload="setTimeout(window.print, 0);"<? } ?>>

<div style="margin: 0pt; padding: <?=join('pt ', $margin); ?>pt; width: <?=$width; ?>pt; background: <?=$background; ?>">

	<table class="header">
		<tr>
			<td>
				<b>Мориэлти</b><br>
				<b>Россия, Сочи</b><br>
				<b><?=sprintf("Тел.: %s", '8 (926) 333-88-99'); ?></b><br>
			</td>
		</tr>
	</table>


	<table class="acc" width="100%">
		<colgroup>
			<col width="29%">
			<col width="29%">
			<col width="10%">
			<col width="32%">
		</colgroup>
		<tr>
			<td>
				<?=sprintf("ИНН %s", "SELLER_INN"); ?>
			</td>
			<td>
				<?=sprintf("КПП %s", "SELLER_KPP"); ?>
			</td>
			<td rowspan="2">
				<br>
				<br>
				Сч. №
			</td>
			<td rowspan="2">
				<br>
				<br>
				<?=$sellerRs; ?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				Получатель<br>
				<?=$arResult['arUser']['UF_FULL_NAME'];?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				Банк получателя<br>
				<?=$arResult['arUser']['UF_BANK_NAME']?>
			</td>
			<td>
				БИК<br>
				Сч. №
			</td>
			<td>
				<?="SELLER_BIK"?><br>
				<?="SELLER_KS"?>
			</td>
		</tr>
	</table>

	<br>
	<br>

	<table width="100%">
		<colgroup>
			<col width="50%">
			<col width="0">
			<col width="50%">
		</colgroup>
		<tr>
			<td></td>
			<td style="font-size: 2em; font-weight: bold; text-align: center"><nobr><?=sprintf(
						"СЧЕТ № %s от %s",
						htmlspecialcharsbx($arResult['doc']['ID']),
						$arResult['doc']['TIMESTAMP_X']
					); ?></nobr></td>
			<td></td>
		</tr>
		<? /*if (CSalePaySystemAction::GetParamValue("ORDER_SUBJECT", false)) { ?>
			<tr>
				<td></td>
				<td><?=CSalePaySystemAction::GetParamValue("ORDER_SUBJECT", false); ?></td>
				<td></td>
			</tr>
		<? } ?>
		<? if (CSalePaySystemAction::GetParamValue("DATE_PAY_BEFORE", false)) { ?>
			<tr>
				<td></td>
				<td><?=sprintf(
						"Срок оплаты %s",
						ConvertDateTime(CSalePaySystemAction::GetParamValue("DATE_PAY_BEFORE", false), FORMAT_DATE)
							?: CSalePaySystemAction::GetParamValue("DATE_PAY_BEFORE", false)
					); ?></td>
				<td></td>
			</tr>
		<? } */?>
	</table>

	<br>
	<?

	if (trim($arResult['arUser']['UF_FULL_NAME']) != '') {

		echo sprintf(
			"Плательщик: %s",
			$arResult['arUser']['UF_FULL_NAME']
		);
		echo sprintf(" ИНН %s", $arResult['arUser']['UF_INN']);
		echo sprintf(", %s", $arResult['arUser']['UF_LEGAL_ADDRESS']);

		/*if (CSalePaySystemAction::GetParamValue("BUYER_PAYER_NAME", false))
			echo sprintf(", %s", CSalePaySystemAction::GetParamValue("BUYER_PAYER_NAME", false));*/
	}

	?>

	<br>
	<br>

	<?
	$arBasketItems = array(
		array(
			"NAME"  => "Пополнение личного счета на сайте",
			'QUANTITY' => 1,
			'PRICE' => $arResult['doc']['PROPERTIES']['SUMM']['VALUE'],
			'VAT_RATE' => 0
		)
	);


	if (!empty($arBasketItems))
	{
		$arCells = array();
		$arProps = array();

		$n = 0;
		$sum = 0.00;
		$vat = 0;
		foreach($arBasketItems as $arBasket)
		{
			$productName = $arBasket["NAME"];


			$arCells[++$n] = array(
				1 => $n,
				htmlspecialcharsbx($productName),
				roundEx($arBasket["QUANTITY"]),
				'шт.',
				number_format($arBasket["PRICE"], 0, '.', ' ') . ' руб',
				roundEx($arBasket["VAT_RATE"]*100) . "%",
				number_format($arBasket["PRICE"] * $arBasket["QUANTITY"], 0, '.', ' ') . ' руб'
			);

			$sum += doubleval($arBasket["PRICE"] * $arBasket["QUANTITY"]);
			$vat = max($vat, $arBasket["VAT_RATE"]);
		}


		$items = $n;

		if (!$taxes)
		{
			$arCells[++$n] = array(
				1 => null,
				null,
				null,
				null,
				null,
				htmlspecialcharsbx("НДС:"),
				htmlspecialcharsbx("Без НДС")
			);
		}


		$arCells[++$n] = array(
			1 => null,
			null,
			null,
			null,
			null,
			"Итого:",
			number_format($arBasket["PRICE"], 0, '.', ' ') . ' руб'
		);
	}


	$currency = trim('Рублей РФ');
	?>
	<table class="it" width="100%">
		<tr>
			<td><nobr>№</nobr></td>
			<td><nobr>Наименование товара</nobr></td>
			<td><nobr>Кол-во</nobr></td>
			<td><nobr>Ед.</nobr></td>
			<td><nobr>Цена, <?=$currency; ?></nobr></td>
			<? if ($vat > 0) { ?>
				<td><nobr>Ставка НДС</nobr></td>
			<? } ?>
			<td><nobr>Сумма, <?=$currency; ?></nobr></td>
		</tr>
		<?

		$rowsCnt = count($arCells);
		for ($n = 1; $n <= $rowsCnt; $n++)
		{
			$accumulated = 0;

			?>
			<tr valign="top">
				<? if (!is_null($arCells[$n][1])) { ?>
					<td align="center"><?=$arCells[$n][1]; ?></td>
				<? } else {
					$accumulated++;
				} ?>
				<? if (!is_null($arCells[$n][2])) { ?>
					<td align="left"
					    style="word-break: break-word; word-wrap: break-word; <? if ($accumulated) {?>border-width: 0pt 1pt 0pt 0pt; <? } ?>"
					    <? if ($accumulated) { ?>colspan="<?=($accumulated+1); ?>"<? $accumulated = 0; } ?>>
						<?=$arCells[$n][2]; ?>
						<? if (isset($arProps[$n]) && is_array($arProps[$n])) { ?>
							<? foreach ($arProps[$n] as $property) { ?>
								<br>
								<small><?=$property; ?></small>
							<? } ?>
						<? } ?>
					</td>
				<? } else {
					$accumulated++;
				} ?>
				<? for ($i = 3; $i <= 7; $i++) { ?>
					<? if (!is_null($arCells[$n][$i])) { ?>
						<? if ($i != 6 || $vat > 0 || is_null($arCells[$n][2])) { ?>
							<td align="right"
								<? if ($accumulated) { ?>
									style="border-width: 0pt 1pt 0pt 0pt"
									colspan="<?=(($i == 6 && $vat <= 0) ? $accumulated : $accumulated+1); ?>"
									<? $accumulated = 0; } ?>>
								<nobr><?=$arCells[$n][$i]; ?></nobr>
							</td>
						<? }
					} else {
						$accumulated++;
					}
				} ?>
			</tr>
			<?

		}

		?>
	</table>
	<br>

	<?=sprintf(
		"Всего наименований %s, на сумму %s",
		$items,
		number_format($arBasket["PRICE"], 0, '.', ' ')
	); ?>
	<br>

	<b>
		<?
		echo Number2Word_RusLib($arBasket["PRICE"]);
		?>
	</b>

	<br>
	<br>

	<?/* if (CSalePaySystemAction::GetParamValue("COMMENT1", false) || CSalePaySystemAction::GetParamValue("COMMENT2", false)) { ?>
		<b>Условия и комментарии</b>
		<br>
		<? if (CSalePaySystemAction::GetParamValue("COMMENT1", false)) { ?>
			<?=nl2br(HTMLToTxt(preg_replace(
				array('#</div>\s*<div[^>]*>#i', '#</?div>#i'), array('<br>', '<br>'),
				htmlspecialcharsback(CSalePaySystemAction::GetParamValue("COMMENT1", false))
			), '', array(), 0)); ?>
			<br>
			<br>
		<? } ?>
		<? if (CSalePaySystemAction::GetParamValue("COMMENT2", false)) { ?>
			<?=nl2br(HTMLToTxt(preg_replace(
				array('#</div>\s*<div[^>]*>#i', '#</?div>#i'), array('<br>', '<br>'),
				htmlspecialcharsback(CSalePaySystemAction::GetParamValue("COMMENT2", false))
			), '', array(), 0)); ?>
			<br>
			<br>
		<? } ?>
	<? } */?>

	<br>
	<br>

	<? /*if (!$blank) { ?>
		<div style="position: relative; "><?=CFile::ShowImage(
				CSalePaySystemAction::GetParamValue("PATH_TO_STAMP", false),
				160, 160,
				'style="position: absolute; left: 40pt; "'
			); ?></div>
	<? } ?>

	<div style="position: relative">
		<table class="sign">
			<? if (CSalePaySystemAction::GetParamValue("SELLER_DIR_POS", false)) { ?>
				<tr>
					<td style="width: 150pt; "><?=CSalePaySystemAction::GetParamValue("SELLER_DIR_POS", false); ?></td>
					<td style="width: 160pt; border: 1pt solid #000000; border-width: 0pt 0pt 1pt 0pt; text-align: center; ">
						<? if (!$blank) { ?>
							<?=CFile::ShowImage(CSalePaySystemAction::GetParamValue("SELLER_DIR_SIGN", false), 200, 50); ?>
						<? } ?>
					</td>
					<td>
						<? if (CSalePaySystemAction::GetParamValue("SELLER_DIR", false)) { ?>
							(<?=CSalePaySystemAction::GetParamValue("SELLER_DIR", false); ?>)
						<? } ?>
					</td>
				</tr>
				<tr><td colspan="3">&nbsp;</td></tr>
			<? } ?>
			<? if (CSalePaySystemAction::GetParamValue("SELLER_ACC_POS", false)) { ?>
				<tr>
					<td style="width: 150pt; "><?=CSalePaySystemAction::GetParamValue("SELLER_ACC_POS", false); ?></td>
					<td style="width: 160pt; border: 1pt solid #000000; border-width: 0pt 0pt 1pt 0pt; text-align: center; ">
						<? if (!$blank) { ?>
							<?=CFile::ShowImage(CSalePaySystemAction::GetParamValue("SELLER_ACC_SIGN", false), 200, 50); ?>
						<? } ?>
					</td>
					<td>
						<? if (CSalePaySystemAction::GetParamValue("SELLER_ACC", false)) { ?>
							(<?=CSalePaySystemAction::GetParamValue("SELLER_ACC", false); ?>)
						<? } ?>
					</td>
				</tr>
			<? } ?>
		</table>
	</div>
    */?>

</div>

</body>
</html>