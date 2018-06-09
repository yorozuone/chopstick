<?php
namespace core;

class globalvars
{
    // --------------------------------------------------------------------------------
    // キャッシュ登録
    // --------------------------------------------------------------------------------
    public static function set_value($key, $value)
    {
        $GLOBALS[CS_APPLICATION_KEY][$key] = $value;
        return true;
    }
    //
    //
    //
    public static function set_values($values)
    {
        if (is_array($values))
        {
            foreach($values as $k => $v)
            {
                $GLOBALS[CS_APPLICATION_KEY][$k] = $v;
            }
        }
        return true;
    }
    // --------------------------------------------------------------------------------
    // キャッシュ取得
    // --------------------------------------------------------------------------------
    public static function get_value($key)
    {
        if (isset($GLOBALS[CS_APPLICATION_KEY][$key]))
        {
            return $GLOBALS[CS_APPLICATION_KEY][$key];
        }
        return false;
    }
    //
    //
    //
    public static function get_values()
    {
        if (isset($GLOBALS[CS_APPLICATION_KEY]))
        {
            return $GLOBALS[CS_APPLICATION_KEY];
        }
        return false;
    }
}
