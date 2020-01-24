<?

namespace Morealty;

class Settings
{
	
	const CATALOG_IBLOCK_CACHE_TIME = 3600;
	
	const CATALOG_DEFAULT_MAP_PAGE	= "/sell/map/";
	
	const CATALOG_CUSTOM_MAP_PAGE = "/sell/map/#IBLOCK_CODE#/";
	
	const CATALOG_FLAT	= 7;
	
	const IBLOCK_DISTRICT = 14;
	
	const IBLOCK_MICRODISTRICT = 15;
	
	const PROPERTY_MICRODISTRICT_DISTRICT = "district";
	
	public static $CATALOG_IBLOCKS_INFO	= array(
		array(
			"ID" => 7,
			"FILTER_NAME" => "Квартира",
		),
		array(
			"ID" => 8,
			"FILTER_NAME" => "Дом"
		),
		array(
			"ID" => 11,
			"FILTER_NAME" => "Коммерция"
		),
		array(
			"ID" => 10,
			"FILTER_NAME" => "Участок"
		)
	);
	
	public static $CATALOG_IBLOCK_NEWBUILDING = array(
			"ID" => 19,
			"FILTER_NAME" => "Новостройки"
	);
	
	
	public static $CATALOG_SORT_PARAMS = array(
		array(
			"NAME" => "По умолчанию"
		),
		array(
			"NAME" => "По дате",
			"SORT" => array("created" => "DESC", "created" => "DESC"),
			"ID" => "date"
		),
		array(
			"NAME" => "Дешевле",
			"SORT" => array("PROPERTY_price" => "ASC", "PROPERTY_price" => "ASC"),
			"ID" => "cheaper"
		),
		array(
			"NAME" => "Дороже",
			"SORT" => array("PROPERTY_price" => "DESC", "PROPERTY_price" => "DESC"),
			"ID" => "expensive"
		),
	);
	
	
	public static $REALTOR_SORT_PARAMS = array(
			array(
					"NAME" => "По умолчанию"
			),
			array(
					"NAME" => "Дата обновления",
					"SORT" => array("BY" =>"timestamp_x","ORDER" => "DESC"),
					"ID" => "date"
			),
			array(
					"NAME" => "Имя",
					"SORT" => array("BY" => "name", "ORDER" => "ASC"),
					"ID" => "name"
			),
			array(
					"NAME" => "Кол-во объектов",
					"SORT" => array("BY" => "UF_REALTOR_OBJECTS","ORDER" => "DESC"),
					"ID" => "cnt"
			)
	);
	
	const CATALOG_VIEW_TYPE_PARAM = "catalog_view_type";
	
	public static $CATALOG_VIEW_TYPES = array(
		"tiles" => array(
			"class" => "item1",
			"id" => "tiles"
		),
		"list" => array(
			"class" => "item2",
			"id" => "list"
		),
		"mini-list" => array(
			"class" => "item3",
			"id" => "mini-list"
		)
	);
	
	const CATALOG_VIEW_TYPE_COOKIE_NAME = "catalog_view_type_n";
	
	
	const BILLING_API_KEY = "8117504A-3D29-E414-091E-ED68F029879F";
	
	const BILLING_API_URL = "https://sms.ru/sms/send";
	
	
	public static $USER_AUTH_SMS_MESSAGE = "Ваш код для доступа к порталу #CODE#";
	
	/**
	 * 
	 * Задержка между смс для авторизации
	 * @var integer
	 */
	public static $USER_AUTH_DELAY_SEC = 30;
}