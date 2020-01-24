<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
\MorealtySale\Packets::UpdateActivePockets(61);
var_dump(\MorealtySale\Ability::totalHowMuchUserCanAdd(61));
die();
\MorealtySale\Packets::UpdateActivePockets();
var_dump(\MorealtySale\Ability::totalHowMuchUserCanAdd());
//$packets = \MorealtySale\Packets::getActivePockets();
my_print_r($packets);
