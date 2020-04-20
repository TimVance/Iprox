<?


class writeInfo
{

    var $iblock = 33;

    function writeBase($post)
    {
        $el = new CIBlockElement;
        global $USER;

        $PROP = array();

        foreach ($post as $code => $item) {
            $PROP[$code] = $item;
        }

        $arLoadProductArray = array(
            "MODIFIED_BY"       => $USER->GetID(),
            "IBLOCK_SECTION_ID" => false,
            "IBLOCK_ID"         => $this->iblock,
            "PROPERTY_VALUES"   => $PROP,
            "NAME"              => $PROP["phone"],
            "ACTIVE"            => "Y",
        );

        if ($PRODUCT_ID = $el->Add($arLoadProductArray))
            return "success";
        else
            return "Error: " . $el->LAST_ERROR;
    }

}


?>