<?php
namespace app\model\controller\admin\group;

use \core\db;

class create extends \app\model\controller\admin\group\base
{
    // ################################################################################
    // 検証
    // ################################################################################
    // --------------------------------------------------------------------------------
    // 検証（作成）
    // --------------------------------------------------------------------------------
    public function check()
    {
        $this->validate('required', 'group_key');
        $this->validate('required', 'caption');
        return $this->is_valid;
    }
    // ################################################################################
    // データ操作
    // ################################################################################
    // --------------------------------------------------------------------------------
    // 作成
    // --------------------------------------------------------------------------------
    public function create()
    {
        $con = new db();
        //
        $sql_1 = <<< EOT
INSERT INTO cs_group
(
    group_key,
    caption,
    description,
    reserved,
    order_at,
    created_at,
    updated_at
)
VALUE
(
    :group_key,
    :caption,
    :description,
    :reserved,
    :order_at,
    NOW(),
    NOW()
);
EOT;
        $sql_params_1 = array
        (
            ':group_key'        => $this->get_value('group_key'),
            ':caption'          => $this->get_value('caption'),
            ':description'      => $this->get_value('description'),
            ':reserved'         => $this->get_value('reserved'),
            ':order_at'         => $this->get_value('order_at'),
        );
        $con->query($sql_1, $sql_params_1);
        //
        $sql_2 = <<< EOT
INSERT INTO cs_group_roll
(
    group_key,
    roll_key,
    reserved,
    created_at,
    updated_at
)
VALUE
(
    :group_key,
    :roll_key,
    0,
    NOW(),
    NOW()
);
EOT;
        foreach($this->get_value('roll_keys') as $v)
        {
            $sql_params_2 = array
            (
                ':group_key'        => $this->get_value('group_key'),
                ':roll_key'         => $v,
            );
            $con->query($sql_2, $sql_params_2);
        }
    }
}