<?php
namespace core;

class input
{
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public static function post($field_names)
    {
        return input::read($_POST, $field_names);        
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public static function get($field_names)
    {
        return input::read($_GET, $field_names);        
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public static function read($source, $field_names)
    {
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
