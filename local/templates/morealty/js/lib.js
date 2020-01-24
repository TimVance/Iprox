/**
 * Обновлено:
 * isNum - добавлены коды цифр с NumPad`а + TAB
 * isPhone - добавлены коды знаков "-", "+" основной и цифровой клавиатур
 *
 * weXpert js lib
 * @version 1.2 2016
 */

(function ($) {
	/**
	 * Плагин для сокрытия емейлов
	 * @memberOf JQuery
	 */
	$.fn.mailme = function () {
		var at = / AT /;
		var dot = / DOT /g;

		return this.each(function () {
			var text = $(this).text(),
				span_class = $(this).attr('class'),
				addr = text.replace(at, '@').replace(dot, '.'),
				rgx = new RegExp(text),
				html = $(this).html().replace(rgx, addr),
				link = $('<a href="mailto:' + addr + '">' + html + '</a>');
			link.addClass(span_class);
			$(this).after(link);
			$(this).remove();
		});
	};

	/**
	 * сериализует форму в объект JSON
	 * @usage $('form').serializeJSON();
	 */
	$.fn.serializeJSON = function () {
		var json = {};
		jQuery.map($(this).serializeArray(), function (n, i) {
			json[n['name']] = n['value'];
		});
		return json;
	};

	/**
	 * Проверяет код нажатой клавиши для полей типа "телефон"
	 * Разрешены символы: 0-9 + - \s ( )
	 * Разрешены комбинации: Backspace, ctrl + v, ctrl + c, ctrl + r
	 * 
	 * @returns {Boolean}
	 */
	$.fn.checkPhone = function () {
		return this.each(function () {
			$(this).unbind().keydown(function (e) {
				if ((e.ctrlKey == true && (e.keyCode != 67 || e.keyCode != 86 || e.keyCode != 82)) || e.key == 'Backspace') {
					return true; // пускаем ctrl + ( v c r )
				}
				if (e.key.search(/[^0-9\(\)\+\-\s]/i) != -1) {
					return false;
				}
			})
		}).keyup(function (e) {
			$(this).val($(this).val().replace(/[^0-9\(\)\+\-\s]+/gi, ''));
		});
	};
	
	/**
	 * 
	 * Проверяем есть ли элементы скрытые из-за overflow
	 * 
	 * @returns {Boolean}
	 */
	$.fn.isChildOverflowing = function (child) {
		  var p = $(this).get(0);
		  var el = (this).find(child).last().get(0);
		  if(!p || !el)
		  	return false;
		  return (el.offsetTop < p.offsetTop || el.offsetLeft < p.offsetLeft) ||
		    (el.offsetTop + el.offsetHeight > p.offsetTop + p.offsetHeight || el.offsetLeft + el.offsetWidth > p.offsetLeft + p.offsetWidth);
	};
	
	$.fn.toggleText = function () {
		var $This = $(this);
		var CurText = $This.text();
		var NewText = $This.attr("data-other-var");
		if (typeof(NewText) != "undefined")
		{
			$This.text(NewText);
		}
		$This.attr("data-other-var",CurText);
		
	};

})(jQuery);


/**
 * Закрывает открытое окно и убирает овелей
 * @param {obj} th Объект jQuery который необходима закрыть
 */
function HideWin(th, speed) {
	if (speed == undefined) {
		$(th).hide();
		$('#overlay').hide();
	} else {
		$(th).animate({'opacity': '0'}, speed, function () {
			$('#overlay').hide();
			$(this).hide();
		});
	}
}

/**
 * Открывает открытое окно и убирает овелей
 * @param {obj} th Объект jQuery который необходима закрыть
 */
function ShowWin(th, speed) {
	//$("body").prepend('<div id="overlay"></div>');
	if (speed == undefined) {
		$("#overlay").center({'resize': true});
		$('#overlay').show();
		$(th).center().show();
	} else {
		$("#overlay").center({'resize': true});
		$('#overlay').show(speed, function () {
			$(th).css({'opacity': '0', 'display': 'block'}).center().animate({'opacity': '1'}, speed);
		});
	}
}

/**
 * отображает ошибку заполненности формы
 * @param {jQuery} layer родитель-форма, в которой после заголовка .headess нужно вставить ошибку
 * @param {String} errorHtml Ошибка в виде html
 */
function ShowFillFormErrorMess(layer, errorHtml) {
	if ($.trim(errorHtml) == '') {
		return;
	}

	var html = '<div class="alert-errors">';
	html += errorHtml;
	html += '</div>';
	$(html).insertAfter($('.headess', layer));
}

/**
 * убирает ошибку заполненности формы
 * @param {jQuery} layer родитель-форма, в которой после заголовка .HeaderTitle нужно вставить ошибку
 */
function ClearFillFormErrorMess(layer) {
	$('.alert-errors', layer).remove();
}


/**
 * Проверяет строку на пустоту
 * @param str строка для проверки
 * @returns {Boolean}
 */
function isEmpty(str) {
	if ($.trim(str).length > 0) {
		return false;
	} else {
		return true;
	}
}

/**
 * Проверяет код нажатой клавиши, и возвращает true, если это цифра
 * @param int cCode код клавиши
 * @returns {Boolean}
 */
function isNum(cCode) {
	if ((cCode >= 48 && cCode <= 57) || (cCode >= 96 && cCode <= 105) || (cCode >= 17 && cCode <= 20) || cCode == 27 || cCode == 0 || cCode == 127 || cCode == 8 || cCode == 9) {
		return true;
	} else {
		return false;
	}
}
/**
 * Проверяет код нажатой клавиши для полей типа "телефон"
 * @param int cCode код клавиши
 * @returns {Boolean}
 *
 * FIXME перестала работать
 */
function isPhone(cCode) {
	// позволяю пробел, скобки () и знак + - (и комбинацию "вставить" - нельзя)
	if (cCode == 32 || cCode == 40 || cCode == 41 || cCode == 43 || cCode == 45 || cCode == 107 || cCode == 109 || cCode == 189 || cCode == 187) {
		return true;
	} else {
		return isNum(cCode);
	}
}

/**
 * Проверяет емайл по регулярке (строго, но не жадно!)
 * @param {String} str строка для проверки
 * @returns {Boolen}
 * @version 1.0
 */
function isMail(str) {
	return /^[=_.0-9a-z+~-]+@(([-0-9a-z_]+\.)+)([a-z]{2,10})$/i.test(str);
}

/**
 * получить данные по урлу, передав ему параметры и выполнив функцию при получении данных
 * @param string url
 * @param object params
 * @param callback success function(data, textStatus, jqXHR){}
 */
function getResultFromUrl(url, params, success) {
	$.ajax({
		async: true,
		cache: false,
		data: params,
		dataType: 'html',
		timeout: 8000,
		type: 'POST',
		url: url,
		error: function (jqXHR, textStatus, errorThrown) {
		},
		success: success,
		complete: function(data)
		{
			if (data.readyState == 4)
			{
				//success(data.responseText);
			}
		}
	});
}

/**
 * 
 * Функция изменяет параметры в адрессе url по массиву объектов params
 * 
 * Если в params есть объект с key , но без value и в начальной ссылке этот параметр имеется, то в результате этот параметр будет удален.
 * Если в params есть объект с key , с value и в начальной ссылке этот параметр имеется, то в результате значение этого параметра изменится.
 * Если в params есть объект с key , с value, а в начальной ссылке этот параметр остутствует, то в результате этот параметр добавиться в ссылке (value в данном случае не имеет значения).
 * 
 * @param url URL по которому работает
 * @param params Массив объектов вида {key: Название параметра [,value : Значение параметра ]}
 * @returns Обработанный URL
 */
function changeUrlParams(url , paramsEnter, killNoneValue)
{
	
	var params = paramsEnter.slice();

	if (typeof(url) != "string")
	{
		return false;
	}
	if(params.length <= 0)
	{
		return url;
	}
	
	var arUrl = url.split("?");
	/*if(!arUrl[1])
	{
		return arUrl[0];
	}*/
	var arParams = (typeof(arUrl[1]) != "undefined")? arUrl[1].split("&") : [];
	var arComplParams = [];
	for (var i = 0;i<arParams.length;i++)
	{
		var arCurVal = arParams[i].split("=");
		if(typeof(params) == "object" && params.length > 0)
		{
			var $Found = false;
			for(var j = 0; j< params.length; j++)
			{
				
				if (params[j].key == arCurVal[0])
				{
					$Found = true;
					if(params[j].value)
					{
						if (typeof(params[j].value) != "undefined")
						{
							arCurVal[1] = params[j].value;
							
							arComplParams.push({key: arCurVal[0], value : arCurVal[1]});
						}

						
					}
					else
					{
						
					}
					params[j].changed = true;
					break;
				}
			}
			if(!$Found && typeof(arCurVal[1]) != "undefined")
			{
				arComplParams.push({key: arCurVal[0], value : arCurVal[1]});
			}
		}
		else if (typeof(arCurVal[1]) != "undefined")
		{
			arComplParams.push({key: arCurVal[0], value : arCurVal[1]});
		}
		
	}
	arParams = [];
	for(var i = 0; i<arComplParams.length;i++)
	{
		arParams.push(arComplParams[i].key+"="+arComplParams[i].value);
	}
	if(params.length > 0)
	{
		for(var i = 0; i<params.length;i++)
		{
			if(!params[i].changed && typeof(params[i].value) != "undefined")
			{
				if (killNoneValue === true && !params[i].value)
					continue;
				arParams.push(params[i].key+"="+params[i].value);
			}
			
		}
	}

	if(arParams.length <= 0)
	{
		return arUrl[0];
	}
	
	arUrl[1] = arParams.join("&");
	return arUrl.join("?");
	
}

/**
 * 
 * 
 * Проверяет является ли элемент экземпляром jQuery
 * 
 * @param ele 
 * @returns bool
 */
function isjQueryElement(ele)
{
	if (typeof(jQuery) != "function")
	{
		return false;
	}
	return target instanceof jQuery;
}

/**
 * 
 * 
 * Проверяет является ли элемент ванильным Dom Элементом
 * 
 * @param ele 
 * @returns bool
 */
function isVanillaElement(ele)
{
  try {
	    return ele instanceof HTMLElement;
	  }
	  catch(e){
	    return (typeof ele==="object") &&
	      (ele.nodeType===1) && (typeof ele.style === "object") &&
	      (typeof ele.ownerDocument ==="object");
	  }
}

$.fn.isElementOverflowed = function()
{
	var t = $(this);
	return t.prop('scrollWidth') > t.innerWidth() || t.prop('scrollHeight') > t.innerHeight(); 
}


/**
 * 
 * Заствляет браузер перерасчитать стили.
 * Если функция принимает элемент, то заставляет браузер перерасчитать стили этого элемента
 * 
 * @param target (HTMLElement|jQuery)
 */
function FixBrowserDraw(target)
{
	if(typeof(target) != "undefined")
	{
		if (isVanillaElement(target))
		{
			target.style.zoom = 1.0000001;
			setTimeout(function(){target.style.zoom = 1;},50);
		}
		else if(isjQueryElement)
		{
			target[0].style.zoom = 1.0000001;
			setTimeout(function(){target[0].style.zoom = 1;},50);
		}
	}
	else 
	{
		document.body.style.zoom = 1.0000001;
		setTimeout(function(){document.body.style.zoom = 1;},50);
	}
}



/**
 * Analog PHP htmlspecialchars
 * @param text
 * @returns
 * @see http://stackoverflow.com/questions/1787322/htmlspecialchars-equivalent-in-javascript
 */
function escapeHtml(text) {
	var map = {
		'&': '&amp;',
		'<': '&lt;',
		'>': '&gt;',
		'"': '&quot;',
		"'": '&#039;'
	};
	return text.replace(/[&<>"']/g, function (m) {
		return map[m];
	});
}
$.fn.onlyNum =
	function()
	{
	    return this.each(function()
	    {
	        $(this).keydown(function(e)
	        {
	            var key = e.charCode || e.keyCode || 0;
	            return isNum(key);
	        });
	    });
	};