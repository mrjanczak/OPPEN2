
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- fos_user
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `fos_user`;

CREATE TABLE `fos_user`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(255),
    `username_canonical` VARCHAR(255),
    `email` VARCHAR(255),
    `email_canonical` VARCHAR(255),
    `enabled` TINYINT(1) DEFAULT 0,
    `salt` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `last_login` DATETIME,
    `locked` TINYINT(1) DEFAULT 0,
    `expired` TINYINT(1) DEFAULT 0,
    `expires_at` DATETIME,
    `confirmation_token` VARCHAR(255),
    `password_requested_at` DATETIME,
    `credentials_expired` TINYINT(1) DEFAULT 0,
    `credentials_expire_at` DATETIME,
    `roles` TEXT,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `fos_user_U_1` (`username_canonical`),
    UNIQUE INDEX `fos_user_U_2` (`email_canonical`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- fos_group
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `fos_group`;

CREATE TABLE `fos_group`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `roles` TEXT,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- fos_user_group
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `fos_user_group`;

CREATE TABLE `fos_user_group`
(
    `fos_user_id` INTEGER NOT NULL,
    `fos_group_id` INTEGER NOT NULL,
    PRIMARY KEY (`fos_user_id`,`fos_group_id`),
    INDEX `fos_user_group_FI_2` (`fos_group_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- year
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `year`;

CREATE TABLE `year`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(10),
    `is_active` TINYINT(1) DEFAULT 0,
    `is_closed` TINYINT(1) DEFAULT 0,
    `from_date` DATE,
    `to_date` DATE,
    `sortable_rank` INTEGER,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- month
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `month`;

CREATE TABLE `month`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(10),
    `is_active` TINYINT(1) DEFAULT 0,
    `is_closed` TINYINT(1) DEFAULT 0,
    `from_date` DATE,
    `to_date` DATE,
    `year_id` INTEGER,
    `sortable_rank` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `month_FI_1` (`year_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- file_cat
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `file_cat`;

CREATE TABLE `file_cat`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100),
    `symbol` VARCHAR(10),
    `as_project` TINYINT(1) DEFAULT 0,
    `as_income` TINYINT(1) DEFAULT 0,
    `as_cost` TINYINT(1) DEFAULT 0,
    `as_contractor` TINYINT(1) DEFAULT 0,
    `is_locked` TINYINT(1) DEFAULT 0,
    `year_id` INTEGER,
    `sub_file_cat_id` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `file_cat_FI_1` (`year_id`),
    INDEX `file_cat_FI_2` (`sub_file_cat_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- file
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `file`;

CREATE TABLE `file`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100),
    `acc_no` INTEGER,
    `file_cat_id` INTEGER,
    `sub_file_id` INTEGER,
    `first_name` VARCHAR(50),
    `second_name` VARCHAR(50),
    `last_name` VARCHAR(50),
    `maiden_name` VARCHAR(50),
    `father_name` VARCHAR(50),
    `mother_name` VARCHAR(50),
    `birth_date` DATE,
    `birth_place` VARCHAR(50),
    `PESEL` VARCHAR(50),
    `Passport` VARCHAR(50),
    `NIP` VARCHAR(50),
    `profession` VARCHAR(50),
    `street` VARCHAR(50),
    `house` VARCHAR(5),
    `flat` VARCHAR(5),
    `code` VARCHAR(6),
    `city` VARCHAR(50),
    `district2` VARCHAR(50),
    `district` VARCHAR(50),
    `province` VARCHAR(50),
    `country` VARCHAR(50),
    `post_office` VARCHAR(50),
    `bank_account` VARCHAR(100),
    `bank_name` VARCHAR(50),
    `phone` VARCHAR(50),
    `email` VARCHAR(50),
    PRIMARY KEY (`id`),
    INDEX `file_FI_1` (`file_cat_id`),
    INDEX `file_FI_2` (`sub_file_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- doc_cat
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `doc_cat`;

CREATE TABLE `doc_cat`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100),
    `symbol` VARCHAR(10),
    `doc_no_tmp` VARCHAR(20),
    `as_income` TINYINT(1) DEFAULT 0,
    `as_cost` TINYINT(1) DEFAULT 0,
    `as_bill` TINYINT(1) DEFAULT 0,
    `is_locked` TINYINT(1) DEFAULT 0,
    `year_id` INTEGER,
    `file_cat_id` INTEGER,
    `commitment_acc_id` INTEGER,
    `tax_commitment_acc_id` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `doc_cat_FI_1` (`year_id`),
    INDEX `doc_cat_FI_2` (`file_cat_id`),
    INDEX `doc_cat_FI_3` (`commitment_acc_id`),
    INDEX `doc_cat_FI_4` (`tax_commitment_acc_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- doc
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `doc`;

CREATE TABLE `doc`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `document_date` DATE,
    `operation_date` DATE,
    `receipt_date` DATE,
    `bookking_date` DATE,
    `payment_deadline_date` DATE,
    `payment_date` DATE,
    `payment_method` INTEGER,
    `month_id` INTEGER,
    `doc_cat_id` INTEGER,
    `reg_idx` INTEGER,
    `reg_no` VARCHAR(20),
    `doc_idx` INTEGER,
    `doc_no` VARCHAR(20),
    `file_id` INTEGER,
    `user_id` INTEGER,
    `desc` VARCHAR(500),
    `comment` TEXT,
    PRIMARY KEY (`id`),
    INDEX `doc_FI_1` (`month_id`),
    INDEX `doc_FI_2` (`doc_cat_id`),
    INDEX `doc_FI_3` (`file_id`),
    INDEX `doc_FI_4` (`user_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- bookk
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `bookk`;

CREATE TABLE `bookk`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `no` INTEGER,
    `desc` VARCHAR(500),
    `is_accepted` TINYINT(1) DEFAULT 0,
    `bookking_date` DATE,
    `doc_id` INTEGER,
    `project_id` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `bookk_FI_1` (`doc_id`),
    INDEX `bookk_FI_2` (`project_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- bookk_entry
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `bookk_entry`;

CREATE TABLE `bookk_entry`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `acc_no` VARCHAR(20),
    `value` FLOAT,
    `side` TINYINT,
    `bookk_id` INTEGER,
    `account_id` INTEGER,
    `file_lev1_id` INTEGER,
    `file_lev2_id` INTEGER,
    `file_lev3_id` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `bookk_entry_FI_1` (`bookk_id`),
    INDEX `bookk_entry_FI_2` (`account_id`),
    INDEX `bookk_entry_FI_3` (`file_lev1_id`),
    INDEX `bookk_entry_FI_4` (`file_lev2_id`),
    INDEX `bookk_entry_FI_5` (`file_lev3_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- account
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `account`;

CREATE TABLE `account`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `acc_no` VARCHAR(100),
    `name` VARCHAR(100),
    `report_side` TINYINT,
    `as_bank_acc` TINYINT(1) DEFAULT 0,
    `as_income` TINYINT(1) DEFAULT 0,
    `as_cost` TINYINT(1) DEFAULT 0,
    `inc_open_b` TINYINT(1) DEFAULT 0,
    `inc_close_b` TINYINT(1) DEFAULT 0,
    `as_close_b` TINYINT(1) DEFAULT 0,
    `year_id` INTEGER,
    `file_cat_lev1_id` INTEGER,
    `file_cat_lev2_id` INTEGER,
    `file_cat_lev3_id` INTEGER,
    `tree_left` INTEGER,
    `tree_right` INTEGER,
    `tree_level` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `account_FI_1` (`year_id`),
    INDEX `account_FI_2` (`file_cat_lev1_id`),
    INDEX `account_FI_3` (`file_cat_lev2_id`),
    INDEX `account_FI_4` (`file_cat_lev3_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- report
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `report`;

CREATE TABLE `report`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100),
    `shortname` VARCHAR(10) NOT NULL,
    `is_locked` TINYINT(1) DEFAULT 0,
    `year_id` INTEGER,
    `template_id` INTEGER,
    `sortable_rank` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `report_FI_1` (`year_id`),
    INDEX `report_FI_2` (`template_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- report_entry
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `report_entry`;

CREATE TABLE `report_entry`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `no` VARCHAR(50) NOT NULL,
    `name` VARCHAR(500) NOT NULL,
    `symbol` VARCHAR(10),
    `formula` VARCHAR(100),
    `report_id` INTEGER,
    `tree_left` INTEGER,
    `tree_right` INTEGER,
    `tree_level` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `report_entry_FI_1` (`report_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- template
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `template`;

CREATE TABLE `template`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100),
    `symbol` VARCHAR(100),
    `type` INTEGER,
    `as_contract` TINYINT(1) DEFAULT 0,
    `as_report` TINYINT(1) DEFAULT 0,
    `as_booking` TINYINT(1) DEFAULT 0,
    `as_transfer` TINYINT(1) DEFAULT 0,
    `contents` TEXT,
    `data` TEXT,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- project
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `project`;

CREATE TABLE `project`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(250) NOT NULL,
    `status` INTEGER,
    `desc` TEXT,
    `place` VARCHAR(100),
    `from_date` DATE,
    `to_date` DATE,
    `comment` TEXT,
    `year_id` INTEGER,
    `file_id` INTEGER,
    `file_cat_id` INTEGER,
    `income_acc_id` INTEGER,
    `cost_acc_id` INTEGER,
    `bank_acc_id` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `project_FI_1` (`year_id`),
    INDEX `project_FI_2` (`file_id`),
    INDEX `project_FI_3` (`file_cat_id`),
    INDEX `project_FI_4` (`income_acc_id`),
    INDEX `project_FI_5` (`cost_acc_id`),
    INDEX `project_FI_6` (`bank_acc_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- income
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `income`;

CREATE TABLE `income`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `shortname` VARCHAR(10) NOT NULL,
    `value` FLOAT,
    `comment` TEXT,
    `show` TINYINT(1) DEFAULT 1,
    `project_id` INTEGER,
    `file_id` INTEGER,
    `bank_acc_id` INTEGER,
    `income_acc_id` INTEGER,
    `sortable_rank` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `income_FI_1` (`project_id`),
    INDEX `income_FI_2` (`file_id`),
    INDEX `income_FI_3` (`bank_acc_id`),
    INDEX `income_FI_4` (`income_acc_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- cost
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `cost`;

CREATE TABLE `cost`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `value` FLOAT,
    `comment` TEXT,
    `project_id` INTEGER,
    `file_id` INTEGER,
    `bank_acc_id` INTEGER,
    `cost_acc_id` INTEGER,
    `sortable_rank` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `cost_FI_1` (`project_id`),
    INDEX `cost_FI_2` (`file_id`),
    INDEX `cost_FI_3` (`bank_acc_id`),
    INDEX `cost_FI_4` (`cost_acc_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- income_doc
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `income_doc`;

CREATE TABLE `income_doc`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `value` FLOAT,
    `desc` VARCHAR(500),
    `income_id` INTEGER,
    `doc_id` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `income_doc_FI_1` (`income_id`),
    INDEX `income_doc_FI_2` (`doc_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- cost_income
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `cost_income`;

CREATE TABLE `cost_income`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `value` FLOAT,
    `cost_id` INTEGER,
    `income_id` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `cost_income_FI_1` (`cost_id`),
    INDEX `cost_income_FI_2` (`income_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- cost_doc
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `cost_doc`;

CREATE TABLE `cost_doc`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `value` FLOAT,
    `desc` VARCHAR(500),
    `cost_id` INTEGER,
    `doc_id` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `cost_doc_FI_1` (`cost_id`),
    INDEX `cost_doc_FI_2` (`doc_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- cost_doc_income
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `cost_doc_income`;

CREATE TABLE `cost_doc_income`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `value` FLOAT,
    `cost_doc_id` INTEGER,
    `income_id` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `cost_doc_income_FI_1` (`cost_doc_id`),
    INDEX `cost_doc_income_FI_2` (`income_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- contract
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `contract`;

CREATE TABLE `contract`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `contract_no` VARCHAR(20),
    `contract_date` DATE,
    `contract_place` VARCHAR(20),
    `event_desc` VARCHAR(1000),
    `event_date` DATE,
    `event_place` VARCHAR(100),
    `event_name` VARCHAR(100),
    `event_role` VARCHAR(100),
    `gross` FLOAT,
    `income_cost` FLOAT,
    `tax` FLOAT,
    `netto` FLOAT,
    `tax_coef` FLOAT,
    `cost_coef` FLOAT,
    `payment_period` VARCHAR(16),
    `payment_method` INTEGER,
    `comment` TEXT,
    `cost_id` INTEGER,
    `template_id` INTEGER,
    `file_id` INTEGER,
    `doc_id` INTEGER,
    `month_id` INTEGER,
    `sortable_rank` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `contract_FI_1` (`cost_id`),
    INDEX `contract_FI_2` (`template_id`),
    INDEX `contract_FI_3` (`file_id`),
    INDEX `contract_FI_4` (`doc_id`),
    INDEX `contract_FI_5` (`month_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- temp_contract
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `temp_contract`;

CREATE TABLE `temp_contract`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `contract_no` VARCHAR(20),
    `contract_date` DATE,
    `contract_place` VARCHAR(20),
    `event_desc` VARCHAR(1000),
    `event_date` DATE,
    `event_place` VARCHAR(100),
    `event_name` VARCHAR(100),
    `event_role` VARCHAR(100),
    `gross` FLOAT,
    `income_cost` FLOAT,
    `tax` FLOAT,
    `netto` FLOAT,
    `first_name` VARCHAR(50),
    `last_name` VARCHAR(50),
    `PESEL` VARCHAR(50),
    `NIP` VARCHAR(50),
    `street` VARCHAR(50),
    `house` VARCHAR(5),
    `flat` VARCHAR(5),
    `code` VARCHAR(6),
    `city` VARCHAR(50),
    `district` VARCHAR(50),
    `country` VARCHAR(50),
    `bank_account` VARCHAR(32),
    `bank_name` VARCHAR(50),
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- task
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `task`;

CREATE TABLE `task`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `desc` VARCHAR(100) NOT NULL,
    `from_date` DATE,
    `to_date` DATE,
    `comment` TEXT,
    `send_reminder` TINYINT(1) DEFAULT 0,
    `user_id` INTEGER,
    `project_id` INTEGER,
    `sortable_rank` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `task_FI_1` (`user_id`),
    INDEX `task_FI_2` (`project_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- parameter
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `parameter`;

CREATE TABLE `parameter`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `label` VARCHAR(100) NOT NULL,
    `field_type` VARCHAR(10) NOT NULL,
    `value_float` FLOAT,
    `value_int` INTEGER,
    `value_varchar` VARCHAR(255),
    `value_date` DATE,
    `sortable_rank` INTEGER,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- template_archive
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `template_archive`;

CREATE TABLE `template_archive`
(
    `id` INTEGER NOT NULL,
    `name` VARCHAR(100),
    `symbol` VARCHAR(100),
    `type` INTEGER,
    `as_contract` TINYINT(1) DEFAULT 0,
    `as_report` TINYINT(1) DEFAULT 0,
    `as_booking` TINYINT(1) DEFAULT 0,
    `as_transfer` TINYINT(1) DEFAULT 0,
    `contents` TEXT,
    `data` TEXT,
    `archived_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
