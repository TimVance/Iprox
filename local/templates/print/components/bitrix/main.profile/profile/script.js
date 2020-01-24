$(document).ready( function() {
	$("label input[type=file]").change(function(){
		var filename = $(this).val().replace(/.*\\/, "");
		$("#filename").show().val(filename);
	});

	$("input[type=checkbox]").change(function(){
		var name = $(this).attr('name');
		name = name.substr(0, name.length - 6);
		if($(this).is(':checked')) {
			$('input[name=' + name + ']').val('1');
		}
		else {
			$('input[name=' + name + ']').val('0');
		}
	});
});