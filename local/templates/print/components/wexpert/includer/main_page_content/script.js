$(function() {
	$('.change-estate_type').click(function() {
		$('input[name=estate_type]').attr('value', $(this).attr('data-link'));
		$('input[name=iblock_id]').attr('value', $(this).attr('data-id'));
		if($(this).attr('data-id') != '7') {
			$(this).parents('.tabs-room').find('.radio-rooms input').attr('disabled', 'disabled');
			$(this).parents('.tabs-room').find('.radio-rooms .ez-radio.ez-selected').removeClass('ez-selected');
			$(this).parents('.tabs-room').find('.radio-rooms').css('opacity', '0.45');
		} else {
			$(this).parents('.tabs-room').find('.radio-rooms input').removeAttr('disabled');
			$(this).parents('.tabs-room').find('.radio-rooms .ez-radio:eq(0)').addClass('ez-selected');
			$(this).parents('.tabs-room').find('.radio-rooms').css('opacity', '1');
		}
		//$('#find_estate').attr('action', $(this).attr('data-link'));
	});
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
		$('#find_estate').attr('action', '/' + $('input[name=buy_or_arend]').val() + $('input[name=estate_type]').val());

		//если не квартиры
		if($('input[name=iblock_id]').val() != '7') {
			$('input[name=rooms_number]').attr('value', '');
		}
		//e.preventDefault();
	});

	
	$('.more-params a').click(function() {
		$('.add-options-forms').toggle(400);
	});
}); //\document.ready
