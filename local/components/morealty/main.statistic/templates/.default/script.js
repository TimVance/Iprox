$(document).ready(function(){
	$('.inp-rub').click(function() {
		$('.currency-wrap').hide();
		$('.currency-rub').show();
	});
	$('.inp-usd').click(function() {
		$('.currency-wrap').hide();
		$('.currency-usd').show();
	});
	$('.inp-eur').click(function() {
		$('.currency-wrap').hide();
		$('.currency-eur').show();
	});
});