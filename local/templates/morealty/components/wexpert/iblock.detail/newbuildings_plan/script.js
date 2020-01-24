$(document).ready(function(){
	var $StartTable = $(".all-propos .sel-on [name='currency-table']");
	$StartTable.find("option").eq(0).attr("selected","selected").trigger("change");
	
	$(".all-propos .table-history2").eq(0).show();
})