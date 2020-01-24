$(document).ready(function(){
	window.BuyState = false;
	
	$(".objects_val").bind("keyup",function(){
		
		var Val = $(this).val();
		var Ele = $(this).attr("data-ele");
		var $TisShow = $(this).parents(".pop3").find(".big-price");
    	$.ajax({
    		async: true,
    		cache: false,
    		dataType: 'json',
    		data: {prod_id: Ele,qant: Val},
    		type: 'POST',
    		url: '/local/templates/morealty/ajax_php/getPrice.php',
    		error: function (data) {
    			console.log(data);
    		},
    		success: function(data)
    		{
    			if (typeof(data.status) != "undefined")
				{
        			if (data.status != "error")
        			{
        				$TisShow.text(data.price);
        			}
				}

    		},
    		});
	});
	
    $(".goodbuy").click(function(){
    	if (window.BuyState)
		{
    		return false;
		}
        var Tis = this,
         $Tis = $(this),
            gid = $(Tis).data('package');
        var ParentBlock = $(this).parents(".pop");
        var quant = ParentBlock.find(".objects_val").val();
    	$.ajax({
    		async: true,
    		cache: false,
    		data: {prod_id: gid,quant : quant},
    		dataType: 'json',
    		/*timeout: 8000,*/
    		type: 'POST',
    		url: '/local/components/morealty/personal.account/add_to_basket.php',
    		beforeSend : function(){
    			window.BuyState = true;
    			ParentBlock.find('.property-pop').html("<strong>Создание оплаты</strong>");
    			$Tis.hide();
    		},
    		error: function (data) {
    			alert("Ошибка при создании оплаты");
    			$Tis.show();
    		},
    		success: function(data)
    		{
    			if(typeof(data.response) != "undefined")
    			{
    				ParentBlock.find('.property-pop').html(data.response);
    			}
    			if(typeof(data.status) != "undefined" && data.status != "error")
    				{
	    				if (data.status == "need-balance")
						{
	    					window.BuyState = false;
	    	    			$Tis.show();
						}
	    				else
	    				{
	            	    	$.ajax({
	            	    		async: true,
	            	    		cache: false,
	            	    		dataType: 'json',
	            	    		type: 'POST',
	            	    		url: '/local/components/morealty/personal.account/try_order.php',
	            	    		error: function (data) {
	            	    			alert("Ошибка при создании оплаты");
	            	    		},
	            	    		success: function(data)
	            	    		{
	            	    			if(typeof(data.response) != "undefined")
	            	    			{
	            	    				ParentBlock.find('.property-pop').html(data.response);
	            	    			}
	            	    		},
	            	    		complete : function(data)
	            	    		{
	            	    			window.BuyState = false;
	            	    			$Tis.show();
	            	    		}});
	    				}

    				}
    			else 
    			{
    				window.BuyState = false;
    				ParentBlock.find('.property-pop').html(data.response);
    				
    			}
    		},
    		
    	});
        
    });
});