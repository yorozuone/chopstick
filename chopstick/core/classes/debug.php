<?php
namespace core;

use \core\globalvars;
use \core\view;

class debug {
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public static function alert($message)
    {
        $vars = globalvars::get_value(self::GLOBALVARS_NAME.'_ALERT');
        if ($vars == false)
        {
            $vars = array();
        }
        $vars[] = $message;
        globalvars::set_value(self::GLOBALVARS_NAME.'_ALERT', $vars);
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public static function dump($src)
    {
        $vars = globalvars::get_value(self::GLOBALVARS_NAME.'_DUMP');
        if ($vars == false)
        {
            $vars = array();
        }
        $vars[] = print_r($src, true);
        globalvars::set_value(self::GLOBALVARS_NAME.'_DUMP', $vars);
    }
}
