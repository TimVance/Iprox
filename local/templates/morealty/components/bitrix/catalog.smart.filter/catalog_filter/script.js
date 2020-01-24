$(document).ready(function(){
	$(".map-filter .search-element").bind("click",function(){
		$(this).parents("form").find("input[type='submit']").click();
	});
	$(".show-type-selector").bind("click",function(){
		var $Target = $(".types-list");
		if ($Target.is(".active"))
		{
			$Target.fadeOut("slow",function(){
				$(this).removeClass("active");
			});
		}
		else
		{
			$Target.fadeIn("slow",function(){
				$(this).addClass("active");
			});
		}
	})
});