<?php
namespace core;

class log
{
    //
    // --------------------------------------------------------------------------------
    // ログを出力
    // --------------------------------------------------------------------------------
    //
    public static function write($msg, $level='notice')
    {
        $config = config::read('log');
        //
        $fn = $config['path'].date('Ymd').'.txt';
        //
        error_log($msg."\r\n\r\n", 3, $fn);
    }
}
