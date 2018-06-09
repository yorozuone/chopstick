<?php
namespace app\model\controller\admin\user;

use \core\db;

class update_password extends \app\model\controller\admin\user\base
{
    //
    // ********************************************************************************
    // ****
    // **** 検証
    // ****
    // ********************************************************************************
    //
    // ------------------------------------------------------------
    // 検証（パスワード更新）
    // ------------------------------------------------------------
    //
    public function check()
    {
        $this->validate('required', 'password');
        $this->validate('alnum',    'password');
        $this->validate('required', 'password2');
        $this->validate('alnum',    'password2');
        //
        return $this->is_valid;
    }
    //
    // ********************************************************************************
    //
    // データ操作
    //
    // ********************************************************************************
    //
    // --------------------------------------------------------------------------------
    // データ操作（パスワード更新）
    // --------------------------------------------------------------------------------
    //
    public function update_password()
    {
        $con = new db();
        //
        $sql = <<< EOT
UPDATE
    cs_user
SET
    password_hash = :password_hash
WHERE
    user_id = :user_id
EOT;
        $sql_params = array
        (
            'user_id' => $this->get_value('user_id'),
            'password_hash' => password_hash($this->get_value('password'), PASSWORD_DEFAULT),
        );
        //
        $con->query($sql, $sql_params);
    }
}