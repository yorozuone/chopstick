<?php
namespace core;

class response
{
    // --------------------------------------------------------------------------------
    // 指定したURLへリダイレクトします
    // --------------------------------------------------------------------------------
    public static function redirect($url)
    {
        debug::trace('[core/response/redirect] : 開始');
        header('Location:'.$url);
        die();
    }
}
