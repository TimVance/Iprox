$(function() {
    $('select[name=newbuilding]').change(function() {
    	
    	
    	
        var newbuildingID = $(this).find("option:checked").val();
        console.log(newbuildingID);
        if(newbuildingID == "0" || typeof(newbuildingID) == "undefined")
    	{
        	var Selected = $(".newbuild_line .dropdown ul li.selected");
        	var Text = Selected.text();
        	
        	var ISElement = $('select[name=newbuilding] option:contains("'+Text+'")');
        	if (ISElement.length > 0)
        	{
        		newbuildingID = ISElement.val();
        	}
        	if (newbuildingID == "0" || typeof(newbuildingID) == "undefined")
        	{
            	$('.new-builds').html("");
            	return false;
        	}

    	}
        $.ajax({
            url: '/local/templates/morealty/ajax_php/get-newbuilding.php?ID=' + newbuildingID,
            success: function(data) {
            	
                //заменяем html новостройки на запрошенный
                $('.new-builds').html(data);
                //нужно заново обработать слайдер
                $('.slider-agent').bxSlider({ minSlides: 1, slideWidth: 324, maxSlides: 1, moveSlides: 1, slideMargin: 0, pager: false});
                
                var City = $("[name='hidden_city']").val(),
                Region = $("[name='hidden_district']").val(),
                MicroDistrict = $("[name='hidden_microdistrict']").val(),
                Street = $("[name='hidden_street']").val(),
                Floors = $("[name='hidden_floors']").val();
                //$('[name="city"]').val("").trigger("refresh").trigger("change");
                //$('[name="district"]').val("").trigger("refresh").trigger("change");
                //$('[name="microdistrict"]').val("").trigger("refresh").trigger("change");
                if (City)
                {
                	$('[name="city"]').val(City).trigger("refresh").trigger("change");
                    setTimeout(function(){
                        if (Region)
                        {
                        	$('[name="district"]').val(Region).trigger("refresh").trigger("change");
                            setTimeout(function(){
                                if (MicroDistrict)
                                {
                                	$('[name="microdistrict"]').val(MicroDistrict).trigger("refresh").trigger("change");
                                }
                            },700);
                        }
                    },700);
                }





                if (Street)
                {
                	$('[name="street"]').val(Street).trigger("change");
                }
                if (Floors)
                {
                	$('[name="floors"]').val(Floors).trigger("change");
                }
                //Floors
            }
        });
    });
    if (typeof(window.InputedValues) != "undefied")
	{
    	var $ParentBlock = $("#object-form");
    	$ParentBlock.find(":checkbox").prop("checked",false);
    	$.each(window.InputedValues,function(i,v){
    		var TisEle = $ParentBlock.find(":input[name='"+i+"']");
			if(i == "currency")
			{
				
				switch (v)
				{
					case "RUB":
					{
						v = "Рубли";
						break;
					}
					case "USD":
					{
						v = "Доллары";
						break;
					}
					case "EUR":
					{
						v = "Евро";
						break;
					}
					default:
					{
						//v = "Рубли";
						break;
					}
					
				}
			}
    		if(TisEle.is("[type='text'],textarea,select"))
			{
    			
    			TisEle.val(v).trigger("change");;
    			
			}
    		else if (TisEle.is("[type='radio']"))
			{
    			
    			TisEle.each(function(iinner,vinner){
    				if (v == vinner.value)
					{
    					
    					$(this).prop("checked",true);
					}
    				else {
    					$(this).prop("checked",false);
    				}
    				$(this).trigger("change");
    				
    			});
    			
			}
    		else if (TisEle.is(":checkbox"))
    		{
    			
    			TisEle.prop("checked",true).trigger("change");
    		}
    	});
	}
    
    
    $(".newbuilding_filter").bind("input change keydown keyup",function(){
    	var t = $(this);
    	var val = t.val();
    	if (val && val.length > 0)
    	{
    		FilterNewbuildSelector(val);
    	}
    	else
    	{
    		FilterNewbuildSelector(false);
    	}
    });
    
    setTimeout(function(){
    	$(".newbuild_line .dropdown ul li").bind("click",function(e,param){
        	if (typeof(param) != "undefined" && param == "newbuild")
        	{
        		
        		e.stopPropagation();
        	}
        });
    },500);
}); //\document.ready

function FilterNewbuildSelector(val)
{
	
	var selectBox = $(".newbuild_line .dropdown ul");
	var first = true;
	var found = false;
	var Items = selectBox.find("li:not(:eq(0))");
	if (!val)
	{
		Items.show();
		first = false;
		found = true;
		$(".newbuild_line .dropdown ul li:eq(0)").trigger("click",["newbuild"]);
		return;
	}
	val = val.toLowerCase();
	
	$.each(Items,function(){
		var t = $(this);
		var n = t.text();
		n = n.toLowerCase();
		if (n.indexOf(val) == -1)
		{
			t.hide();
		}
		else
		{
			if (first)
			{
				t.trigger("click",["newbuild"]);
				first = false;
				found = true;
			}
			t.show();
		}		
	});
	if (!found)
	{
		$(".newbuild_line .dropdown ul li:eq(0)").trigger("click",["newbuild"]);
	}
}