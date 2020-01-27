<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php';

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Artamonov\Api\Init as Api;
use Artamonov\Api\Options;

Loc::loadMessages(__FILE__);

Loader::IncludeModule('artamonov.api');

$options = new Options();

$geoIpExist = Api::checkLibraryAvailability(Api::GEOIP);
$form = basename(__FILE__, '.php');

$tabControl = new CAdminTabControl(
    'tabControl',
    [
        ['DIV' => 'tab-1', 'TAB' => Loc::getMessage('TAB_MAIN_TITLE')],
        ['DIV' => 'tab-2', 'TAB' => Loc::getMessage('TAB_AUTHORIZATION_TITLE')],
        ['DIV' => 'tab-3', 'TAB' => Loc::getMessage('TAB_FILTERS_TITLE')]
    ]
);

$APPLICATION->SetTitle(Loc::getMessage('TITLE'));

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php';

if ($_POST) {
    $_POST['form'] = $form;
    if ($_POST['save']) {
        if ($options->save($_POST)) {
            echo CAdminMessage::ShowNote(Loc::getMessage('OPTIONS_SAVED'));
        }
    }
    if ($_POST['restore']) {
        if ($options->restore($_POST)) {
            echo CAdminMessage::ShowNote(Loc::getMessage('OPTIONS_RESTORED'));
        }
    }
}

if ($_GET['generateToken'] == 'Y') {
    $count = $options->generateTokens();
    if ($count > 0) {
        echo CAdminMessage::ShowNote(Loc::getMessage('TOKENS_GENERATED', ['#COUNT#' => $count]));
    } else {
        echo CAdminMessage::ShowNote(Loc::getMessage('TOKENS_NOT_GENERATED'));
    }
}

$tabControl->Begin();
?>
    <form method='POST' name='<?=$form?>' action='<?=$APPLICATION->GetCurUri()?>'>
        <?=bitrix_sessid_post()?>

        <?$tabControl->BeginNextTab()?>

        <tr>
            <td width='45%' valign='top'><?=Loc::getMessage('OPTION_USE_ONLY_HTTPS_EXCHANGE_TITLE')?><td>
            <td width='55%' valign='middle'>
                <?
                echo InputType('checkbox', 'OPTION_ONLY_HTTPS_EXCHANGE', 'Y', $options->getValue('ONLY_HTTPS_EXCHANGE'));
                ShowJSHint(Loc::getMessage('OPTION_ONLY_HTTPS_EXCHANGE_HINT'));
                ?>
            <td>
        </tr>

        <?$tabControl->BeginNextTab()?>

        <tr>
            <td width='45%' valign='top'><?=Loc::getMessage('OPTION_USE_AUTH_BY_LOGIN_PASSWORD')?><td>
            <td width='55%' valign='middle'>
                <?
                echo InputType('checkbox', 'OPTION_USE_AUTH_BY_LOGIN_PASSWORD', 'Y', $options->getValue('USE_AUTH_BY_LOGIN_PASSWORD'));
                ShowJSHint(Loc::getMessage('OPTION_USE_AUTH_BY_LOGIN_PASSWORD_HINT'));
                ?>
            <td>
        </tr>

        <tr><td colspan="4">&nbsp;</td></tr>
        <tr><td colspan="4">&nbsp;</td></tr>

        <tr>
            <td width='45%' valign='top'><?=Loc::getMessage('OPTION_USE_AUTH_TOKEN_TITLE')?><td>
            <td width='55%' valign='middle'>
                <?
                echo InputType('checkbox', 'OPTION_USE_AUTH_TOKEN', 'Y', $options->getValue('USE_AUTH_TOKEN'));
                ShowJSHint(Loc::getMessage('OPTION_AUTH_TOKEN_HINT', ['#FIELD_NAME_RESTFUL_API_TOKEN#' => $options->getUserFieldCodeApiToken()], LANG));
                ?>
            <td>
        </tr>

        <tr>
            <td width='45%' valign='middle'><?=Loc::getMessage('OPTION_TOKEN_KEYWORD_TITLE')?><td>
            <td width='55%' valign='middle'>
                <input type='text' name='OPTION_TOKEN_KEYWORD' size='20' value='<?=$options->getValue('TOKEN_KEYWORD')?>'>
                <?ShowJSHint(Loc::getMessage('OPTION_TOKEN_KEYWORD_HINT'))?>
            <td>
        </tr>

        <tr>
            <td width='45%' valign='middle'><?=Loc::getMessage('TOKEN_GENERATE_TITLE')?><td>
            <td width='55%' valign='middle'>
                <a href="<?=str_replace('&generateToken=Y', '', $APPLICATION->GetCurUri())?>&generateToken=Y"><?=Loc::getMessage('GENERATE_LINK_TEXT')?></a>
                <?ShowJSHint(Loc::getMessage('TOKEN_GENERATE_HINT'))?>
            <td>
        </tr>

        <tr><td colspan="4">&nbsp;</td></tr>
        <tr><td colspan="4">&nbsp;</td></tr>

        <tr>
            <td width='45%' valign='top'><?=Loc::getMessage('OPTION_USE_CHECK_USER_GROUP')?><td>
            <td width='55%' valign='middle'>
                <?
                echo InputType('checkbox', 'OPTION_USE_CHECK_USER_GROUP', 'Y', $options->getValue('USE_CHECK_USER_GROUP'));
                ShowJSHint(Loc::getMessage('OPTION_USE_CHECK_USER_GROUP_HINT'));
                ?>
            <td>
        </tr>

        <tr>
            <td width='45%' valign='top'><?=Loc::getMessage('OPTION_GROUP_LIST')?><td>
            <td width='55%' valign='middle'>
                <?
                $res = CGroup::GetList(($by='C_SORT'), ($order='ASC'), ['ACTIVE' => 'Y', 'ANONYMOUS' => 'N']);
                while ($group = $res->fetch()) {
                    $ar['REFERENCE'][] = $group['NAME'];
                    $ar['REFERENCE_ID'][] = $group['REFERENCE_ID'];
                }
                echo SelectBoxMFromArray('OPTION_GROUP_LIST[]', $ar, explode('|', $options->getValue('GROUP_LIST')));
                ?>
            <td>
        </tr>

        <?$tabControl->BeginNextTab()?>

        <tr>
            <td width='45%' valign='middle'><?=Loc::getMessage('OPTION_USE_LIST_COUNTRY_FILTER')?><td>
            <td width='55%' valign='middle'>
                <?
                $ar = [
                    'REFERENCE' => [
                        Loc::getMessage('OPTION_USE_LIST_COUNTRY_FILTER_SELECT_TITLE_1'),
                        Loc::getMessage('OPTION_USE_LIST_COUNTRY_FILTER_SELECT_TITLE_2')
                    ],
                    'REFERENCE_ID' => [
                        Loc::getMessage('OPTION_USE_LIST_COUNTRY_FILTER_SELECT_ID_1'),
                        Loc::getMessage('OPTION_USE_LIST_COUNTRY_FILTER_SELECT_ID_2')
                    ]
                ];
                echo SelectBoxFromArray('OPTION_USE_LIST_COUNTRY_FILTER', $ar, $options->getValue('USE_LIST_COUNTRY_FILTER'), '', (!$geoIpExist) ? 'disabled' : '', false, $options->getFormName());
                ShowJSHint(Loc::getMessage('OPTION_USE_LIST_COUNTRY_FILTER_HINT'));
                ?>
            <td>
        </tr>

        <tr>
            <td width='45%' valign='top'><?=Loc::getMessage('OPTION_WHITE_LIST_COUNTRY_TITLE')?><td>
            <td width='55%' valign='middle'>
                <?php
                $text = (!$geoIpExist) ? 'Error: the '.Api::GEOIP.' library was not found' : $options->getValue('WHITE_LIST_COUNTRY');
                ?>
                <textarea name="OPTION_WHITE_LIST_COUNTRY" cols="50" rows="5" <?if(!$geoIpExist):?>disabled<?endif?> ><?=$text?></textarea>
                <?ShowJSHint(Loc::getMessage('OPTION_WHITE_LIST_COUNTRY_HINT'))?>
            <td>
        </tr>

        <tr><td colspan="4">&nbsp;</td></tr>
        <tr><td colspan="4">&nbsp;</td></tr>

        <tr>
            <td width='45%' valign='middle'><?=Loc::getMessage('OPTION_USE_BLACK_LIST_ADDRESS_FILTER')?><td>
            <td width='55%' valign='middle'>
                <?
                $ar = [
                    'REFERENCE' => [
                        Loc::getMessage('OPTION_USE_BLACK_LIST_ADDRESS_FILTER_SELECT_TITLE_1'),
                        Loc::getMessage('OPTION_USE_BLACK_LIST_ADDRESS_FILTER_SELECT_TITLE_2')
                    ],
                    'REFERENCE_ID' => [
                        Loc::getMessage('OPTION_USE_BLACK_LIST_ADDRESS_FILTER_SELECT_ID_1'),
                        Loc::getMessage('OPTION_USE_BLACK_LIST_ADDRESS_FILTER_SELECT_ID_2')
                    ]
                ];
                echo SelectBoxFromArray('OPTION_USE_BLACK_LIST_ADDRESS_FILTER', $ar, $options->getValue('USE_BLACK_LIST_ADDRESS_FILTER'), '', '', false, $options->getFormName());
                ShowJSHint(Loc::getMessage('OPTION_USE_BLACK_LIST_ADDRESS_FILTER_HINT'));
                ?>
            <td>
        </tr>

        <tr>
            <td width='45%' valign='top'><?=Loc::getMessage('OPTION_BLACK_LIST_ADDRESS_TITLE')?><td>
            <td width='55%' valign='middle'>
                <textarea name="OPTION_BLACK_LIST_ADDRESS" cols="50" rows="5"><?=$options->getValue('BLACK_LIST_ADDRESS')?></textarea>
                <?ShowJSHint(Loc::getMessage('OPTION_BLACK_LIST_ADDRESS_HINT'))?>
            <td>
        </tr>

        <tr><td colspan="4">&nbsp;</td></tr>
        <tr><td colspan="4">&nbsp;</td></tr>

        <tr>
            <td width='45%' valign='middle'><?=Loc::getMessage('OPTION_USE_WHITE_LIST_ADDRESS_FILTER')?><td>
            <td width='55%' valign='middle'>
                <?
                $ar = [
                    'REFERENCE' => [
                        Loc::getMessage('OPTION_USE_WHITE_LIST_ADDRESS_FILTER_SELECT_TITLE_1'),
                        Loc::getMessage('OPTION_USE_WHITE_LIST_ADDRESS_FILTER_SELECT_TITLE_2')
                    ],
                    'REFERENCE_ID' => [
                        Loc::getMessage('OPTION_USE_WHITE_LIST_ADDRESS_FILTER_SELECT_ID_1'),
                        Loc::getMessage('OPTION_USE_WHITE_LIST_ADDRESS_FILTER_SELECT_ID_2')
                    ]
                ];
                echo SelectBoxFromArray('OPTION_USE_WHITE_LIST_ADDRESS_FILTER', $ar, $options->getValue('USE_WHITE_LIST_ADDRESS_FILTER'), '', '', false, $options->getFormName());
                ShowJSHint(Loc::getMessage('OPTION_USE_WHITE_LIST_ADDRESS_FILTER_HINT'));
                ?>
            <td>
        </tr>

        <tr>
            <td width='45%' valign='top'><?=Loc::getMessage('OPTION_WHITE_LIST_ADDRESS_TITLE')?><td>
            <td width='55%' valign='middle'>
                <textarea name="OPTION_WHITE_LIST_ADDRESS" cols="50" rows="5"><?=$options->getValue('WHITE_LIST_ADDRESS')?></textarea>
                <?ShowJSHint(Loc::getMessage('OPTION_WHITE_LIST_ADDRESS_HINT'))?>
            <td>
        </tr>

        <tr><td colspan="4">&nbsp;</td></tr>
        <tr><td colspan="4">&nbsp;</td></tr>

        <tr>
            <td width='45%' valign='middle'><?=Loc::getMessage('OPTION_USE_ACCESS_CONTROL_ALLOW_ORIGIN_FILTER')?><td>
            <td width='55%' valign='middle'>
                <?
                $ar = [
                    'REFERENCE' => [
                        Loc::getMessage('OPTION_USE_ACCESS_CONTROL_ALLOW_ORIGIN_FILTER_SELECT_TITLE_1'),
                        Loc::getMessage('OPTION_USE_ACCESS_CONTROL_ALLOW_ORIGIN_FILTER_SELECT_TITLE_2')
                    ],
                    'REFERENCE_ID' => [
                        Loc::getMessage('OPTION_USE_ACCESS_CONTROL_ALLOW_ORIGIN_FILTER_SELECT_ID_1'),
                        Loc::getMessage('OPTION_USE_ACCESS_CONTROL_ALLOW_ORIGIN_FILTER_SELECT_ID_2')
                    ]
                ];
                echo SelectBoxFromArray('OPTION_USE_ACCESS_CONTROL_ALLOW_ORIGIN_FILTER', $ar, $options->getValue('USE_ACCESS_CONTROL_ALLOW_ORIGIN_FILTER'), '', '', false, $options->getFormName());
                ShowJSHint(Loc::getMessage('OPTION_USE_ACCESS_CONTROL_ALLOW_ORIGIN_FILTER_HINT'));
                ?>
            <td>
        </tr>

        <tr>
            <td width='45%' valign='top'><?=Loc::getMessage('OPTION_WHITE_LIST_DOMAIN_ACCESS_CONTROL_ALLOW_ORIGIN_TITLE')?><td>
            <td width='55%' valign='middle'>
                <textarea name="OPTION_WHITE_LIST_DOMAIN_ACCESS_CONTROL_ALLOW_ORIGIN" cols="50" rows="5"><?=$options->getValue('WHITE_LIST_DOMAIN_ACCESS_CONTROL_ALLOW_ORIGIN')?></textarea>
                <?ShowJSHint(Loc::getMessage('OPTION_WHITE_LIST_DOMAIN_ACCESS_CONTROL_ALLOW_ORIGIN_HINT'))?>
            <td>
        </tr>

        <tr>
            <td colspan='4' valign='middle'>
                <?$tabControl->Buttons();?>
                <input type='submit' name='save' value='<?=Loc::getMessage('BTN_SAVE')?>' class='adm-btn-save'>
                <input type='submit' name='restore' value='<?=Loc::getMessage('BTN_RESTORE')?>'>
            </td>
        </tr>
    </form>
<?php
$tabControl->End();
require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php';