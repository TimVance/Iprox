$(document).ready(function(){
	
	$('[name="regform"]').bind("submit",function(){
		yaCounter47701606.reachGoal("register");
		ga("send", "event" , "forms" , "submit" , "register");
	});
	//
	$("[name='REGISTER[PERSONAL_MOBILE]']").inputmask("+9(999)999-99-99");
	$(".agents-selectbox").selectbox();
	$(".agents-selectbox option").each(function(){
		var t = $(this);
		var image = t.attr("data-image");
		if (image && typeof(image) != "undefined")
		{
			var index = t.index();
			var selectElement = t.parent("select");
			var list = selectElement.parent(".sel-on").find(".dropdown ul");
			var visible = list.find("li:eq('"+index+"')");
			var text = visible.text();
			visible.addClass("agency_dropdown_element").html('<img src="'+image+'"/><span>'+text+'</span>');
		}
		
	});
})