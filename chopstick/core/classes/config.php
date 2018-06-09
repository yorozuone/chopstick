<?php
namespace core;

use \core\debug;

class config
{
    //
    // --------------------------------------------------------------------------------
    // config を読み込む
    // --------------------------------------------------------------------------------
    //
    public static function read($config_name)
    {
        $config = array();
        foreach(array('core', 'app', 'site') as $v)
        {
            $tmp_config = self::read_config(CS_BASE_DIR.$v.'/config/'  .$config_name.'.php');
            $config = array_replace_recursive($config, $tmp_config);
        }
        return $config;
    }
    //
    // --------------------------------------------------------------------------------
    // config をファイルから読み込む
    // --------------------------------------------------------------------------------
    //
    private static function read_config($filename)
    {
        if (is_readable($filename) == false) 
        {
            return array();
        }
        return require($filename);
    }
}
