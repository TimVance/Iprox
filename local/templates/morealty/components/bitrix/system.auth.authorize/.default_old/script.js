$(document).ready(function(){
	$(".custom-check").change(function(){
		var $State = $(this).is(":checked");
		var $ParentBlock = $(this).parents(".check-wrap");
		if($State)
		{
			$ParentBlock.addClass("checked");
		}
		else 
		{
			$ParentBlock.removeClass("checked");
		}
	})
});