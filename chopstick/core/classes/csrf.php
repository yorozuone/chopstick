<?php
namespace core;

use \core\input;

class csrf
{
    const HASH_ALGO = 'sha256';
    // --------------------------------------------------------------------------------
    // CSRF トークンを作成
    // --------------------------------------------------------------------------------
    public static function create_token()
    {
        debug::trace('[core/csrf/create_token] : 開始');
        return hash(self::HASH_ALGO, session_id());
    }
    // --------------------------------------------------------------------------------
    // CSRF トークをチェックする
    // --------------------------------------------------------------------------------
    public static function check()
    {
        debug::trace('[core/csrf/check] : 開始');
        $input = input::post('csrf_token');
        if ($input['csrf_token'] != self::create_token())
        {
            return false;
        }
        return true;
    }
}
