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

    protected static function configure($config = array())
    {
        $config['db_table'] = 'css_modifications';
        parent::configure($config);
    }

}


