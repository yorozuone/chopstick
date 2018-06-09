<?php
namespace app\model\controller\admin\user;

use \core\db;

class edit extends \app\model\controller\admin\user\base
{
    //
    // ********************************************************************************
    // ****
    // **** 検証
    // ****
    // ********************************************************************************
    //
    // ------------------------------------------------------------
    // 検証（更新）
    // ------------------------------------------------------------
    //
    public function check()
    {
        $this->validate('required', 'email');
        $this->validate('email',    'email');
        $this->validate('required', 'group_key');
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
    // データ操作（更新）
    // --------------------------------------------------------------------------------
    //
    public function update()
    {
        $con = new db();
        //
        $sql = <<< EOT
UPDATE
    cs_user
SET
    email = :email,
    group_key = :group_key,
    updated_at = NOW()
WHERE
    user_id = :user_id;
EOT;
        $sql_params = array
        (
            ':user_id'      => $this->get_value('user_id'),
            ':email'        => $this->get_value('email'),
            ':group_key'    => $this->get_value('group_key'),
        );
        //
        $con->query($sql, $sql_params);
    }
}