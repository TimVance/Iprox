$(document).ready(function(){
	
	$('[name="regform"]').bind("submit",function(){
		yaCounter47701606.reachGoal("register");
		ga("send", "event" , "forms" , "submit" , "register");
	});
	//
})