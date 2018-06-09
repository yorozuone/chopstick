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
        return hash(self::HASH_ALGO, session_id());
    }
    // --------------------------------------------------------------------------------
    // CSRF トークをチェックする
    // --------------------------------------------------------------------------------
    public static function check()
    {
        $input = input::post('csrf_token');
        if ($input['csrf_token'] != self::create_token())
        {
            return false;
        }
        return true;
    }
}
