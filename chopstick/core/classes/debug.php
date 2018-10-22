<?php
namespace core;

use \core\globalvars;
use \core\view;

class debug {
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public static function trace($message)
    {
        // debug::trace('[core/debug/alert] : 開始');
        $core_debug_message = globalvars::get_value('core_debug_message');
        $core_debug_message[] = $message;
        globalvars::set_value('core_debug_message', $core_debug_message);
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public static function alert($message)
    {
        debug::trace('[core/debug/alert] : 開始');
        die($message);
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public static function dump($mode=false)
    {
        debug::trace('[core/debug/dump] : 開始');
        $core_debug_message = implode('<br>',array_reverse(globalvars::get_value('core_debug_message')));
        if ($mode == true)
        {
            return $core_debug_message; 
        }
        else
        {
            die($core_debug_message);
        }
    }
}
