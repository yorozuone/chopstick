<?php
namespace core;

use \core\auth;
use \core\db;
use \core\globalvars;

class acl
{
    const GLOBALVARS_NAME = 'CS_CORE_ACL';
    // --------------------------------------------------------------------------------
    // ログイン中のユーザーの、ロール設定情報を取得
    // --------------------------------------------------------------------------------
    public static function get_all()
    {
        // ------------------------------------------------------------
        // ログイン中のユーザー情報取得
        // ------------------------------------------------------------
        $rs_auth = auth::read();
        if ($rs_auth == false)
        {
            return false;
        }
        // ------------------------------------------------------------
        // ロール情報取得
        // ------------------------------------------------------------
        $rs_roll = globalvars::get_value(self::GLOBALVARS_NAME);
        if ($rs_roll == false)
        {
            $con = new db();
            //
            $sql = <<< EOT
SELECT
    cs_group_roll.roll_key
FROM
    cs_user
LEFT JOIN
    cs_group_roll
ON
    cs_user.group_key = cs_group_roll.group_key
WHERE
    cs_user.user_id = :user_id
EOT;
            $sql_params = array
            (
                ':user_id' => $rs_auth['user_id'],
            );
            $rs_roll = $con->query($sql, $sql_params);
            //
            globalvars::set_value(self::GLOBALVARS_NAME, $rs_roll);
        }
        return array_column($rs_roll, 'roll_key');
    }
    // --------------------------------------------------------------------------------
    // ログイン中のユーザーが、roll_key の権限を持つかどうか判断
    // --------------------------------------------------------------------------------
    public static function has_access($roll_key)
    {
        // ログイン中のユーザー情報取得
        $rs_auth = auth::read();
        if ($rs_auth == false)
        {
            return false;
        }
        // ロール情報取得
        $rs_roll = self::get_all(self::GLOBALVARS_NAME);
        //
        return in_array($roll_key, $rs_roll);       
    }
}
