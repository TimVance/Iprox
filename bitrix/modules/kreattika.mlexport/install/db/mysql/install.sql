create table if not exists `b_kreattika_ml_export_entity` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `LOCK` CHAR(1) NOT NULL,
  `STATUS` VARCHAR(50) NOT NULL,
  `PROFILE_ID` int(11) NOT NULL,
  `CREATE_NS` TEXT NULL,
  `CREATE_ELEMENT_NS` LONGTEXT NULL,
  `START` datetime NULL,
  `END` datetime NULL,
  `LAST_START` datetime NULL,
  `COMMENT` VARCHAR(255) NULL,
  PRIMARY KEY (`ID`)
);

create table if not exists `b_kreattika_ml_export_services_entity` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ACTIVE` CHAR(1) NOT NULL,
  `SORT` int DEFAULT 500,
  `CODE` VARCHAR(50) NOT NULL,
  `NAME` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`ID`)
);

create table if not exists `b_kreattika_ml_export_services_classes_entity` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ACTIVE` CHAR(1) NOT NULL,
  `SORT` int DEFAULT 500,
  `CODE` VARCHAR(50) NOT NULL,
  `NAME` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`ID`)
);

create table if not exists `b_kreattika_ml_export_profile_entity` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `LOCK` CHAR(1) NOT NULL,
  `ACTIVE` CHAR(1) NOT NULL,
  `SORT` int DEFAULT 500,
  `NAME` VARCHAR(255) NOT NULL,
  `MAX_EXECUTE_TIME` int(11) NOT NULL,
  `RECORD_LIMIT` int(11) NOT NULL,
  `SITE_ID` CHAR(2) NOT NULL,
  `IBLOCK_TYPE` VARCHAR(50) NULL,
  `IBLOCK_ID` int(11) NOT NULL,
  `EXPORT_TO` VARCHAR(50) NOT NULL,
  `TEMPLATE_ID` int(11) NOT NULL,
  `TEMPLATE_FIELD_VALUE` LONGTEXT NULL,
  `EXPORT_ALL` CHAR(1) NOT NULL,
  `EXPORT_ONLY_STORE` CHAR(1) NOT NULL,
  `EXPORT_ONLY_PRICE` CHAR(1) NOT NULL,
  `EXPORT_PRICE_ID` VARCHAR(50) NOT NULL,
  `EXPORT_DISCOUNT` CHAR(1) NULL,
  `EXPORT_PRICE_PROPERTY` VARCHAR(50) NULL,
  `EXPORT_PRICE_CURRENCY` VARCHAR(3) NULL,
  `LOG` LONGTEXT NULL,
  `FOLDER_PATH` VARCHAR(255) NOT NULL,
  `FILE_NAME` VARCHAR(255) NOT NULL,
  `FILE_ENCODE` VARCHAR(25) NOT NULL,
  `CLASS_NAME` VARCHAR(255) NOT NULL,

  PRIMARY KEY (`ID`)
);

create table if not exists `b_kreattika_ml_export_template_entity` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ACTIVE` CHAR(1) NOT NULL,
  `SORT` int DEFAULT 500,
  `NAME` VARCHAR(255) NOT NULL,
  `EXPORT_TO` VARCHAR(50) NOT NULL,
  `CLASS_NAME` VARCHAR(255) NOT NULL,
  `FIELD` TEXT NULL,
  PRIMARY KEY (`ID`)
);

create table if not exists `b_kreattika_ml_export_gpc_entity` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `NAME` VARCHAR(255) NULL,
  PRIMARY KEY (`ID`)
);

create table if not exists `b_kreattika_ml_export_gpc_link_entity` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ACTIVE` CHAR(1) NOT NULL,
  `SORT` int DEFAULT 500,
  `GPC_ID` int(11) NOT NULL,
  `SECTION_ID` int(11) NULL,
  `ELEMENT_ID` int(11) NULL,
  PRIMARY KEY (`ID`)
);

create table if not exists `b_kreattika_ml_export_ypc_entity` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `NAME` VARCHAR(255) NULL,
  PRIMARY KEY (`ID`)
);

create table if not exists `b_kreattika_ml_export_ypc_link_entity` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ACTIVE` CHAR(1) NOT NULL,
  `SORT` int DEFAULT 500,
  `YPC_ID` int(11) NOT NULL,
  `SECTION_ID` int(11) NULL,
  `ELEMENT_ID` int(11) NULL,
  PRIMARY KEY (`ID`)
);

create table if not exists `b_kreattika_ml_export_log_entity` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `DATE` datetime NULL,
  `TYPE` VARCHAR(50) NOT NULL,
  `TEXT` LONGTEXT NULL,
  `DATA` LONGTEXT NULL,
  PRIMARY KEY (`ID`)
);