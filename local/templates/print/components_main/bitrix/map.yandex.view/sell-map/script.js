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
			props.iconContent = "<span class=\"white map-mark\">"+arPlacemark.NAME+"</span>";
		}
		if(typeof(arPlacemark.TITLE) != "undefined")
		{
			if(props.iconContent.length <= 0) props.iconContent = "";
				
			props.iconContent+='<div class="hover-placemark-info">'+arPlacemark.TITLE+"</div>";
		}
		if(typeof(arPlacemark.INNER_DATA) != "undefined")
		{
			if(props.iconContent.length <= 0) props.iconContent = "";
			
			props.iconContent+='<div class="active-placemark-info" id="yandex-element-'+arPlacemark.ID+'">'+arPlacemark.INNER_DATA+"</div>";
		}
		var obPlacemark = new ymaps.Placemark(
			[arPlacemark.LAT, arPlacemark.LON],
			props,
			{balloonCloseButton: true,
				elementID : arPlacemark.ID,
			}
		);
		obPlacemark.events.add("click",function(e){
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
