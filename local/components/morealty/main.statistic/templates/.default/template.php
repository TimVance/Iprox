<div class="block-info sale_wrapper_elements">
	<?

	$averageNewbuilding = $arResult["averageNewbuilding"];
	$averageResells = $arResult["averageResells"];
	$averageLand = $arResult["averageLand"];




 	?>
	<div class="sale ">
		<div class="tit">Продажа</div>
		<ul>
			<? foreach($arResult['SELL'] as $item): ?>
				<?
				$bLink = (bool) $arParams["SELL_LINK_TEMPLATE"];
				?>
				<?
				if ($bLink)
				{
					$link = str_replace("#code#", $item["CODE"], $arParams["SELL_LINK_TEMPLATE"]);
					?>
					<li><a href="<?=$link?>"><?=$item['NAME']?></a><span><?=$item['COUNT']?></span></li>
					<?
				}
				else
				{
					?>
					<li><?=$item['NAME']?><span><?=$item['COUNT']?></span></li>
					<?
				}
				?>
				
			<? endforeach; ?>
		</ul>
	</div><!--sale-->
	<?
	CModule::IncludeModule("currency");
	$db_rate = CCurrencyRates::GetList($by, $order, $arFilter);
	while($ar_rate = $db_rate->Fetch())
	{
		if($ar_rate['CURRENCY'] == 'USD') {
			$USD = $ar_rate['RATE'];
		} else if($ar_rate['CURRENCY'] == 'EUR') {
			$EUR = $ar_rate['RATE'];
		}
	}
	?>

	<div class="price-property">
		<div class="tit-prop">Цены на недвижимость в Сочи</div>
		<div class="date-prop"><span>от <?=rus_date("j F Y", strtotime(date("d.m.y")))?></span></div>

		<div class="list-price-prop" <?=$arParams["SHOW_DYNAMIC"] !== "Y"? 'style="padding-right:24px"' : "" ?>>
			<ul>
				<li>
					<span class="val-pr">м<sup>2</sup> в новостройке</span>
					<span class="num-price">
						<span class="currency-wrap currency-rub"><?=priceDigit(intval($averageNewbuilding))?></span>
						<span class="currency-wrap currency-usd"><?=priceDigit(intval($averageNewbuilding / $USD))?></span>
						<span class="currency-wrap currency-eur"><?=priceDigit(intval($averageNewbuilding / $EUR))?></span>
						<?
						$prefix = "";
						if ($arResult["averageNewbuildingInc"] > 0)
						{
							$class="";
							$prefix = "+";
						}
						else if ($arResult["averageNewbuildingInc"] < 0)
						{
							$class="down";
						}
						else
						{
							$class="grey";
						}
						?>
						<?
						if ($arParams["SHOW_DYNAMIC"] == "Y")
						{
							?>
								<sup class="<?=$class?>"><?=$prefix?><?=number_format($arResult["averageNewbuildingInc"],2)?></sup>
							<?
						}
						?>
						
					</span>
				</li>
				<li>
					<span class="val-pr">м<sup>2</sup> вторичное жильё</span>
					<span class="num-price">
						<span class="currency-wrap currency-rub"><?=priceDigit(intval($averageResells))?></span>
						<span class="currency-wrap currency-usd"><?=priceDigit(intval($averageResells / $USD))?></span>
						<span class="currency-wrap currency-eur"><?=priceDigit(intval($averageResells / $EUR))?></span>
						<? 
						$prefix = "";
						if ($arResult["averageResellsInc"] > 0)
						{
							$class="";
							$prefix = "+";
						}
						else if ($arResult["averageResellsInc"] < 0)
						{
							$class="down";
						}
						else
						{
							$class="grey";
						}
						?>
						<?
						if ($arParams["SHOW_DYNAMIC"] == "Y")
						{
							?>
								<sup class="<?=$class?>"><?=$prefix?><?=number_format($arResult["averageResellsInc"],2)?></sup>
							<?
						}
						?>
						
					</span>
				</li>
				<li>
					<span class="val-pr">1 сотка земли</span>
					<span class="num-price">
						<span class="currency-wrap currency-rub"><?=priceDigit(intval($averageLand))?></span>
						<span class="currency-wrap currency-usd"><?=priceDigit(intval($averageLand / $USD))?></span>
						<span class="currency-wrap currency-eur"><?=priceDigit(intval($averageLand / $EUR))?></span>
						<? 
						$prefix = "";
						if ($arResult["averageLandInc"] > 0)
						{
							$class="";
							$prefix = "+";
						}
						else if ($arResult["averageLandInc"] < 0)
						{
							$class="down";
						}
						else
						{
							$class="grey";
						}
						?>
						<?
						if ($arParams["SHOW_DYNAMIC"] == "Y")
						{
							?>
								<sup class="<?=$class?>"><?=$prefix?><?=number_format($arResult["averageLandInc"],2)?></sup>
							<?
						}
						?>
						
					</span>
				</li>
			</ul>
		</div><!--lit-price-prop-->

		<div class="check-currency">
			<ul>
				<li class="defaultP">
					<input id="currency1" class="inp-rub" type="radio" name="currency" checked />
					<label for="currency1">Руб.</label>
				</li>
				<li class="defaultP">
					<input id="currency2" class="inp-usd" type="radio" name="currency" />
					<label for="currency2">USD</label>
				</li>
				<li class="defaultP">
					<input id="currency3" class="inp-eur" type="radio" name="currency" />
					<label for="currency3">EUR</label>
				</li>
			</ul>
		</div><!--check-currency-->

		<div class="more-stat temp-hidden"><a href="#">Подробная статистика</a></div>
	</div><!--price-property-->

	<div class="rent">
		<div class="tit">Аренда</div>
		<ul>
			<? foreach($arResult['AREND'] as $item): ?>
				<li><?=$item['NAME']?><span><?=$item['COUNT']?></span></li>
			<? endforeach; ?>
		</ul>
	</div><!--rent-->
</div><!--block-info-->