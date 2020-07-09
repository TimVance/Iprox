<?
##########################################################################
# Name: kreattika.mlexport                                               #
# http://marketplace.1c-bitrix.ru/solutions/kreattika.mlexport/          #
# File: kreattika_mlexport_profile_list.php                              #
# Version: 1.0.5                                                         #
# (c) 2011-2015 Kreattika, Sedov S.Y.                                    #
# Proprietary licensed                                                   #
# http://kreattika.ru/                                                   #
# mailto:info@kreattika.ru                                               #
##########################################################################
?>
<?
$fileInfo = pathinfo(__FILE__);
$pattern = array("'/modules/kreattika.mlexport/admin'si");
$TMP_BX_ROOT = preg_replace($pattern, array(""), $fileInfo['dirname']);
require_once($TMP_BX_ROOT."/modules/main/include/prolog_admin_before.php");

if(!$USER->CanDoOperation('list_kreattika_mlexport_profile'))
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

$isAdmin = $USER->CanDoOperation('list_kreattika_mlexport_profile');

IncludeModuleLangFile(__FILE__);

use Bitrix\Main\Entity;
use Bitrix\Main\Type;
use Bitrix\Main\Entity\Query;
use Bitrix\Main\Entity\ExpressionField;

use Kreattika\MLExport as MLEx;

$MODULE_ID = GetModuleID(__FILE__);
\Bitrix\Main\Loader::IncludeModule($MODULE_ID);

$arSiteList = array();
$arSitesList = \Bitrix\Main\SiteTable::getList()->fetchAll();
foreach( $arSitesList as $arSite):
        #if($arSite["ACTIVE"] != 'Y'): continue; endif;
        $arSiteList[$arSite["LID"]] = "[".$arSite["LID"]."] ".$arSite["NAME"];
endforeach;

\Bitrix\Main\Loader::IncludeModule("iblock");
$arIBlockList = array();
$obIBlocks = CIBlock::GetList(array("SORT"=>"ASC", "ID"=>"ASC"));
while ($arIBlock = $obIBlocks->Fetch()):
    $arIBlockList[$arIBlock["ID"]] = "[".$arIBlock["ID"]."] ".$arIBlock["NAME"];
endwhile;

$arExportToList = array();
$arExportToList["GOOGLE_MERCHANT"] = GetMessage("KREATTIKA_MLEXPORT_EXPORT_TO_GOOGLE_MERCHANT");
$arExportToList["YANDEX_MARKET"] = GetMessage("KREATTIKA_MLEXPORT_EXPORT_TO_YANDEX_MARKET");

$sTableID = "tbl_kreattika_mlexport_profile";
$oSort = new CAdminSorting($sTableID, "ID", "desc");
$lAdmin = new CAdminList($sTableID, $oSort);

if (!in_array($by, $lAdmin->GetVisibleHeaderColumns(), true))
{
    $by = 'ID';
}

function CheckFilter()
{
    global $FilterArr, $lAdmin;
    foreach ($FilterArr as $f) global $$f;
    return count($lAdmin->arFilterErrors)==0;
}

$FilterArr = Array(
    "find_name",
    "find_active",
    "find_id",
    "find_site_id",
    "find_iblock_id",
    "find_export_to",
);

$lAdmin->InitFilter($FilterArr);

$arFilter = Array();
if(CheckFilter())
{
    $arFilter = Array(
        "NAME"		            => $find_name,
        "ACTIVE"	            => $find_active,
        "ID"		            => $find_id,
        "SITE_ID"	            => $find_site_id,
        "IBLOCK_ID"	            => $find_iblock_id,
        "EXPORT_TO"	            => $find_export_to,
    );
}

if($lAdmin->EditAction())
{
    foreach($FIELDS as $ID=>$arFields)
    {
        $ID = IntVal($ID);
        if($ID <= 0)
            continue;
        $arUpdate['NAME'] = $arFields['NAME'];
        $arUpdate['ACTIVE'] = $arFields['ACTIVE'] == 'Y' ? 'Y' : 'N';
        if(!MLEx\MLExportProfileTable::Update($ID, $arUpdate))
        {
            $e = $APPLICATION->GetException();
            $lAdmin->AddUpdateError(($e? $e->GetString():GetMessage("KREATTIKA_MLEXPORT_LIST_ERR_EDIT")), $ID);
        }
    }
}

if(($arID = $lAdmin->GroupAction()))
{
    if($_REQUEST['action_target']=='selected')
    {
        $rsData = MLEx\MLExportProfileTable::GetList(array('order' => array($by=>$order), 'filter' => $arFilter));
        while($arRes = $rsData->Fetch())
            $arID[] = $arRes['ID'];
    }

    foreach($arID as $ID)
    {
        $ID = IntVal($ID);
        if($ID <= 0)
            continue;
        switch($_REQUEST['action'])
        {
            case "delete":
                if(!MLEx\MLExportProfileTable::Delete($ID))
                    $lAdmin->AddGroupError(GetMessage("KREATTIKA_MLEXPORT_LIST_ERR_DEL"), $ID);
                break;
            case "activate":
                $arUpdate['ACTIVE'] = 'Y';
                if(!MLEx\MLExportProfileTable::Update($ID, $arUpdate))
                    $lAdmin->AddGroupError(GetMessage("KREATTIKA_MLEXPORT_LIST_ERR_ACTIVATE"), $ID);
                break;
            case "deactivate":
                $arUpdate['ACTIVE'] = 'N';
                if(!MLEx\MLExportProfileTable::Update($ID, $arUpdate))
                    $lAdmin->AddGroupError(GetMessage("KREATTIKA_MLEXPORT_LIST_ERR_DEACTIVATE"), $ID);
                break;
        }
    }
}

$order = strtoupper($order);

#print_r($filterValues);

$arCleanFilter = array();
foreach($arFilter as $keyFilter=>$elFilter):
    if( isset($elFilter) && !empty($elFilter) ):
        $arCleanFilter[$keyFilter] = $elFilter;
    endif;
endforeach;

#$arFilter = $filterValues = $arCleanFilter;
$arFilter = $arCleanFilter;

$usePageNavigation = true;
if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'excel')
{
    $usePageNavigation = false;
}
else
{
    $navyParams = CDBResult::GetNavParams(CAdminResult::GetNavSize(
        $sTableID,
        array('nPageSize' => 20, 'sNavID' => $APPLICATION->GetCurPage().'?ENTITY_ID='.$ENTITY_ID)
    ));
    if ($navyParams['SHOW_ALL'])
    {
        $usePageNavigation = false;
    }
    else
    {
        $navyParams['PAGEN'] = (int)$navyParams['PAGEN'];
        $navyParams['SIZEN'] = (int)$navyParams['SIZEN'];
    }
}
$getListParams = array();

#$getListParams['select'] = $lAdmin->GetVisibleHeaderColumns();
$getListParams['select'] = array('*');
$getListParams['filter'] = $arFilter;
$getListParams['order'] = array($by => $order);

unset($filterValues);
if ($usePageNavigation)
{
    $getListParams['limit'] = $navyParams['SIZEN'];
    $getListParams['offset'] = $navyParams['SIZEN']*($navyParams['PAGEN']-1);
}

if ($usePageNavigation)
{
    $countQuery = new Entity\Query(Kreattika\MLExport\MLExportProfileTable::getEntity());
    #$countQuery = new Query($entity_data_class::getEntity());
    $countQuery->addSelect(new ExpressionField('CNT', 'COUNT(1)'));
    $countQuery->setFilter($getListParams['filter']);
    $totalCount = $countQuery->setLimit(null)->setOffset(null)->exec()->fetch();
    unset($countQuery);
    $totalCount = (int)$totalCount['CNT'];
    if ($totalCount > 0)
    {
        $totalPages = ceil($totalCount/$navyParams['SIZEN']);
        if ($navyParams['PAGEN'] > $totalPages)
            $navyParams['PAGEN'] = $totalPages;
        $getListParams['limit'] = $navyParams['SIZEN'];
        $getListParams['offset'] = $navyParams['SIZEN']*($navyParams['PAGEN']-1);
    }
    else
    {
        $navyParams['PAGEN'] = 1;
        $getListParams['limit'] = $navyParams['SIZEN'];
        $getListParams['offset'] = 0;
    }
}

$rsData = MLEx\MLExportProfileTable::getList($getListParams);
$rsData = new CAdminResult($rsData, $sTableID);

if ($usePageNavigation)
{
    $rsData->NavStart($getListParams['limit'], $navyParams['SHOW_ALL'], $navyParams['PAGEN']);
    $rsData->NavRecordCount = $totalCount;
    $rsData->NavPageCount = $totalPages;
    $rsData->NavPageNomer = $navyParams['PAGEN'];
}
else
{
    $rsData->NavStart();
}

$lAdmin->NavText($rsData->GetNavPrint(GetMessage("KREATTIKA_MLEXPORT_LIST_NAV")));

$aHeaders = array(
    array("id"=>"ID", "content"=>"ID", "sort"=>"ID", "default"=>true),
    array("id"=>"ACTIVE", "content"=>GetMessage("KREATTIKA_MLEXPORT_ACTIVE"), "sort"=>"ACTIVE", "default"=>true),
    array("id"=>"SORT", "content"=>GetMessage("KREATTIKA_MLEXPORT_SORT"), "sort"=>"SORT", "default"=>true),
    array("id"=>"NAME", "content"=>GetMessage("KREATTIKA_MLEXPORT_NAME"), "sort"=>"NAME", "default"=>true),
    array("id"=>"SITE_ID", "content"=>GetMessage("KREATTIKA_MLEXPORT_SITE"), "sort"=>"SITE_ID", "default"=>true),
    array("id"=>"IBLOCK_ID", "content"=>GetMessage("KREATTIKA_MLEXPORT_IBLOCK"), "sort"=>"IBLOCK_ID", "default"=>true),
    array("id"=>"EXPORT_TO", "content"=>GetMessage("KREATTIKA_MLEXPORT_EXPORT_TO"), "sort"=>"EXPORT_TO", "default"=>true),
    array("id"=>"FILE_LINK", "content"=>GetMessage("KREATTIKA_MLEXPORT_FILE_LINK"), "sort"=>"FILE_LINK", "default"=>true),
);

$lAdmin->AddHeaders($aHeaders);

$existActiveProfile = false;
$countProfiles = 0;

while($arRes = $rsData->NavNext(true, "f_"))
{
    $countProfiles++;
    if( $f_ACTIVE == "Y" ):
        $existActiveProfile = true;
    endif;

    $row =& $lAdmin->AddRow($f_ID, $arRes);
    $row->AddViewField("ACTIVE", $f_ACTIVE == "Y" ? GetMessage("KREATTIKA_MLEXPORT_ACTIVE_YES") : GetMessage("KREATTIKA_MLEXPORT_ACTIVE_NO"));
    $row->AddViewField("SORT", $f_SORT);
    #$row->AddInputField("NAME", array("size"=>20));
    $row->AddViewField("NAME", '<a href="kreattika_mlexport_profile_edit.php?ID='.$f_ID.'&lang='.LANG.'">'.$f_NAME.'</a>');
    $row->AddViewField("SITE_ID", $arSiteList[$f_SITE_ID]);
    $row->AddViewField("IBLOCK_ID", $arIBlockList[$f_IBLOCK_ID]);
    $row->AddViewField("EXPORT_TO", $arExportToList[$f_EXPORT_TO]);
    $FilePathName = CMLExport::GetProfileDomainPathFileName($f_ID);
    $row->AddViewField("FILE_LINK", '<a href="'.$FilePathName.'">'.$FilePathName.'</a>');

    $arActions = Array();
    $arActions[] = array(
            "ICON"=>"edit",
            "DEFAULT"=>true,
            "TEXT"=>GetMessage("KREATTIKA_MLEXPORT_LIST_EDIT"),
            "ACTION"=>$lAdmin->ActionRedirect("kreattika_mlexport_profile_edit.php?ID=".$f_ID)
        );
    if( $f_ACTIVE == "N" ):
        $arActions[] = array(
            "ICON"=>"activate",
            "TEXT"=>GetMessage("KREATTIKA_MLEXPORT_LIST_ACTIVATE"),
            "ACTION"=>$lAdmin->ActionDoGroup($f_ID, "activate")
        );
    else:
        $arActions[] = array(
            "ICON"=>"deactivate",
            "TEXT"=>GetMessage("KREATTIKA_MLEXPORT_LIST_DEACTIVATE"),
            "ACTION"=>$lAdmin->ActionDoGroup($f_ID, "deactivate")
        );
    endif;

    $arActions[] = array(
            "ICON"=>"delete",
            "TEXT"=>GetMessage("KREATTIKA_MLEXPORT_LIST_DEL"),
            "ACTION"=>"if(confirm('".GetMessage("KREATTIKA_MLEXPORT_LIST_DEL_CONF")."')) ".$lAdmin->ActionDoGroup($f_ID, "delete")
        );


    $row->AddActions($arActions);
}

$lAdmin->AddGroupActionTable(Array(
    "delete"=>true,
    "activate"=>GetMessage("KREATTIKA_MLEXPORT_LIST_ACTIVATE"),
    "deactivate"=>GetMessage("KREATTIKA_MLEXPORT_LIST_DEACTIVATE"),
));

$aContext = array(
    array(
        "TEXT"=>GetMessage("KREATTIKA_MLEXPORT_LIST_ADD"),
        "LINK"=>"kreattika_mlexport_profile_edit.php?lang=".LANG,
        "TITLE"=>GetMessage("KREATTIKA_MLEXPORT_LIST_ADD_TITLE"),
        "ICON"=>"btn_new",
    ),
);
$lAdmin->AddAdminContextMenu($aContext);
$lAdmin->CheckListMode();

$APPLICATION->SetTitle(GetMessage("MAIN_KREATTIKA_MLEXPORT_LIST"));
require_once ($DOCUMENT_ROOT.BX_ROOT."/modules/main/include/prolog_admin_after.php");

$oFilter = new CAdminFilter(
    $sTableID."_filter",
    array(
        GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_NAME"),
        GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_ACTIVE"),
        GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_ID"),
        GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_SITE_ID"),
        GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_IBLOCK_ID"),
        GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_EXPORT_TO"),
    )
);

if($countProfiles == 0):
    echo CAdminMessage::ShowMessage(Array("TYPE"=>"ERROR", "MESSAGE" =>GetMessage("KREATTIKA_MLEXPORT_LIST_PROFILE_NOT_EXIST"), "DETAILS"=>'', "HTML"=>true));
elseif(!$existActiveProfile):
    echo CAdminMessage::ShowMessage(Array("TYPE"=>"ERROR", "MESSAGE" =>GetMessage("KREATTIKA_MLEXPORT_LIST_ALL_PROFILE_NOT_ACTIVE"), "DETAILS"=>'', "HTML"=>true));
endif

?>
    <form name="form1" method="GET" action="<?=$APPLICATION->GetCurPage()?>">
        <input type="hidden" name="lang" value="<?=LANGUAGE_ID?>">
        <?$oFilter->Begin();?>
        <tr>
            <td><?echo GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_NAME")?></td>
            <td><input type="text" name="find_name" size="40" value="<?echo htmlspecialcharsbx($find_name)?>"><?=ShowFilterLogicHelp()?></td>
        </tr>
        <tr>
            <td><?echo GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_ACTIVE")?></td>
            <td><select name="find_active">
                    <option value=""><?echo GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_ALL")?></option>
                    <option value="Y"<?if($find_active == "Y") echo " selected"?>><?echo GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_ACTIVE")?></option>
                    <option value="N"<?if($find_active == "N") echo " selected"?>><?echo GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_INACTIVE")?></option>
                </select>
            </td>
        </tr>
        <tr>
            <td><?echo GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_ID")?></td>
            <td><input type="text" name="find_id" size="13" value="<?echo htmlspecialcharsbx($find_id)?>"></td>
        </tr>
        <tr>
            <td><?echo GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_SITE_ID")?></td>
            <td><select name="find_site_id">
                    <option value=""><?echo GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_ALL")?></option>
                    <?foreach( $arSiteList as $keySite=>$valueSite ):?>
                        <option value="<?=$keySite?>"<?if($find_site_id == $keySite) echo " selected"?>><?=$valueSite?></option>
                    <?endforeach;?>
                </select>
            </td>
        </tr>
        <tr>
            <td><?echo GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_IBLOCK_ID")?></td>
            <td><select name="find_iblock_id">
                    <option value=""><?echo GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_ALL")?></option>
                    <?foreach( $arIBlockList as $keyIBlock=>$valueIBlock ):?>
                        <option value="<?=$keyIBlock?>"<?if($find_iblock_id == $keyIBlock) echo " selected"?>><?=$valueIBlock?></option>
                    <?endforeach;?>
                </select>
            </td>
        </tr>
        <tr>
            <td><?echo GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_EXPORT_TO")?></td>
            <td><select name="find_export_to">
                    <option value=""><?echo GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_ALL")?></option>
                    <?foreach( $arExportToList as $keyExportTo=>$valueExportTo ):?>
                        <option value="<?=$keyExportTo?>"<?if($find_export_to == $keyExportTo) echo " selected"?>><?=$valueExportTo?></option>
                    <?endforeach;?>
                </select>
            </td>
        </tr>
<?
$oFilter->Buttons(array("table_id"=>$sTableID,"url"=>$APPLICATION->GetCurPage(),"form"=>"form1"));
$oFilter->End();
?>
	</form>
<?
$lAdmin->DisplayList();
require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/epilog_admin.php");
?>