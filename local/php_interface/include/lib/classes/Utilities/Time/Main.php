<?

namespace Utilities\Time;

class Main
{
	
	
	public static function ConvertTimeStampToDB($timestamp)
	{
		$objDate = new \DateTime();
		$objDate->setTimestamp($timestamp);
		return trim(\CDatabase::CharToDateFunction(ConvertTimeStamp($objDate->getTimestamp(), "FULL")), "\'");
	}
}