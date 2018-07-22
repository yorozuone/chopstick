<?php
namespace core;

use \core\config;

class theme
{
    public static function get_current()
    {
        $conf = config::read('theme');
        return isset($conf['theme']) ? $conf['theme'] : 'default';
    }
}