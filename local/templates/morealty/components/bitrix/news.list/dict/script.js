jQuery(document).ready(function(){
	$(".alphabet a").click(function(e){
		last = $(this);
		link = last.attr("href");
		$(this).attr("href","#pop6");
		e.preventDefault();
		$.ajax({
			method: "GET",
			url: link,
			dataType: "html"
		}).done(function(data){
			$('#pop6').html(data);
			$.colorbox.resize();
			last.attr("href",link);
			$(".close").click(function(){
				$.colorbox.close();
			});
			jQuery(document).bind('keydown', function (e) {
		        if (e.keyCode === 27) {
		        	$(".close").click();
		        }
			});
		}).fail(function(e){
			console.log(e);
		});
		
	});
	
});
