function number_format( number, decimals, dec_point, thousands_sep ) {	// Format a number with grouped thousands
        //
        // +   original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
        // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
        // +	 bugfix by: Michael White (http://crestidg.com)

        var i, j, kw, kd, km;

        // input sanitation & defaults
        if( isNaN(decimals = Math.abs(decimals)) ){
            decimals = 2;
        }
        if( dec_point == undefined ){
            dec_point = ",";
        }
        if( thousands_sep == undefined ){
            thousands_sep = ".";
        }

        i = parseInt(number = (+number || 0).toFixed(decimals)) + "";

        if( (j = i.length) > 3 ){
            j = j % 3;
        } else{
            j = 0;
        }

        km = (j ? i.substr(0, j) + thousands_sep : "");
        kw = i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands_sep);
        //kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).slice(2) : "");
        kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).replace(/-/, 0).slice(2) : "");


        return km + kw + kd;
    }
function LengthOfNum(n)           //Функция возвращает количество цифр
{                                 //  в записи натурального числа n
    var count=0;

    do {n /= 10; count++} while (n >= 1);

    return count;
}
function del_spaces(str)
{
    str = str.replace(/\s/g, '');
    return str;
}
$(document).ready(function(){
	if (typeof(BX.setCookie) == "undefined")
	{
		BX.setCookie = function (name, value, options)
		{
			options = options || {};

			var expires = options.expires;
			if (typeof(expires) == "number" && expires)
			{
				var currentDate = new Date();
				currentDate.setTime(currentDate.getTime() + expires * 1000);
				expires = options.expires = currentDate;
			}

			if (expires && expires.toUTCString)
			{
				options.expires = expires.toUTCString();
			}

			value = encodeURIComponent(value);

			var updatedCookie = name + "=" + value;

			for (var propertyName in options)
			{
				if (!options.hasOwnProperty(propertyName))
				{
					continue;
				}
				updatedCookie += "; " + propertyName;
				var propertyValue = options[propertyName];
				if (propertyValue !== true)
				{
					updatedCookie += "=" + propertyValue;
				}
			}

			document.cookie = updatedCookie;

			return true;
		};
	}
	function isMap()
	{
		return window.catalog_is_map === true;
	}
	
	function getCurrentIblock()
	{
		return $(".iblocks_catalog li.active a");
	}
	
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
		$(".sell-section-ajaxed .pages a").unbind("click").bind("click", function(e){
			
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
			getItemsByFilter(Params, function(){
				if (!isMap())
				{
					$('html, body').animate({
					    scrollTop: $(".sell-section-ajaxed").offset().top - 100
					}, 300);
				}
			});
			e.preventDefault();
			return false;
		});
			
	}
	function filterBeforeSerialize()
	{
		$('.smartfilter .fields-price-bb.pricefield.number-fields input').each(function(){
			var t = $(this);
			t.val(del_spaces(t.val()));
		});
	}
	function filterAfterSerialize()
	{
		$('.smartfilter .fields-price-bb.pricefield.number-fields input').each(function(){
			var t = $(this);
			var val = t.val();
			if (val && val.length > 0)
			{
				t.val(number_format(val, 0, '', ' '));
			}
			
		});
	}
	
	function getFilterArray()
	{
		filterBeforeSerialize();
		var Data = $(".smartfilter").serializeArray();
		filterAfterSerialize();
		Data.push({name : "set_filter", value : "Y"});
		Data.push({name : 'ajax', value : "Y"});
		var Sort = $("#sort").val();
		if (typeof(Sort) != "undefined" && Sort.length > 0)
		{
			Data.push({name : 'sort', value : Sort});
		}
		var addProp = $(".custom_prop_filter li.active a");
		var dataProp = addProp.attr("data-prop");
		var dataVal = addProp.attr("data-val");
		if (typeof(dataProp) != "undefined" && typeof(dataVal) != "undefined")
		{
			Data.push({name : dataProp, value : dataVal});
		}
		return Data;
	}
	
	function getFilterParamsString()
	{
		filterBeforeSerialize();
		var Data = $(".smartfilter").serialize();
		filterAfterSerialize();
		
		Data+="&set_filter=Y";
		var Sort = $("#sort").val();
		if (typeof(Sort) != "undefined" && Sort.length > 0)
		{
			Data+="&sort="+Sort;
		}
		
		var addProp = $(".custom_prop_filter li.active a");
		var dataProp = addProp.attr("data-prop");
		var dataVal = addProp.attr("data-val");
		if (typeof(dataProp) != "undefined" && typeof(dataVal) != "undefined")
		{
			Data+="&"+dataProp+"="+dataVal;
		}
		return Data;
	}
	
	function getItemsByFilter(innerData, callback)
	{
		data = getFilterArray();
		if (typeof(innerData) != "undefined")
		{
			data = $.merge(innerData, data);
			data = $.unique(data);
		}
		var url = location.href.split("?")[0];
		//var linkToView = location.href;
		var linkToView = url;
		
		if (data.length > 0)
		{
			var arParams = [];
			for(var i = 0; i < data.length; i++)
			{
				var current = data[i];
				if (typeof(current["name"]) != "undefined" && typeof(current["value"]) != "undefined" && current["name"])
				{
					arParams.push({key : current["name"], value : current["value"]});
				}
			}
			linkToView = changeUrlParams(linkToView, arParams, true);
		}
		
		window.history.pushState(document.title, document.title, linkToView);
		if (isMap())
		{
			
			$.get(url, data, function(response){
				//console.log(response);
				window.BX_YMapUpdatePlaceMarks(window.catalogmap, response);
				if (typeof(callback) == "function")
				{
					callback(response);
				}
				//window.catalogmap
			}, "json");
		}
		else
		{
			$(".sell-section-ajaxed").addClass("waiting");
			$.get(url, data, function(response){
				$(".sell-section-ajaxed").removeClass("waiting").replaceWith(response);
				var totalCount = $(".sell-section-ajaxed #all_count").val();
				if (totalCount)
				{
					$(".catalog_counts_wrapper").text(totalCount);
				}
				bindAjax();
				window.initFavourPart();
				window.initMyObjectsSlider();
				if (typeof(callback) == "function")
				{
					callback(response);
				}
			}, "HTML");
		}
		
	}
	
	
	
	$(".view-map-bb a").bind("click", function(e){
		$(".catalog_go_to_map a").trigger("click");
		e.preventDefault();
		return false;
	});
	
	$(".item_root_checkbox p input[type='checkbox']").bind("change", function(e){
		var t = $(this);
		var bChecked = t.is(":checked");
		var li = t.parents(".item_root_checkbox");
		
		var ul = li.find("ul");
		if (ul.length > 0)
		{
			ul.find("input[type='checkbox']").prop("checked", bChecked).trigger("change", [{dontUpdate : true}]);
		}
	});
	
	
	
	$(".smartfilter :input").bind("change", function(e, params){
		if (typeof(params) != "undefined" && params.dontUpdate)
		{
			return false;
		}
		getItemsByFilter();
	});
	$(".smartfilter").bind("submit", function(e){
		
		getItemsByFilter();
		e.preventDefault();
		return false;
	})
	
	$("#sort").bind("change", function(e){
		
		getItemsByFilter();
		e.preventDefault();
		return false;
	});
	
	$(".custom_prop_filter a").bind("click", function(){
		var t = $(this);
		var li = t.parent("li");
		if (li.is(".active"))
			return false;
		$(".custom_prop_filter li").removeClass("active");
		li.addClass("active");
		getItemsByFilter();
	});
	
	
	$(document).mouseup(function(e) 
	{
	    var container = $(".die-ff");

	    // if the target of the click isn't the container nor a descendant of the container
	    if (!container.is(e.target) && container.has(e.target).length === 0) 
	    {
	        container.hide();
	    }
	});
	$('.smartfilter .fields-price-bb.pricefield.number-fields input').keyup(function() {
        // var max = $(this).attr('data-max');
        $(this).val(del_spaces($(this).val()));
        var thiis = $(this);
        var max = 10000000000000000;
        var die_ff = thiis.parent('.inp-bb').children('.die-ff');
        var list = die_ff.children('ul');
        var number = thiis.val();
        list.empty();
        if($.isNumeric(number) && number != 0) {
            var in_number = number * 100000;
            var is_show = false;
            if(LengthOfNum(number)  == 1) {
                if(in_number <= max) {
                    list.append('<li>' + number_format(number * 100000, 0, '', ' ') + '</li>');
                    is_show = true;
                }
                in_number = number * 110000;
                if(in_number <= max) {
                    list.append('<li>' + number_format(number * 110000, 0, '', ' ') + '</li>');
                    is_show = true;
                }
                if(in_number <= max) {
                    list.append('<li>' + number_format(number * 1000000, 0, '', ' ') + '</li>');
                    is_show = true;
                }
                in_number = number * 1100000;
                if(in_number <= max) {
                    list.append('<li>' + number_format(number * 1100000, 0, '', ' ') + '</li>');
                    is_show = true;
                }
                in_number = number * 10000000;
                if(in_number <= max) {
                    list.append('<li>' + number_format(number * 10000000, 0, '', ' ') + '</li>');
                    is_show = true;
                }
                in_number = number * 11000000;
                if(in_number <= max) {
                    list.append('<li>' + number_format(number * 11000000, 0, '', ' ') + '</li>');
                    is_show = true;
                }
                in_number = number * 100000000;
                if(in_number <= max) {
                    list.append('<li>' + number_format(number * 100000000, 0, '', ' ') + '</li>');
                    is_show = true;
                }
                in_number = number * 110000000;
                if(in_number <= max) {
                    list.append('<li>' + number_format(number * 110000000, 0, '', ' ') + '</li>');
                    is_show = true;
                }
                if(is_show == true) {
                    die_ff.show();
                } else {
                    die_ff.hide();
                }
            } else {
                if(in_number <= max) {
                    list.append('<li>' + number_format(number * 100000, 0, '', ' ') + '</li>');
                    is_show = true;
                }
                if(in_number <= max) {
                    list.append('<li>' + number_format(number * 1000000, 0, '', ' ') + '</li>');
                    is_show = true;
                }
                in_number = number * 10000000;
                if(in_number <= max) {
                    list.append('<li>' + number_format(number * 10000000, 0, '', ' ') + '</li>');
                    is_show = true;
                }
                in_number = number * 100000000;
                if(in_number <= max) {
                    list.append('<li>' + number_format(number * 100000000, 0, '', ' ') + '</li>');
                    is_show = true;
                }
                if(is_show == true) {
                    die_ff.show();
                } else {
                    die_ff.hide();
                }
            }

            $('.die-ff ul li').click(function() {
                var val = $(this).text();
                var newval = val.replace(/[^-0-9]/gim,'');
                newval = number_format(newval, 0, '', ' ');
                // $(this).parent('ul').parent('.die-ff').parent('.field-cost').children('input[name="arrFilter_pf[LEFT_PRICE]"]').val(newval);
                thiis.val(newval);
                $(this).parent('ul').parent('.die-ff').hide();
                thiis.trigger("change");
            });
            $(this).val(number_format($(this).val(), 0, '', ' '));
        }
        else
        {
        	$(this).val("");
        }

        
    });
	
	$(".type-selector li a").bind("click", function(){
		var t = $(this);
		var li = t.parent("li");
		if (li.is(".active"))
			return false;
		li.addClass("active").siblings("li").removeClass("active");
		var type = t.attr("data-val");
		var code = t.attr("data-code");
		
		if (li.is(".catalog_go_to_map") || (typeof(code) != "undefined" && code.length > 0 && typeof(type) != "undefined" && type.length > 0))
		{
			if (code && type)
			{
				$.cookie('BITRIX_SM_'+code, type, { expires: 365 });
				//BX.setCookie('BITRIX_SM_'+code, type, {expires: 86400 * 365});
			}
			
			if (isMap() || li.is(".catalog_go_to_map"))
			{
				var current = getCurrentIblock();
				var url = (li.is(".catalog_go_to_map"))? current.attr("data-map") :  current.attr("data-def");
				if (url)
				{
					var postfix = ""
					if (type && code)
					{
						postfix = "&"+code+"="+type;
					}
					location.href = url+"?"+getFilterParamsString()+postfix;
				}
			}
			else
			{
				getItemsByFilter([{name : code, value : type}]);
			}
			
		}
		
		
		return false;
	})
	
	bindAjax();
	
	setTimeout(function(){
		
		var Active = $(".type-selector li.active a");
		var type = Active.attr("data-val");
		var code = Active.attr("data-code");
		if ((typeof(code) != "undefined" && code.length > 0 && typeof(type) != "undefined" && type.length > 0))
		{
			$.cookie('BITRIX_SM_'+code, type, { expires: 365 });
		}
	}, 200);
});