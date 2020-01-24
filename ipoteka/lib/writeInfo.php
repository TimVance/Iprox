<?


class writeInfo
{

    var $iblock = 33;

    function writeBase($post)
    {
        $el = new CIBlockElement;
        GLOBAL $USER;

        $PROP                     = array();
        $PROP["program"]          = $post["program"];
        $PROP["type"]             = $post["type"];
        $PROP["sum"]              = $post["sum"];
        $PROP["first_pay"]        = $post["first_pay"];
        $PROP["time"]             = $post["time"];
        $PROP["comment"]          = $post["comment"];
        $PROP["bank"]             = $post["bank"];
        $PROP["second_name"]      = $post["second_name"];
        $PROP["first_name"]       = $post["first_name"];
        $PROP["patronymic"]       = $post["patronymic"];
        $PROP["sex"]              = $post["sex"];
        $PROP["born_date"]        = $post["born_date"];
        $PROP["phone"]            = $post["phone"];
        $PROP["email"]            = $post["email"];
        $PROP["additional_phone"] = $post["additional_phone"];
        $PROP["serial_number"]    = $post["serial_number"];
        $PROP["date_of_issue"]    = $post["date_of_issue"];
        $PROP["code_subdivision"] = $post["code_subdivision"];
        $PROP["born_place"]       = $post["born_place"];
        $PROP["snils"]            = $post["snils"];
        $PROP["inn"]              = $post["inn"];


        $arLoadProductArray = Array(
            "MODIFIED_BY"       => $USER->GetID(),
            "IBLOCK_SECTION_ID" => false,
            "IBLOCK_ID"         => 33,
            "PROPERTY_VALUES"   => $PROP,
            "NAME"              => $PROP["phone"],
            "ACTIVE"            => "Y",
        );

        if ($PRODUCT_ID = $el->Add($arLoadProductArray))
            return "New ID: " . $PRODUCT_ID;
        else
            return "Error: " . $el->LAST_ERROR;
    }


    function writeDop($post)
    {
        CIBlockElement::SetPropertyValuesEx(
            38726, //ID обновляемого элемента
            33, //ID инфоблока
            array(
                'address'         => $post["address"],
                'family'          => $post["family"],
                'education'       => $post["education"],
                'children'        => $post["children"],
                'employment_form' => $post["employment_form"],
                'income_proof'    => $post["income_proof"],
                'position'        => $post["position"],
                'income'          => $post["income"],
                'work_phone'      => $post["work_phone"],
            )
        );
    }

}


?>