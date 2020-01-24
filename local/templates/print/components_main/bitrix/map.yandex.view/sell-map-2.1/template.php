<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$templateFolder = $this->GetFolder();
if ($arParams['BX_EDITOR_RENDER_MODE'] == 'Y'):
?>
<img src="/bitrix/components/bitrix/map.yandex.view/templates/.default/images/screenshot.png" border="0" />
<?
else:
    
	$arTransParams = array(
		'KEY' => $arParams['KEY'],
		'INIT_MAP_TYPE' => $arParams['INIT_MAP_TYPE'],
		'INIT_MAP_LON' => $arResult['POSITION']['yandex_lat'],
		'INIT_MAP_LAT' => $arResult['POSITION']['yandex_lon'],
		'INIT_MAP_SCALE' => $arResult['POSITION']['yandex_scale'],
		'MAP_WIDTH' => $arParams['MAP_WIDTH'],
		'MAP_HEIGHT' => $arParams['MAP_HEIGHT'],
		'CONTROLS' => $arParams['CONTROLS'],
		'OPTIONS' => $arParams['OPTIONS'],
		'MAP_ID' => $arParams['MAP_ID'],
		'LOCALE' => $arParams['LOCALE'],
		"YANDEX_VERSION" => "2.1",
		'ONMAPREADY' => 'BX_SetPlacemarks_'.$arParams['MAP_ID'],
	);

	if ($arParams['DEV_MODE'] == 'Y')
	{
		$arTransParams['DEV_MODE'] = 'Y';
		if ($arParams['WAIT_FOR_EVENT'])
			$arTransParams['WAIT_FOR_EVENT'] = $arParams['WAIT_FOR_EVENT'];
	}
?>
<script type="text/javascript">

function updateClasters(objectManager)
{
	Clasters = objectManager.getClusters();
	for (var i = 0; i < Clasters.length;i++)
	{
		var Count = 0;
		claster = Clasters[i];
		PlaceMarks = claster.getGeoObjects();
		//console.log(PlaceMarks);
		for (var j = 0; j < PlaceMarks.length; j++)
		{
			Placemark = PlaceMarks[j];
			Count += parseInt(Placemark.properties.get("iconContent"));
		}
		if (Count >= 0)
		{
			claster.properties.set("clusterinnerdata",Count);
			claster.properties.set("iconContent",Count);
			//iconContent
			
		}
		//claster.refresh();
	}
	//console.log(Clasters);
	//console.log(objectManager);
}

function BX_SetPlacemarks_<?echo $arParams['MAP_ID']?>(map)
{
	window.initStylesMap();
	if(typeof window["BX_YMapAddPlacemark"] != 'function')
	{
		(function(d, s, id)
		{
			var js, bx_ym = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "<?=$templateFolder.'/script.js'?>";
			bx_ym.parentNode.insertBefore(js, bx_ym);
		}(document, 'script', 'bx-ya-map-js'));

		var ymWaitIntervalId = setInterval( function(){
				if(typeof window["BX_YMapAddPlacemark"] == 'function')
				{
					BX_SetPlacemarks_<?echo $arParams['MAP_ID']?>(map);
					clearInterval(ymWaitIntervalId);
				}
			}, 300
		);

		return;
	}
	MyIconContentLayout = ymaps.templateLayoutFactory.createClass( '<div id="" class="map_cluster_info" style="color: #20a6ad; font-size: 18px;">$[properties.clusterinnerdata]</div>');
	var clusterIcons = [
	                    {
	                        href: '/local/templates/morealty/images/cluster.png',
	                        size: [50, 50],
	                        // Отступ, чтобы центр картинки совпадал с центром кластера.
	                        offset: [-20, -20]
	                    },
	                    {
	                        href: '/local/templates/morealty/images/cluster.png',
	                        size: [50, 50],
	                        offset: [-30, -30]
	                    }]
	window.catalog_custer = objectManager = new ymaps.Clusterer({
        /**
         * Через кластеризатор можно указать только стили кластеров,
         * стили для меток нужно назначать каждой метке отдельно.
         * @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/option.presetStorage.xml
         */
        //preset: 'islands#invertedVioletClusterIcons',
        /**
         * Ставим true, если хотим кластеризовать только точки с одинаковыми координатами.
         */
        clusterNumbers : [100],
        clusterIconContentLayout: MyIconContentLayout,
        groupByCoordinates: false,
        /**
         * Опции кластеров указываем в кластеризаторе с префиксом "cluster".
         * @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/ClusterPlacemark.xml
         */
        clusterDisableClickZoom: true,
        clusterHideIconOnBalloonOpen: false,
        geoObjectHideIconOnBalloonOpen: false,
       	clusterinnerdata : "0",
       	/*clusterIcons: [
       	           	{href : "/local/templates/morealty/components/bitrix/map.yandex.view/sell-map-2.1/images/placemark_map.png",size : [40,40]}
		],*/
       	clusterIcons: clusterIcons,
    });
    //console.log(objectManager);
	

	objectManager.options.set({
        gridSize: 80,
        clusterDisableClickZoom: false,
    });
	
	//objectManager.objects.options.set('preset', 'islands#greenDotIcon');
   // objectManager.clusters.options.set('preset', 'islands#greenClusterIcons');
    
    
	var arObjects = {PLACEMARKS:[],POLYLINES:[]};
<?
	if (is_array($arResult['POSITION']['PLACEMARKS']) && ($cnt = count($arResult['POSITION']['PLACEMARKS']))):
		for($i = 0; $i < $cnt; $i++):
?>
	arObjects.PLACEMARKS[arObjects.PLACEMARKS.length] = BX_YMapAddPlacemark(map, <?echo CUtil::PhpToJsObject($arResult['POSITION']['PLACEMARKS'][$i])?>);
<?
		endfor;
	endif;
	
	?>
	objectManager.add(arObjects.PLACEMARKS);

	window.catalogmap = map;
	//'boundschange'
	map.events.add(['boundschange'],function(e){
		setTimeout(function(){
			map.events.fire("click");
		},100);
    });
	map.events.add(['click',],function(e){
		updateClasters(objectManager);
    });
	setTimeout(function(){
		map.events.fire("click");
	},300);
	
	map.geoObjects.add(objectManager);
	<?
	if (is_array($arResult['POSITION']['PLACEMARKS']) && count($arResult['POSITION']['PLACEMARKS']) > 0)
	{
		?>
			map.setBounds(objectManager.getBounds());
		<?
	}
	if ($arResult['POSITION']['yandex_scale'] && $arParams["AUTO_ZOOM"] !== "Y")
	{
		?>
		map.setZoom(<?=$arResult['POSITION']['yandex_scale']?>);
		<?
	}
	
	
	if (is_array($arResult['POSITION']['POLYLINES']) && ($cnt = count($arResult['POSITION']['POLYLINES']))):
		for($i = 0; $i < $cnt; $i++):
?>
	arObjects.POLYLINES[arObjects.POLYLINES.length] = BX_YMapAddPolyline(map, <?echo CUtil::PhpToJsObject($arResult['POSITION']['POLYLINES'][$i])?>);
<?
		endfor;
	endif;

	if ($arParams['ONMAPREADY']):
?>
	if (window.<?echo $arParams['ONMAPREADY']?>)
	{
		
		window.<?echo $arParams['ONMAPREADY']?>(map, arObjects);
	}
<?
	endif;
?>
}
</script>
<div class="bx-yandex-view-layout">
	<div class="bx-yandex-view-map">
<?
	$APPLICATION->IncludeComponent('bitrix:map.yandex.system', '.default', $arTransParams, false, array('HIDE_ICONS' => 'Y'));
?>
	</div>
</div>
<?
endif;
?>