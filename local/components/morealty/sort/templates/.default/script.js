$(document).ready(function(){
	//submit-form
	$(".sort-block .sort-item").bind("click.SORT",function(){
		
		var $PropId = $(this).attr("data-prop");
		if(typeof($PropId) != "undefined")
		{
			if($PropId.length <= 0) return false;
			
			var $Form = $(this).parents(".sort-block").find("form.submit-form");
			$Form.find("input[name='SORT_BY']").val($PropId);
			//if()
			var $CurDirection = $(this).attr("data-direction");
			if(typeof($CurDirection) !="undefined")
			{
				if($CurDirection.length > 0)
				{
					$nextDirection = ($CurDirection.toLowerCase() == "asc")? "DESC": "ASC";
					$Form.find("input[name='SORT_DIRECTION']").val($nextDirection);
				}
			}
			else 
			{
				$Form.find("input[name='SORT_DIRECTION']").attr("name","");
			}
			
			$Form.submit();
			
		}
		
	});
});