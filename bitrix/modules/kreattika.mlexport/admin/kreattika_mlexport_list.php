<?
##########################################################################
# Name: kreattika.mlexport                                               #
# http://marketplace.1c-bitrix.ru/solutions/kreattika.mlexport/          #
# File: kreattika_mlexport_list.php                                      #
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

if(!$USER->CanDoOperation('list_kreattika_mlexport'))
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

$arProfilesList = array();
$arMLExProfileParam = array(
    'select'=>array('*'),
    'order'=>array('SORT'=>'ASC', 'ID'=>'DESC'),
);
$arMLExProfileData = MLEx\MLExportProfileTable::getList($arMLExProfileParam)->fetchAll();
foreach($arMLExProfileData as $arMLExProfile):
    $arProfilesList[$arMLExProfile['ID']] = $arMLExProfile['NAME'];
endforeach;

/*
$arSiteList = array();
$arSitesList = \Bitrix\Main\SiteTable::getList()->fetchAll();
foreach( $arSitesList as $arSite):
    $arSiteList[$arSite["LID"]] = "[".$arSite["LID"]."] ".$arSite["NAME"];
endforeach;
*/

$sTableID = "tbl_kreattika_mlexport";
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
    "find_status",
);

$lAdmin->InitFilter($FilterArr);

$arFilter = Array();
if(CheckFilter())
{
    $arFilter = Array(
        "STATUS"	            => $find_status,
    );
}

if(($arID = $lAdmin->GroupAction()))
{
    if($_REQUEST['action_target']=='selected')
    {
        $rsData = MLEx\MLExportTable::GetList(array('order' => array($by=>$order), 'filter' => $arFilter));
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
                if(!MLEx\MLExportTable::Delete($ID))
                    $lAdmin->AddGroupError(GetMessage("KREATTIKA_MLEXPORT_LIST_ERR_DEL"), $ID);
                break;
            case "run":
                $arUpdate['START'] = new \Bitrix\Main\Type\DateTime(date('Y-m-d H:i:s',time()),'Y-m-d H:i:s');
                if(!MLEx\MLExportTable::Update($ID, $arUpdate))
                    $lAdmin->AddGroupError(GetMessage("KREATTIKA_MLEXPORT_LIST_ERR_RUN"), $ID);
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
    $countQuery = new Entity\Query(MLEx\MLExportTable::getEntity());
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

$rsData = MLEx\MLExportTable::getList($getListParams);
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
    array("id"=>"PROFILE_ID", "content"=>GetMessage("KREATTIKA_MLEXPORT_PROFILE_ID"), "sort"=>"PROFILE_ID", "default"=>true),
    array("id"=>"STATUS", "content"=>GetMessage("KREATTIKA_MLEXPORT_STATUS"), "sort"=>"STATUS", "default"=>true),
    array("id"=>"START", "content"=>GetMessage("KREATTIKA_MLEXPORT_START"), "sort"=>"START", "default"=>true),
    array("id"=>"END", "content"=>GetMessage("KREATTIKA_MLEXPORT_END"), "sort"=>"END", "default"=>true),
    array("id"=>"COMMENT", "content"=>GetMessage("KREATTIKA_MLEXPORT_COMMENT"), "sort"=>"COMMENT", "default"=>true),
);

$lAdmin->AddHeaders($aHeaders);

$existActiveJob = false;

while($arRes = $rsData->NavNext(true, "f_"))
{
    $strCreatePercent = '';
    $row =& $lAdmin->AddRow($f_ID, $arRes);
    $row->AddViewField("PROFILE_ID", '<a href="kreattika_mlexport_profile_edit.php?ID='.$f_PROFILE_ID.'&lang='.LANGUAGE_ID.'">[ '.$f_PROFILE_ID.' ] '.$arProfilesList[$f_PROFILE_ID].'</a>');
    if( $f_STATUS == 'START' ):
        $curStatus = GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_STATUS_START");
        $colorStatus = '00cc33';
    elseif( $f_STATUS == 'CREATE' ):
        $existActiveJob = true;
        $curStatus = GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_STATUS_CREATE");
        $colorStatus = 'ff6600';
        #$arCreateNS = unserialize(htmlspecialcharsBack($f_CREATE_NS));
        $arCreateNS = $f_CREATE_NS;
        $CreatePercent = round($arCreateNS['LAST']*100/$arCreateNS['COUNT']);
        if( $CreatePercent < 1 ): $CreatePercent = 1; endif;
        $strCreatePercent = ' / '.strval(round($CreatePercent)).'% / '.FormatDate('x', MakeTimeStamp($f_LAST_START->toString()));
    elseif( $f_STATUS == 'END' ):
        $curStatus = GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_STATUS_END");
        $colorStatus = '0099cc';
    elseif( $f_STATUS == 'ERROR' ):
        $curStatus = GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_STATUS_ERROR");
        $colorStatus = 'cc0000';
    else:
        $curStatus = GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_STATUS_UNABLE");
        $colorStatus = '999999';
    endif;
    $row->AddViewField("STATUS", '<div style="display: inline-block; width: 12px; height: 12px; margin: 3px 7px 0px 0px; border-radius: 50%; background: #'.$colorStatus.'; "></div>'.$curStatus.' '.$strCreatePercent);
    $row->AddViewField("START", $f_START);
    $row->AddViewField("END", $f_END);
    $row->AddViewField("COMMENT", htmlspecialcharsBack($f_COMMENT));

    $arActions = Array();

    if( $f_STATUS == 'START' ):
        $arActions[] = array(
            "ICON"=>"run",
            "TEXT"=>GetMessage("KREATTIKA_MLEXPORT_LIST_RUN"),
            "ACTION"=>$lAdmin->ActionDoGroup($f_ID, "run")
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
));

$lAdmin->CheckListMode();

$APPLICATION->SetTitle(GetMessage("MAIN_KREATTIKA_MLEXPORT_LIST"));
require_once ($DOCUMENT_ROOT.BX_ROOT."/modules/main/include/prolog_admin_after.php");

if($existActiveJob):
    $MODULE_ID = GetModuleID(__FILE__);
    $reloadTime = COption::GetOptionString($MODULE_ID, "mlexport_time");
    $APPLICATION->AddHeadString('<script>onload = function () {setTimeout (\'location.reload (true)\', '.($reloadTime*1000*2).')}</script>',true);
endif;

$oFilter = new CAdminFilter(
    $sTableID."_filter",
    array(
        GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_STATUS"),
    )
);

if( count($arMLExProfileData) == 0 ):
    echo CAdminMessage::ShowMessage(Array("TYPE"=>"ERROR", "MESSAGE" =>GetMessage("KREATTIKA_MLEXPORT_LIST_PROFILE_NOT_EXIST"), "DETAILS"=>'', "HTML"=>true));
else:
    $existActiveProfile = false;
    foreach( $arMLExProfileData as $arMLExProfile ):
        if( $arMLExProfile["ACTIVE"] == "Y" ):
            $existActiveProfile = true;
        endif;
    endforeach;
    if(!$existActiveProfile):
        echo CAdminMessage::ShowMessage(Array("TYPE"=>"ERROR", "MESSAGE" =>GetMessage("KREATTIKA_MLEXPORT_LIST_ALL_PROFILE_NOT_ACTIVE"), "DETAILS"=>'', "HTML"=>true));
    endif;
endif;
?>
    <form name="form1" method="GET" action="<?=$APPLICATION->GetCurPage()?>">
        <input type="hidden" name="lang" value="<?=LANGUAGE_ID?>">
        <?$oFilter->Begin();?>
        <tr>
            <td><?echo GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_STATUS")?></td>
            <td><select name="find_status">
                    <option value=""><?echo GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_ALL")?></option>
                    <option value="START"<?if($find_status == "START") echo " selected"?>><?echo GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_STATUS_START")?></option>
                    <option value="CREATE"<?if($find_status == "CREATE") echo " selected"?>><?echo GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_STATUS_CREATE")?></option>
                    <option value="END"<?if($find_status == "END") echo " selected"?>><?echo GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_STATUS_END")?></option>
                    <option value="ERROR"<?if($find_status == "ERROR") echo " selected"?>><?echo GetMessage("KREATTIKA_MLEXPORT_LIST_FLT_STATUS_ERROR")?></option>
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