<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="nav-agents">
	<div class="sort catalog-sort">
		<form action="" name="SORT_FORM" method="GET">
			<input type="hidden" name="SORT_BY" value="<?=$_GET["SORT_BY"]?>">
			<input type="hidden" name="SORT_ORDER" value="<?=(!empty($_GET["SORT_ORDER"]) ? $_GET["SORT_ORDER"] : 'DESC')?>">
			<input id="submit" type="submit" value="sort">
		</form>
		<p>Сортировка:</p>
		<ul>
			<li value="TIMESTAMP_X"><span class="<? echo (($_GET["SORT_BY"] == "TIMESTAMP_X" || empty($_GET["SORT_BY"])) ? 'active' : 'waited');?>">Дата обновления</span></li>
			<? if($APPLICATION->GetCurPage() == '/realtors/'): ?>
				<li value="NAME"><span class="<? echo (($_GET["SORT_BY"] == "NAME") ? 'active' : 'waited');?>">Имя</span></li>
				<li value="UF_AGENT_NAME"><span class="<? echo (($_GET["SORT_BY"] == "UF_AGENT_NAME") ? 'active' : 'waited');?>">Название</span></li>
			<? else: ?>
				<li value="NAME"><span class="<? echo (($_GET["SORT_BY"] == "NAME") ? 'active' : 'waited');?>">Название</span></li>
			<? endif; ?>
			<li value="PROPERTY_COUNT_OF_OFFERS"><span class="<? echo (($_GET["SORT_BY"] == "PROPERTY_COUNT_OF_OFFERS") ? 'active' : 'waited');?>">Число предложений</span></li>
			<? if($APPLICATION->GetCurPage() == '/builders/'): ?>
				<li value="PROPERTY_COUNT_OF_EMPLOYEES"><span class="<? echo (($_GET["SORT_BY"] == "PROPERTY_COUNT_OF_EMPLOYEES") ? 'active' : 'waited');?>">Число предложений</span></li>
			<? endif; ?>
		</ul>
	</div><!--sort-->
	<div class="link-map"><a href="#">Карта</a></div>
</div><!--nav-agents-->