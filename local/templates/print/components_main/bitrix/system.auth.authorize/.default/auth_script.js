$(document).ready(function(){
	
	$('body').on('click', '#cboxOverlay, #cboxClose', function () {$('#colorbox').removeClass('active');$('body').removeClass('act');$('.wr-pop-inp').hide();})
	
	
	$(".inline").colorbox({inline: true, closeButton: false, escKey: true});

});