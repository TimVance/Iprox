$(function(){
	$("#sort").bind("change", function(e){
		var t = $(this);
		
		location.href = changeUrlParams(location.href, [{key :"sort", value : t.val()}]);
	});
});