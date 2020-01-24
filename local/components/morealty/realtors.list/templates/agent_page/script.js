$(function(){
	$(".agent_employers .list-emploe .view-all").bind("click.showRealtors", function(){
		var t = $(this);
		var parent = t.parents(".agent_employers");
		parent.find(".list-emploe").addClass("opened");
		t.fadeOut().unbind(".showRealtors");
	});
})