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
        $_SESSION[CS_APPLICATION_KEY][$key] = $value; 
    } 
    // --------------------------------------------------------------------------------
    // セッション取得
    // --------------------------------------------------------------------------------
    public static function get_value($key)
    {
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
        unset($_SESSION[CS_APPLICATION_KEY]);
    }
}
