$(document).ready(function(){

		
		//agents-ajax-change
		
		$(".agents-ajax-change a").on("click.ChangePage",function(e){
			
			$(".agents-ajax-change a").removeClass("active");
			$(this).addClass("active");
			var Link  = $(this).attr("href");
			if (Link.search("\\?") != "-1")
			{
				Link+="&ajax=Y";
			}
			else {
				Link+="?ajax=Y";
			}
			$.ajax({
				url : Link,
				beforeSend : function()
				{
					$("#block-agents").html("");
				},
				success: function(data){
					if(typeof(data) != "undefined")
					{
						$("#block-agents").html(data);
						window.initMyObjectsSlider();
					}
				}
			});
			return false;
		});
});
