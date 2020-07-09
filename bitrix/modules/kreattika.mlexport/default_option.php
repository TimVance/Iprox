<?
##########################################################################
# Name: kreattika.mlexport                                               #
# http://marketplace.1c-bitrix.ru/solutions/kreattika.mlexport/          #
# File: default_option.php                                               #
# Version: 1.0.5                                                         #
# (c) 2011-2016 Kreattika, Sedov S.Y.                                    #
# Proprietary licensed                                                   #
# http://kreattika.ru/                                                   #
# mailto:info@kreattika.ru                                               #
##########################################################################
?><?
$kreattika_mlexport_default_option = array(
    "mlexport_on"                       => "Y",
    "mlexport_run"                      => "AGENT",
    "mlexport_period"                   => "DAY",
    "mlexport_time"                     => "30",
    "mlexport_del_old"                  => "Y",
    "mlexport_del_days"                 => "90",
    "mlexport_log_del_old"              => "Y",
    "mlexport_log_del_days"             => "90",
    "mlexport_reload_on_active_job"     => "Y",
    "mlexport_record_limit"             => "100",
    "mlexport_check_elements"           => "Y",
    "mlexport_check_agent"              => "Y",
    "mlexport_last_check_agent_ts"      => "",
    "mlexport_max_lock_time"            => "86400",
    "mlexport_folder_path"              => "upload/mlexport/",
    "mlexport_fale_name_prefix"         => "catalog",
    "mlexport_include_external_classes" => "N",
);
?>