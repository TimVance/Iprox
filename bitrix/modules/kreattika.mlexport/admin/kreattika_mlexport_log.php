<?
##########################################################################
# Name: kreattika.mlexport                                               #
# http://marketplace.1c-bitrix.ru/solutions/kreattika.mlexport/          #
# File: kreattika_mlexport_log.php                                       #
# Version: 1.0.5                                                         #
# (c) 2011-2016 Kreattika, Sedov S.Y.                                    #
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

if(!$USER->CanDoOperation('log_kreattika_mlexport'))
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

$isAdmin = $USER->CanDoOperation('list_kreattika_mlexport');

IncludeModuleLangFile(__FILE__);
use \Bitrix\Main\Localization\Loc;

use Bitrix\Main\Entity;
use Bitrix\Main\Type;
use Bitrix\Main\Entity\Query;
use Bitrix\Main\Entity\ExpressionField;

use \Kreattika\MLExport as MLEx;

\Bitrix\Main\Loader::IncludeModule(GetModuleID(__FILE__));

$sTableID = "tbl_kreattika_mlexport_log";
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
    "find_type",
);

$lAdmin->InitFilter($FilterArr);

$arFilter = Array();
if(CheckFilter())
{
    $arFilter = Array(
        "TYPE"	            => $find_type,
    );
}

if(($arID = $lAdmin->GroupAction()))
{
    if($_REQUEST['action_target']=='selected')
    {
        $rsData = MLEx\MLExportLogTable::GetList(array('order' => array($by=>$order), 'filter' => $arFilter));
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
                if(!MLEx\MLExportLogTable::Delete($ID))
                    $lAdmin->AddGroupError(GetMessage("KREATTIKA_MLEXPORT_LIST_ERR_DEL"), $ID);
                break;
        }
    }
}

$order = strtoupper($order);

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
    $countQuery = new Entity\Query(MLEx\MLExportLogTable::getEntity());
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

$rsData = MLEx\MLExportLogTable::getList($getListParams);
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

$aContext=array();
$lAdmin->AddAdminContextMenu($aContext);

$aHeaders = array(
    array("id"=>"ID", "content"=>"ID", "sort"=>"ID", "default"=>true),
    array("id"=>"DATE", "content"=>GetMessage("KREATTIKA_MLEXPORT_DATE"), "sort"=>"DATE", "default"=>true),
    array("id"=>"TYPE", "content"=>GetMessage("KREATTIKA_MLEXPORT_TYPE"), "sort"=>"TYPE", "default"=>true),
    array("id"=>"TEXT", "content"=>GetMessage("KREATTIKA_MLEXPORT_TEXT"), "sort"=>"TEXT", "default"=>true),
);

$lAdmin->AddHeaders($aHeaders);

while($arRes = $rsData->NavNext(true, "f_"))
{
    $strCreatePercent = '';
    $row =& $lAdmin->AddRow($f_ID, $arRes);
    $row->AddViewField("DATE", $f_DATE);
    if( $f_TYPE == 'SUCCESS' ):
        $curType = GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_TYPE_SUCCESS");
        $colorType = '00cc33';
    elseif( $f_TYPE == 'WARNING' ):
        $curType = GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_TYPE_WARNING");
        $colorType = 'ff6600';
    elseif( $f_TYPE == 'ERROR' ):
        $curType = GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_TYPE_ERROR");
        $colorType = 'cc0000';
    else:
        $curType = GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_TYPE_UNKNOWN");
        $colorType = '999999';
    endif;
    $row->AddViewField("TYPE", '<div style="display: inline-block; width: 12px; height: 12px; margin: 3px 7px 0px 0px; border-radius: 50%; background: #'.$colorType.'; "></div>'.$curType);
    $row->AddViewField("TEXT", htmlspecialcharsBack($f_TEXT));

    $arActions = Array();

    $arActions[] = array(
        "ICON"=>"delete",
        "TEXT"=>GetMessage("KREATTIKA_MLEXPORT_LIST_DEL"),
        "ACTION"=>"if(confirm('".GetMessage("KREATTIKA_MLEXPORT_LIST_DEL_CONF")."')) ".$lAdmin->ActionDoGroup($f_ID, "delete")
    );

    $row->AddActions($arActions);
}

$lAdmin->AddGroupActionTable(Array(
    "delete"=>true,
));

$lAdmin->CheckListMode();

$APPLICATION->SetTitle(GetMessage("MAIN_KREATTIKA_MLEXPORT_LIST"));
require_once ($DOCUMENT_ROOT.BX_ROOT."/modules/main/include/prolog_admin_after.php");

$oFilter = new CAdminFilter(
    $sTableID."_filter",
    array(
        GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_STATUS"),
    )
);
?>
    <form name="form1" method="GET" action="<?=$APPLICATION->GetCurPage()?>">
        <input type="hidden" name="lang" value="<?=LANGUAGE_ID?>">
        <?$oFilter->Begin();?>
        <tr>
            <td><?echo GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_TYPE")?></td>
            <td><select name="find_type">
                    <option value=""><?echo GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_ALL")?></option>
                    <option value="UNKNOWN"<?if($find_type == "UNKNOWN") echo " selected"?>><?echo GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_TYPE_UNKNOWN")?></option>
                    <option value="SUCCESS"<?if($find_type == "SUCCESS") echo " selected"?>><?echo GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_TYPE_SUCCESS")?></option>
                    <option value="WARNING"<?if($find_type == "WARNING") echo " selected"?>><?echo GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_TYPE_WARNING")?></option>
                    <option value="ERROR"<?if($find_type == "ERROR") echo " selected"?>><?echo GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_TYPE_ERROR")?></option>
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