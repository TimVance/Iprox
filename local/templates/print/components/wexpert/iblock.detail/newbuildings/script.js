$(document).ready(function() {
	$('ul.slider-big1').lightGallery({
		thumbnail:true,
		thumbWidth: 50,
		thumbContHeight: 70
	});
	
	$(".showmap").bind("click.NewBuildings",function(){
		var $Show = true;
		if ($(this).hasClass("active")) {
			$Show = false;
		}
		if($Show) {
			$(this).addClass("active");
			$(".tab-big.tab-all").hide();
			$(".map-state").show();
			$(".card-gal .nav-gal,.card-gal .cont-thumbs").hide();
		}
		else {
			$(this).removeClass("active");
			$(".tab-big.tab-all:eq(0)").show();
			$(".map-state").hide();
			$(".card-gal .nav-gal,.card-gal .cont-thumbs").show();
		}
		
	});
	
	$.each($(".newbuilding-apartamets .newbuild-ap-type-selector li"),function(i,v){
		var $TisIndex = $(this).index();
		var hideMe = $(".newbuilding-apartamets .apartaments-block").eq($TisIndex).find(".hide-me").length > 0;
		console.log($(".newbuilding-apartamets .apartaments-block").eq($TisIndex).find(".hide-me"));
		if(hideMe)
		{
			$(this).hide();
		}
	})
	
	$(".newbuild-ap-type-selector li").click(function(){
		
		$tisIndex = $(this).index();
		$TargetBlock = $(this).parents(".newbuilding-apartamets").find(".apartaments-block").hide().eq($tisIndex).show();
		$(".b-propos.b-propos2").removeClass("b-propos2").addClass("b-propos2");
		
	});
	$(".newbuild-ap-type-selector li").eq(0).click();
});
