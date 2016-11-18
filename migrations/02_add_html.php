<?php

class AddHtml extends Migration
{
    public function up()
    {
        DBManager::get()->exec("
            ALTER TABLE `css_modifications`
            ADD COLUMN `html` TEXT NULL DEFAULT NULL AFTER `css`
        ");

        SimpleORMap::expireTableScheme();
    }

    public function down()
    {
        DBManager::get()->exec("
            ALTER TABLE `css_modifications`
            DROP COLUMN `html`
        ");

        SimpleORMap::expireTableScheme();
    }
}