<?php
namespace app\model\controller\admin\group;

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
        $this->append('group_key',      'グループ・キー');
        //
        $this->append('caption',        '見出し');
        $this->append('description',    '説明');
        $this->append('reserved',       '予約', 0);
        $this->append('order_at',       '並び順', 0);
        //
        $this->append('created_at',     '作成日時');
        $this->append('updated_at',     '更新日時');
        //
        $this->append('roll_keys',      'ロール・キー', array());
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
        $sql_1 = <<< EOT
SELECT
    group_key,
    caption,
    description,
    reserved,
    order_at,
    created_at,
    updated_at
FROM
    cs_group
WHERE
    group_key = :group_key;
EOT;
        $sql_params_1 = array
        (
            ':group_key' => $this->get_value('group_key'),
        );
        $rs_1 = $con->query($sql_1, $sql_params_1);
        if (!isset($rs_1[0]))
        {
            return false;
        }
        $this->set_values($rs_1[0]);
        //
        $sql_2 = <<< EOT
SELECT
    roll_key
FROM
    cs_group_roll
WHERE
    group_key = :group_key;
EOT;
        $sql_params_2 = array
        (
            ':group_key' => $this->get_value('group_key'),
        );
        $rs_2 = $con->query($sql_2, $sql_params_2);
        $this->set_value('roll_keys', array_column($rs_2, 'roll_key'));
        //
        return true;
    }
}