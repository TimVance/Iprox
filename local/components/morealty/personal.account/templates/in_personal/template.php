<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

//my_print_r( $GLOBALS['USER_ACCOUNT'] );

/* @var $this CBitrixComponentTemplate */

?>

<div class="b-balans">
	<div class="balans">Баланс: 
	<? foreach ($arResult["ACCOUNT_LIST"] as $arBalance)
	{
		?>
		<span><?=$arBalance["SUM"]?></span>
		<?
	}?>
	</div>
	<? $addClass= ($_REQUEST["add_balance"] == "Y")? "click_me_at_start": ""; ?>
	<div class="add-balans"><a class="inline <?=$addClass?>" href="#pop1">Пополнить счёт</a></div>
</div><!--b-balans-->

<div class="b-history">
	<div class="history-pay"><a href="javascript:void(0);">История платежей</a></div>

	<div class="table-history">
		<table>
			<tr>
				<th>Дата</th>
				<th>Платежный документ</th>
				<th>Сумма</th>
				<th>Статус</th>
			</tr>
			<?/*foreach ($GLOBALS['USER_ACCOUNT']['PAYS'] as $pay) {
				?>
				<tr>
					<td><?=$pay['TIMESTAMP_X']?></td>
					<td><strong>
							<?if ($pay['PROPERTIES']['TYPE']['VALUE'] == 391 && $pay['PROPERTIES']['STATUS']['VALUE'] != 390) {?><a target="_blank" href="/personal/?DOC=<?=$pay['ID']?>"><?}?>
							№ <?=$pay['ID']?>

								<?if (trim($pay['PROPERTY_USER_PACKET_NAME']) != '') {?>
									(за <?=$pay['PROPERTY_USER_PACKET_NAME']?>)
								<?}?>

							<?if ($pay['PROPERTIES']['TYPE']['VALUE'] == 391 && $pay['PROPERTIES']['STATUS']['VALUE'] != 390) {?></a><?}?>
						</strong>
					</td>
					<td>
						<?if ($pay['PROPERTIES']['STATUS']['VALUE'] == 390) {?> &mdash; <?}?>
						<?=number_format($pay['PROPERTIES']['SUMM']['VALUE'], 0, '.', ' ')?> руб.</td>
					<td>
						<?if ($pay['PROPERTIES']['STATUS']['VALUE'] == 387) {?>
							<strong>
						<?}?>

						<?=$pay['PROPERTIES']['STATUS']['VALUE_ENUM']?>

						<?if ($pay['PROPERTIES']['STATUS']['VALUE'] == 387) {?>
							</strong>
						<?}?>
					</td>
				</tr>
			<?}*/?>
			<?foreach ($arResult["TRANSACTIONS"] as $arTransaction) {
				?>
				<tr>
					<td><?=$arTransaction['TRANSACT_DATE']?></td>
					<td><strong>
						<?
						if ($arTransaction["LINK"])
						{
							?>
							<a href="<?=$arTransaction["LINK"]?>" target="_blank">
							<?
						}
						?>
							№ <?=$arTransaction["ID"]?>
							
						<?
						if ($arTransaction["LINK"])
						{
							?>
							</a>
							<?
						}
						?>
						</strong>
					</td>
					<td>
						<?=CurrencyFormat($arTransaction["SUM"], $arTransaction["CUR"])?>
					<td>
						<strong>
						<?
						if (in_array($arTransaction["PLUS"], array("Y", "N")))
						{
							?>
							<?if ($arTransaction["BUY"] == "Y") 
							{
								?>Покупка пакета<?
							}
							else if ($arTransaction["PLUS"] == "Y"){
								?>Пополнение счета<?
							}?>
							<?
						}
						else 
						{
							echo($arTransaction["PLUS"]);
						}
						?>
						
						</strong>
						
					</td>
				</tr>
			<?}?>
			<? //$arResult["TRANSACTIONS"]?>
		</table>
	</div><!--table-history-->
</div><!--b-history-->




<?$this->SetViewTarget('PAY_POPUPS');?>
<div id="pop1" class="pop pay-step1">
	<div class="close"></div>
	<div class="t-pop">Пополнение счёта</div>
	<div class="field-pop field-pop3">
		<label>Сумма</label>
		<input type="text" name="summ" class="summ" />
		<span>руб.</span>
	</div>
	<div class="field-pop field-pop3 sel-pr select-type-order">
		<label>Способ оплаты</label>
		<select name="type-order" id="type-order">
			<option value="bnk">Банк</option>
			<option value="poo">PayOnline</option>
		</select>
	</div>
	<div class="buts">
		<div class="but-pop but-pop2">
			<button type="submit" class="bill"
				<?if ($arResult['BILL_FIELDS_FILLED']) {?> data-href="#pop2" <?} else {?> data-href="#pop3" <?}?>>Запросить счёт</button>
		</div>
		<?/*<div class="but-pop but-pop2"><button type="submit" class="card">Оплатить картой</button></div>*/?>
	</div>
</div><!--pop-->

<div id="pop2" class="pop pop2">
	<div class="close"></div>
	<div class="t-pop t-pop2">Счет на оплату</div>

	<div class="price-pop"><span>_</span> руб.</div>

	<div class="score-pop">
		Ваш счёт готов! Вы можете загрузить его по ссылке:<br>
		<span><a href="#">№ _</a></span>
	</div>

	<div class="history-score">Счёт для загрузки всегда доступен в <a href="/personal/">«Истории платежей»</a></div>

	<div class="but-pop"><button type="submit" onclick="$('.close').trigger('click'); location.reload();">Готово</button></div>
</div><!--pop-->

<div id="pop3" class="pop pop2">
	<div class="close"></div>
	<div class="t-pop t-pop3">Внимание</div>
	<div class="rekviz-pop">
		<p>Мы не можем выставить Вам счёт, поскольку вы не указали реквизиты.</p>
		<a href="/personal/requisites/">Заполнить реквизиты</a>
	</div><!--rekviz-pop-->
	<div class="but-pop"><button type="submit" onclick="$('.close').trigger('click'); location.reload();">Готово</button></div>
</div><!--pop-->
<a href="#pay-oonline" id="call-pay-online" class="payOnline"></a>
<div id="pay-oonline" class="pop pop2">
	<div class="close"></div>
<?$APPLICATION->IncludeComponent("bitrix:sale.account.pay",
    "add_founds",
    Array(
        "ELIMINATED_PAY_SYSTEMS" => array("1"),
        "PATH_TO_BASKET" => "/personal/cart",
        "PATH_TO_PAYMENT" => "/test_pay.php",
        "PERSON_TYPE" => "1",
        "REFRESHED_COMPONENT_MODE" => "Y",
    	"SELL_CURRENCY" => "RUB",

    )
);?>
</div>
<?/*<div id="pop4" class="pop pop2">
		<div class="close"></div>
		<div class="t-pop t-pop4">Пополнение счёта картой</div>
		<div class="pay-p">
			<p>Платёж выполнен успешно.</p>
		</div><!--pay-p-->
		<div class="but-pop"><button type="submit">Готово</button></div>
	</div><!--pop-->*/?>

<?$this->EndViewTarget();?>

<br><br><br>

<?
$DateActiveTo = \Bitrix\Main\Type\DateTime::createFromUserTime($arResult['ACCOUNT']["ACTIVE_TO"]);

$bActive = $arResult['ACCOUNT']["ACTIVE_TO"] && $DateActiveTo->getTimestamp() > time();
$text = ($bActive)? "Активен до ".$DateActiveTo->toString() : "Аккаунт не активен";
?>
<div class="b-pakets">
	<div class="t-paket"><?=$text?></div>
</div>

<?
if ($arResult["PACKETS"] && count($arResult["PACKETS"]) > 0)
{
	?>
	<div class="b-pakets">
		<div class="t-paket">Мои пакеты</div>
	
		<div class="nav-pakets">
			<div class="sort-object">
				<p>Мои объекты:</p>
				<ul>
					<? 
					foreach ($arResult["PACKETS"] as $arPacket)
					{
						if (!$arPacket["NAME"])
							continue;
						?>
							<li><?=$arPacket["NAME"]?> (<?=$arPacket["CNT"]?> шт.)</li>
						<?
					}
					?>
					
				</ul>
			</div><!--sort-object-->
	
			<div class="buy-pakets"><a href="/personal/service/">Купить пакеты</a></div>
		</div>
	</div>
	<?
}
?>
<?/* ?><div class="b-pakets">
	<div class="t-paket">Мои пакеты</div>

	<div class="nav-pakets">
		<div class="sort-object">
			<p>Мои объекты:</p>
			<ul>
				<? 
				foreach ($arResult["PACKETS"] as $arPacket)
				{
					if (!$arPacket["NAME"])
						continue;
					?>
						<li><?=$arPacket["NAME"]?> (<?=$arPacket["CNT"]?> шт.)</li>
					<?
				}
				?>
				
			</ul>
		</div><!--sort-object-->

		<div class="buy-pakets"><a href="/personal/service/">Купить пакеты</a></div>
	</div><!--nav-pakets-->
</div><!--b-pakets-->
<?


if ($arResult["PACKETS"] && count($arResult["PACKETS"]) > 0)
{
	$iblockForms = array(
			7 => 'квартира|квартиры|квартир',
			11 => 'коммерческая недвижимость|коммерческие недвижимости|коммерческой недвижимости',
			8 => 'дом|дома|домов',
			9 => 'поселок|поселка|поселков',
			10 => 'участок|участка|участков',
			12 => 'торговый цент|торговых центра|торговых центов',
			13 => 'инвестиционный проект|инвестиционных проекта|инвестиционных проектов'
	);
	
	?>
	<div class="list-pakets">
		<ul>
			<?foreach ($arResult["PACKETS"] as $arPacket) {
				$bIsDate = (bool) $arPacket["DATES"];
				$cntDays = 0;
				if ($bIsDate)
				{
					$startDate = reset($arPacket["DATES"])["START_DATE"];
					$endDate = end($arPacket["DATES"])["END_DATE"];
					$obStartDate = new \DateTime($startDate);
					$obEndDate = new \DateTime($endDate);
					$interVal = $obEndDate->diff(new \DateTime());
					$cntDays = $interVal->format("%d");
				}
				$arObjectsCount = array();
				foreach ($arPacket["REAL_POSITIONS"] as $arPostion)
				{
					$arObjectsCount[] = '<strong>'.$arPostion["VALUE"].'</strong> '.Suffix($arPostion["VALUE"], $iblockForms[$arPostion["ID"]]);
				}
				?>
				<li <?if ($cntDays <= 0) {?>class="no-active"<?}?>>
					<div class="tit-pakets"><p><?=$arPacket["NAME"]?></p></div>
					<div class="num-pakets">
						<?=implode(", ", $arObjectsCount);?>
					</div>
					<?
					if ($arPacket['PRICE'])
					{
						?>
						<div class="price-pakets">
							<?=MoneyOutPut($arPacket['PRICE'])?> руб.
						</div>
						<?
					}
					?>
					
					<div class="condit-tarif">
						<?
						if ($bIsDate && $startDate && $endDate)
						{
							?>
							<div class="shelf">Активен с <?=FormatDate('d.m.Y', MakeTimeStamp($startDate))?> по
								<?=FormatDate('d.m.Y', MakeTimeStamp($endDate))?>
							</div>
							<?
						}
						?>
						
						<div class="days-off">Осталось <?=$cntDays?> <?=Suffix($cntDays, 'день|дня|дней')?></div>
					</div>
					<?if ($cntDays <= 0) {?>
						<div class="archive">в архиве</div>
					<?}?>
				</li>
			<?}?>
		</ul>
	</div>
	
	<?
}*/
?>

<?/*$APPLICATION->IncludeComponent('wexpert:iblock.list', 'personal.user_packets', array(
	'IBLOCK_ID'     => 26,
	'PAGESIZE'      => 8,
	'ORDER'         => array('ID' => 'DESC'),
	'SELECT'        => array(
		'DATE_ACTIVE_FROM', 'DATE_ACTIVE_TO',
		'PROPERTY_package.PROPERTY_price', 'PROPERTY_package.PROPERTY_count_7', 'PROPERTY_package.PROPERTY_count_11',
		'PROPERTY_package.PROPERTY_count_8', 'PROPERTY_package.PROPERTY_count_9', 'PROPERTY_package.PROPERTY_count_10',
		'PROPERTY_package.PROPERTY_count_12', 'PROPERTY_package.PROPERTY_count_13',
	),
	'FILTER'        => array('PROPERTY_owner' => (int)$USER->GetID()),
	'GET_PROPERTY'  => 'Y',
	'CACHE_TIME'    => 0, //нужно включить кеш
	'SET_404'       => 'N'
), $component);*/?>

