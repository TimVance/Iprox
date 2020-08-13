/**
 * morealty common scripts
 * @author wexpert.ru
 * @version 1.2
 */
window.initMyObjectsSlider = function()
{
	$('.slider-agent').bxSlider({
		minSlides: 1,
		//slideWidth: 324,
		adaptiveHeight : true,
		maxSlides: 1,
		moveSlides: 1,
		slideMargin: 0,
		pager: false
	});
	window.mainpageInnerSlider = $('.slider-agent2').bxSlider({
		minSlides: 1,
		slideWidth: 235,
		slideHeight : 176,
		maxSlides: 1,
		moveSlides: 1,
		slideMargin: 17,
		pager: false,
		adaptiveHeight: true,
		responsive : true,
		superresize : true,
	});
	
	
	/*$('.slider-agent2').slick({
		slidesToScroll: 1,
		infinite : true,
		slidesToShow: 1,
		centerMode : true,
		arrows : true,
		
	});*/
}
window.initPhones = function(parent)
{
	$prefix = (typeof(parent) != "undefined")? parent : "";
	console.log($prefix+' .view-phone a');
	$($prefix+' .view-phone a').click(function() {
		var Builder = $(this).attr("data-builder");
		var Agents  = $(this).attr("data-agents");
		var url;
		if (typeof(Builder) != "undefined")
		{
			url = '/local/templates/morealty/ajax_php/get-user-phone-number.php?BUILDER=' + Builder;
		}
		else if (typeof(Agents) != "undefined")
		{
			url = '/local/templates/morealty/ajax_php/get-agents-phone-number.php?agents=' + Agents;
		}
		else if ($(this).attr('data-realtor')) 
		{
			var ID = $(this).attr('data-realtor');
			url = '/local/templates/morealty/ajax_php/get-user-phone-number.php?ID=' + ID;
		}
		else
		{
			var ID = $('#realtor_id').val();
			url = '/local/templates/morealty/ajax_php/get-user-phone-number.php?ID=' + ID;
		}

		var currentBlock = $(this);
		$('.view-phone').find('span').text("Загрузка...");
		$(currentBlock).hide();
		$(currentBlock).parents('.view-phone').find('.phone-v').show();
		$.ajax({
			url: url,
			success: function(data) {
				$('.view-phone').find('span').text(data);
			}
		});
	});	
}

window.initFavourPart = function()
{
	/**
	 * 
	 * 
	 * Избранное на кэшируемых страницах
	 * 
	 */
	if($('#is_authorized').val() == 'y') {
		var $FavourIds = [];
		$(".check-favor").each(function(i,v)
		{
			$FavourIds.push($(this).attr("data-element"));
		});
		if($FavourIds.length > 0)
		{
			$.ajax({
				dataType: "json",
				data: {favourIDS: $FavourIds},
				url: '/local/templates/morealty/ajax_php/check_favor.php',
				success: function(data) {
					if(typeof(data) == "object")
					{
						for(var key in data)
						{
							if(data.hasOwnProperty(key)){
								
						        var value = data[key];
						        if(value == "Y")
						        {
						        	var $RealId = key.replace("key","");
						        	$(".check-favor[data-element='"+$RealId+"']").removeClass("check-favor").addClass("active");
						        }
						        
						        
						    }
						}
					}
				}
			});
		}
	}
	$('#btn-favor,.favor_status').unbind(".Favour");
	//favorites: add / delete
	$('#btn-favor,.favor_status').bind("click.Favour",function() {
		if ($(this).is(".new-type"))
		{
			var itemValue = $(this).attr("data-element");
			if (typeof(itemValue) == "undefined")
			{
				return false;
			}
		}
		else 
		{
			var itemValue = $('#item-id').val();
		}
		
		if($('#is_authorized').val() == 'y') {
			if(!$(this).hasClass('active')) {
				$.ajax({
					dataType: "json",
					url: '/local/templates/morealty/ajax_php/add-favorites.php?ID=' + itemValue,
					success: function(data) {
						if(typeof(data.COUNT) != "undefined")
						{
							$(".func-head .favor a span").text(data.COUNT);
						}
					}
				});
				$(this).addClass('active');
			}
			else {
				$.ajax({
					dataType: "json",
					url: '/local/templates/morealty/ajax_php/delete-favorites.php?ID=' + itemValue,
					success: function(data) {
						if(typeof(data.COUNT) != "undefined")
						{
							$(".func-head .favor a span").text(data.COUNT);
						}
					}
				});
				$(this).removeClass('active');
			}
		} else {
			$('a[href="#pop12"]').trigger('click');
		}

	});
	$('.but-favor').unbind(".Favour");
	$('.but-favor').bind("click.Favour",function() {
		var itemValue = $(this).parent('.desc-agents').parent('.item-agents').children('#item-id').val();
		if($('#is_authorized').val() == 'y') {
			if(!$(this).hasClass('active')) {
				$.ajax({
					dataType: "json",
					url: '/local/templates/morealty/ajax_php/add-favorites.php?ID=' + itemValue,
					success: function(data) {
						if(typeof(data.COUNT) != "undefined")
						{
							$(".func-head .favor a span").text(data.COUNT);
						}
					}
				});
				$(this).addClass('active');
			}
			else {
				$.ajax({
					dataType: "json",
					url: '/local/templates/morealty/ajax_php/delete-favorites.php?ID=' + itemValue,
					success: function(data) {
						if(typeof(data.COUNT) != "undefined")
						{
							$(".func-head .favor a span").text(data.COUNT);
						}
					}
				});
				$(this).removeClass('active');
			}
		} else {
			$('a[href="#pop12"]').trigger('click');
		}
	});
}

window.initSort = function()
{
	$('.catalog-sort:not(.stop_default) ul li span').click(function() {
		var val = $(this).parent('li').attr('value');
		var currentVal = $('input[name=SORT_BY]').val();
		$('input[name=SORT_BY]').attr('value', val);
		if(val == currentVal) {
			var currentSortOrder = $('input[name=SORT_ORDER]').val();
			$('input[name=SORT_ORDER]').attr('value', (currentSortOrder == 'ASC') ? 'DESC' : 'ASC');
		}
		$('#submit').trigger('click');
	});
}

window.initTiles = function(){
	$('#show_map_list, .show_map_list').not(".map-object-list,.sell-items-corrupter, .im_myself").click(function(e) {
		var val = $(this).attr('value');
		var $this = $(this);
		if(!$(this).is(".not-reload"))
		{
			//$('input[name=VIEW_TYPE]').attr('value', val);

			//$('#submit').trigger('click');
			window.location.href = changeUrlParams(window.location.href,[{key : "VIEW_TYPE", value : val},{key : "PAGEN_2"},{key: "PAGEN_1"}]);
		}
		else 
		{
			getResultFromUrl(changeUrlParams(window.location.href,[{key : "VIEW_TYPE", value : val},{key : "PAGEN_2"}]),{ajax : "Y"},
				function(data){
					$("#special-offers").html(data);
					$this.hide();
					$this.siblings("a").show();
					window.initMyObjectsSlider();
					window.initFavourPart();
				}		
			);
		}
		e.preventDefault();
	});
	$(".sell-items-corrupter").bind("click",function(e){
		var val = $(this).attr('value');
		var $this = $(this);
		$(this).text("Загрузка...");
		getResultFromUrl(changeUrlParams(window.location.href,[{key : "VIEW_TYPE", value : val},{key : "PAGEN_2"},{key : "ajax", value : "Y"}]),{},
				function(data){
					$("body").append('<div class="tmp-hidden ajax-tiles-block"></div>');
					
					$(".ajax-tiles-block").html(data);
					var dataHTML = $(".ajax-tiles-block").find(".sell-section-ajaxed").html();
					$(".ajax-tiles-block").remove();
					$(".sell-section-ajaxed").html(dataHTML);
					window.initMyObjectsSlider();
					window.initFavourPart();
					window.initTiles();
					window.initSort();
				}		
			);
		e.preventDefault();
	});
}
$(function() {
	//colorbox
	if ($.colorbox) {
		$(".inline").colorbox({inline: true, closeButton: true, escKey: true});
		$(".payOnline").colorbox({inline: true, closeButton: true, escKey: true,
			onComplete: function(e){
			var $Block = $("#pay-oonline");
			$Block.find("[name='buyMoney']").val($(this).attr("data-sum"));
			BX.fireEvent($Block.find(".sale-account-pay-button")[0],"click");
		}});
		$(".image-viewer").colorbox({closeButton: true, escKey: true,photo : true,maxWidth : "100%",maxHeight : "100%"});
		$(".inline-message").colorbox({inline: true, closeButton: true, escKey: true,onOpen:function(e){
			var $USERId = $(this).attr("data-user");
			if(typeof($USERId) != "undefined")
			{
				$("input[name='form_text_17']").val($USERId);
			}
		}});
		$(".cboxAjaxData").bind("click",function(){
			var $this = $(this);
			var $Target = $this.attr("data-target");
			var $floor = $this.attr("data-floor");
			$.colorbox(
					{
						href : "/local/templates/morealty/ajax_php/plan-element.php?floor="+$floor+"&ID="+$Target,
						escKey: true,
						ajax: true,
						closeButton: true,
						onComplete: function(){
							$.colorbox.resize();
						}
					}
			);
		})
		
		$('.close').not(".map-close").click(function(){$('#cboxOverlay').click();});
		$(".group1").colorbox({closeButton: true});
		$('body').on('click', '.group1', function () {setTimeout(function() { $('#colorbox').addClass('active');}, 100)});
		$('body').on('click', '#cboxOverlay, #cboxClose', function () {$('#colorbox').removeClass('active');$('body').removeClass('act');$('.wr-pop-inp').hide();})
	}
 
	$('.add-ask a').click(function() {
		$('#form-question-wrap').toggle(400);
	});
	$('.reply-btn').click(function() {
		$(this).parent('.bl-ask').children('.reply-form').toggle(400);
	});

	// hide модалкам по нажатию esc (27)
	$(this).keydown(function(eventObject){
		if (eventObject.which == 27) {
			$('body').removeClass('act');
			$('.wr-pop-inp').hide();
		}
	});
	$(".map-pop .close").click(function(){
		$(this).parents(".map-pop").fadeOut();
	});

	window.initPhones();
	window.initFavourPart();
	//set view
	window.initTiles();
	window.initSort();
	
	

	//set sort_order

	//триггер формы фильтра при клике на "Подобрать"
	
	$(".filter-line input").bind("keyup",function(e){
		if (e.keyCode == 13)
		{
			$(this).parents(".sec").find("a.pick").trigger("click");
		}
	});
	
	$('.pick').click(function() {
		var propertyValue;
		if($(this).hasClass('pick-search')) {
			console.log('pick-search');
			propertyValue = $(this).parent('.form').children('.field').children('input[type=text]').val();
		}
		else if ($(this).parent('.form').children('.field').children('input:not([name="from"],[name="to"])[type="text"]').val() != undefined)
		{
			propertyValue = $(this).parent('.form').children('.field').children('input[type="text"]').val();
		}
		else if($(this).parent('.form').children('.field').children('input[name=from]').val() == undefined) {
			console.log('select');
			propertyValue = $(this).parent('.form').children('.field').children('select').children('option:selected').val();
		} else if($(this).parent('.form').children('.field').children('input[name=from]').val() != undefined) {
			console.log('input');
			var from = $(this).parent('.form').children('.field').children('input[name=from]').val();
			var to = $(this).parent('.form').children('.field').children('input[name=to]').val();
			propertyValue = from + ',' + to;
		}

		$('input[name=FILTER_PROPERTY]').attr('value', $(this).attr('value'));
		$('input[name=FILTER_VALUE]').attr('value', propertyValue);
		$('#FILTER_FORM_SUBMIT').trigger('click');
	});
	$(".catalog_smart_filter .pick").unbind("click").bind("click", function(){
		var t = $(this);
		t.parents("form").find("#set_filter").click();
	});
	$(".catalog_smart_filter .filter-cancel").bind("click", function(){
		var t = $(this);
		t.parents("form").find("#del_filter").click();
	});

	//триггер формы фильтра по нажатию enter в форме ввода названия объекта
	$("#search input[name=query]").keyup(function(event){
		if(event.keyCode == 13){
			event.preventDefault();
			$(this).parents('.form').children('.pick').trigger('click');
		}
	});


	//require fields
	$('input[name=save]').click(function(e) {
		var requiredFields = $('.req-star');
		var currentInput;
		var label;
		for(var i = 0; i < requiredFields.length; i++) {
			currentInput = $(requiredFields[i]).parent('label').parent('.field-pass').children('input');
			if($.trim($(currentInput).val()) == '') {
				$(currentInput).addClass('error');
				if(($(currentInput).parent('.field-pass').find('div span').text() == '')) {
					label = $(currentInput).parent('.field-pass').children('label').text().replace(':', '');
					label = label.replace('*', '');
					$(currentInput).parent('.field-pass').append("<div><label></label><span class='comp-s comp-s2'>Заполните обязательное поле «" + label + "»</span></div>");
				}
				e.preventDefault();
			} else {
				if ($(currentInput).hasClass('error')) {
					$(currentInput).removeClass('error');
					$(currentInput).parents('.field-pass').children('div').remove();
				}
			}
		}
	});

	
	$('body').on('click', '.inline', function(){setTimeout(function() {$('body').addClass('act');}, 0)})
	$('body').on('click', '.close, #cboxOverlay', function(){$('body').removeClass('act');$('.wr-pop-inp, .ov').hide();})
	$('body').on('click', '.lich-pop', function(){
		$('body').addClass('act');
		$('.wr-pop-inp, .ov').show();
		return false;
	});
	$('body').on('click', '.ov', function(){
		$('.wr-pop-inp').hide();
		$(this).hide();
		$('body').removeClass('act');
	});
	$('body').on('click', 'input[name=register_submit_button]', function(e) {
		var reqFields = $('.tab-visible .req');
		var isEmpty = false;
		var label;
		for(var i = 0; i < reqFields.length; i++) {

			if( ($(reqFields[i]).attr('type') == 'text') || ($(reqFields[i]).attr('type') == 'password')) {
				if(($(reqFields[i]).val() == '')) {
					$(reqFields[i]).addClass('error');
					if(($(reqFields[i]).parents('.ff-pass').find('span').text() == '')) {
						label = $(reqFields[i]).parents('.field-pass').find('label').text().replace(':', '');
						label = label.replace('*', '');
						$(reqFields[i]).parents('.ff-pass').append("<span class='comp-s'>Заполните обязательное поле «" + label + "»</span>");
					}
					isEmpty = true;
				} else {
					if($(reqFields[i]).hasClass('error')) {
						$(reqFields[i]).removeClass('error');
						$(reqFields[i]).parents('.ff-pass').find('.comp-s').remove();
					}
				}
			}

			if($(reqFields[i]).attr('type') == 'checkbox') {
				if(!($(reqFields[i]).is(':checked')) && $(reqFields[i]).attr('name') == 'UF_DISPLAY_PRIVATE') {
					$(reqFields[i]).parent('div').addClass('error');
					isEmpty = true;
				}
				else {
					if($(reqFields[i]).parent('div').hasClass('error')) {
						$(reqFields[i]).parent('div').removeClass('error');
					}
				}
			}
		}
		if(isEmpty) {
			e.preventDefault();
		}
	});
	
	//for mac
	if (navigator.userAgent.indexOf('Mac') > 0) {
		$('body').addClass('mac-os');
	}
	//$('.slier-objects').show();
	window.initMyObjectsSlider();
	//slider projects
	/*window.mainPagesliders = $('.slider1, .slider2, .slider3, .slider4, .slider5').filter(":visible").bxSlider({
		minSlides: 1,
		slideWidth: 237,
		maxSlides: 4,
		moveSlides: 1,
		slideMargin: 17,
		pager: false,
		adaptiveHeight: true,
		responsive : true,
	});*/
	
	window.mainPagesliders = $('.slider1, .slider2, .slider3, .slider4, .slider5').slick({
		slidesToShow: 4,
		slidesToScroll: 1,
		//centerMode: true,
		//variableWidth: true,
		adaptiveHeight: true,
		//responsive : true,
		
	});
	setTimeout(function(){
		$(window).trigger("superresize");
		setTimeout(function(){
			$(window).trigger("superresize");
			setTimeout(function(){
				$(window).trigger("superresize");
				setTimeout(function(){
					$(window).trigger("superresize");
					setTimeout(function(){
						$(window).trigger("superresize");
						setTimeout(function(){
							$(window).trigger("superresize");
						},100);
					},100);
				},200);
			},100);
		},100);
	},200);
	
	//$('.slier-objects').not(":eq(0)").fadeOut(100);
	//последние объекты на главной
	
	var bxViewportStyles = $('.slider1').parents('.bx-viewport').attr('style');
	var slideStyles = $('.slider1').children('.slide:eq(0)').attr('style');
	$('.last-objects .head-object ul li a').click(function() {
		
		//$parentBlock = $(this).parents("");
		$('.head-object li').removeClass('active');
		$(this).parent('li').addClass('active');
		$('.slier-objects').hide();
		$('.slier-objects.visible').removeClass('visible');
		var sliderClass = $(this).attr('data-slider');
		$('.' + sliderClass).parents('.slier-objects').addClass('visible');
		$('.' + sliderClass).parents('.slier-objects').show();
		$('.slider1, .slider2, .slider3, .slider4, .slider5').filter(":visible").slick('setPosition');
		$(window).trigger("superresize");
		/*$('.slider1, .slider2, .slider3, .slider4, .slider5').filter(":visible").bxSlider({
			minSlides: 1,
			slideWidth: 237,
			maxSlides: 4,
			moveSlides: 1,
			slideMargin: 17,
			pager: false,
			adaptiveHeight: true,
			responsive : true,
		});
		$('.' + sliderClass).parents('.bx-viewport').attr('style', bxViewportStyles);
		$height  = $('.' + sliderClass+" .slide .img-propos").height();
		$('.' + sliderClass+" .slide .bx-viewport").css({"height": $height});
		$('.' + sliderClass).children('.slide').attr('style', slideStyles);*/

		
		
		
		//window.mainpageInnerSlider.redrawSlider();
		//$(window).trigger("superresize");
		//$(window).trigger("resize");
	});


	
	$(".thumb-img a.full-screener").bind("click",function(){
		$(this).addClass("showfull");
	});
	
	
	$('.slider-big1').bxSlider({
		pagerCustom: '#bx-pager',
		onSlideBefore : function(a,b,c){
			if (typeof(this.pagerCustom) != "undefined")
			{
				var target = $("#bx-pager a").eq(c)
				if (target.is(".showfull"))
				{
					a.trigger("click");
					target.removeClass("showfull");
				}
			}

		}
	});

	$(".filter-line .secs .sec .form .pricefield input, .line-field.field-bold [name=\"price\"], input[name='price_to-temp'], input[name='price_from-temp']").inputmask('999 999 999 999', { numericInput: true ,'autoUnmask' : true,placeholder: '',});
	//.filter-line .secs .sec .form .pricefield
	
	
	
	function explode(){
		window.newbuilding_slider = $('.slider-big2').bxSlider({
			pagerCustom: '#bx-pager2'
		});
	}
	setTimeout(explode, 1000);

	$('.slider-big3').bxSlider({
		pagerCustom: '#bx-pager3'
	});

	//select
	$('.sel-pr select, .select-town select, .sel-on select, .sel-view select, .filter-line select, .select-wrapper select').not(".not_selectobx").selectbox();

	$('body').on('click','.select-check-bb > span',function(){
		$(this).toggleClass('act').next().toggle();
	});
	
	$(document).mouseup(function (e){
		var div = $('.select-check-bb');
		if (!div.is(e.target)
			&& div.has(e.target).length === 0) {
			$('.panel-check-bb').hide();
			$('.select-check-bb span').removeClass('act');
		}
	});			
	$('.select-bb select').selectbox();
	
	
	//checkbox
	$('.customP input[type="checkbox"]').ezMark({checkboxCls: 'ez-checkbox-green', checkedCls: 'ez-checked-green'})
	$('.customP input:checked').parent().next('label').addClass('active');
	$('.ez-hide').change(function() {
		$(this).parent().next('label').toggleClass('active');
	});
	$('.defaultP input').ezMark();

	//li active
	$('.nav-rooms ul li').click(function() {
		$(this).parent('ul').children('li').removeClass('active');
		$(this).addClass('active');
	});
	//tabs
	$('.tabs-tb').each(function(){
		if(!$(this).hasClass('lich-obj') && !$(this).hasClass('block-regist')) {
			$(this).find('.tab-tb').hide();
			$(this).find('.tab-tb.active').show();
			$(this).find('.tab-tb:eq(0)').show();
			$(this).find('ul li:eq(0)').addClass('active');
		}
		//$(this).find('.nav-tb li:eq(0)').addClass('active');
		$(this).find('.nav-tb li').click(function () {
			var id  = $(this).attr('id');
			if(id == 'owner') {
				id = "8";
				$('.free-ads').addClass('offer-hidden');
			}
			else if(id == 'realtor') {
				id = "7";
				$('.free-ads').removeClass('offer-hidden');
			}
			console.log(id);
			$('input[name=WORK_PROFILE]').val(id); // set USER_ROLE (owner or realtor)
			var ind = $(this).index();
			$(this).parents('.tabs-tb').find('.cont-tb').find('.tab-tb:eq(' + ind + ')').show().removeClass('tab-hidden').addClass('tab-visible').siblings('.tab-tb:visible').hide().removeClass('tab-visible').addClass('tab-hidden');
			$(this).addClass('active');
			$(this).siblings('.nav-tb li').removeClass('active');
			return false;
		});
	});
	
	//gallery card
	setTimeout(function() { $('.tab-all').hide();}, 200)
	setTimeout(function() {$('.tab-all:eq(0), .tab-thumbs:eq(0)').show();}, 300)
	$('.nav-gal li:eq(0)').addClass('active');
	$('.nav-gal li').click(function () {
		var ind = $(this).index();
		$('.cont-all').find('.tab-all:eq(' + ind + ')').show().siblings('.tab-all:visible').hide();
		$(this).addClass('active');
		$(this).siblings('.nav-gal li').removeClass('active')
		return false;
	});
	
	//menu
	$('.menu > ul > li').hover(function() {
		$(this).find('.panel-menu').show();
	}, function() {
		$(this).find('.panel-menu').hide();
	});
	
	//click functions
	$('.history-pay a').click(function() {
		$(this).parent().next().toggle()
		return false;
	});
	
	$('.link-acc a').click(function() {
		$(this).toggleClass('act')
		$('.text-acoord').toggleClass('act')
		return false;
	});
	
	$('.tit-accord').click(function() {$(this).next('.text-accrod').toggle();FixBrowserDraw($(".main"))});
	

	
	$('.view-rielt-phone a').click(function() {
		var ID = $('#realtor_id').val();
		var currentBlock = $(this);
		$('.view-rielt-phone').children('span').text("Загрузка");
		$(currentBlock).hide();
		$(currentBlock).next('span').show();
		console.log(ID);
		$.ajax({
			url: '/local/templates/morealty/ajax_php/get-user-phone-number.php?ID=' + ID,
			success: function(data) {
				$('.view-rielt-phone').children('span').text(data);
			}
		});
		return false;
	});
	
	//die benef
	/*$('.ico-info').hover(function() {
		var fin = $(this).find('.die-benef').height();
		var heig = fin/2-4
		$(this).find('.die-benef').css({ 'margin-top': - heig }).show();
	}, function() {
		$(this).find('.die-benef').hide();
	});*/
	$('.ico-info > .die-benef').click(function(e){
		e.stopPropagation();
	});
	$('.ico-info').click(function(){
		var Benef = $(this).find('.die-benef');
		if (Benef.is(".opened_tooltip"))
		{
			Benef.removeClass("opened_tooltip").hide();
		}
		else
		{
			var fin = Benef.height();
			var heig = fin/2-4
			Benef.css({ 'margin-top': - heig }).show().addClass("opened_tooltip");
		}
	});
	$(document.body).click(function(e){
	    if (e.target)
	    {
	    	var $parents = $(e.target).parents(".ico-info");
	    	var Stack = $(".ico-info");
	    	if ($parents.length > 0)
	    	{
	    		Stack = Stack.not($parents[0]);
	    	}
	    	Stack.find(".die-benef").hide().removeClass("opened_tooltip");
	    }
	});
	$('.ico-info')



	// scroll
	$(".scroll").click(function(event){
		event.preventDefault();
		var full_url = this.href;
		var parts = full_url.split("#");
		var trgt = parts[1];
		var target_offset = $("#"+trgt).offset();
		var target_top = target_offset.top;
		$('html, body').animate({scrollTop:target_top}, 1000);
	});
		

	//sales polygon info blocks
	$('.sales-map .cloth td').hover(function(){
		$(this).children('.info').stop().fadeIn(200);
	},function(){
		$(this).children('.info').stop().fadeOut(200);
	});

	//filter items
	$('.filter-line .sec-head').click(function(e){
		e.preventDefault();
		if($(this).next('.form').hasClass('act')){
		  $(this).next('.form').removeClass('act').slideUp(200);
		  $('.filter-line .f-toggle').slideUp(200);
		} else {
		  $('.filter-line .form').removeClass('act').slideUp(200);
		  $(this).next('.form').addClass('act').slideDown(200);
		  $('.filter-line .f-toggle').slideDown(200);
		}
	})
	$(document).click( function(event){
		if( $(event.target).closest(".filter-line").length )
		  return;
		$(".filter-line .slide-pop").slideUp(200);
		event.stopPropagation();
	});

	//close popup
	$('.map-pop .close').click(function(){
		$(this).closest('.map-pop').fadeOut(200);
	});
	
	//map popups
	$('.map .mark').hover(function(){
		$(this).children('.price_range').fadeIn(200);
	},function(){
		$(this).children('.price_range').fadeOut(200);
	});
	$('.map .mark').click(function(e){
		e.preventDefault();
		 $(this).children('.price_range').fadeOut(5);
		 $(this).children('.info_pop').fadeIn(200);
	})
	$('.info_pop .close').click(function(e){
		$(this).closest('.info_pop').fadeOut(200);
		e.stopPropagation();
	});

	//show/hide map list
	var opts = $('.map_wrap .options');
	opts.css('right',-opts.width()+"px");
	var picker = opts.children('.picker');
	picker.css('right',(opts.width()+15)+"px");
	$('#show_map_list,.show_map_list').click(function(e){
		e.preventDefault();
		var opts = $('.map_wrap .options');
		var picker = opts.children('.picker');
		if(opts.hasClass('opened')){
		  opts.removeClass('opened');
		  picker.fadeOut(100);
		  opts.stop().animate({right:-opts.width()+"px"},250);
		  
		} else {
		  opts.addClass('opened');
		  opts.stop().animate({right:"0"},250);
		  picker.fadeIn(200);
		}
	});

	$('.all-propos.sm .dropdown ul li').click(function() {
		var number = $(this).index();
		switch(number) {
			case 0: $('.table-history').hide(); $('#table-rubles').show(); break;
			case 1: $('.table-history').hide(); $('#table-dollars').show(); break;
			case 2: $('.table-history').hide(); $('#table-euros').show(); break;
			default: ;
		}
	});

	//hide map list
	$('#hide_map_list').click(function(e){
		e.preventDefault();
		var opts = $('.map_wrap .options');
		var picker = opts.children('.picker');
		opts.removeClass('opened');
		picker.fadeOut(100);
		opts.stop().animate({right:-opts.width()+"px"},250);
	});
	/* /new script */
		
	//scroll block
	if ($('.floating').size() > 0) {
		var topPos = $('.floating').offset().top;
		$(window).scroll(function() {
			var top = $(document).scrollTop(),
			pip = $('.footer').offset().top,
			height = $('.floating').outerHeight();
			if (top > topPos && top < pip - height) {
				$('.floating').addClass('fixed').fadeIn();
			} else if (top > pip - height) {
				$('.floating').fadeOut(300);
			} else {
				$('.floating').removeClass('fixed');
			}
		});
	}
	
	var wheig = $(window).height();
	$('.list-search-answ ul').css({height:wheig-210});
	
	//sales polygon info blocks
	$('.cloth').hover(function(){
		$(this).children('.info').fadeIn(200);
	},function(){
		$(this).children('.info').fadeOut(200);
	});
	
	
	$(".mailme").mailme();


	$(document).ready( function(event){
		// footer scripts
		$("#tb1").tablesorter();
		$("#tb1 th:eq(0)").addClass('headerSortUp');
		$("#tb2").tablesorter();
		$("#tb2 th:eq(0)").addClass('headerSortUp');
		$("#tb3").tablesorter();
		$("#tb3 th:eq(0)").addClass('headerSortUp');
	});
	$(".personal-mobiles").inputmask("+7(999)999-99-99");


	$('#remove_object').click(function() {
		//console.log('tt');
		$('input[name=is_need_remove]').val("y");
		$('#btn_submit_advert').trigger('click');
	});
	$('body').on('click', '.dropdown ul li', function(){
		if ($(this).parents("#object-form").length > 0)
			return;
		var ID = $(this).parents('.sel-on').find('option[selected=selected]').val();
		var object = $(this).parents('.sel-on').find('select').attr('name');
		if (object && ID)
		{
			var lineSelect = $(this).parents('.line-select');
			var miniType;
			if(object == 'city') {
				miniType = 'district';
			} else if(object == 'district') {
				miniType = 'microdistrict';
			}

			//console.log(ID);
			$.ajax({
				url: '/local/templates/morealty/ajax_php/get-parent-object.php?object=' + object + '&ID=' + ID,
				success: function(data) {
					//console.log('success');

					var html = $('select[name=' + miniType + ']').html();
					$('select[name=' + miniType + ']').html('');
					$('select[name=' + miniType + ']').append(data);
					$('select[name=' + miniType + ']').parents('.sel-on').children('.selectbox:eq(0)').remove();
					$('.sel-pr select, .select-town select, .sel-on select, .sel-view select').selectbox();
				},
				error: function(data) {
					console.log('error');
				}
			});

			if(object == 'microdistrict') {
				var cityID = $('select[name=city]').children('option[selected=selected]');
				var districtID = $('select[name=district]').children('option[selected=selected]');
				var microdistrictID = $('select[name=microdistrict]').children('option[selected=selected]');
				$.ajax({
					url: '/local/templates/morealty/ajax_php/get-parent-object.php?city=' + cityID + '&district=' + districtID + '&microdistrict=' + microdistrictID,
					success: function(data) {
						//console.log(data);
					},
					error: function(data) {
						console.log(data);
					}
				});
			}
		}
		

	});

	$("#object-form").find("select[name='city'],select[name='district'],select[name='microdistrict']").bind("change",function(){
		var ID = $(this).val();
		if (typeof(ID) == "undefined" || ID == "all")
			return;
		var object = $(this).attr('name');
		var self = $(this);
		if (object && ID)
		{
			var lineSelect = $(this).parents('.line-select');
			var miniType;
			if(object == 'city') {
				miniType = 'district';
			} else if(object == 'district') {
				miniType = 'microdistrict';
			}
			
			//console.log(ID);
			$.ajax({
				url: '/local/templates/morealty/ajax_php/get-parent-object.php?object=' + object + '&ID=' + ID,
				success: function(data) {
					//console.log('success');

					var html = $('select[name=' + miniType + ']').html();
					$('select[name=' + miniType + ']').html('');
					$('select[name=' + miniType + ']').append(data);
					$('select[name=' + miniType + ']').trigger("refresh");
					self.trigger("waitingload");
					self.trigger("refresh");
					
					//$('select[name=' + miniType + ']').parents('.sel-on').children('.selectbox:eq(0)').remove();
					//$('.sel-pr select, .select-town select, .sel-on select, .sel-view select').selectbox();
				},
				error: function(data) {
					console.log('error');
				}
			});

			if(object == 'microdistrict') {
				var cityID = $('select[name=city]').children('option[selected=selected]');
				var districtID = $('select[name=district]').children('option[selected=selected]');
				var microdistrictID = $('select[name=microdistrict]').children('option[selected=selected]');
				$.ajax({
					url: '/local/templates/morealty/ajax_php/get-parent-object.php?city=' + cityID + '&district=' + districtID + '&microdistrict=' + microdistrictID,
					success: function(data) {
						self.trigger("refresh");
						//console.log(data);
					},
					error: function(data) {
						console.log(data);
					}
				});
			}
		}
	});
	
	$('.logout').click(function() {
		$this = $(this);
		if (!$(this).hasClass("wait")) {
			$.ajax({
				url: '/local/templates/morealty/ajax_php/logout.php',
				beforeSend : function(){$this.addClass("wait");},
				success: function(data) {
					window.location.reload();
					
				}
			});
			
		}
	});
	$(".click_me_at_start").trigger("click");
	
    $(".objects_val").keydown(function (event) {
        if (event.shiftKey) {
            event.preventDefault();
        }

        if (event.keyCode == 46 || event.keyCode == 8) {
        }
        else {
            if (event.keyCode < 95) {
                if (event.keyCode < 48 || event.keyCode > 57) {
                    event.preventDefault();
                }
            }
            else {
                if (event.keyCode < 96 || event.keyCode > 105) {
                    event.preventDefault();
                }
            }
        }
    });
    $('ul.news-images-gal').lightGallery({
		thumbnail:true,
		thumbWidth: 50,
		thumbContHeight: 70
	});
    
    $(".stabs_navs .stabs_nav").bind("click", function(){
    	var t = $(this);
    	var index = t.index();
    	var parentNav = t.parents(".stabs_navs");
    	var contentBlock = parentNav.siblings(".stabs_contents");
    	var targetContent = contentBlock.find(".stabs_content:eq("+index+")");
    	
    	
    	t.siblings().removeClass("active");
    	targetContent.siblings().removeClass("active");
    	t.addClass("active");
    	targetContent.addClass("active");
    	
    });
    
    $(".send_map_element a").bind("click", function(){
    	var t = $(this);
    	var link = t.attr("data-link");
    	if (typeof(link) != "undefined" && link.length > 0)
    	{
    		t.parents("form").attr("action", link).submit();
    	}
    });
    
    $("#top_search").bind("change input keyup", function(){
    	var t = $(this);
    	var val = t.val();
    	if (typeof(val) != "undefined" && val.length > 0)
    	{
    		$.get("/local/templates/morealty/ajax_php/search_items.php", [{name : "q", value : val}, {name : "ajax", value : "Y"}], function(data){
    			$(".search_elements").show();
    			$(".search_elements .searched_elements").html(data);
    		}, "HTML");
    	}
    	else
    	{
    		$(".search_elements").hide();
    	}
    });
    $(document).click(function(event) { 
        if(!$(event.target).closest('.menu_search').length) {
            $(".search_elements").hide();
        }
        else
        {
        	$(".search_elements").show();
        }
    });

    // Выбор города
    $(".select-main-city").change(function () {
    	let city = $(this).val();
		setCookie('city', city, '14');
		window.location = '/sell/flat/';
	});

});

function setCookie(key, value, expiry) {
	var expires = new Date();
	expires.setTime(expires.getTime() + (expiry * 24 * 60 * 60 * 1000));
	document.cookie = key + '=' + value + ';expires=' + expires.toUTCString() + '; path=/';
}