<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if($arParams["USE_FILTER"]=="Y"):?>
<?
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/seo/data.php')) include($_SERVER['DOCUMENT_ROOT'].'/seo/data.php');
		if(isset($promo['h1'])){
			if($promo['h1']) echo('<h1>'.$promo['h1'].'</h1>');
		}
		?>


    <div class="row">
        <div class="span14">
            <?
              /* // Вызов формы подбора по параметрам
                $arPropz = $APPLICATION->IncludeComponent("kandk:json.wheels","catalog",
                Array(
                    "CACHE_TIME" => "36000000000",	// Время кеширования (сек.)
                    "ID_PARAMS" => array(8903,8904,8906,8907,8905,8908), // ID записей подсказок, для параметров
                    "IBLOCK_ID" => 15, // Инфоблок словарика (где храняться подсказки для параметров).
                    "IBLOCK_ID_WHEELS" => 6 // Инфоблок словарика (где храняться подсказки для параметров).
                )
                );*/
					
				$arPropz = $APPLICATION->IncludeComponent("kandk:json.wheels","catalog_mini",
					Array(
						"CACHE_TIME" => "36000000000",	// Время кеширования (сек.)
						"ID_PARAMS" => array(8903,8904,8906,8907,8905,8908), // ID записей подсказок, для параметров
						"IBLOCK_ID" => 15, // Инфоблок словарика (где храняться подсказки для параметров).
						"IBLOCK_ID_WHEELS" => 6 // Инфоблок словарика (где храняться подсказки для параметров).
					)
				);
					


            if($_REQUEST['del_filter'])
                unset($_SESSION['arWheelsFilterarrPFV']);
            ?>

            <?
            if((!empty($_GET['arWheelsFilter_pf']) || !empty($_SESSION['arWheelsFilterarrPFV'])) && !isset($_GET['del_filter'])) {
                $arFilter = !empty($_GET['arWheelsFilter_pf']) ? $_GET['arWheelsFilter_pf'] : $_SESSION['arWheelsFilterarrPFV'];?>
                <p><b>Показаны только колёса с параметрами: </b><?=!empty($arFilter['wheel_size']) ? '<b>диаметр '.$arPropz['wheel_size'][$arFilter['wheel_size']].'"</b>': ''?><?=!empty($arFilter['rim']) ? ', <b>ширина обода '.$arPropz['rim'][$arFilter['rim']].'"</b>': ''?><?=(!empty($arFilter['lz_1']) && !empty($arFilter['pcd_1'])) ? ', <b>сверловка (LZxPCD) '.$arPropz['lz_1'][$arFilter['lz_1']].'x'.$arPropz['pcd_1'][$arFilter['pcd_1']].'</b>': ''?><?=(!empty($arFilter['lz_1']) && empty($arFilter['pcd_1'])) ? ', <b>количество отверстий (LZ) '.$arPropz['lz_1'][$arFilter['lz_1']].'</b>': ''?><?=(empty($arFilter['lz_1']) && !empty($arFilter['pcd_1'])) ? ', <b>диаметр окружности (PCD) '.$arPropz['pcd_1'][$arFilter['pcd_1']].'</b>': ''?><?=!empty($arFilter['et']) ? ', <b>вылет (ET) '.$arPropz['et'][$arFilter['et']].' мм</b>': ''?><?=!empty($arFilter['dia']) ? '<b>, диаметр центровочного отверстия '.$arPropz['dia'][$arFilter['dia']].'</b>': ''?>.
                    <br />Чтобы показать все модели дисков - нажмите кнопку <b><a href="/catalog/wheels/?del_filter=Y">Сбросить</a></b>.
                </p>
            <?}
            if(!empty($_GET['arWheelsFilter_pf'])){
                $_SESSION['arWheelsFilterarrPFV'] = $_GET['arWheelsFilter_pf'];
            }
            ?>
        </div>
    </div>


    <div class="row"></div>
    <br>
    <?  // Вызов формы подбора по автомобилю (горизонтальный шаблон).
        $APPLICATION->IncludeComponent("kandk:json.car", "horizont", Array());
    ?>
    <div class="row"></div>

    <? 
		
        global ${$arParams["FILTER_NAME"]};
        if((!empty($_GET['arWheelsFilter_pf']) || !empty($_SESSION['arWheelsFilterarrPFV'])) && !isset($_GET['del_filter'])) { ${$arParams["FILTER_NAME"]} = !empty($_GET['arWheelsFilter_pf']) ? $_GET['arWheelsFilter_pf'] : $_SESSION['arWheelsFilterarrPFV'];}
    ?>
<?endif?>
<?
    // Обрабатываем до ума фильтр
    $arFilter = Array("ACTIVE_DATE" => "Y", "ACTIVE" => "Y");
    foreach($arWheelsFilter as $key=>$value)
        $arFilter["PROPERTY_".$key] = $value;


    $APPLICATION->IncludeComponent("kandk:wheels.sections","sections",
        Array(
            "FILTER" => $arFilter,
            "IBLOCK_ID" => 6,
            "CACHE_TIME" => "36000000000",	// Время кеширования (сек.)
        )
    );

		if(isset($promo['text'])){
			if($promo['text']) echo('<div>'.$promo['text'].'</div>');
		}
?>