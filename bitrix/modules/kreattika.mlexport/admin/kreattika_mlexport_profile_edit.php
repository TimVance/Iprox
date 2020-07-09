<?
##########################################################################
# Name: kreattika.mlexport                                               #
# http://marketplace.1c-bitrix.ru/solutions/kreattika.mlexport/          #
# File: kreattika_mlexport_profile_edit.php                              #
# Version: 1.0.2                                                         #
# (c) 2011-2015 Kreattika, Sedov S.Y.                                    #
# Proprietary licensed                                                   #
# http://kreattika.ru/                                                   #
# mailto:info@kreattika.ru                                               #
##########################################################################
?>
<?
#require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
$fileInfo = pathinfo(__FILE__);
$pattern = array("'/modules/kreattika.mlexport/admin'si");
$TMP_BX_ROOT = preg_replace($pattern, array(""), $fileInfo['dirname']);
require_once($TMP_BX_ROOT."/modules/main/include/prolog_admin_before.php");
define("ADMIN_MODULE_NAME", GetModuleID(__FILE__));

IncludeModuleLangFile(__FILE__);
IncludeModuleLangFile(__DIR__.'/kreattika_mlexport_profile_list.php');

if(!$USER->CanDoOperation('edit_kreattika_mlexport_profile'))
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

$isAdmin = $USER->CanDoOperation('edit_kreattika_mlexport_profile');

if (!CModule::IncludeModule(ADMIN_MODULE_NAME))
{
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

use Bitrix\Main\Entity;
use Bitrix\Main\Type;
use Bitrix\Main\Entity\Query;
use Bitrix\Main\Entity\ExpressionField;

use \Kreattika\MLExport as MLEx;

$MODULE_ID = GetModuleID(__FILE__);
\Bitrix\Main\Loader::IncludeModule($MODULE_ID);

$arSiteList = array();
$arSitesList = \Bitrix\Main\SiteTable::getList()->fetchAll();
foreach( $arSitesList as $arSite):
    if($arSite["ACTIVE"] != 'Y'): continue; endif;
    $arSiteList[$arSite["LID"]] = "[".$arSite["LID"]."] ".$arSite["NAME"];
endforeach;

\Bitrix\Main\Loader::IncludeModule("iblock");

$arExportToList = array();
$arExportToList["GOOGLE_MERCHANT"] = GetMessage("KREATTIKA_MLEXPORT_EXPORT_TO_GOOGLE_MERCHANT");
$arExportToList["YANDEX_MARKET"] = GetMessage("KREATTIKA_MLEXPORT_EXPORT_TO_YANDEX_MARKET");

$ModuleCatalogIsIncluded = CModule::IncludeModule("catalog");

if ( $ModuleCatalogIsIncluded ):
    $arExportPriceTypesList = array();
    $obPriceType = CCatalogGroup::GetList( array("SORT" => "ASC") );
    while ($arPriceType = $obPriceType->Fetch()):
        $arExportPriceTypesList[$arPriceType["ID"]] = '[ '.$arPriceType["ID"].' ] ('.$arPriceType["NAME"].') '.$arPriceType["NAME_LANG"];
    endwhile;
else:
    $arExportPriceTypesList = array();
endif;

$arFileEncodeList = array();
$arFileEncodeList["UTF-8"] = 'UTF-8';
//$arFileEncodeList["windows-1251"] = 'windows-1251';


// form
$aTabs = array(
    array("DIV" => "edit1", "TAB" => GetMessage('KREATTIKA_MLEXPORT_ADMIN_ENTITY_TITLE'), "ICON"=>"ad_contract_edit", "TITLE"=> GetMessage('KREATTIKA_MLEXPORT_ADMIN_ENTITY_TITLE'))
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);

$is_create_form = true;
$is_update_form = false;

$errors = array();

$arIBlockFilter = array("ACTIVE"=>"Y");
$arIBlockPropFilter = array("ACTIVE"=>"Y", "PROPERTY_TYPE"=>"N");

// get data
if (isset($_REQUEST['ID']) && $_REQUEST['ID'] > 0)
{
    $filter = array(
        'select' => array('*'),
        'filter' => array('=ID' => $_REQUEST['ID'])
    );
    $arData = MLEx\MLExportProfileTable::getList($filter)->fetch();

    if (!empty($arData))
    {
        $is_update_form = true;
        $is_create_form = false;

        if ( isset($arData['SITE_ID']) && !empty($arData['SITE_ID']) ):
            $arIBlockFilter = array_merge($arIBlockFilter, array("SITE_ID"=>$arData['SITE_ID']));
        endif;

        if ( isset($arData['IBLOCK_TYPE']) && !empty($arData['IBLOCK_TYPE']) ):
            $arIBlockFilter = array_merge($arIBlockFilter, array("TYPE"=>$arData['IBLOCK_TYPE']));
        endif;

        if ( isset($arData['IBLOCK_ID']) && !empty($arData['IBLOCK_ID']) ):
            $arIBlockPropFilter = array_merge($arIBlockPropFilter, array("IBLOCK_ID"=>$arData['IBLOCK_ID']));
        endif;


    }
}

$arIBlockTypesSort = array("SORT"=>"ASC", "ID"=>"ASC");
$arIBlockTypesList = array();
$obIBlockTypes = CIBlockType::GetList($arIBlockTypesSort, $arIBlockTypesFilter);
while ($arIBlockType = $obIBlockTypes->Fetch()):
    $arIBlockTypesList[$arIBlockType["ID"]] = "[".$arIBlockType["ID"]."] ".$arIBlockType["NAME"];
endwhile;

$arIBlockSort = array("SORT"=>"ASC", "ID"=>"ASC");
$arIBlockList = array();
$obIBlocks = CIBlock::GetList($arIBlockSort, $arIBlockFilter);
while ($arIBlock = $obIBlocks->Fetch()):
    $arIBlockList[$arIBlock["ID"]] = "[".$arIBlock["ID"]."] ".$arIBlock["NAME"];
endwhile;

if ( !$ModuleCatalogIsIncluded ):
    $arIBlockPropSort = array("sort"=>"asc", "name"=>"asc");
    $obProp = CIBlockProperty::GetList($arIBlockPropSort, $arIBlockPropFilter );
    while ($arProp = $obProp->GetNext()):
        $arExportPriceTypesList[$arProp["ID"]] = '[ '.$arProp["CODE"].' ] '.$arProp["NAME"];
    endwhile;
endif;

if ($is_create_form)
{
    // default values for create form
    //$arData = array_fill_keys(array('REDIRECT_FROM'), '');
    $rnd = substr(str_shuffle(str_repeat('0123456789',1)),0,6);
    $arData = array(
        'ACTIVE'=>'Y',
        'SORT'=>500,
        'MAX_EXECUTE_TIME'=>\Bitrix\Main\Config\Option::get($MODULE_ID, "mlexport_time"),
        'RECORD_LIMIT'=>\Bitrix\Main\Config\Option::get($MODULE_ID, "mlexport_record_limit"),
        'FOLDER_PATH'=>\Bitrix\Main\Config\Option::get($MODULE_ID, "mlexport_folder_path"),
        'FILE_NAME'=>\Bitrix\Main\Config\Option::get($MODULE_ID, "mlexport_fale_name_prefix").$rnd,
        'EXPORT_PRICE_CURRENCY'=>'RUB',
        'EXPORT_ONLY_PRICE'=>'Y',
        'TEMPLATE_ID'=>'1', //temp value
    );

    // page title
    $APPLICATION->SetTitle(GetMessage('KREATTIKA_MLEXPORT_ADMIN_ENTITY_EDIT_PAGE_TITLE_NEW'));
}
else
{
    if( isset($arData['NAME']) && !empty($arData['NAME']) ):
        $ElName = $arData['NAME'];
    else:
        $ElName = $arData['ID'];
    endif;

    $APPLICATION->SetTitle(GetMessage('KREATTIKA_MLEXPORT_ADMIN_ENTITY_EDIT_PAGE_TITLE_EDIT', array('#NAME#' => $ElName)));

}

$isEditMode = true;

// delete action
if ($is_update_form && isset($_REQUEST['action']) && $_REQUEST['action'] === 'delete' && check_bitrix_sessid())
{
    MLEx\MLExportProfileTable::delete($arData['ID']);

    LocalRedirect("kreattika_mlexport_profile_list.php?lang=".LANGUAGE_ID);
}

$strWarning="";
$bWarning = false;

// save action
if ((strlen($save)>0 || strlen($apply)>0) && $REQUEST_METHOD=="POST" && check_bitrix_sessid())
{
    $data = array();
    if( isset($_REQUEST['ACTIVE']) && !empty($_REQUEST['ACTIVE']) ): $data['ACTIVE'] = "Y"; else: $data['ACTIVE'] = "N"; endif;
    if( isset($_REQUEST['SORT']) && !empty($_REQUEST['SORT']) ): $data['SORT'] = trim($_REQUEST['SORT']); else: $data['SORT'] = 500; endif;
    if( isset($_REQUEST['NAME']) && !empty($_REQUEST['NAME']) ): $data['NAME'] = htmlspecialcharsbx($_REQUEST['NAME']); else: $strWarning .= GetMessage('KREATTIKA_MLEXPORT_ADMIN_ERR_NAME_EMPTY'); $bWarning = true; endif;
    if( isset($_REQUEST['MAX_EXECUTE_TIME']) && !empty($_REQUEST['MAX_EXECUTE_TIME']) ): $data['MAX_EXECUTE_TIME'] = htmlspecialcharsbx($_REQUEST['MAX_EXECUTE_TIME']); endif;
    if( isset($_REQUEST['RECORD_LIMIT']) && !empty($_REQUEST['RECORD_LIMIT']) ): $data['RECORD_LIMIT'] = htmlspecialcharsbx($_REQUEST['RECORD_LIMIT']); endif;
    if( isset($_REQUEST['SITE_ID']) && !empty($_REQUEST['SITE_ID']) ): $data['SITE_ID'] = trim($_REQUEST['SITE_ID']); else: $data['SITE_ID'] = NULL; $strWarning .= GetMessage('KREATTIKA_MLEXPORT_ADMIN_ERR_SITE_EMPTY'); $bWarning = true; endif;
    if( isset($_REQUEST['IBLOCK_TYPE']) && !empty($_REQUEST['IBLOCK_TYPE']) ): $data['IBLOCK_TYPE'] = htmlspecialcharsbx($_REQUEST['IBLOCK_TYPE']); else: $strWarning .= GetMessage('KREATTIKA_MLEXPORT_ADMIN_ERR_IBLOCK_TYPE_EMPTY'); $bWarning = true; endif;
    if( isset($_REQUEST['IBLOCK_ID']) && !empty($_REQUEST['IBLOCK_ID']) ): $data['IBLOCK_ID'] = htmlspecialcharsbx($_REQUEST['IBLOCK_ID']); else: $strWarning .= GetMessage('KREATTIKA_MLEXPORT_ADMIN_ERR_IBLOCK_EMPTY'); $bWarning = true; endif;
    if( isset($_REQUEST['EXPORT_TO']) && !empty($_REQUEST['EXPORT_TO']) ): $data['EXPORT_TO'] = htmlspecialcharsbx($_REQUEST['EXPORT_TO']); else: $strWarning .= GetMessage('KREATTIKA_MLEXPORT_ADMIN_ERR_EXPORT_TO_EMPTY'); $bWarning = true; endif;
    if( isset($_REQUEST['EXPORT_ALL']) && !empty($_REQUEST['EXPORT_ALL']) ): $data['EXPORT_ALL'] = "Y"; else: $data['EXPORT_ALL'] = "N"; endif;
    if( isset($_REQUEST['EXPORT_ONLY_STORE']) && !empty($_REQUEST['EXPORT_ONLY_STORE']) ): $data['EXPORT_ONLY_STORE'] = "Y"; else: $data['EXPORT_ONLY_STORE'] = "N"; endif;
    if( isset($_REQUEST['EXPORT_ONLY_PRICE']) && !empty($_REQUEST['EXPORT_ONLY_PRICE']) ): $data['EXPORT_ONLY_PRICE'] = "Y"; else: $data['EXPORT_ONLY_PRICE'] = "N"; endif;
    if ( $ModuleCatalogIsIncluded ):
        if( isset($_REQUEST['EXPORT_PRICE_ID']) && !empty($_REQUEST['EXPORT_PRICE_ID']) ): $data['EXPORT_PRICE_ID'] = htmlspecialcharsbx($_REQUEST['EXPORT_PRICE_ID']); else: $strWarning .= GetMessage('KREATTIKA_MLEXPORT_ADMIN_ERR_PRICE_TYPE_ID_EMPTY'); $bWarning = true; endif;
        if( isset($_REQUEST['EXPORT_DISCOUNT']) && !empty($_REQUEST['EXPORT_DISCOUNT']) ): $data['EXPORT_DISCOUNT']  = "Y"; else: $data['EXPORT_DISCOUNT'] = "N"; endif;
    else:
        if( isset($_REQUEST['EXPORT_PRICE_PROPERTY']) && !empty($_REQUEST['EXPORT_PRICE_PROPERTY']) ): $data['EXPORT_PRICE_PROPERTY'] = htmlspecialcharsbx($_REQUEST['EXPORT_PRICE_PROPERTY']); else: $strWarning .= GetMessage('KREATTIKA_MLEXPORT_ADMIN_ERR_PRICE_PROPERTY_EMPTY'); $bWarning = true; endif;
        if( isset($_REQUEST['EXPORT_PRICE_CURRENCY']) && !empty($_REQUEST['EXPORT_PRICE_CURRENCY']) ): $data['EXPORT_PRICE_CURRENCY'] = htmlspecialcharsbx($_REQUEST['EXPORT_PRICE_CURRENCY']); else: $strWarning .= GetMessage('KREATTIKA_MLEXPORT_ADMIN_ERR_PRICE_CURRENCY_EMPTY'); $bWarning = true; endif;
    endif;
    if( isset($_REQUEST['FOLDER_PATH']) && !empty($_REQUEST['FOLDER_PATH']) ): $data['FOLDER_PATH'] = htmlspecialcharsbx($_REQUEST['FOLDER_PATH']); else: $strWarning .= GetMessage('KREATTIKA_MLEXPORT_ADMIN_ERR_FOLDER_PATH_EMPTY'); $bWarning = true; endif;
    if( isset($_REQUEST['FILE_NAME']) && !empty($_REQUEST['FILE_NAME']) ): $data['FILE_NAME'] = htmlspecialcharsbx($_REQUEST['FILE_NAME']); else: $strWarning .= GetMessage('KREATTIKA_MLEXPORT_ADMIN_ERR_FILE_NAME_EMPTY'); $bWarning = true; endif;
    if( isset($_REQUEST['FILE_ENCODE']) && !empty($_REQUEST['FILE_ENCODE']) ): $data['FILE_ENCODE'] = htmlspecialcharsbx($_REQUEST['FILE_ENCODE']); else: $strWarning .= GetMessage('KREATTIKA_MLEXPORT_ADMIN_ERR_FILE_ENCODE_EMPTY'); $bWarning = true; endif;
    if( isset($_REQUEST['CLASS_NAME']) && !empty($_REQUEST['CLASS_NAME']) ): $data['CLASS_NAME'] = htmlspecialcharsbx($_REQUEST['CLASS_NAME']); endif;

    if ($is_update_form)
    {
        $ID = intval($_REQUEST['ID']);
        $result = MLEx\MLExportProfileTable::update($ID, $data);
    }
    else
    {
        if( !$bWarning ):
            // create
            $result = MLEx\MLExportProfileTable::add($data);

        endif;

    }

    if( !$bWarning ):

        if ($result->isSuccess())
        {
            $ID = $result->getId();

            if (strlen($save)>0)
            {
                LocalRedirect("kreattika_mlexport_profile_list.php?lang=".LANGUAGE_ID);
            }
            else
            {
                LocalRedirect("kreattika_mlexport_profile_edit.php?ID=".$ID."&lang=".LANGUAGE_ID."&".$tabControl->ActiveTabParam());
            }
        }
        else
        {
            $errors = $result->getErrorMessages();
        }

    endif;
    // rewrite original value by form value to restore form
    foreach ($data as $k => $v)
    {
        $arData[$k] = $v;
    }
}


// menu
$aMenu = array(
    array(
        "TEXT"	=> GetMessage('KREATTIKA_MLEXPORT_ADMIN_RETURN_TO_LIST_BUTTON'),
        "TITLE"	=> GetMessage('KREATTIKA_MLEXPORT_ADMIN_RETURN_TO_LIST_BUTTON'),
        "LINK"	=> "kreattika_mlexport_profile_list.php?lang=".LANGUAGE_ID,
        "ICON"	=> "btn_list",
    )
);

$context = new CAdminContextMenu($aMenu);

// view
if ($_REQUEST["mode"] == "list")
{
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_js.php");
}
else
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
}

$context->Show();

if (!empty($errors))
{
    CAdminMessage::ShowMessage(join("\n", $errors));
}
?>
    <form name="form1" method="POST" action="<?=$APPLICATION->GetCurPage()?>">
        <?=bitrix_sessid_post()?>
        <input type="hidden" name="ID" value="<?=htmlspecialcharsbx($arData['ID'])?>">
        <input type="hidden" name="lang" value="<?=LANGUAGE_ID?>">
        <?CAdminMessage::ShowOldStyleError($strWarning);?>
        <?
        $tabControl->Begin();

        $tabControl->BeginNextTab();

        ?>
        <?if ( $is_update_form ):?>
        <tr>
            <td width="40%"><strong><?=GetMessage('KREATTIKA_MLEXPORT_ADMIN_ID_FIELD')?></strong></td>
            <td><?=htmlspecialcharsEx($arData['ID'])?>
            </td>
        </tr>
        <?endif;?>
        <tr>
            <td width="40%"><strong><?=GetMessage('KREATTIKA_MLEXPORT_ADMIN_ACTIVE_FIELD')?></strong></td>
            <td><input type="checkbox"<?if (!$isEditMode):?> disabled="disabled"<?endif;?> name="ACTIVE"<?if($arData['ACTIVE']=="Y"):?> checked="checked"<?endif;?> value="<?=htmlspecialcharsbx($arData['ACTIVE'])?>"></td>
        </tr>
        <tr>
            <td width="40%"><strong><?=GetMessage('KREATTIKA_MLEXPORT_ADMIN_SORT_FIELD')?></strong></td>
            <td><input type="text"<?if (!$isEditMode):?> disabled="disabled"<?endif;?> name="SORT" size="3" value="<?=htmlspecialcharsbx($arData['SORT'])?>"></td>
        </tr>
        <tr>
            <td width="40%"><strong><?=GetMessage('KREATTIKA_MLEXPORT_ADMIN_NAME_FIELD')?></strong></td>
            <td><input type="text"<?if (!$isEditMode):?> disabled="disabled"<?endif;?> name="NAME" size="50" value="<?=htmlspecialcharsbx($arData['NAME'])?>"></td>
        </tr>
        <tr>
            <td width="40%"><strong><?=GetMessage('KREATTIKA_MLEXPORT_ADMIN_MAX_EXECUTE_TIME_FIELD')?></strong></td>
            <td><input type="text"<?if (!$isEditMode):?> disabled="disabled"<?endif;?> name="MAX_EXECUTE_TIME" size="6" value="<?=htmlspecialcharsbx($arData['MAX_EXECUTE_TIME'])?>"></td>
        </tr>
        <tr>
            <td width="40%"><strong><?=GetMessage('KREATTIKA_MLEXPORT_ADMIN_RECORD_LIMIT_FIELD')?></strong></td>
            <td><input type="text"<?if (!$isEditMode):?> disabled="disabled"<?endif;?> name="RECORD_LIMIT" size="6" value="<?=htmlspecialcharsbx($arData['RECORD_LIMIT'])?>"></td>
        </tr>
        <tr>
            <td width="40%"><strong><?=GetMessage('KREATTIKA_MLEXPORT_ADMIN_SITE_ID_FIELD')?></strong></td>
            <td><select name="SITE_ID" <?if (!$isEditMode):?> disabled="disabled"<?endif;?>>
                    <?if( !isset($arData['SITE_ID']) || empty($arData['SITE_ID']) ):?><option selected="selected" value=""><?=GetMessage('KREATTIKA_MLEXPORT_ADMIN_SELECT_NOT_SELECTED')?></option><?endif;?>
                    <?foreach($arSiteList as $keySite=>$ValueSite):?>
                        <option<?if($arData['SITE_ID']==$keySite):?> selected="selected"<?endif;?> value="<?=$keySite?>"><?=$ValueSite?></option>
                    <?endforeach;?>
                </select>
            </td>
        </tr>
        <tr>
            <td width="40%"><strong><?=GetMessage('KREATTIKA_MLEXPORT_ADMIN_IBLOCK_TYPE_FIELD')?></strong></td>
            <td><select name="IBLOCK_TYPE" <?if (!$isEditMode):?> disabled="disabled"<?endif;?>>
                    <?if( !isset($arData['IBLOCK_TYPE']) || empty($arData['IBLOCK_TYPE']) ):?><option selected="selected" value=""><?=GetMessage('KREATTIKA_MLEXPORT_ADMIN_SELECT_NOT_SELECTED')?></option><?endif;?>
                    <?foreach($arIBlockTypesList as $keyIBlockType=>$ValueIBlockType):?>
                        <option<?if($arData['IBLOCK_TYPE']==$keyIBlockType):?> selected="selected"<?endif;?> value="<?=$keyIBlockType?>"><?=$ValueIBlockType?></option>
                    <?endforeach;?>
                </select>
            </td>
        </tr>
        <tr>
            <td width="40%"><strong><?=GetMessage('KREATTIKA_MLEXPORT_ADMIN_IBLOCK_ID_FIELD')?></strong></td>
            <td><select name="IBLOCK_ID" <?if (!$isEditMode):?> disabled="disabled"<?endif;?>>
                    <?if( !isset($arData['IBLOCK_ID']) || empty($arData['IBLOCK_ID']) ):?><option selected="selected" value=""><?=GetMessage('KREATTIKA_MLEXPORT_ADMIN_SELECT_NOT_SELECTED')?></option><?endif;?>
                    <?foreach($arIBlockList as $keyIBlock=>$ValueIBlock):?>
                        <option<?if($arData['IBLOCK_ID']==$keyIBlock):?> selected="selected"<?endif;?> value="<?=$keyIBlock?>"><?=$ValueIBlock?></option>
                    <?endforeach;?>
                </select>
            </td>
        </tr>
        <tr>
            <td width="40%"><strong><?=GetMessage('KREATTIKA_MLEXPORT_ADMIN_EXPORT_TO_FIELD')?></strong></td>
            <td><select name="EXPORT_TO" <?if (!$isEditMode):?> disabled="disabled"<?endif;?>>
                    <?if( !isset($arData['EXPORT_TO']) || empty($arData['EXPORT_TO']) ):?><option selected="selected" value=""><?=GetMessage('KREATTIKA_MLEXPORT_ADMIN_SELECT_NOT_SELECTED')?></option><?endif;?>
                    <?foreach($arExportToList as $keyExportTo=>$ValueExportTo):?>
                        <option<?if($arData['EXPORT_TO']==$keyExportTo):?> selected="selected"<?endif;?> value="<?=$keyExportTo?>"><?=$ValueExportTo?></option>
                    <?endforeach;?>
                </select>
            </td>
        </tr>
        <input type="hidden" name="TEMPLATE_ID" value="1" />
        <tr>
            <td width="40%"><?=GetMessage('KREATTIKA_MLEXPORT_ADMIN_EXPORT_ALL_FIELD')?></td>
            <td><input type="checkbox"<?if (!$isEditMode):?> disabled="disabled"<?endif;?> name="EXPORT_ALL"<?if($arData['EXPORT_ALL']=="Y"):?> checked="checked"<?endif;?> value="<?=htmlspecialcharsbx($arData['EXPORT_ALL'])?>"></td>
        </tr>
        <tr>
            <td width="40%"><?=GetMessage('KREATTIKA_MLEXPORT_ADMIN_EXPORT_ONLY_STORE_FIELD')?></td>
            <td><input type="checkbox"<?if (!$isEditMode):?> disabled="disabled"<?endif;?> name="EXPORT_ONLY_STORE"<?if($arData['EXPORT_ONLY_STORE']=="Y"):?> checked="checked"<?endif;?> value="<?=htmlspecialcharsbx($arData['EXPORT_ONLY_STORE'])?>"></td>
        </tr>
        <tr>
            <td width="40%"><?=GetMessage('KREATTIKA_MLEXPORT_ADMIN_EXPORT_ONLY_PRICE_FIELD')?></td>
            <td><input type="checkbox"<?if (!$isEditMode):?> disabled="disabled"<?endif;?> name="EXPORT_ONLY_PRICE"<?if($arData['EXPORT_ONLY_PRICE']=="Y"):?> checked="checked"<?endif;?> value="<?=htmlspecialcharsbx($arData['EXPORT_ONLY_PRICE'])?>"></td>
        </tr>
        <?if ( $ModuleCatalogIsIncluded ):?>
        <tr>
            <td width="40%"><strong><?=GetMessage('KREATTIKA_MLEXPORT_ADMIN_EXPORT_PRICE_ID_FIELD')?></strong></td>
            <td><select name="EXPORT_PRICE_ID" <?if (!$isEditMode):?> disabled="disabled"<?endif;?>>
                    <?foreach($arExportPriceTypesList as $keyPriceType=>$ValuePriceType):?>
                        <option<?if($arData['EXPORT_PRICE_ID']==$keyPriceType):?> selected="selected"<?endif;?> value="<?=$keyPriceType?>"><?=$ValuePriceType?></option>
                    <?endforeach;?>
                </select>
            </td>
        </tr>
            <!--
        <tr>
            <td width="40%"><strong><?=GetMessage('KREATTIKA_MLEXPORT_ADMIN_EXPORT_DISCOUNT_FIELD')?></strong></td>
            <td><input type="checkbox"<?if (!$isEditMode):?> disabled="disabled"<?endif;?> name="EXPORT_DISCOUNT"<?if($arData['EXPORT_DISCOUNT']=="Y"):?> checked="checked"<?endif;?> value="<?=htmlspecialcharsbx($arData['EXPORT_DISCOUNT'])?>"></td>
        </tr>
        //-->
        <?else:?>
            <tr>
                <td width="40%"><strong><?=GetMessage('KREATTIKA_MLEXPORT_ADMIN_EXPORT_PRICE_PROPERTY_FIELD')?></strong></td>
                <td><select name="EXPORT_PRICE_PROPERTY" <?if (!$isEditMode):?> disabled="disabled"<?endif;?>>
                        <?if( !isset($arData['EXPORT_PRICE_PROPERTY']) || empty($arData['EXPORT_PRICE_PROPERTY']) ):?><option selected="selected" value=""><?=GetMessage('KREATTIKA_MLEXPORT_ADMIN_SELECT_NOT_SELECTED')?></option><?endif;?>
                        <?foreach($arExportPriceTypesList as $keyPriceType=>$ValuePriceType):?>
                            <option<?if($arData['EXPORT_PRICE_PROPERTY']==$keyPriceType):?> selected="selected"<?endif;?> value="<?=$keyPriceType?>"><?=$ValuePriceType?></option>
                        <?endforeach;?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong><?=GetMessage('KREATTIKA_MLEXPORT_ADMIN_EXPORT_PRICE_CURRENCY_FIELD')?></strong></td>
                <td><input type="text"<?if (!$isEditMode):?> disabled="disabled"<?endif;?> name="EXPORT_PRICE_CURRENCY" size="3" value="<?=htmlspecialcharsbx($arData['EXPORT_PRICE_CURRENCY'])?>"></td>
            </tr>
        <?endif;?>
        <tr>
            <td><strong><?=GetMessage('KREATTIKA_MLEXPORT_ADMIN_FOLDER_PATH_FIELD')?></strong></td>
            <td><input type="text"<?if (!$isEditMode):?> disabled="disabled"<?endif;?> name="FOLDER_PATH" size="50" value="<?=htmlspecialcharsbx($arData['FOLDER_PATH'])?>"></td>
        </tr>
        <tr>
            <td><strong><?=GetMessage('KREATTIKA_MLEXPORT_ADMIN_FILE_NAME_FIELD')?></strong></td>
            <td><input type="text"<?if (!$isEditMode):?> disabled="disabled"<?endif;?> name="FILE_NAME" size="50" value="<?=htmlspecialcharsbx($arData['FILE_NAME'])?>"></td>
        </tr>
        <tr>
            <td><strong><?=GetMessage('KREATTIKA_MLEXPORT_ADMIN_FILE_ENCODE_FIELD')?></strong></td>
            <td><select name="FILE_ENCODE" <?if (!$isEditMode):?> disabled="disabled"<?endif;?>>
                    <?foreach($arFileEncodeList as $keyFileEncode=>$ValueFileEncode):?>
                        <option<?if($arData['FILE_ENCODE']==$keyFileEncode):?> selected="selected"<?endif;?> value="<?=$keyFileEncode?>"><?=$ValueFileEncode?></option>
                    <?endforeach;?>
                </select>
            </td>
        </tr>
        <?$AllowEditClassName = false;?>
        <tr>
            <td><strong><?=GetMessage('KREATTIKA_MLEXPORT_ADMIN_CLASS_NAME_FIELD')?></strong></td>
            <td><input type="text"<?if (!$AllowEditClassName):?> disabled="disabled"<?endif;?> name="CLASS_NAME" size="50" value="<?=htmlspecialcharsbx($arData['CLASS_NAME'])?>"></td>
        </tr>
        <?
        $disable = true;
        if($isEditMode)
            $disable = false;

        $tabControl->Buttons(array("disabled" => $disable, "back_url"=>"kreattika_mlexport_profile_list.php?lang=".LANGUAGE_ID));
        $tabControl->End();
        ?>
    </form>
<?


if ($_REQUEST["mode"] == "list")
{
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin_js.php");
}
else
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
}
?>