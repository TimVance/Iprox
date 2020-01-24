/**
 * morealty common scripts
 * @author wexpert.ru
 */
$(function() {

	//colorbox
	if ($.colorbox) {
		$(".inline").colorbox({inline: true, closeButton: true,escKey: true});
		$('.close').click(function(){$('#cboxOverlay').click();});
		$(".group1").colorbox({closeButton: true});
		$('body').on('click', '.group1', function () {setTimeout(function() { $('#colorbox').addClass('active');}, 100)});
		$('body').on('click', '#cboxOverlay, #cboxClose', function () {$('#colorbox').removeClass('active');});
	}

	$('.add-ask a').click(function() {
		$('#form-question-wrap').toggle(400);
	});
	$('.reply-btn').click(function() {
		$(this).parent('.bl-ask').children('.reply-form').toggle(400);
	});
	
	$(this).keydown(function(eventObject){
		if (eventObject.which == 27)
			$('body').removeClass('act');
		$('.wr-pop-inp').hide();
	});
	
	//favorites: add / delete
	$('#btn-favor').click(function() {
		var itemValue = $('#item-id').val();
		if($('#is_authorized').val() == 'y') {
			if(!$(this).hasClass('active')) {
				$.ajax({
					url: '/local/templates/morealty/ajax_php/add-favorites.php?ID=' + itemValue,
					success: function(data) {
					}
				});
				$(this).addClass('active');
			}
			else {
				$.ajax({
					url: '/local/templates/morealty/ajax_php/delete-favorites.php?ID=' + itemValue,
					success: function(data) {
					}
				});
				$(this).removeClass('active');
			}
		} else {
			$('a[href="#pop12"]').trigger('click');
		}

	});
	$('.but-favor').click(function() {
		var itemValue = $(this).parent('.desc-agents').parent('.item-agents').children('#item-id').val();
		if($('#is_authorized').val() == 'y') {
			if(!$(this).hasClass('active')) {
				$.ajax({
					url: '/local/templates/morealty/ajax_php/add-favorites.php?ID=' + itemValue,
					success: function(data) {
					}
				});
				$(this).addClass('active');
			}
			else {
				$.ajax({
					url: '/local/templates/morealty/ajax_php/delete-favorites.php?ID=' + itemValue,
					success: function(data) {
					}
				});
				$(this).removeClass('active');
			}
		} else {
			$('a[href="#pop12"]').trigger('click');
		}
	});
	//set sort_order
	$('.catalog-sort ul li span').click(function() {
		var val = $(this).parent('li').attr('value');
		var currentVal = $('input[name=SORT_BY]').val();
		$('input[name=SORT_BY]').attr('value', val);
		if(val == currentVal) {
			var currentSortOrder = $('input[name=SORT_ORDER]').val();
			$('input[name=SORT_ORDER]').attr('value', (currentSortOrder == 'ASC') ? 'DESC' : 'ASC');
		}
		$('#submit').trigger('click');
	});
	//set pick
	$('.pick').click(function() {
		var propertyValue;
			if($(this).parent('.form').children('.field').children('input[name=from]').val() == undefined) {
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

	//require fields
	$('input[name=save]').click(function(e) {
		var requiredFields = $('.req-star');
		var currentInput;
		console.log(requiredFields.length);
		for(var i = 0; i < requiredFields.length; i++) {
			currentInput = $(requiredFields[i]).parent('label').parent('.field-pass').children('input');
			if($.trim($(currentInput).val()) == '') {
				$(currentInput).addClass('error');
				e.preventDefault();
			} else {
				if($(currentInput).hasClass('error')) {
					$(currentInput).removeClass('error');
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
		for(var i = 0; i < reqFields.length; i++) {

			if( ($(reqFields[i]).attr('type') == 'text') || ($(reqFields[i]).attr('type') == 'password')) {
				if(($(reqFields[i]).val() == '')) {
					$(reqFields[i]).addClass('error');
					isEmpty = true;
				} else {
					if($(reqFields[i]).hasClass('error')) {
						$(reqFields[i]).removeClass('error');
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
	
	//slider projects
	$('.slider1, .slider2, .slider3, .slider4, .slider5').bxSlider({
		minSlides: 1,
		slideWidth: 237,
		maxSlides: 4,
		moveSlides: 1,
		slideMargin: 17,
		pager: false
	});

	//последние объекты на главной
	var bxViewportStyles = $('.slider1').parents('.bx-viewport').attr('style');
	var slideStyles = $('.slider1').children('.slide:eq(0)').attr('style');
	$('.last-objects .head-object ul li a').click(function() {
		$('.head-object li').removeClass('active');
		$(this).parent('li').addClass('active');
		$('.slier-objects').hide();
		$('.slier-objects.visible').removeClass('visible');
		var sliderClass = $(this).attr('data-slider');


		$('.' + sliderClass).parents('.bx-viewport').attr('style', bxViewportStyles);
		$('.' + sliderClass).children('.slide').attr('style', slideStyles);

		$('.' + sliderClass).parent('.slier-objects').addClass('visible');
		$('.' + sliderClass).parents('.bx-wrapper').parent('.slier-objects').fadeIn('slow');
	});

	$('.slider-agent').bxSlider({
		minSlides: 1,
		slideWidth: 324,
		maxSlides: 1,
		moveSlides: 1,
		slideMargin: 0,
		pager: false
	});

	$('.slider-big1').bxSlider({
		pagerCustom: '#bx-pager'
	});

	$('.slider-big2').bxSlider({
		pagerCustom: '#bx-pager2'
	});

	$('.slider-big3').bxSlider({
		pagerCustom: '#bx-pager3'
	});

	//select
	$('.sel-pr select, .select-town select, .sel-on select, .sel-view select').selectbox();

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
		if(!$(this).hasClass('lich-obj')) {
			$(this).find('.tab-tb').hide();
			$(this).find('.tab-tb.active').show();
			$(this).find('.tab-tb:eq(0)').show();
		}
		//$(this).find('.nav-tb li:eq(0)').addClass('active');
		$(this).find('.nav-tb li').click(function () {
			var id  = $(this).attr('id');
			if(id == 'owner') {
				id = "8";
			}
			else if(id == 'realtor') {
				id = "7";
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
	
	$('.tit-accord').click(function() {$(this).next('.text-accrod').toggle()});
	
	$('.view-phone a').click(function() {
		var ID = $('#realtor_id').val();
		var currentBlock = $(this);
		$.ajax({
			url: '/local/templates/morealty/ajax_php/get-user-phone-number.php?ID=' + ID,
			success: function(data) {
				$('.view-phone').find('span').text(data);
				$(currentBlock).hide();
				$(currentBlock).parents('.view-phone').find('.phone-v').show();
			}
		});
	});
	
	$('.view-rielt-phone a').click(function() {
		var ID = $('#realtor_id').val();
		var currentBlock = $(this);
		$.ajax({
			url: '/local/templates/morealty/ajax_php/get-user-phone-number.php?ID=' + ID,
			success: function(data) {
				$('.view-rielt-phone').children('span').text(data);
				$(currentBlock).hide();
				$(currentBlock).next('span').show();
			}
		});
		return false;
	});
	
	//die benef
	$('.ico-info').hover(function() {
		var fin = $(this).find('.die-benef').height();
		var heig = fin/2-4
		$(this).find('.die-benef').css({ 'margin-top': - heig }).show();
	}, function() {
		$(this).find('.die-benef').hide();
	});



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
	$('#show_map_list').click(function(e){
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



}); //\document.ready
