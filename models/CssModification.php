<?php

class CssModification extends SimpleORMap
{

    static public function findMine()
    {
        $modification = self::findBySQL("user_id = ?", [$GLOBALS['user']->id]);
        if (count($modification) > 0) {
            return $modification[0];
        } else {
            $modification            = new CssModification();
            $modification['user_id'] = $GLOBALS['user']->id;
            return $modification;
        }
    }

    public function __construct($id = null)
    {
        $this->db_table = "css_modifications";
        parent::__construct($id);
    }
}


