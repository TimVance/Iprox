if (!window.PlaceMarkCreater)
{
	window.PlaceMarkCreater = function(coords)
	{
		
        return new ymaps.Placemark(coords, {
            iconCaption: 'поиск...'
        }, {
            draggable: true
        });
	}
	
}


if (!window.EditorMapEvents)
{
	window.EditorMapEvents = function(map,Objects,StartPoint)
	{
		
		console.log();
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
		            top: '210px',
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
		            top: '255px',
		            left: '10px'
		        }
		    }
		});
		map.controls.add(rulerControl);
		
		if(StartPoint)
		{
			window.MainmyPlacemark = window.PlaceMarkCreater(StartPoint);
			map.geoObjects.add(window.MainmyPlacemark);
			map.setCenter(StartPoint);
		}
		var $MainForm = $("#object-form");
		var $Point = $MainForm.find("#map_point");
		window.MainmyPlacemark;
		
		map.events.add('click', function (e) {
	        var coords = e.get('coords');
	        $Point.val(coords[0]+","+coords[1]);
	        console.log(coords);
	        if (window.MainmyPlacemark) {
	        	// Устанавливаем точку
	        	window.MainmyPlacemark.geometry.setCoordinates(coords);
	        }
	        else {
	        	window.MainmyPlacemark = window.PlaceMarkCreater(coords);
	            map.geoObjects.add(window.MainmyPlacemark);
	            // Слушаем событие окончания перетаскивания на метке.
	            window.MainmyPlacemark.events.add('dragend', function (event) {
	            	var $coords = window.MainmyPlacemark.geometry.getCoordinates();
	            	$Point.val($coords[0]+","+$coords[1]);
	            	// Устанавливаем точку
	            	//window.PlaceMarkGetAdress(myPlacemark.geometry.getCoordinates(),myPlacemark);
	            });
	        }
	    });
        DeleteButton = new ymaps.control.Button("Удалить метку",{selectOnClick: false,});
        DeleteButton.events.add("click",function(){
        	map.geoObjects.remove(window.MainmyPlacemark);
        	window.MainmyPlacemark = "";
        	$Point.val("");
        });
        map.controls.add(DeleteButton, {float: 'right'});
       $(document).ready(function(){
    	  $("#map_adress").bind("change.MapGetPoint",function(){
    		  var $IsOne = false;
    		  map.geoObjects.each(function(geoObject){
    			  $IsOne = true;
    			  return false;
    		  });
    		  if($IsOne)
			  {
    			  return false;
			  }
    		  var $Addres = $(this).val();
    		  if(typeof($Addres) != "undefined" && $Addres.length > 0)
			  {
    			  
    			  var myGeocoder = ymaps.geocode($Addres);
    			  myGeocoder.then(
    			      function (res) {
    			    	  map.setCenter(res.geoObjects.get(0).geometry.getCoordinates());
    			      },
    			      function (err) {
    			          console.log(err);
    			      }
    			  );
    			  

			  }
    	  });
    	  
       });
	}
	
}
$(document).ready(function(){
	var $MainForm = $("#object-form");
	$MainForm.find("select[name='city'],[name='district'],[name='microdistrict']").bind("change.MapGetCoords",function(){
		
		var TisElement =  $(this).find("option[selected='selected']");
		if(TisElement.val() != "all")
		{
			RereadInput();
		}
		
	});
	$MainForm.find("[name='street']").bind("change.MapGetCoords",function(){
		RereadInput();
	});
	
});

function RereadInput(){
	var $MainForm = $("#object-form");
	var $ResultString = "";
	if($MainForm.find("select[name='city'] option[selected='selected']").val() != "all")
	{
		//,[name='district'],[name='microdistrict']
		$ResultString+= $MainForm.find("select[name='city'] option[selected='selected']").text();
		$ResultString+= " "+ $MainForm.find("[name='district'] option[selected='selected']").text();
		$ResultString+= " "+ $MainForm.find("[name='microdistrict'] option[selected='selected']").text();
	}
	$ResultString+= " "+ $MainForm.find("[type='text'][name='street']").val();
	console.log($ResultString);
	if($ResultString.length > 0 )
	{
		$MainForm.find("#map_adress").val($ResultString).trigger("change");
	}
}
