<?
namespace Morealty;

class Main
{
	
	
	
	
	public static function isAjax()
	{
		return (strtolower($_REQUEST["ajax"]) === "y") && (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
	}
	
	
	
	
}