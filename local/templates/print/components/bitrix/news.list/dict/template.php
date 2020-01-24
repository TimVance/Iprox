<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
$fSymb = false;

if ($arParams["COLUM_NUM"]) {
	$nPerCol = ceil(count($arResult["ITEMS"])/$arParams['COLUM_NUM']);
	$index = 1;
}
function GetFSymb($str) {
	return mb_substr(preg_replace('/\s+/', '', $str), 0 ,1 ,"UTF-8");
}
function opencol(){
	?>
					<li>
						<ul>
	<?
}
function closecol(){
					?>
						</ul>
					</li>
					<?
}
function opensubcol($str){
								?>
								<li>
								<?=$str?>
									<ul><?
}
function closesubcol(){
								?>
									</ul>
								</li>
								<?
}
?>
		<div class="alphabet">
				<ul>
<?
opencol();
foreach ($arResult['ITEMS'] as $arItem) {
	if (!$fSymb) {
		$fSymb = GetFSymb($arItem['NAME']);
		opensubcol($fSymb);
	}
	else {
		if ($fSymb != GetFSymb($arItem['NAME'])) {
			$fSymb = GetFSymb($arItem['NAME']);
			if ($arParams['COLUM_NUM']) {
				if ($index >= $nPerCol) {
					closesubcol();
					closecol();
					opencol();
					$index = 0;
					opensubcol($fSymb);
				}
				else {
					closesubcol();
					opensubcol($fSymb);
				}
			}
		}
	}
		?>
		<li><a class="inline dict" href="detail.php?ELEMENT_ID=<?=$arItem["ID"]?>&ajax=Y"><?=$arItem["NAME"]?></a></li>
		<?
	if ($nPerCol) {
		$index++;
	}
}
closesubcol();
closecol();
?>
				</ul>
			</div>
