<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1454002006.
 * Generated on 2016-01-28 18:26:46 by mike
 */
class PropelMigration_1454002006
{

    public function preUp($manager)
    {
        // add the pre-migration code here
    }

    public function postUp($manager)
    {
        // add the post-migration code here
    }

    public function preDown($manager)
    {
        // add the pre-migration code here
    }

    public function postDown($manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `temp_contract`;

ALTER TABLE `bookk_entry` CHANGE `value` `value` FLOAT;

ALTER TABLE `cost` CHANGE `value` `value` FLOAT;

ALTER TABLE `cost_doc` CHANGE `value` `value` FLOAT;

DROP INDEX `fos_user_U_1` ON `fos_user`;

DROP INDEX `fos_user_U_2` ON `fos_user`;

ALTER TABLE `income` CHANGE `value` `value` FLOAT;

ALTER TABLE `income_doc` CHANGE `value` `value` FLOAT;

ALTER TABLE `parameter`
    ADD `value_bool` TINYINT(1) AFTER `value_int`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `bookk_entry` CHANGE `value` `value` DOUBLE;

ALTER TABLE `cost` CHANGE `value` `value` DOUBLE;

ALTER TABLE `cost_doc` CHANGE `value` `value` DOUBLE;

CREATE UNIQUE INDEX `fos_user_U_1` ON `fos_user` (`username_canonical`);

CREATE UNIQUE INDEX `fos_user_U_2` ON `fos_user` (`email_canonical`);

ALTER TABLE `income` CHANGE `value` `value` DOUBLE;

ALTER TABLE `income_doc` CHANGE `value` `value` DOUBLE;

ALTER TABLE `parameter` DROP `value_bool`;

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

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}