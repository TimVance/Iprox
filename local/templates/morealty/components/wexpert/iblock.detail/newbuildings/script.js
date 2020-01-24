$(document).ready(function() {
	function prebindShowDetailText()
	{
		$(".show_detail_text").each(function(){
			var t = $(this);
			var targetR = t.parent(".show_detail_text_wrapper").siblings(".detail_text");
			if (targetR.isElementOverflowed())
			{
				t.parent(".show_detail_text_wrapper").show();
				var text = t.attr("data-off");
				if (text && typeof(text) != "undefined")
				{
					t.text(text);
					t.unbind(".ClickHide").bind("click.ClickHide", function(){
						var tis = $(this);
						var target = tis.parent(".show_detail_text_wrapper").siblings(".detail_text");
						var text = (target.is(".text-opened"))? tis.attr("data-off") : tis.attr("data-on");
						target.toggleClass("text-opened");
						if (text && typeof(text) != "undefined")
							tis.text(text);
					});
				}
			}
			else
			{
				t.parent(".show_detail_text_wrapper").hide();
			}
			
		});
	}
	$(window).resize(function(){
		prebindShowDetailText();
	})
	prebindShowDetailText();
	
	
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
	
	$(".thumb-img.only-three-rows .full-screener").bind("click",function(){
		
		var Iter = $(this).attr("data-slide-index");
		
		target = $(".slider-big1 li:not(.bx-clone)").eq(Iter).trigger("click");
		
	});
	/*
	 * 
	 * раскоментить если им всетаки нужно чтобы не показывались пустые таблицы.
	 * $.each($(".newbuilding-apartamets .newbuild-ap-type-selector li"),function(i,v){
		var $TisIndex = $(this).index();
		var hideMe = $(".newbuilding-apartamets .apartaments-block").eq($TisIndex).find(".hide-me").length > 0;
		if(hideMe)
		{
			$(this).hide();
		}
	})*/
	

	$(".newbuild-ap-type-selector li").click(function(){
		
		$tisIndex = $(this).index();
		$TargetBlock = $(this).parents(".newbuilding-apartamets").find(".apartaments-block").hide().eq($tisIndex).show();
		$(".b-propos.b-propos2").removeClass("b-propos2").addClass("b-propos2");
		
	});
	//$(".newbuild-ap-type-selector li").eq(0).click();
	
	
	$(".newbuild-ap-type-selector li:visible").eq(0).trigger("click");
	if ($(".only-three-rows").isChildOverflowing("a"))
	{
		$(".toogle-thumbs-row").removeClass("temp-hidden");
	}
	$(".toogle-thumbs-row").bind("click",function(){
		var target = $(".only-three-rows");
		target.toggleClass("visible");
		$(this).find("p a").toggleText();
	});
	
	$(".trigger_click_start_first[data-slide-index='0']").trigger("click");
	
	$(".reload_video_slider").bind("click",function(){
		setTimeout(function(){
			if (window.newbuilding_slider)
			{
				window.newbuilding_slider.reloadSlider();
			}
		},200);
	});
});

$(window).resize(function(){
	if ($(".only-three-rows").isChildOverflowing("a"))
	{
		$(".toogle-thumbs-row").removeClass("temp-hidden");
	}
	else
	{
		$(".toogle-thumbs-row").addClass("temp-hidden");
	}
})