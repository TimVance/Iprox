<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

class CostForm
{
    private $fields = array();

    function __construct($request)
    {
        $this->fields = array(
            "IBLOCK_ID" => 31,
            "ACTIVE" => "Y",
            "NAME" => $request['name'],
            "PROPERTY_VALUES" => array(
                "AREA" => $request['cost']['region'],
                "STREET" => $request['cost']['street'],
                "ROOMS" => $request['cost']['rooms'],
                "SQUARE" => $request['cost']['square'],
                "STAGE" => $request['cost']['stage'],
                "E_MAIL" => $request['email'],
            )
        );
    }

    public function Send()
    {
        if (CModule::IncludeModule('iblock')) {
            $el = new CIBlockElement();
            if ($PRODUCT_ID = $el->Add($this->fields)) {
                return $PRODUCT_ID;
            } else {
                return false;
            }
        }
        return false;
    }
}

$form = new CostForm($_REQUEST);

die(json_encode($form->Send()));

?>