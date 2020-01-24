$(document).ready(function(){
	
	$(".plans-selector ul li a").bind("click",function(){
		
		var $Target = $(this).attr('data-target');
		var $Parent = $(this).parent("li");
		if (typeof($Target) != "undefined")
		{
			$(".sales-map").addClass("innactive");
			$("#"+$Target).removeClass("innactive");
			$(this).addClass("active");
			$Parent.siblings("li").find("a").removeClass("active");
			
		}
		
	});
	$(".plans-selector ul li:eq(0) a").trigger("click");
	
});