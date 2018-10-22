<?php
namespace core;

class input
{
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public static function post($field_names)
    {
        debug::trace('[core/input/post] : 開始');
        return input::read($_POST, $field_names);        
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public static function get($field_names)
    {
        debug::trace('[core/input/get] : 開始');
        return input::read($_GET, $field_names);        
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public static function read($source, $field_names)
    {
        debug::trace('[core/input/read] : 開始');
        if (!is_array($field_names))
        {
            $field_names = array($field_names);
        }
        $vars = array();
        foreach($field_names as $v)
        {
            if (isset($source[$v]))
            {
                $vars[$v] = $source[$v];
            }
        }
        return $vars;
    }
}
