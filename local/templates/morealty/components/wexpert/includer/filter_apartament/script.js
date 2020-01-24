$(document).ready(function(){
	$(".aprtaments-filter .filter-cancel").click(function(){
		
		$tisForm = $(this).parents("form.aprtaments-filter");
		$tisForm.find("input[type='number'],input[type='text']").val("").trigger("change");
		$tisForm.find("select").each(function(){
			var t = $(this);
			t.prop('selectedIndex',0);
			t.trigger("change").trigger("refresh");
		});
		$tisForm.find(".pick").eq(0).trigger("click");
		
	});
	$(".aprtaments-filter .pick").click(function(){
		
		var $tisForm = $(this).parents("form.aprtaments-filter");
		if($tisForm.is(".pending"))
			return false;
		
		var $FormData	= $tisForm.find("select, :input:not(select), input:not(select),textarea").serializeArray();
		//var $FormData = $tisForm.serializeArray();
		var $TargetBlock = $tisForm.attr("data-target-block");
		$isBuilders = ($tisForm.attr("data-builders") == "Y")? "Y" : "N";
		$FormData.push({name : "builders",value : $isBuilders},{name : "parents",value : $tisForm.attr("data-building")},{name: "ajax", value : "Y"});
		
		$.ajax({
			data : $FormData,
			url : "/local/templates/morealty/ajax_php/apartaments.php",
			beforeSend : function()
			{
				$tisForm.addClass("pending");
			},
			success: function(data){
				if(typeof(data) != "undefined")
				{
					$($TargetBlock).find("table").html(data);
					$(document).ready( function(event){
						// footer scripts
						$("#tb1").tablesorter();
						$("#tb1 th:eq(0)").addClass('headerSortUp');
						$("#tb2").tablesorter();
						$("#tb2 th:eq(0)").addClass('headerSortUp');
						$("#tb3").tablesorter();
						$("#tb3 th:eq(0)").addClass('headerSortUp');
					});
				}
			},
			complete: function(){
				$tisForm.removeClass("pending");
			}
		});
	})
	
});