

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

if (!window.BX_YMapAddPlacemark)
{
	window.BX_YMapAddPlacemark = function(map, arPlacemark)
	{
		console.log(arPlacemark);
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
			{//balloonCloseButton: true,
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
		/*obPlacemark.events.add("click",function(event)
		{
			var TisElement = "root-by-id-"+event.get("target").options.get("elementID");
			
			var Target = $("#"+TisElement);
			if(!Target.is(".active"))
			{
				Target.addClass("active");
				Target.find(".hover-content").fadeOut("100");
				Target.find(".inner-content").fadeIn("slow");
			}
			else 
			{
				Target.removeClass("active");
				Target.find(".inner-content").fadeOut("slow");
			}

			
		});*/
		
		obPlacemark.events.add("click",function(e){
			//console.log(e.get("target").options.get("iconImageHref"));
			//event.get('target').options.get('elementId');
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
		map.geoObjects.add(obPlacemark);

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
