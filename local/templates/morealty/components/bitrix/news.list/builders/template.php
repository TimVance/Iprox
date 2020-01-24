<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?
//NavPageCount
?>
<? $dopPadding = ($arResult['NAV_RESULT']->NavPageCount == 1) ? true : false; ?>
    <div class="list-agents list-agents2 <? if ($dopPadding) echo("dop-padding-lent") ?>">
        <? $counter = 0; ?>
        <? foreach ($arResult["ITEMS"] as $currentItem): ?>
            <? $photoLink = 'http://' . $_SERVER['HTTP_HOST'] . '/' . addWatermark($photo['VALUE']); ?>
            <?

            $number = $currentItem['PROPERTIES']['COUNT_ITEMS'];
            ?>
            <div class="item-agents">
                <?
                $img = ($currentItem['PREVIEW_PICTURE']["ID"]) ? CImg::Resize($currentItem['PREVIEW_PICTURE']["ID"], 323, 242, CImg::M_CROP) : false;
                ?>
                <? if ($img) {
                    ?>
                    <div class="img-agent">
                        <a href="<?= $currentItem["DETAIL_PAGE_URL"] ?>">

                            <img
                                    class="preview_picture"
                                    border="0"
                                    src="<?= $img ?>"
                                    alt="<?= $currentItem["PREVIEW_PICTURE"]["ALT"] ?>"
                                    title="<?= $currentItem["PREVIEW_PICTURE"]["TITLE"] ?>"
                            />
                        </a>
                    </div>
                    <?
                } ?>

                <div class="desc-agents">
                    <? if ($currentItem["PROPERTIES"]["SHARES"]["VALUE_ENUM_ID"] == 1): ?>
                        <div class="action-ag"><a href="<?= $currentItem["DETAIL_PAGE_URL"] ?>">Акции!</a></div>
                    <? endif; ?>
                    <div class="t-agents"><a
                                href="<?= $currentItem["DETAIL_PAGE_URL"] ?>"><?= $currentItem["NAME"] ?></a></div>
                    <div class="adress-agent"><?= $currentItem["PROPERTIES"]["ADDRESS"]["VALUE"] ?></div>

                    <div class="info-agents">
                        <div class="func-agents">
                            <div class="contacts-agents">
                                <ul>
                                    <li><span><?= $currentItem["PROPERTIES"]["PHONE_NUMBER"]["VALUE"] ?></span></li>
                                    <? if ($currentItem["PROPERTIES"]["EMAIL"]["VALUE"]): ?>
                                        <li>
                                            <a href="mailto:<?= $currentItem["PROPERTIES"]["EMAIL"]["VALUE"] ?>"><?= $currentItem["PROPERTIES"]["EMAIL"]["VALUE"] ?></a>
                                        </li>
                                    <? endif; ?>
                                </ul>
                            </div><!--contacts-agents-->

                            <div class="nums-contacts">
                                <ul>
                                    <li>Всего предложений <span><?= $number ?></span></li>
                                </ul>
                            </div>
                        </div><!--func-agents-->

                        <div class="text-agents">
                            <div class="list-obj">
                                <span>Объекты застройщика в продаже:</span>
                                <ul>
                                    <? $counterObjects = 0; ?>

                                    <? foreach ($currentItem["PROPERTIES"]["OBJECTS_FOR_SALE"]["VALUE"] as $itemValue): ?>
                                        <?
                                        if ($counterObjects > 3) {
                                            ?>
                                            <li>
                                            <a href="/newbuildings/?BUILDER=<?= $currentItem["ID"] ?>">Все <?= count($currentItem["PROPERTIES"]["OBJECTS_FOR_SALE"]["VALUE"]) ?> <?= Suffix(count($currentItem["PROPERTIES"]["OBJECTS_FOR_SALE"]["VALUE"]), array("объект", "объекта", "объектов")) ?></a>
                                            </li><?
                                            break;
                                        }
                                        ?>
                                        <?
                                        $CurObject = $itemValue;
                                        ?>
                                        <li><a href="<?= $CurObject["DETAIL_PAGE_URL"] ?>"><?= $CurObject["NAME"] ?></a>
                                        </li>
                                        <? $counterObjects++; ?>
                                    <? endforeach; ?>
                                </ul>
                                <div class="more-but"><a href="<?= $currentItem["DETAIL_PAGE_URL"] ?>">Подробнее</a>
                                </div>
                            </div>
                        </div><!--text-agents-->
                    </div><!--info-agents-->
                </div><!--desc-agents-->
            </div><!--item-agents-->
            <? if ($counter == 3): ?>
                <div class="item-ads"><a href="#"><img src="<?= SITE_TEMPLATE_PATH ?>/images_tmp/ban-partner.jpg"
                                                       alt=""/></a></div>
            <? endif; ?>
            <? $counter++; ?>
        <? endforeach; ?>
    </div><!--list-agents-->
<?= $arResult["NAV_STRING"] ?>