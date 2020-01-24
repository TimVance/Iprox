<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
?>

<?
if ($arResult["ITEMS"] && count($arResult["ITEMS"]) > 0)
{
	
$count = count($arResult["ITEMS"]);
	?>
	<div class="b-amploe agent_employers">
		<div class="t-emploe">Сотрудники <span>(<?=($arResult["NAV_RESULT"]["RECORDS_COUNT"])? $arResult["NAV_RESULT"]["RECORDS_COUNT"] : count($arResult["ITEMS"])?>)</span></div>
	
		<div class="list-emploe">
			<ul>
				<? foreach($arResult["ITEMS"] as $user): ?>
					<?
	
					if(!empty($user['PERSONAL_PHOTO'])) {
						$photoLink = CImg::Resize($user['PERSONAL_PHOTO'], 150, 150, CImg::M_FULL_S);
					}
					else {
						$photoLink = SITE_TEMPLATE_PATH . '/images/no-photo.jpg';
					}
					?>
					<li>
						<div class="in-emp">
							<div class="img-emp"><img src="<?=$photoLink?>" alt="" /></div>
							<div class="desc-emploe">
								<div class="name-emploe">
									<a href="<?='/realtors/' . $user['ID'] . '/' ?>"><?=$user['NAME'] . ' ' . $user['SECOND_NAME'] . ' ' . $user['LAST_NAME']?></a>
									<span><?=$user['UF_PERSON_POST']?></span>
								</div>
								<div class="propos-emploe">
									<a href="<?='/realtors/' . $user['ID'] . '/#offers' ?>">Предложений <span><?=intval($user["UF_REALTOR_OBJECTS"])?></span></a>
								</div>
							</div><!--desc-emploe-->
						</div>
					</li>				
				<? endforeach; ?>
			</ul>
			<? if($count > 4):?>
				<div class="view-all"><a href="javascript:void(0);">Показать всех</a></div>
			<? endif; ?>
		</div>
	</div>
	<?
}
?>