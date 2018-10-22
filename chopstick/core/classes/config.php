<?php
namespace core;

use \core\debug;

class config
{
    // --------------------------------------------------------------------------------
    // config を読み込む
    // --------------------------------------------------------------------------------
    public static function read($config_name)
    {
        debug::trace('[core/config/read] : 開始 : '.$config_name.' を読込');
        $config = array();
        foreach(array('core', 'app', 'site') as $v)
        {
            $tmp_config = self::read_config(CS_BASE_DIR.$v.'/config/'  .$config_name.'.php');
            $config = array_replace_recursive($config, $tmp_config);
        }
        return $config;
    }
    // --------------------------------------------------------------------------------
    // config をファイルから読み込む
    // --------------------------------------------------------------------------------
    private static function read_config($filename)
    {
        debug::trace('[core/config/read_config] : 開始 : '.$filename.' を読込');
        if (is_readable($filename) == false) 
        {
            return array();
        }
        return require($filename);
    }
}
