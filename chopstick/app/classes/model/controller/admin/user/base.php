<?php
namespace app\model\controller\admin\user;

use \core\db;

class base extends \core\fieldset
{
    //
    // ********************************************************************************
    // ****
    // **** コンストラクタ
    // ****
    // ********************************************************************************
    //
    public function __construct()
    {
        parent::__construct();
        //
        $this->append('user_id',        'ユーザーID');
        //
        $this->append('username',       'ユーザー名');
        $this->append('password',       'パスワード');
        $this->append('password2',      'パスワード（再入力）');
        $this->append('email',          'メールアドレス');
        $this->append('group_key',      '所属グループ');
        //
        $this->append('group_caption',  '所属名');
    }
    //
    // ********************************************************************************
    //
    // データ操作
    //
    // ********************************************************************************
    //
    // --------------------------------------------------------------------------------
    // データ操作（読込）
    // --------------------------------------------------------------------------------
    //
    public function read()
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    cs_user.username,
    cs_user.password_hash,
    cs_user.email,
    cs_user.group_key,
    cs_group.caption as group_caption,
    cs_user.created_at,
    cs_user.updated_at
FROM
    cs_user
LEFT JOIN
    cs_group
ON
    cs_user.group_key = cs_group.group_key
WHERE
    cs_user.user_id = :user_id
EOT;
        $sql_params = array
        (
            ':user_id'  => $this->get_value('user_id'),
        );
        //
        $rs = $con->query($sql, $sql_params);
        //
        if (!isset($rs[0]))
        {
            return false;
        }
        $this->set_values($rs[0]);
        //
        return true;
    }
    //
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    //
    public function fetch_group_all()
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    group_key,
    caption
FROM
    cs_group
EOT;
        //
        return $con->query($sql);
    }
}