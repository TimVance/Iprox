<?
##########################################################################
# Name: kreattika.mlexport                                               #
# http://marketplace.1c-bitrix.ru/solutions/kreattika.mlexport/          #
# File: options.php                                                      #
# Version: 1.0.5                                                         #
# (c) 2011-2016 Kreattika, Sedov S.Y.                                    #
# Proprietary licensed                                                   #
# http://kreattika.ru/                                                   #
# mailto:info@kreattika.ru                                               #
##########################################################################
?>
<?
$MESS['KREATTIKA_AGENSY'] = "Магазин готовых решений для 1С-Битрикс";
$MESS['KREATTIKA_IS_DEMO'] = "Модуль работает в демонстрационном режиме!";
$MESS['KREATTIKA_IS_DEMO_EXPIRED'] = "ВНИМАНИЕ! Демонстрационный период работы модуля окончен! Модуль НЕ работает!";
$MESS['KREATTIKA_FOOL_VERSION_BUY'] = "Купить полную версию";
$MESS['KREATTIKA_MLEXPORT_DESCR'] = 'Модуль позволяет автоматически создавать файлы экспорта товаров для Яндекс, Гугл и др. с указанной периодичностью';
$MESS['KREATTIKA_MLEXPORT_SET_ON'] = "Автоматическая генерация файлов экспорта включена";
$MESS['KREATTIKA_MLEXPORT_SET_OPT_JOB_TITLE'] = 'Параметры <a href="/bitrix/admin/kreattika_mlexport_list.php?lang='.LANGUAGE_ID.'">журнала задач</a>';
$MESS['KREATTIKA_MLEXPORT_DEL_OLD'] = "Удалять устаревшие записи";
$MESS['KREATTIKA_MLEXPORT_DEL_DAYS'] = "Удалять записи старше, дней";
$MESS['KREATTIKA_MLEXPORT_SET_OPT_LOG_TITLE'] = 'Параметры <a href="/bitrix/admin/kreattika_mlexport_log.php?lang='.LANGUAGE_ID.'">лога</a>';
$MESS['KREATTIKA_MLEXPORT_LOG_DEL_OLD'] = "Удалять устаревшие записи лога";
$MESS['KREATTIKA_MLEXPORT_LOG_DEL_DAYS'] = "Удалять записи лога старше, дней";
$MESS['KREATTIKA_MLEXPORT_RELOAD_ON_ACTIVE_JOB'] = "Автоматически обновлять страницу списка при активной задаче";
$MESS['KREATTIKA_MLEXPORT_SET_OPT_TITLE'] = 'Параметры <a href="/bitrix/admin/kreattika_mlexport_profile_list.php?lang='.LANGUAGE_ID.'">профилей</a> (по умолчанию)';
$MESS['KREATTIKA_MLEXPORT_RUN'] = "Запуск модуля:";
$MESS['KREATTIKA_MLEXPORT_RUN_AGENT'] = "Агент";
$MESS['KREATTIKA_MLEXPORT_RUN_CRON'] = "Cron";
$MESS['KREATTIKA_MLEXPORT_SET_PERIOD'] = "Период создания файлов экспорта:";
$MESS['KREATTIKA_MLEXPORT_SET_FOLDER_PATH'] = "Путь до папки выгрузки:";
$MESS['KREATTIKA_MLEXPORT_SET_TIME'] = "Максимальное время одного шага в секундах:";
$MESS['KREATTIKA_MLEXPORT_SET_RECORD_LIMIT'] = "Максимальное количество записей, обрабатываемых за один шаг:";
$MESS['KREATTIKA_MLEXPORT_SET_PERIOD_HOUR'] = 'Час';
$MESS['KREATTIKA_MLEXPORT_SET_PERIOD_DAY'] = 'День';
$MESS['KREATTIKA_MLEXPORT_SET_PERIOD_WEEK'] = 'Неделя';
$MESS['KREATTIKA_MLEXPORT_SET_PERIOD_MONTH'] = 'Месяц';
$MESS['KREATTIKA_MLEXPORT_SET_INCLUDE_EXTERNAL_CLASSES'] = "Подключать файлы внешних классов (подключаемые шаблоны)";
$MESS['KREATTIKA_MLEXPORT_MODULE_DEACTIVATE_NOTE'] = '<span style="color: red;">Модуль деактивирован! файлы экспорта НЕ формируюятся.</span>';
$MESS['KREATTIKA_MLEXPORT_PROFILE_NOT_ACTIVATE_NOTE'] = 'Ни один из профилей не активен! Для начала формирования файлов экспорта, активируйте необходимые <a href="/bitrix/admin/kreattika_mlexport_profile_list.php?lang='.LANGUAGE_ID.'">профили</a>.';
$MESS['KREATTIKA_MLEXPORT_CRON_NOTE'] = 'Для работы модуля необходимо на веб-сервере создать регулярное выполнение php файла на Cron, с периодом запуска #CRON_TIME# минут!<br/>строка запуска: <code><span style="color: #000000;">#CRON_PATH_NAME#</span></code>';
$MESS['KREATTIKA_MLEXPORT_CHECK_AGENT_NOTE'] = 'Проверка расписания: <span style="color: darkgreen;">следующий запуск агента #AGENT_NEXT_EXEC#</span>';
$MESS['KREATTIKA_MLEXPORT_CHECK_AGENT_NOT_EXIST_NOTE'] = 'Проверка расписания: <span style="color: red;">агент не обнаружен! Очередной запуск <b>НЕ будет осуществлен!</b></span>';
?>
