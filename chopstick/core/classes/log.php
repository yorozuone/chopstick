<?php
namespace core;

class log
{
    // --------------------------------------------------------------------------------
    // ログを出力
    // --------------------------------------------------------------------------------
    public static function write($log, $level='notice')
    {
        debug::trace('[core/log/write] : 開始');
        $config = config::read('log');
        $t = time();
        $fn = $config['path'].date('Ymd', $t).'.txt';
        $log = str_replace("\r", '', $log);
        $log = str_replace("\n", '', $log);
        error_log(date('Y-m-d H:i:s', $t).':'.$log.PHP_EOL, 3, $fn);
    }
}