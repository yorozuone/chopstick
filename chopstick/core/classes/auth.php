<?php
namespace core;

use \core\db;
use \core\config;
use \core\fieldset;
use \core\session;

class auth
{
    const GLOBALVARS_NAME = 'CORE_AUTH';
    // --------------------------------------------------------------------------------
    // 認証できる username, password かどうか確認（ログイン処理は実行しない）
    // --------------------------------------------------------------------------------
    public static function is_auth($username, $password)
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    user_id,
    username,
    password_hash
FROM
    cs_user
WHERE
    username = :username
EOT;
        $params = array
        (
            'username' => $username,
        );
        //
        $rs = $con->query($sql, $params);
        //
        if (isset($rs[0]) == false)
        {
            return false;
        }
        return password_verify($password, $rs[0]['password_hash']);
    }
    // --------------------------------------------------------------------------------
    // ログイン処理
    // --------------------------------------------------------------------------------
    public static function login($username, $password)
    {
        if (self::is_auth($username, $password) === false)
        {
            return false;
        }
        //
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    user_id
FROM
    cs_user
WHERE
    username = :username
EOT;
        $params = array
        (
            'username' => $username,
        );
        $rs = $con->query($sql, $params);
        //
        if (isset($rs[0]))
        {
            $core_auth = array
            (
                'is_login' => 1,
                'user_id' => $rs[0]['user_id'],
                'username' => $username,
            );
            sessionvars::set_value(self::GLOBALVARS_NAME, $core_auth);
        }
        //
        session_regenerate_id();
        //
        return true;
    }
    // --------------------------------------------------------------------------------
    // 認証状態かどうかを確認
    // --------------------------------------------------------------------------------
    public static function check()
	{
        $core_auth = sessionvars::get_value(self::GLOBALVARS_NAME);
        if (!isset($core_auth))
        {
            return false;
        }
        if ($core_auth['is_login'] != 1)
        {
            return false;
        }
        return true;
    }
    // --------------------------------------------------------------------------------
    // ログアウト
    // --------------------------------------------------------------------------------
    public static function logout()
	{
        sessionvars::destroy();
    }
    // --------------------------------------------------------------------------------
    // 認証情報読込
    // --------------------------------------------------------------------------------
    public static function read()
    {
        if (!self::check())
        {
            return false;
        }
        return sessionvars::get_value(self::GLOBALVARS_NAME);
    }
}
