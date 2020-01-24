<? if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die(); ?>
<?
$arProperties = $arResult['PROPERTIES'];
$IBLOCK_ID = $_REQUEST['IBLOCK_ID'];
$res = CIBlock::GetByID($IBLOCK_ID);
$IBLOCK = $res->Fetch();
//my_print_r($arResult["INPUTED"]);
if (true || $arResult["Errors"]["UPDATE"]["STATE"] == false)
{
	if (count($arResult["INPUTED"]) > 0)
	{
		?>
		<script>

		window.InputedValues = <?=CUtil::PhpToJSObject($arResult["INPUTED"])?>;
		
		</script>
		<?
	}
}
?>
<?
?>
<?if($arResult['ERROR_MSG']['OWNER_END_OFFERS'] == 'y'):?>
    <div class="pad-empty">
        <p>Владельцы недвижимости не могут добавлять более 1 объявления бесплатно.</p>
    </div>
<?elseif ($arResult['ERROR_MSG']['REALTOR_END_OFFERS'] == 'y'):?>
    <div class="pad-empty">
        <p>Закончилось место на добавление объектов согласно Вашим купленным пакетам.</p>
        <p>Вы можете приобрести новый <a href="/personal/service/">пакет</a>.</p>
    </div>
<?else:?>
    <div class="pad-empty">
        <form action="<?=$APPLICATION->GetCurPage(false)?>" id="object-form" method="post">
            <input type="hidden" name="PRODUCT_ID" value="<?=$_REQUEST['PRODUCT_ID']?>">
            <input type="hidden" name="IBLOCK_ID" value="<?=$IBLOCK_ID?>">
            <input type="hidden" name="city" value="">
            <input type="hidden" name="district" value="">
            <input type="hidden" name="microdistrict" value="">
            <input type="hidden" name="street" value="">
            <input type="hidden" name="is_need_remove" value="n">
            <input type="hidden" name="form_send" value="y">
            <?if(!empty($_REQUEST['form_send']) && $_REQUEST['is_need_remove'] == 'n' && $arResult["Errors"]["UPDATE"]["STATE"] == true):?>
                <span class="info">Объект успешно сохранен.</span>
           <?elseif ($arResult["Errors"]["UPDATE"]["STATE"] == false):?>
            <span class="info info-error"><?=$arResult["Errors"]["UPDATE"]["MSG"]?></span>
            <?endif;?>
            <?if($_REQUEST['is_need_remove'] == 'y'  && $arResult["Errors"]["UPDATE"]["STATE"] == true):?>
                <span class="info info-error">Объект успешно удален.</span>
            <?endif;?>

            <div class="info-object">
                <div class="adress-obj">
                    <?
                        if(!empty($_REQUEST['PRODUCT_ID'])) {
                            $title = "Изменить объявление: " . $IBLOCK['NAME'];
                        } else if(!empty($_REQUEST['IBLOCK_ID'])) {
                            $title = "Добавить объявление: " . $IBLOCK['NAME'];
                        }
                        $APPLICATION->SetPageProperty("title", $title);
                    ?>
                    <div class="t-adress t-adress2"><?=$title?></div>
                    <div class="t-adress">Адрес объекта</div>


                    <div class="line-select">
                        <label>Город <span>*</span></label>

                        <div class="sel-on sel-line sel-line1">
                            <select name="city">
                                <option value="all">Выберите из списка</option>
                                <?foreach($arProperties['CITIES'] as $property):?>
                                    <option value="<?=$property["ID"]?>"><?=$property["NAME"]?></option>
                                <?endforeach;?>
                            </select>
                        </div>
                    </div>

                    <div class="line-select">
                        <label>Район <span>*</span></label>

                        <div class="sel-on sel-line sel-line1">
                            <select name="district">
                                <option value="all">Выберите из списка</option>
                                <?foreach($arProperties['DISTRICTS'] as $property):?>
                                    <option value="<?=$property["ID"]?>"><?=$property["NAME"]?></option>
                                <?endforeach;?>
                            </select>
                        </div>
                    </div>

                    <div class="line-select">
                        <label>Микрорайон <span>*</span></label>

                        <div class="sel-on sel-line sel-line1">
                            <select name="microdistrict">
                                <option value="all">Выберите из списка</option>
                                <?foreach($arProperties['MICRODISTRICTS'] as $property):?>
                                    <option value="<?=$property["ID"]?>"><?=$property["NAME"]?></option>
                                <?endforeach;?>
                            </select>
                        </div>
                    </div>
                    <?if($_REQUEST['IBLOCK_ID']== 7): ?>
                        <div class="line-select">
                            <label>Новостройка<span></span></label>

                            <div class="sel-on sel-line sel-line2 newbuild_line">
                                <select name="newbuilding" class="newbuilding_selector">
                                    <option value="0">Выберите новостройку из списка</option>
                                    <?foreach($arProperties['newbuildings'] as $property):?>
                                        <option value="<?=$property["ID"]?>"><?=$property["NAME"]?></option>
                                    <?endforeach;?>
                                </select>
                            </div>

                            <?/* ?><div class="ico-info">
                                <i>i</i>
                                
                                <div class="die-benef">
                                	<input class="select_filter newbuilding_filter" value="" type="text" />
                                    Фильтрация по названию новостройки
                                </div>
                            </div>
                            <?*/ ?>
                            <div class="ico-info">
	                            <div class="die-benef always_block" style="margin-top: -20px;">
									<input class="select_filter newbuilding_filter" value="" type="text" />
									Фильтрация по названию новостройки
								</div>
							</div>
                        </div>
                    <?endif;?>

                    <div class="line-select">
                        <label>Улица <span>*</span></label>

                        <div class="line-field line-field-without-margin line-field-long">
                            <input type="text" name="street" value="<?=$arProperties['street']['VALUE']?>" />
                        </div>

                    </div>
					<?$APPLICATION->ShowViewContent("addmap_partland");?>
                    <div class="ln-map temp-hidden"><a href="#">Найти на карте</a></div>
                </div>

                <div class="map-object temp-hidden"><img src="<?=SITE_TEMPLATE_PATH?>/images_tmp/map-obj.jpg" alt="" /></div>

                <div class="new-builds">

                </div>

                <?
                switch($_REQUEST['IBLOCK_ID']) {
                    case 7: $templateName = 'flat'; break;
                    case 8: $templateName = 'houses'; break;
                    case 9: $templateName = 'complex'; break;
                    case 10: $templateName = 'land'; break;
                    case 11: $templateName = 'commercial'; break;
                    case 12: $templateName = 'markets'; break;
                    case 13: $templateName = 'projects'; break;
                    case 19: $templateName = 'newbuildings'; break;
                    default: break;
                }
                $APPLICATION->IncludeComponent(
                    "morealty:personal.myobjects.add.properties",
                    $templateName,
                    array(
                        "PROPERTIES" => $arProperties,
                    ),
                    false
                );
                ?>

                <div class="adress-obj adress-obj3">
                    <div class="t-adress">Цена</div>

                    <div class="radio">
                        <p>Стоимость:</p>
                        <ul>
                            <li class="defaultP">
                                <input id="radio1" checked name="radio" type="radio" value="radio_all_price"/>
                                <label for="radio1">Общая</label>
                            </li>
                            <li class="defaultP">
                                <input id="radio2" name="radio" type="radio" value="radio_1m_price"/>
                                <label for="radio2">за м<sup>2</sup></label>
                            </li>
                        </ul>
                    </div>

                    <div class="line-form">
                        <div class="line-field field-bold">
                            <label>Сумма <span>*</span></label>
                            <input type="text" name="price" value="<?=$arProperties['price']['VALUE']?>" />
                        </div>

                        <div class="line-select line-select2">
                            <div class="sel-on sel-line sel-line4">
                                <select name="currency">
                                	<?
                                	if ($IBLOCK_ID == 7)
                                	{
                                		?>
                                		 <?foreach($arProperties['currency_list'] as $key => $value):?>
	                                        <option <?if($value == $arProperties['currency']['VALUE']) echo 'selected=selected';?> value="<?=$value?>"><?=$value?></option>
	                                    <?endforeach;?>
                                		<?
                                	}
                                	else 
                                	{
                                		foreach ($arResult["PROPERTIES"]["CURRENCY_VARIANTS"] as $Variant)
                                		{
                                			?>
                                				<option <?=($arProperties['currency']['VALUE_ENUM_ID'] == $Variant["ID"])? 'selected="selected"' : ""?> value="<?=$Variant["ID"]?>"><?=$Variant["VALUE"]?></option>
                                			<?
                                		}
                                	}
                                	?>
                                   
                                </select>
                            </div>
                        </div>

                    </div>

                    <?if($_REQUEST['IBLOCK_ID']== 7): ?>
                    <div class="line-check line-check2">
                        <ul>
                            <li class="customP">
                                <input id="check4" name="have_furniture" type="checkbox" <?if(strtolower($arProperties['have_furniture']['VALUE']) == 'да') echo 'checked';?> value="348" />
                                <label for="check4">Продается с мебелью</label>
                            </li>
                            <li class="customP">
                                <input id="check5" name="can_mortgage" type="checkbox" <?if(strtolower($arProperties['can_mortgage']['VALUE']) == 'да') echo 'checked'; ?> value="349"/>
                                <label for="check5">Возможна ипотека</label>
                            </li>
                        </ul>
                    </div>
                    <?endif;?>
                </div>

                <div class="more-info">
                    <div class="t-adress">Дополнительная информация</div>
                    <?
                    if ($_REQUEST["IBLOCK_ID"] != 7 && $_REQUEST["IBLOCK_ID"] != 8 && $_REQUEST["IBLOCK_ID"] != 10 && false)
                    {
                    	?>
                    <div class="line-form">
                        <div class="line-field line-field-long">
                            <label>Название <span>*</span></label>
                            <input type="text" name="name" value="<?=$arResult['ITEMS']['NAME']?>" />
                        </div>
                    </div>
                    	<?
                    }
                    ?>


                    <div class="textar-info">
                    	<label><p>Описание</p></label>
                        <textarea class="only_vertical_resize" name="DETAIL_TEXT"><?=$arResult['ITEMS']['DETAIL_TEXT']?></textarea>
                    </div>
                </div>
                <div class="more-info">
                    <div class="t-adress">Изображения</div>
                    <div class="line-form">
                                    <? 
				//my_print_r($arResult['PROPERTIES']);

				//PHOTO_FILES
				if (false)
				{
					$arr_object_images = $_REQUEST["PHOTO_FILES"];
				}
				else
				{
					$arr_object_images = $arResult['PROPERTIES']["photo_gallery"]["VALUE"];
					$arr_object_images = array();
					foreach ($arResult['PROPERTIES']["photo_gallery"]["VALUE"] as $key=>$File)
					{
						$arr_object_images["PHOTO_FILES[".$key."]"] = $File;
					}
					if ($arResult["INPUTED"]["PHOTO_FILES"])
					{
						foreach ($arResult["INPUTED"]["PHOTO_FILES"] as $key => $File)
						{
							$arr_object_images["PHOTO_FILES[".$key."]"] = $File;
						}
					}
				}
				
				/*?>
				<input type="text" name="object_images333" value="">
				<?$APPLICATION->IncludeComponent("bitrix:main.file.input", "",
				   array(
				      "INPUT_NAME"=>"object_images22",
				      "MULTIPLE"=>"Y",
				      "MODULE_ID"=>"iblock",
				      "MAX_FILE_SIZE"=>2*1024*1024,
				      "ALLOW_UPLOAD"=>"I", 
				      "ALLOW_UPLOAD_EXT"=>""
				   ),
				   false
				);?>
				<?//maxCount ?>$name."[n#IND#]",
					"id" => $name."[n#IND#]_".mt_rand(1, 1000000),
				<?*/?>
				<?=\Bitrix\Main\UI\FileInput::createInstance(array("name" =>  'PHOTO_FILES[n#IND#]',"id"=> "PHOTO_FILES[n#IND#]_".mt_rand(1, 1000000),"description" => true,"upload" => true,"allowUpload" => "I",
                     "medialib" => true,
                     "fileDialog" => true,
                     "cloud" => false,
                     "delete" => true,
					 "edit"   => false,
                     "maxCount" => false,
					 "maxSize" => 5*1024*1024,
					 "allowSort" => "Y",
                  ))->show(count($arr_object_images) > 0? $arr_object_images: 0)?>
				<??>
                    
                    </div>
                </div>
                
                <?
				if ($_REQUEST["IBLOCK_ID"] == 8 || $_REQUEST["IBLOCK_ID"] == 10)
				{
					$arr_object_images = $arResult['PROPERTIES']["layouts_gallery"]["VALUE"];
					$arr_object_images = array();
					//PHOTO_FILES
					foreach ($arResult['PROPERTIES']["layouts_gallery"]["VALUE"] as $key=>$File)
					{
						$arr_object_images["PLAN_FILES[".$key."]"] = $File;
					}
					
					if ($arResult["INPUTED"]["PLAN_FILES"])
					{
						foreach ($arResult["INPUTED"]["PLAN_FILES"] as $key => $File)
						{
							$arr_object_images["PLAN_FILES[".$key."]"] = $File;
						}
					}
					?>
					<div class="more-info">
                    	<div class="t-adress">Планировки</div>
							<div class="line-form">
								<?=\Bitrix\Main\UI\FileInput::createInstance(array("name" =>  'PLAN_FILES[n#IND#]',"id"=> "PLAN_FILES[n#IND#]_".mt_rand(1, 1000000),"description" => true,"upload" => true,"allowUpload" => "I",
										"medialib" => true,
										"fileDialog" => true,
										"cloud" => false,
										"delete" => true,
										"edit"   => false,
										"maxCount" => false,
										"maxSize" => 5*1024*1024,
										"allowSort" => "Y",
								))->show(count($arr_object_images) > 0? $arr_object_images: 0)?>
							</div>
					</div>
						<?
				}
				?>
                <? 
                if ($arResult["USE_MAP"])
                {
                	if ($_REQUEST["IBLOCK_ID"] == "10" || $_REQUEST["IBLOCK_ID"] == 8 || $IBLOCK_ID == 11)
                	{
                		ob_start();
                	}
                	?>
                <?
                if ($_REQUEST["map_point"])
                {
                	$arResult['PROPERTIES']["yandex_map"]["VALUE"] = $_REQUEST["map_point"];
                }
                ?>
                <div class="<?=($_REQUEST["IBLOCK_ID"] == "10")? "" : "more-info" ?>">
				<?$APPLICATION->IncludeComponent(
						"bitrix:map.yandex.view",
						"object_edit_map",
						array(
								"INIT_MAP_TYPE" => "MAP",
								"MAP_DATA" => "",
								"MAP_WIDTH" => "auto",
								"MAP_HEIGHT" => "400",
								"CONTROLS" => array(
										0 => "ZOOM",
								),
								"OPTIONS" => array(
										1 => "ENABLE_DRAGGING",
								),
								"EventsFunction"=> "EditorMapEvents",
								"MAP_ID" => "yam_1",
								"MAP_VERSION" => "2.1",
								"POST_POINT" => $_REQUEST["map_point"],
								"COMPONENT_TEMPLATE" => "object_edit_map",
								"START_POINT" => ($arResult['PROPERTIES']["yandex_map"]["VALUE"])? preg_split("/[\s,]+/", $arResult['PROPERTIES']["yandex_map"]["VALUE"]): false,
								//"ONMAPREADY"=> "EventMapReady"
						),
						false
				);?>
				</div>
				<div class="hidden">
					<input type="text" id="map_adress" value="">
					<input type="text" id="map_point" name="map_point" value="<?=$arResult['PROPERTIES']["yandex_map"]["VALUE"]?>">
				</div>
                	<?
                	
                	if ($_REQUEST["IBLOCK_ID"] == "10" || $_REQUEST["IBLOCK_ID"] == 8 || $IBLOCK_ID == 11)
                	{
                		$APPLICATION->AddViewContent("addmap_partland", ob_get_clean());
                	}
                }
                ?>
            </div>
            <div class="but-pop but-pop4">
                <button id="btn_submit_advert" type="submit">Сохранить</button>

                <?if(!empty($_REQUEST['PRODUCT_ID'])):?>
                    <div class="del-object"><a id="remove_object" href="javascript:void(0);">Удалить объект</a></div>
                <?endif;?>
            </div>
        </form>
    </div>
<?endif;?>