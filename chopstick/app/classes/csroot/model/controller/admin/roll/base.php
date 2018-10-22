<?php
namespace app\model\controller\admin\roll;

use \core\db;

class base extends \core\fieldset
{
    // ################################################################################
    // コンストラクタ
    // ################################################################################
    public function __construct()
    {
        parent::__construct();
        //
        $this->append('roll_key',       'ロール・キー');
        //
        $this->append('caption',        '見出し');
        $this->append('description',    '説明');
        $this->append('reserved',       '予約', 0);
        $this->append('order_at',       '並び順', 0);
        //
        $this->append('created_at',     '作成日時');
        $this->append('updated_at',     '更新日時');
    }
    // ################################################################################
    // データ操作
    // ################################################################################
    // --------------------------------------------------------------------------------
    // 読込
    // --------------------------------------------------------------------------------
    public function read()
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    roll_key,
    caption,
    description,
    reserved,
    order_at,
    created_at,
    updated_at
FROM
    cs_roll
WHERE
    roll_key = :roll_key;
EOT;
        $sql_params = array
        (
            ':roll_key' => $this->get_value('roll_key'),
        );
        $rs = $con->query($sql, $sql_params);
        if (!isset($rs[0]))
        {
            return false;
        }
        $this->set_values($rs[0]);
        return true;
    }
}