<?php

use Bitrix\Main\Localization\Loc;

return [
    [
        'parent_menu' => 'global_menu_services',
        'section' => 'restful-api',
        'sort' => 1,
        'module_id' => Loc::getMessage('API_MODULE_ID'),
        'items_id' => 'menu_'.Loc::getMessage('API_MODULE_ID'),
        'icon' => 'clouds_menu_icon',
        'page_icon' => 'clouds_menu_icon',
        'text' => Loc::getMessage('API_MODULE_NAME'),
        'items' => [

            [
                'items_id' => 'menu_'.Loc::getMessage('API_MODULE_ID').'_security',
                'sort' => 1,
                'icon' => 'security_menu_icon',
                'page_icon' => 'security_menu_icon',
                'text' => Loc::getMessage('SECURITY'),
                'url' => 'api-security.php?lang='.LANG
            ],

            [
                'items_id' => 'menu_'.Loc::getMessage('API_MODULE_ID').'_support',
                'sort' => 1,
                'icon' => 'support_menu_icon',
                'page_icon' => 'support_menu_icon',
                'text' => Loc::getMessage('SUPPORT'),
                'url' => 'api-support.php?lang='.LANG
            ],

            [
                'items_id' => 'menu_'.Loc::getMessage('API_MODULE_ID').'_settings',
                'sort' => 1,
                'icon' => 'sys_menu_icon',
                'page_icon' => 'sys_menu_icon',
                'text' => Loc::getMessage('SETTINGS'),
                'url' => 'api-settings.php?lang='.LANG
            ]
        ]
    ]
];