$(function () {

    $(".pay-step1 .bill").click(function () {
    	
    	var parent = $(this).parents(".pay-step1");
    	
        var sum = $.trim(parent.find(".summ").val()),
            _t = this;
        parent.find(".summ").removeClass('field-error');

        if (sum == '') {
        	 parent.find(".summ").addClass('field-error');
        } else {
        	
        	if(parent.find("#type-order").val() == "bnk")
    		{
                //$(_t).attr('href', $(this).data('href')).addClass('inline');
                //	$(".inline").colorbox({inline: true, closeButton: true, escKey: true});
        		$.colorbox({inline: true, closeButton: true, escKey: true, href : $(this).data('href')});

                getResultFromUrl('/local/components/morealty/personal.account/templates/in_personal/ajax/bill.php', {'sum': sum}, function (data) {
                    try {
                        eval('var d = ' + data + ';');

                        if (d.status == 'OK') {
                            $('#pop2 .price-pop span').html(d.price);
                            $('#pop2 .score-pop span').html(d.link);
                        } else {
                            $('#pop2 .price-pop, #pop2 .history-score').html('');
                            $('#pop2 .score-pop').html(d.html);
                        }
                    } catch (i) {
                    }
                });
    		}
        	else 
        	{
        		//console.log(parent.find("#type-order").val());
        		
        		$("#call-pay-online").attr("data-sum",sum).trigger("click");
              
        	}
        	

        }
    });
	
    $(".pay-step1 .summ").checkPhone();

});