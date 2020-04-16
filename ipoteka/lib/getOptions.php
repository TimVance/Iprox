<?

class getOptions {

    var $iblock = 33;

    public function getList($properties) {
        if (CModule::IncludeModule("iblock")):
            $list_fields = array();
            foreach ($properties as $code) {
                $property_enums = CIBlockPropertyEnum::GetList(
                    Array(),
                    Array("IBLOCK_ID" => $this->iblock, "CODE" => $code)
                );
                while($enum_fields = $property_enums->GetNext())
                {
                    $list_fields[$code][] = $enum_fields;
                }
            }
            return $list_fields;
        endif;
    }

}