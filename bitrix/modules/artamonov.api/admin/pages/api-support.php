<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php';

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Artamonov\Api\Options;

Loc::loadMessages(__FILE__);

Loader::IncludeModule('artamonov.api');

$options = new Options();
$form = basename(__FILE__, '.php');

$tabControl = new CAdminTabControl(
    'tabControl',
    [
        ['DIV' => 'tab-1', 'TAB' => Loc::getMessage('TAB_MAIN_TITLE')],
        ['DIV' => 'tab-2', 'TAB' => Loc::getMessage('TAB_LOG_TITLE')]
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

$tabControl->Begin();
?>
    <form method='POST' name='<?=$form?>' action='<?=$APPLICATION->GetCurUri()?>'>
        <?=bitrix_sessid_post()?>
        <?$tabControl->BeginNextTab()?>
        <tr>
            <td width='45%' valign='middle'><?=Loc::getMessage('SUPPORT_LINK_TITLE')?><td>
            <td width='55%' valign='middle'>
                <a href="<?=Loc::getMessage('SUPPORT_LINK')?>" target="_blank"><?=Loc::getMessage('SUPPORT_LINK_TEXT')?></a>
                <?ShowJSHint(Loc::getMessage('SUPPORT_LINK_HINT'))?>
            <td>
        </tr>

        <tr>
            <td width='45%' valign='middle'><?=Loc::getMessage('SUPPORT_DOCUMENTATION_LINK_TITLE')?><td>
            <td width='55%' valign='middle'>
                <a href="<?=Loc::getMessage('SUPPORT_DOCUMENTATION_LINK')?>" target="_blank"><?=Loc::getMessage('SUPPORT_DOCUMENTATION_LINK_TEXT')?></a>
                <?ShowJSHint(Loc::getMessage('SUPPORT_DOCUMENTATION_LINK_HINT'))?>
            <td>
        </tr>

        <tr>
            <td width='45%' valign='middle'><?=Loc::getMessage('SUPPORT_FEEDBACK_LINK_TITLE')?><td>
            <td width='55%' valign='middle'>
                <a href="<?=Loc::getMessage('SUPPORT_FEEDBACK_LINK')?>" target="_blank"><?=Loc::getMessage('SUPPORT_FEEDBACK_LINK_TEXT')?></a>
                <?ShowJSHint(Loc::getMessage('SUPPORT_FEEDBACK_LINK_HINT'))?>
            <td>
        </tr>

        <?$tabControl->BeginNextTab()?>

        <tr>
            <td width='45%' valign='middle'><?=Loc::getMessage('OPTION_SUPPORT_USE_LOG_TITLE')?><td>
            <td width='55%' valign='middle'>
                <?
                $ar = [
                    'REFERENCE'    => [
                        Loc::getMessage('OPTION_SUPPORT_USE_LOG_SELECT_TITLE_1'),
                        Loc::getMessage('OPTION_SUPPORT_USE_LOG_SELECT_TITLE_2')
                    ],
                    'REFERENCE_ID' => [
                        Loc::getMessage('OPTION_SUPPORT_USE_LOG_SELECT_ID_1'),
                        Loc::getMessage('OPTION_SUPPORT_USE_LOG_SELECT_ID_2')
                    ]
                ];
                echo SelectBoxFromArray('OPTION_SUPPORT_USE_LOG', $ar, $options->getValue('SUPPORT_USE_LOG'), '', '', false, $options->getFormName());
                ShowJSHint(Loc::getMessage('OPTION_SUPPORT_USE_LOG_HINT', ['#CURRENT_DATE#' => date('Y-m-d')]));
                ?>
            <td>
        </tr>

        <tr>
            <td width='45%' valign='middle'><?=Loc::getMessage('OPTION_SUPPORT_LOG_PATH_TITLE')?><td>
            <td width='55%' valign='middle'>
                <input type='text' name='OPTION_SUPPORT_LOG_PATH' size='40' value='<?=$options->getValue('SUPPORT_LOG_PATH')?>'>
                <?ShowJSHint(Loc::getMessage('OPTION_SUPPORT_LOG_PATH_HINT'))?>
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