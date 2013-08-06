<?php

class CssModification extends SimpleORMap {
    
    static public function findMine() {
        $modification = self::findBySQL("user_id = ?", array($GLOBALS['user']->id));
        if (count($modification) > 0) {
            return $modification[0];
        } else {
            $modification = new CssModification();
            $modification['user_id'] = $GLOBALS['user']->id;
            return $modification;
        }
    }
    
    public function __construct($id = null) {
        $this->db_table = "css_modifications";
        parent::__construct($id);
    }
    
}

/*
 CREATE TABLE IF NOT EXISTS `css_modifications` (
  `css_id` varchar(32) NOT NULL,
  `css` text NOT NULL,
  `user_id` varchar(32) NOT NULL,
  `chdate` bigint(20) NOT NULL,
  `mkdate` bigint(20) NOT NULL,
  PRIMARY KEY (`css_id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB
 */