<?php
namespace core;

class globalvars
{
    // --------------------------------------------------------------------------------
    // キャッシュ登録
    // --------------------------------------------------------------------------------
    public static function set_value($key, $value)
    {
        // debug::trace('[core/globalvars/set_value] : 開始');
        $GLOBALS[CS_APPLICATION_KEY][$key] = $value;
        return true;
    }
    //
    //
    //
    public static function set_values($values)
    {
        // debug::trace('[core/globalvars/set_values] : 開始');
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
        // debug::trace('[core/globalvars/get_value] : 開始');
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
        // debug::trace('[core/globalvars/get_values] : 開始');
        if (isset($GLOBALS[CS_APPLICATION_KEY]))
        {
            return $GLOBALS[CS_APPLICATION_KEY];
        }
        return false;
    }
}
