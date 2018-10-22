<?php
namespace core;

use \core\config;

class sessionvars
{
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public static function set_value($key, $value)
    {
        debug::trace('[core/sessionvars/set_value] : 開始');
        $_SESSION[CS_APPLICATION_KEY][$key] = $value; 
    } 
    // --------------------------------------------------------------------------------
    // セッション取得
    // --------------------------------------------------------------------------------
    public static function get_value($key)
    {
        debug::trace('[core/sessionvars/get_value] : 開始');
        if (isset($_SESSION[CS_APPLICATION_KEY][$key]))
        {
          return $_SESSION[CS_APPLICATION_KEY][$key];
        }
        else
        {
          return false;
        }
    } 
    // --------------------------------------------------------------------------------
    // すべてのセッション取得
    // --------------------------------------------------------------------------------
    public static function get_values()
    {
        debug::trace('[core/sessionvars/get_values] : 開始');
        if (!isset($_SESSION[CS_APPLICATION_KEY]))
        {
            return false;
        }
        return $_SESSION[CS_APPLICATION_KEY];
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public static function destroy()
    {
        debug::trace('[core/sessionvars/destroy] : 開始');
        unset($_SESSION[CS_APPLICATION_KEY]);
    }
}
