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
	            '<div id="root-by-id-$[properties.elementID]" class="placemark-root"><div class="iconContent">$[properties.iconContent]</div><div class="hover-content">$[properties.hoverContent]</div><div class="inner-content">$[properties.innerContent]</div></div>'
        ));

		map.events.add('balloonopen', function (e) {
				$(".info_pop .close").click(function(){
					//$(".balloon_layout .close").unbind(".BalloonClose");
					map.balloon.close();
				});


		});
		
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
	}
}


