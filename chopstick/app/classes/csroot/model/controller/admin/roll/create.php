<?php
namespace app\model\controller\admin\roll;

use \core\db;

class create extends \app\csroot\model\controller\admin\roll\base
{
    // ################################################################################
    // 検証
    // ################################################################################
    // --------------------------------------------------------------------------------
    // 検証（作成）
    // --------------------------------------------------------------------------------
    public function check()
    {
        $this->validate('required', 'roll_key');
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
        $sql = <<< EOT
INSERT INTO cs_roll
(
    roll_key,
    caption,
    description,
    reserved,
    order_at,
    created_at,
    updated_at
)
VALUE
(
    :roll_key,
    :caption,
    :description,
    :reserved,
    :order_at,
    NOW(),
    NOW()
);
EOT;
        $sql_params = array
        (
            ':roll_key'     => $this->get_value('roll_key'),
            ':caption'      => $this->get_value('caption'),
            ':description'  => $this->get_value('description'),
            ':reserved'     => $this->get_value('reserved'),
            ':order_at'     => $this->get_value('order_at'),
        );
        $con->query($sql, $sql_params);
    }
}