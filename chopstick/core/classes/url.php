<?php
namespace core;

class url
{
    //
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    //
    public static function create($url, $params = array())
    {
        // 文字列先頭が、'/' の場合、削除します。
        if (substr($url, 0, 1) == '/')
        {
            $url = substr($url, 1);
        }
        // $param が指定されている場合、URL に追加します。
        if (count($params) > 0)
        {
            if (substr($url,-1) != '/')
            {
                $url .= '/';
            }
            $url .= implode('/', $params);
        }
        // URL をデコードして終了
        return url::base() . $url;
    }
    //
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    //
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
