<?php
namespace app\model\controller\admin\user;

use \core\db;

class create extends \app\csroot\model\controller\admin\user\base
{
    // ********************************************************************************
    // **** 検証
    // ********************************************************************************
    // ------------------------------------------------------------
    // 検証（作成）
    // ------------------------------------------------------------
    public function check()
    {
        $this->validate('required', 'username');
        $this->validate('alnum',    'username');
        $this->validate('required', 'password');
        $this->validate('alnum',    'password');
        $this->validate('required', 'password2');
        $this->validate('alnum',    'password2');
        if ($this->get_value('password') != $this->get_value('password2'))
        {
            $this->set_error('password2', 'パスワード（再確認）が、パスワードと一致しません。');
        }
        $this->validate('required', 'email');
        $this->validate('email',    'email');
        $this->validate('required', 'group_key');
        //
        return $this->is_valid;
    }
    // ********************************************************************************
    // データ操作
    // ********************************************************************************
    // --------------------------------------------------------------------------------
    // データ操作（作成）
    // --------------------------------------------------------------------------------
    public function create()
    {
        $con = new db();
        //
        $sql = <<< EOT
INSERT INTO cs_user
(
    username,
    password_hash,
    email,
    group_key,
    created_at,
    updated_at
) VALUES (
    :username,
    :password_hash,
    :email,
    :group_key,
    NOW(),
    NOW()
)
EOT;
        $sql_params = array
        (
            ':username'         => $this->get_value('username'),
            ':password_hash'    => password_hash($this->get_value('password'), PASSWORD_DEFAULT),
            ':email'            => $this->get_value('email'),
            ':group_key'        => $this->get_value('group_key'),
        );
        //
        $con->query($sql, $sql_params);
    }
}