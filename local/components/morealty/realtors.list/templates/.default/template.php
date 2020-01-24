<? if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die(); ?>
<? CModule::IncludeModule("iblock"); ?>
<? $dopPadding = ($arResult['NAV_RESULT']['PAGES_COUNT'] == 1)? true: false;?>
<div class="list-rielts <?if($dopPadding)echo("dop-padding-lent")?>">
	<? foreach($arResult["ITEMS"] as $arItem): ?>
	<div class="item-rielt">
		<div class="info-rielt">
			<div class="table-rielt">
				<div class="photo-rielt">
					<a href="<?='/realtors/'.$arItem["ID"].'/'?>">
						<?
						if(!empty($arItem["PERSONAL_PHOTO"])) {
							//$arItem["PERSONAL_PHOTO"] = 'http://' . $_SERVER['HTTP_HOST'] . '/' . CFile::GetPath($arItem["PERSONAL_PHOTO"]);
							$arItem["PERSONAL_PHOTO"] = weImg::Resize($arItem["PERSONAL_PHOTO"],100,100,weImg::M_CROP);
						}
						?>
						<?if(!empty($arItem["PERSONAL_PHOTO"]) && $arItem["PERSONAL_PHOTO"] != null) :?>
							<img class="realtor-list-photo" src="<?=$arItem["PERSONAL_PHOTO"]?>" alt="" />
						<? else: ?>
							<img class="realtor-list-photo" src="<?=SITE_TEMPLATE_PATH?>/images/no-photo.jpg" alt="" />
						<? endif; ?>
					</a>
				</div>
				<div class="contacts-rielt">
					<?
					$Res = CIBlockElement::GetByID($arItem["UF_AGENT_NAME"]);
					if ($ar_props = $Res->GetNext()) {
						$arItem["UF_AGENT_ID"] = $arItem["UF_AGENT_NAME"];
						$arItem["UF_AGENT_NAME"] = $ar_props["NAME"];
					}
					?>
					<?if((int)$arItem["UF_AGENT_ID"] > 0):?>
						<div class="agency-rielt"><a href="<?='/agents/'.$arItem["UF_AGENT_ID"].'/'?>"><?=$arItem["UF_AGENT_NAME"]?></a></div>
					<?endif;?>
					<div class="name-rielt"><a href="<?='/realtors/'.$arItem["ID"].'/'?>"><?=$arItem["NAME"] . ' ' . $arItem["SECOND_NAME"] . ' ' . $arItem["LAST_NAME"]?></a></div>
					<div class="stat-rielt"><?=$arItem["UF_PERSON_POST"]?></div>
					<div class="phone-rielt"><?=$arItem["PERSONAL_MOBILE"]?></div>
				</div><!--contacts-rielt-->
			</div><!--table-rielt-->
		</div><!--info-rielt-->

		<div class="propos-rielt">
			<div class="list-propos-r">
				<?
				$totalCount = intval($arItem["UF_REALTOR_OBJECTS"]);
				

				$number = $totalCount;

				
				?>
				<p><a href="<?='/realtors/'.$arItem["ID"].'/'?>"><?=$totalCount?> <?=Suffix($totalCount, array("предложение", "предложения", "предложений"))?></a></p>
				<ul>
					<? foreach($arItem["GROUPS"] as $key => $value): ?>
						<li><?=strtolower($value["NAME"]) . ' - ' . $value["CNT"]?></li>
					<? endforeach; ?>
				</ul>
			</div><!--list-propos-r-->
			<div class="more-but"><a href="<?='/realtors/'.$arItem["ID"].'/'?>">Подробнее</a></div>
		</div><!--propos-rielt-->
	</div><!--item-rielt-->
	<? endforeach; ?>
</div><!--list-rielts-->
<?
$APPLICATION->IncludeComponent(
"bitrix:main.pagenavigation",
"",
array(
		"NAV_OBJECT" => $arResult["NAV_OBJECT"],
		"SEF_MODE" => "N",
),
false
);
?>


