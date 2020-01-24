if (!window.EventMapReady)
{
	window.EventMapReady = function(map,arPlacemarks)
	{
		
		map.controls.each(function(i,a){
			map.controls.remove(i);
		});
		map.controls.remove('trafficControl');
		map.controls.remove('scaleLine');
		map.controls.remove('geolocation');
		map.controls.remove("searchControl");
		map.controls.remove("geolocationControl");
		//макет для балуна
		ymaps.layout.storage.add('sell#balloonLayout', ymaps.templateLayoutFactory.createClass('<div class="balloon-tail"></div><div class="balloon_layout"><div class="balloon_content">$[[options.contentLayout]]</div></div>'));
		
		//макет для контента балуна
		ymaps.layout.storage.add('sell#balloonContentLayout', ymaps.templateLayoutFactory.createClass(
        	    '<div class="inner-balloon">$[properties.balloonContent]</div>' 
        ));
		
		//макет для метки
		ymaps.layout.storage.add('sell#iconContentLayout',  ymaps.templateLayoutFactory.createClass(
	            '<div id="root-by-id-$[properties.elementID]" class="placemark-root"><div class="iconContent">$[properties.iconContent]</div><div class="hover-content"><p>$[properties.hoverContent]</p></div><div class="inner-content">$[properties.innerContent]</div></div>'
        ));

		
		window.catalog_mapPlacemarks = arPlacemarks.PLACEMARKS;
		for (var i = 0;i< arPlacemarks.PLACEMARKS.length;i++)
		{
			curPlaceMark = arPlacemarks.PLACEMARKS[i];
			curPlaceMark.events.add('balloonopen', function (e) {
				$(".info_pop .close").click(function(){
					if (map.balloon.isOpen())
					{
						map.balloon.close();
					}
					
				});


			});
		}

		
		var zoomControl = new ymaps.control.ZoomControl({
		    options: {
		        layout: 'round#zoomLayout'
		    }
		});
		map.controls.add(zoomControl);
		var typeSelector = new ymaps.control.TypeSelector({
		    options: {
		        layout: 'round#listBoxLayout',
		        itemLayout: 'round#listBoxItemLayout',
		        itemSelectableLayout: 'round#listBoxItemSelectableLayout',
		        float: 'left',
		        position: {
		            top: '300px',
		            left: '10px'
		        }
		    }
		});
		map.controls.add(typeSelector);
		var rulerControl = new ymaps.control.RulerControl({
		    options: {
		        layout: 'round#rulerLayout',
		        float: 'left',
		        position: {
		            top: '345px',
		            left: '10px'
		        }
		    }
		});
		map.controls.add(rulerControl);
		var search = new ymaps.control.SearchControl({options : {noPlacemark:true}});
		map.controls.add(search, { right: '10px', top: '8px' });
		
		
		

	    //map.setBounds(geoObjects.getBounds());
	   
	}
}




$(document).ready(function(){
	$(".sell-map .ymaps-image-with-content").on("click.MapClick",function(){
		if($(this).is(".active"))
		{
			$(this).find(".active-placemark-info").fadeOut();
			$(this).removeClass("active");
		}
		else 
		{
			$(this).find(".active-placemark-info").fadeIn();
			$(this).addClass("active");
		}
		
		
	});
});

if (!window.BX_YMapRemovePlacemarks)
{
	window.BX_YMapRemovePlacemarks = function(map)
	{
		map.geoObjects.removeAll();
	}
}


if (!window.BX_YMapUpdatePlaceMarks)
{
	window.BX_YMapUpdatePlaceMarks = function(map, arPlacemarks)
	{
		
		window.catalog_custer.removeAll();
		//window.BX_YMapRemovePlacemarks(map);
		$(".object-bb.catalog_counts_wrapper").text("0 Объектов");
		if (!arPlacemarks || !arPlacemarks.length )
			return false;
		for(var i=0; i< arPlacemarks.length; i++)
		{
			
			if (typeof(arPlacemarks[i]["CNT"]) != "undefined")
			{
				$(".object-bb.catalog_counts_wrapper").text(arPlacemarks[i]["CNT"]);
			}
			else
			{
				window.catalog_custer.add(window.BX_YMapAddPlacemark(map, arPlacemarks[i], true));
			}
			
		}
		map.events.fire("click");
	}
}

if (!window.BX_YMapAddPlacemark)
{
	window.BX_YMapAddPlacemark = function(map, arPlacemark,event)
	{
		if (null == map)
			return false;

		if(!arPlacemark.LAT || !arPlacemark.LON)
			return false;

		var props = {};
		if (null != arPlacemark.TEXT && arPlacemark.TEXT.length > 0)
		{
			var value_view = '';

			if (arPlacemark.TEXT.length > 0)
			{
				var rnpos = arPlacemark.TEXT.indexOf("\n");
				value_view = rnpos <= 0 ? arPlacemark.TEXT : arPlacemark.TEXT.substring(0, rnpos);
			}

			props.balloonContent = arPlacemark.TEXT.replace(/\n/g, '<br />');
			//props.hintContent = value_view;
			
		}
		if(typeof(arPlacemark.NAME) != "undefined")
		{
			props.iconContent = arPlacemark.NAME;
			//props.iconContent = "<span class=\"white map-mark\">"+arPlacemark.NAME+"</span>";
		}
		if(typeof(arPlacemark.TITLE) != "undefined")
		{
				
			props.hoverContent = arPlacemark.TITLE;//hover-placemark-info
		}
		if(typeof(arPlacemark.INNER_DATA) != "undefined")
		{
			
			
			//props.innnerContent='<div class="active-placemark-info" id="yandex-element-'+arPlacemark.ID+'">'+arPlacemark.INNER_DATA+"</div>";
			props.balloonContent = arPlacemark.INNER_DATA;
		}
		props.elementID = arPlacemark.ID;
		var obPlacemark = new ymaps.Placemark(
			[arPlacemark.LAT, arPlacemark.LON],
			props,
			{
				iconContentLayout: "sell#iconContentLayout",
		        balloonContentLayout: "sell#balloonContentLayout",
		        balloonLayout : "sell#balloonLayout",
		        balloonOffset: [28, -124],
				iconLayout: 'default#imageWithContent',
				iconImageHref : '/local/templates/morealty/components/bitrix/map.yandex.view/sell-map-2.1/images/placemark_map.png',
				iconImageHrefHover : "/local/templates/morealty/components/bitrix/map.yandex.view/sell-map-2.1/images/placemark_map_hover.png",
				iconImageSize : [31,31],
				iconImageOffset:[-10,-37],
				elementID : arPlacemark.ID,
			}
		);
		
		
		
		obPlacemark.events.add("mouseenter",function(event)
		{
			
			var TisElement = "root-by-id-"+event.get("target").options.get("elementID");
			var Target = $("#"+TisElement);
			if(!Target.is(".active"))
			{
				var $TargetHover = Target.find(".hover-content");
				$TargetHover.stop();
				$TargetHover.fadeIn("100");//.hover-content
			}
			
		});
		obPlacemark.events.add("mouseleave",function(event)
		{
			var TisElement = "root-by-id-"+event.get("target").options.get("elementID");
			var Target = $("#"+TisElement);
			if(!Target.is(".active"))
			{
				var $TargetHover = Target.find(".hover-content");
				$TargetHover.stop();
				$TargetHover.fadeOut("100");//.hover-content
				
			}
			
		});
		
		obPlacemark.events.add("click",function(e){
			var InnerElementId = e.get("target").options.get('elementID');
			var $TargetElement = $("#yandex-element-"+InnerElementId);
			if($TargetElement.is(".active"))
			{
				$TargetElement.fadeOut("slow",function(){
					$TargetElement.parents(".ymaps-image-with-content").removeClass("active");
					setTimeout(function(){
						$TargetElement.siblings(".hover-placemark-info").removeClass("fading");
					},300);
				});
				$TargetElement.removeClass("active");
			}
			else 
			{
				$TargetElement.siblings(".hover-placemark-info").addClass("fading");
				$TargetElement.fadeIn("slow",function(){
					$TargetElement.parents(".ymaps-image-with-content").addClass("active");
				});
				$TargetElement.addClass("active");
			}
		});
		if (event)
		{
			obPlacemark.events.add('balloonopen', function (e) {
				$(".info_pop .close").click(function(){
					if (map.balloon.isOpen())
					{
						map.balloon.close();
					}
					
				});
			});
		}
		//map.geoObjects.add(obPlacemark);

		return obPlacemark;
	}
}

if (!window.BX_YMapAddPolyline)
{
	window.BX_YMapAddPolyline = function(map, arPolyline)
	{
		if (null == map)
			return false;

		if (null != arPolyline.POINTS && arPolyline.POINTS.length > 1)
		{
			var arPoints = [];
			for (var i = 0, len = arPolyline.POINTS.length; i < len; i++)
			{
				arPoints.push([arPolyline.POINTS[i].LAT, arPolyline.POINTS[i].LON]);
			}
		}
		else
		{
			return false;
		}

		var obParams = {clickable: true};
		if (null != arPolyline.STYLE)
		{
			obParams.strokeColor = arPolyline.STYLE.strokeColor;
			obParams.strokeWidth = arPolyline.STYLE.strokeWidth;
		}
		var obPolyline = new ymaps.Polyline(
			arPoints, {balloonContent: arPolyline.TITLE}, obParams
		);

		map.geoObjects.add(obPolyline);

		return obPolyline;
	}
}
