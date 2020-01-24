<?




class getOptions {

    var $iblock = 33;

    function getList($code) {
        if (CModule::IncludeModule("iblock")):
            $property_enums = CIBlockPropertyEnum::GetList(
                Array(),
                Array("IBLOCK_ID" => $this->iblock, "CODE" => $code)
            );
            while($enum_fields = $property_enums->GetNext())
            {
                $list_fields[] = $enum_fields;
            }
            return $list_fields;
        endif;
    }

}


?>