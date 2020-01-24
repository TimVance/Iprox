$(function() {
	//$(this).parents('.tabs-room').find('.check-rooms').addClass("not-delimiter");
	$("input[name='price_from-temp'],input[name='price_to-temp'],input[name='square_from-temp'],input[name='square_to-temp']").onlyNum();
	$('.change-estate_type').click(function() {
		$('input[name=estate_type]').attr('value', $(this).attr('data-link'));
		$('input[name=iblock_id]').attr('value', $(this).attr('data-id'));
		var TabsRooms = $(this).parents(".tabs-room");
		TabsRooms.removeClass("uchastok_form");
		var iblock_id = $(this).attr('data-id');
		if (iblock_id == "12")
		{
			TabsRooms.find(".commerce_type").show();
		}
		else
		{
			TabsRooms.find(".commerce_type").hide();
		}
		if($(this).attr('data-id') != '7') {
			var ParentBlock = $(this).parents('.tabs-room');
			
			ParentBlock.find('.choice-rooms, .choice-top-form').removeClass("apartaments_case");
			$(this).parents('.tabs-room').find('.radio-rooms input').attr('disabled', 'disabled');
			$(this).parents('.tabs-room').find('.radio-rooms .ez-radio.ez-selected').removeClass('ez-selected');
			//$(this).parents('.tabs-room').find('.radio-rooms').css('opacity', '0.45');
			$(this).parents('.tabs-room').find('.radio-rooms').hide();
			$(this).parents('.tabs-room').find('.check-rooms').addClass("not-delimiter");
			//not-delimiter
			
			
			var Link = "/sell/map";
			var Addition = $(this).attr('data-link');
			if (Addition)
			{
				Link += Addition;
			}
			TabsRooms.find(".bot-forms .view-map a").attr("data-link", Link);
			//$(this).parents('.tabs-room').find('.view-map').addClass("innactive").css('opacity', '0.45');
			$(this).parents(".tabs-room").find(".bot-forms .more-params").fadeIn("slow");
			
			if($(this).attr('data-id') == "8" || $(this).attr("data-id") == "10")
			{
				var BlockRooms = $(this).parents(".tabs-room").find(".check-rooms ul");
				BlockRooms.find("li").hide();
				BlockRooms.find("#check-rooms3").parents("li").show();
			}
			else if($(this).attr("data-id") == "12")
			{
				var BlockRooms = $(this).parents(".tabs-room").find(".check-rooms ul");
				BlockRooms.find("li").hide();
				BlockRooms.find("#check-rooms4,#check-rooms5").parents("li").show();
			}
			/*else if ($(this).attr("data-id") == "10")
			{
				var BlockRooms = $(this).parents(".tabs-room").find(".check-rooms ul");
				BlockRooms.find("li").hide();
				BlockRooms.find("#check-rooms3").parents("li").show();
			}*/
			if ($(this).attr("data-id") == "10" || $(this).attr("data-id") == "8")
			{
				TabsRooms.addClass("uchastok_form");
				var BlockRooms = $(this).parents(".tabs-room").find(".check-rooms ul");
				BlockRooms.find("li").hide();
			}
			
		}
		else {
			var BlockRooms = $(this).parents(".tabs-room").find(".check-rooms ul");
			BlockRooms.find("li").hide();
			BlockRooms.find("#check-rooms1,#check-rooms2").parents("li").show();
			var ParentBlock = $(this).parents('.tabs-room');
			ParentBlock.find('.radio-rooms input').removeAttr('disabled');
			ParentBlock.find('.radio-rooms .ez-radio:eq(0)').addClass('ez-selected');
			ParentBlock.find('.radio-rooms').show();
			ParentBlock.find('.check-rooms').hide().removeClass("not-delimiter");
			
			
			ParentBlock.find('.choice-rooms, .choice-top-form:not(.choice-top-form2)').addClass("apartaments_case");
			//
			//$(this).parents('.tabs-room').find('.radio-rooms').css('opacity', '1');
			
			
			//$(this).parents('.tabs-room').find('.view-map').removeClass("innactive").css('opacity', '1');
			
			
			var Link = "/sell/map/";
			TabsRooms.find(".bot-forms .view-map a").attr("data-link", Link);
			
			if($(this).parents(".tabs-room").find(".bot-forms .more-params a").is(".activited"))
			{
				$(this).parents(".tabs-room").find(".bot-forms .more-params a").trigger("click");
			}
			$(this).parents(".tabs-room").find(".bot-forms .more-params").show();
		}
		if ($(".check-rooms ul li:visible").length == 0)
		{
			$(this).parents('.tabs-room').find('.check-rooms').addClass("not-delimiter");
		}
		//$('#find_estate').attr('action', $(this).attr('data-link'));
	});
	/*$(".buy-or-arend[data-type='arend']").click(function(){
		$(".cont-func").find('.tabs-room .check-rooms').addClass("not-delimiter");
	})*/
	$('.defaultP label').click(function() {
		$('input[name=rooms_number]').attr('value', $(this).attr('data-rooms'));
	});
	$('.buy-or-arend').click(function() {
		$('input[name=buy_or_arend]').attr('value', $(this).attr('data-type'));
	});
	$('#find-estate-submit').click(function() {
		$('#find_estate-submit-final').trigger('click');
	});
	//форма поиска на главной
	$('#find_estate').submit(function(e) {
		$('input[name=price_from]').attr('value', $('input[name=price_from-temp]').val());
		$('input[name=price_to]').attr('value', $('input[name=price_to-temp]').val());
		$('input[name=square_from]').attr('value', $('input[name=square_from-temp]').val());
		$('input[name=square_to]').attr('value', $('input[name=square_to-temp]').val());
		$('input[name=currency]').attr('value', $('.sel-pr select option:selected').val());
		$('input[name=buy_type1]').attr('value', $('label.lbl-check-rooms1.active').attr('data-val'));
		$('input[name=buy_type2]').attr('value', $('label.lbl-check-rooms2.active').attr('data-val'));
		$('input[name=buy_type3]').attr('value', $('label.lbl-check-rooms3.active').attr('data-val'));
		
		$('input[name=is_shop]').attr('value', $('label.lbl-check-rooms4.active').attr('data-val'));
		$('input[name=is_office]').attr('value', $('label.lbl-check-rooms5.active').attr('data-val'));
		$('input[name=commerce_info]').attr('value',$("select[name='commerce_type']").val());
		//commerce_info
		$('#find_estate').attr('action', '/' + $('input[name=buy_or_arend]').val() + $('input[name=estate_type]').val());

		//если не квартиры
		if($('input[name=iblock_id]').val() != '7') {
			$('input[name=rooms_number]').attr('value', '');
		}
		//e.preventDefault();
	});

	$(".cont-func .view-map:not(.send_map_element) a").bind("click.Filter",function(e){
		var $ThisParent = $(this).parents(".view-map");
		if($ThisParent.is(".innactive"))
			return false;
		var $Form = $("#find_estate");
		$Form.find("[name='iblock_id'],[name='estate_type']").attr("name","");
		var $FormData = $Form.serialize();
		var $LinkPrefix = (typeof($(this).attr("data-link")) != "undefined")? $(this).attr("data-link"): "";
		$newLink = $LinkPrefix+"?"+$FormData;
		window.location.href = $newLink;
	});
	$('.more-params a').click(function() {
		if(!$(this).is(".activited"))
		{
			$(this).addClass("activited");
		}
		else
		{
			$(this).removeClass("activited");
		}
		
		$('.add-options-forms').toggle(400);
	});
	$(".tabs-room .nav-rooms ul li").eq(0).find("a").trigger("click");
	
	$(".try_to_get_arend button").bind("click",function(){
		$(".error_arend_class").fadeIn(300);
	});
	
	$(".main_page_filter .more-params a").unbind("click").bind("click",function(){
		var t = $(this);
		t.parents(".main_page_filter").find(".add-options-forms").slideToggle("300");
	});
});
