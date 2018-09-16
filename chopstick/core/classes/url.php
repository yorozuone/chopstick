<?php
namespace core;

class url
{
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public static function create($url, $params = array())
    {
        $url = ltrim($url, '/');
        $url = rtrim($url, '/');
        if (count($params) > 0)
        {
            $url .= '/'.implode('/', $params);
        }
        //
        return url::base() . $url;
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public static function base()
    {
        $base = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . rtrim($_SERVER['SCRIPT_NAME'], 'index.php');
        // 行末に '/' がない場合は、追加します。
        if (substr($base, -1, 1) != '/') {
            $base .= '/';
        }
        return $base;
    }
}
