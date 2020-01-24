$(document).ready(function(){
	function getParamsFromUrl(url)
	{
		var $return = [];
		var parts = url.split("?");
		if (parts.length > 1)
		{
			var queries = parts[1].split("&");
			for (var i = 0; i < queries.length; i++)
			{
				var current = queries[i];
				$return.push({name : current.split("=")[0], value : current.split("=")[1]});
			}
		}
		return $return;
	}
	
	function bindAjax()
	{
		$(".realtor_objects_wrapper .pages a").unbind("click").bind("click", function(e){
			
			var link = $(this).attr("href");
			var Params = [];
			var LinkParams = getParamsFromUrl(link);
			for(var i = 0; i < LinkParams.length; i++)
			{
				var current = LinkParams[i];
				if (typeof(current.name) != "undefined")
				{
					if (current["name"].indexOf("PAGEN") > -1)
					{
						Params.push(current);
					}
				}
			}
			updateObjects(Params, function(){
				
				$('html, body').animate({
				    scrollTop: $(".agents_objects_wrapper").offset().top - 100
				}, 300);
				
			});
			e.preventDefault();
			return false;
		});
			
	}
	
	function getParamsForFilter()
	{
		$return = [{name  : "ajax", value : "Y"}];
		$return.push({name : "TYPE_BUILD", value : $("select.select-offer-type").val()});
		var currentSort = $(".agent_objects_workarea .sort_wrapper .items_list li span.active");
		if (currentSort.length > 0)
		{
			var mainSort = currentSort.parent("li");
			var order = mainSort.attr("value");
			var destination = mainSort.attr("data-order");
			if (typeof(order) != "undefined" && typeof(destination) != "undefined")
			{
				$return.push({name : "SORT_BY", value : order});
				$return.push({name : "SORT_ORDER", value : destination});
			}
		}
		var currentViewType = $(".agent_objects_workarea .sort_wrapper .more a.activated");
		if (currentViewType.length > 0)
		{
			var type = currentViewType.attr("value");
			if (typeof(type) != "undefined")
			{
				$return.push({name : "VIEW_TYPE", value : type});
			}
		}
		
		return $return;
	}
	
	
	function updateObjects(paramsAdded, callback)
	{
		var url = window.location.href.split("?")[0];
		var params = getParamsForFilter();
		if (typeof(paramsAdded) != "undefined")
		{
			params = $.merge(params, paramsAdded);
			params = $.unique(params);
		}
		$(".agent_objects_workarea .agents_objects_prewrapper").addClass("waiting");
		
		if (params.length > 0)
		{
			var arParams = [];
			for(var i = 0; i < params.length; i++)
			{
				var current = params[i];
				if (typeof(current["name"]) != "undefined" && typeof(current["value"]) != "undefined" && current["name"])
				{
					arParams.push({key : current["name"], value : current["value"]});
				}
			}
			linkToView = changeUrlParams(url, arParams, true);
		}
		
		window.history.pushState(document.title, document.title, linkToView);
		$.get(url, params , function(data){
			$(".agent_objects_workarea .agents_objects_wrapper").replaceWith(data);
			$(".agent_objects_workarea .agents_objects_prewrapper").removeClass("waiting");
			window.initFavourPart();
			window.initMyObjectsSlider();
			bindAjax();
			if (typeof(callback) == "function")
			{
				callback(data);
			}
		}, "html");
	}
	$(".agent_objects_workarea .sort_wrapper ul.items_list li").attr("data-order", "desc").unbind("click");
	
	$(".agent_objects_workarea select.select-offer-type").bind("change",function(){
		/*val  =  $(this).find(":selected").attr("data-value");
		if (typeof(val) != "undefined")
		{
			window.location.href = changeUrlParams(window.location.href,[{key  : "TYPE_BUILD", value : val}]);
		}
		*/
		updateObjects();
	});
	
	$(".agent_objects_workarea .sort_wrapper ul.items_list li").bind("click", function(){
		var t = $(this);
		var currentSpan = t.find("span");
		if (currentSpan.is(".active"))
		{
			var currentDest = t.attr("data-order");
			var nextDest = "asc";
			
			if (typeof(currentDest) != "undefined")
			{
				if (currentDest.toLowerCase() == "asc")
				{
					nextDest = "desc";
				}
			}
			t.attr("data-order", nextDest);
		}
		else
		{
			currentSpan.addClass("active").removeClass("waited");
			t.siblings("li").find("span").removeClass("active").addClass("waited");
		}
		
		updateObjects();
	});
	
	
	$(".agent_objects_workarea .sort_wrapper .more a").bind("click", function(){
		var t = $(this);
		t.addClass("activated").hide();
		t.siblings("a").removeClass("activated").show();
		updateObjects();
	});
	bindAjax();
});