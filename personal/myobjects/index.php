<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Мои объекты"); ?>

<?
CModule::IncludeModule('iblock');

if(!empty($_REQUEST['TYPE'])) {
	$TYPE = $_REQUEST['TYPE'];
} else {
	$TYPE = 'SELL';
}
?>


	<div class="tabs-tb lich-obj">
		<div class="add-advice"><a class="inline" href="#pop7">Добавить объявление</a></div>

		<div class="nav-tabs-pop nav-tb">
			<ul>
				<li <?if($TYPE == 'SELL') { echo 'class="active"'; }?>><a href="#">Продажа</a></li>
				<?/* ?><li <?if($TYPE == 'AREND') { echo 'class="active"'; }?>><a href="#">Аренда</a></li><?*/ ?>
			</ul>
		</div><!--nav-regist-->
		<div class="cont-tb">

			<?
			//определяем переменные $IBLOCKS, $objectsIDs, $IBLOCK_ID, $IBLOCK_COUNTS
			$APPLICATION->IncludeComponent("morealty:personal.myobjects.iblocks","",Array()); ?>
			<?  $IBLOCKS = $GLOBALS['IBLOCKS'];
				$objectsIDs = $GLOBALS['objectsIDs'];
				$IBLOCK_ID = $GLOBALS['IBLOCK_ID'];
				$IBLOCK_COUNTS = $GLOBALS['IBLOCK_COUNTS'];
				$totalCounts = 0;
				foreach ($IBLOCK_COUNTS as $arIblock)
				{
					$totalCounts+= $arIblock["SELL"]["COUNTER"];
				}
				?>
				
			<div class="tab-tb <?if($TYPE == 'SELL') { echo 'active'; }?>">
				<div class="menu-tages agents-ajax-change">
					<ul>
					<? 
					if ($totalCounts > 0)
					{
						?><li><a <?=(!$_REQUEST["IBLOCK_ID"])? 'class="active"' : ""?> href="/personal/myobjects/">Все <span>(<?=$totalCounts?>)</span></a></li><?
					}
					?>
						<?foreach($IBLOCKS as $iblock):?>
							<?if($IBLOCK_COUNTS[$iblock['ID']]['SELL']['COUNTER'] > 0):?>
								<li><a <?=($_REQUEST["IBLOCK_ID"] == $iblock["ID"])? 'class="active"' : ""?> href="?IBLOCK_ID=<?=$iblock['ID']?>&TYPE=SELL"><?=$iblock['NAME']?> <span>(<?=$IBLOCK_COUNTS[$iblock['ID']]['SELL']['COUNTER']?>)</span></a></li>
							<?endif;?>
						<?endforeach;?>
					</ul>
				</div><!--menu-tags-->

				<div class="control-view temp-hidden"><a href="javascript:void(0);">Активировать групповое управление публикацией</a></div>
				
				<? if ($_SESSION["ADDED_OBJECT"] || $_SESSION["UPDATE_OBJECT"])
				{
					unset($_SESSION["ADDED_OBJECT"],$_SESSION["UPDATE_OBJECT"]);
					?>
					<span class="info">Объект успешно сохранен.</span>
					<?
				}?>
				<? if ($_SESSION["REMOVE_OBJECT"])
				{
					unset($_SESSION["REMOVE_OBJECT"]);
					?>
						<span class="info info-error">Объект успешно удален.</span>
					<?
				}?>
				
				<?
				$APPLICATION->IncludeComponent("wexpert:iblock.list","personal.myobjects",Array(
					"ORDER"                             => array($_GET["SORT_BY"] => $_GET["SORT_ORDER"]),
					//"FILTER"                            => array($GLOBALS['FILTER_PROPERTY'] => $GLOBALS['FILTER_VALUES'], '!SECTION_ID' => array(1, 2, 3)),
					"FILTER"                            => array('ID' => $objectsIDs, '!SECTION_ID' => array(1, 2, 3),"ACTIVE"=>"Y"),
					"IBLOCK_ID"							=> $IBLOCK_ID,
					"PAGESIZE"						    => 4,
					"SELECT"						    => array("DATE_CREATE","NAME","DETAIL_PAGE_URL","CODE","IBLOCK_ID","ID","PROPERTY_IS_ACCEPTED","ACTIVE","PROPERTY_currency.XML_ID"),
					"GET_PROPERTY"						=> "Y",
					"CACHE_TIME"    => 0,
					"CACHE_TYPE"	=> "N",
				));
				?>

			</div><!--tab-tb-->


			<div class="tab-tb <?if($TYPE == 'AREND') { echo 'active'; }?>">
				<div class="menu-tages">
					<ul>
						<?foreach($IBLOCKS as $iblock):?>
							<?if($IBLOCK_COUNTS[$iblock['ID']]['AREND']['COUNTER'] > 0):?>
								<li><a href="?IBLOCK_ID=<?=$iblock['ID']?>&TYPE=AREND"><?=$iblock['NAME']?> <span>(<?=$IBLOCK_COUNTS[$iblock['ID']]['AREND']['COUNTER']?>)</span></a></li>
							<?endif;?>
						<?endforeach;?>
					</ul>
				</div><!--menu-tags-->

				<div class="control-view temp-hidden"><a href="#">Активировать групповое управление публикацией</a></div>

				<?
				$APPLICATION->IncludeComponent("wexpert:iblock.list","personal.myobjects",Array(
					"ORDER"                             => array($_GET["SORT_BY"] => $_GET["SORT_ORDER"]),
					//"FILTER"                            => array($GLOBALS['FILTER_PROPERTY'] => $GLOBALS['FILTER_VALUES'], '!SECTION_ID' => array(1, 2, 3)),
					"FILTER"                            => array('ID' => $objectsIDs, 'SECTION_ID' => array(1, 2, 3),"ACTIVE"=>""),
					"IBLOCK_ID"							=> $IBLOCK_ID,
					"PAGESIZE"						    => 4,
					"SELECT"						    => array("DATE_CREATE","NAME","DETAIL_PAGE_URL","CODE","IBLOCK_ID","ID","PROPERTY_IS_ACCEPTED","ACTIVE"),
					"GET_PROPERTY"						=> "Y",
					"CACHE_TIME"    					=> 3600
				));
				?>

			</div><!--tab-tb-->
		</div><!--cont-tb-->
	</div><!--tabs-tb-->





<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>