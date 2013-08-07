<?php
class InitPlugin extends DBMigration
{
    function up() 
    {
        DBManager::get()->exec("
            CREATE TABLE IF NOT EXISTS `css_modifications` (
                `css_id` varchar(32) NOT NULL,
                `css` text NOT NULL,
                `user_id` varchar(32) NOT NULL,
                `chdate` bigint(20) NOT NULL,
                `mkdate` bigint(20) NOT NULL,
                PRIMARY KEY (`css_id`),
                UNIQUE KEY `user_id` (`user_id`)
          ) ENGINE=InnoDB
        ");
    }
}