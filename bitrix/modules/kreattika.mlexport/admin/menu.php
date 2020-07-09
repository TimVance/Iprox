<?
##########################################################################
# Name: kreattika.mlexport                                               #
# http://marketplace.1c-bitrix.ru/solutions/kreattika.mlexport/          #
# File: menu.php                                                         #
# Version: 1.0.5                                                         #
# (c) 2011-2015 Kreattika, Sedov S.Y.                                    #
# Proprietary licensed                                                   #
# http://kreattika.ru/                                                   #
# mailto:info@kreattika.ru                                               #
##########################################################################
?>
<?
use \Bitrix\Main\Localization\Loc;

$MODULE_ID = $module_id = GetModuleID(__FILE__);

if($APPLICATION->GetGroupRight($MODULE_ID) > "D")
{
    if(\Bitrix\Main\ModuleManager::isModuleInstalled($MODULE_ID))
    {
        IncludeModuleLangFile(__FILE__);

        $aMenuInnerItems[] = array(
            "url" => "kreattika_mlexport_profile_list.php?lang=".LANGUAGE_ID,
            "more_url" => array("kreattika_mlexport_profile_edit.php?lang=".LANGUAGE_ID),
            "text" => Loc::getMessage("KREATTIKA_MLEXPORT_MENU_PROFILE_LIST_ALT"),
            //"title" => Loc::getMessage("KREATTIKA_MLEXPORT_MENU_LIST_ALT"),
        );

        $aMenuInnerItems[] = array(
            "url" => "kreattika_mlexport_list.php?lang=".LANGUAGE_ID,
            "text" => Loc::getMessage("KREATTIKA_MLEXPORT_MENU_LIST_ALT"),
            //"title" => Loc::getMessage("KREATTIKA_MLEXPORT_MENU_LIST_ALT"),
        );

        $aMenuInnerItems[] = array(
            "url" => "kreattika_mlexport_log.php?lang=".LANGUAGE_ID,
            "text" => Loc::getMessage("KREATTIKA_MLEXPORT_MENU_LOG_ALT"),
            //"title" => Loc::getMessage("KREATTIKA_MLEXPORT_MENU_LIST_ALT"),
        );

        $aMenu = array(
            array(
                "parent_menu" => "global_menu_services",
                "section" => "mlexport",
                "sort" => 20,
                "text" => Loc::getMessage("KREATTIKA_MLEXPORT_MENU_MAIN"),
                "title" => Loc::getMessage("KREATTIKA_MLEXPORT_MENU_MAIN_TITLE"),
                "icon" => "mlexport-list-ico",
                //"page_icon" => "seo_page_icon",
                "module_id" => $MODULE_ID,
                "items_id" => "menu_mlexport",
                "items" => $aMenuInnerItems,
            )
        );

        return $aMenu;
    }
}
return false;
