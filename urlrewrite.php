<?php
$arUrlRewrite=array (
  16 => 
  array (
    'CONDITION' => '#^/sell/map/([^/]+)/\\??#',
    'RULE' => 'CODE=$1&',
    'ID' => '',
    'PATH' => '/sell/map/custom-map.php',
    'SORT' => '50',
  ),
  0 => 
  array (
    'CONDITION' => '#^/personal/favorites/([0-9a-zA-Z_-]+)/?\\??#',
    'RULE' => 'catalog=$1&ID=$2&',
    'ID' => '',
    'PATH' => '/personal/favorites/index.php',
    'SORT' => '100',
  ),
  1 => 
  array (
    'CONDITION' => '#^/online/([\\.\\-0-9a-zA-Z]+)(/?)([^/]*)#',
    'RULE' => 'alias=$1',
    'ID' => '',
    'PATH' => '/desktop_app/router.php',
    'SORT' => '100',
  ),
  2 => 
  array (
    'CONDITION' => '#^/sell/map/filter/(.+?)/apply/\\??(.*)#',
    'RULE' => 'SMART_FILTER_PATH=$1&$2',
    'ID' => 'bitrix:catalog.smart.filter',
    'PATH' => '/sell/index.php',
    'SORT' => '100',
  ),
  3 => 
  array (
    'CONDITION' => '#^/sell/map/filter/(.+?)/apply/\\??(.*)#',
    'RULE' => 'SMART_FILTER_PATH=$1&$2',
    'ID' => 'bitrix:catalog.smart.filter',
    'PATH' => '/sell/map/index.php',
    'SORT' => '100',
  ),
  4 => 
  array (
    'CONDITION' => '#^/sell/map/filter/(.+?)/apply/\\??(.*)#',
    'RULE' => 'SMART_FILTER_PATH=$1&$2',
    'ID' => 'bitrix:catalog.smart.filter',
    'PATH' => '/sell/section.php',
    'SORT' => '100',
  ),
  5 => 
  array (
    'CONDITION' => '#^/sell/map/filter/(.+?)/apply/\\??(.*)#',
    'RULE' => 'SMART_FILTER_PATH=$1&$2',
    'ID' => 'bitrix:catalog.smart.filter',
    'PATH' => '/newbuildings/index.php',
    'SORT' => '100',
  ),
  6 => 
  array (
    'CONDITION' => '#^/sell/map/filter/(.+?)/apply/\\??(.*)#',
    'RULE' => 'SMART_FILTER_PATH=$1&$2',
    'ID' => 'bitrix:catalog.smart.filter',
    'PATH' => '/sell/map/custom-map.php',
    'SORT' => '100',
  ),
  7 => 
  array (
    'CONDITION' => '#^/sell/map/filter/(.+?)/apply/\\??(.*)#',
    'RULE' => 'SMART_FILTER_PATH=$1&$2',
    'ID' => 'bitrix:catalog.smart.filter',
    'PATH' => '/personal/favorites/index.php',
    'SORT' => '100',
  ),
  8 => 
  array (
    'CONDITION' => '#^/newbuildings/([\\d]+)/questions/\\??#',
    'RULE' => 'ID=$1&PAGE=questions&',
    'ID' => '',
    'PATH' => '/newbuildings/detail.php',
    'SORT' => '100',
  ),
  9 => 
  array (
    'CONDITION' => '#^/newbuildings/([\\d]+)/plan/\\??#',
    'RULE' => 'ID=$1&PAGE=plan&',
    'ID' => '',
    'PATH' => '/newbuildings/detail.php',
    'SORT' => '100',
  ),
  10 => 
  array (
    'CONDITION' => '#^/newbuildings/([\\d]+)/plan/$#',
    'RULE' => 'ID=$1&PAGE=plan&',
    'ID' => '',
    'PATH' => '/newbuildings/detail.php',
    'SORT' => '100',
  ),
  12 => 
  array (
    'CONDITION' => '#^/newbuildings/([\\d]+)/\\??#',
    'RULE' => 'ID=$1&',
    'ID' => '',
    'PATH' => '/newbuildings/detail.php',
    'SORT' => '100',
  ),
  11 => 
  array (
    'CONDITION' => '#^/sell/([^/]+)/([^/]+)/\\??#',
    'RULE' => 'catalog=$1&ID=$2&',
    'ID' => '',
    'PATH' => '/sell/detail.php',
    'SORT' => '100',
  ),
  13 => 
  array (
    'CONDITION' => '#^/arend/([^/]+)/([^/]+)/$#',
    'RULE' => 'catalog=$1&ID=$2&',
    'ID' => '',
    'PATH' => '/arend/detail.php',
    'SORT' => '100',
  ),
  14 => 
  array (
    'CONDITION' => '#^/sell/([^/]+)/([^/]+)/$#',
    'RULE' => 'catalog=$1&ID=$2&',
    'ID' => '',
    'PATH' => '/sell/detail.php',
    'SORT' => '100',
  ),
  15 => 
  array (
    'CONDITION' => '#^/realtors/([\\d]+)/\\??#',
    'RULE' => 'ID=$1&',
    'ID' => '',
    'PATH' => '/realtors/detail.php',
    'SORT' => '100',
  ),
  17 => 
  array (
    'CONDITION' => '#^/builders/([\\d]+)/\\??#',
    'RULE' => 'ID=$1&',
    'ID' => '',
    'PATH' => '/builders/detail.php',
    'SORT' => '100',
  ),
  18 => 
  array (
    'CONDITION' => '#^/acrit.exportpro/(.*)#',
    'RULE' => 'path=$1',
    'ID' => '',
    'PATH' => '/acrit.exportpro/index.php',
    'SORT' => 100,
  ),
  19 => 
  array (
    'CONDITION' => '#^/online/(/?)([^/]*)#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/desktop_app/router.php',
    'SORT' => '100',
  ),
  20 => 
  array (
    'CONDITION' => '#^/agents/([\\d]+)/\\??#',
    'RULE' => 'ID=$1&',
    'ID' => '',
    'PATH' => '/agents/detail.php',
    'SORT' => '100',
  ),
  21 => 
  array (
    'CONDITION' => '#^/arend/([^/]+)/\\??#',
    'RULE' => 'catalog=$1&ID=$2&',
    'ID' => '',
    'PATH' => '/arend/index.php',
    'SORT' => '100',
  ),
  22 => 
  array (
    'CONDITION' => '#^/sell/([^/]+)/\\??#',
    'RULE' => 'catalog=$1&ID=$2&',
    'ID' => '',
    'PATH' => '/sell/index.php',
    'SORT' => '100',
  ),
  23 => 
  array (
    'CONDITION' => '#^/arend/([^/]+)/$#',
    'RULE' => 'catalog=$1&ID=$2&',
    'ID' => '',
    'PATH' => '/arend/index.php',
    'SORT' => '100',
  ),
  24 => 
  array (
    'CONDITION' => '#^/sell/([^/]+)/$#',
    'RULE' => 'catalog=$1&ID=$2&',
    'ID' => '',
    'PATH' => '/sell/index.php',
    'SORT' => '100',
  ),
  25 => 
  array (
    'CONDITION' => '#^/info/news/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/info/news/index.php',
    'SORT' => '100',
  ),
  26 => 
  array (
    'CONDITION' => '#^/contacts/#',
    'RULE' => '',
    'ID' => 'bitrix:form',
    'PATH' => '/contacts/index.php',
    'SORT' => 100,
  ),
);
