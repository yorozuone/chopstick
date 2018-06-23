<?php
namespace app\model\controller\admin\composer_block;

use \core\db;

class create extends \app\model\controller\admin\composer_block\base
{
    // ################################################################################
    // 検証
    // ################################################################################
    // ------------------------------------------------------------
    // 検証（作成）
    // ------------------------------------------------------------
    public function check()
    {
        $this->validate('required', 'composer_key');
        $this->validate('required', 'composer_block_key');
        $this->validate('required', 'block_key');
        $this->validate('required', 'caption');
        //
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
INSERT INTO cs_composer_block
(
    composer_key,
    composer_block_key,
    block_key,
    caption,
    description,
    order_at,
    created_at,
    updated_at
)
VALUES
(
    :composer_key,
    :composer_block_key,
    :block_key,
    :caption,
    :description,
    :order_at,
    NOW(),
    NOW()
);
EOT;
        $sql_params = array
        (
            ':composer_key'         => $this->get_value('composer_key'),
            ':composer_block_key'   => $this->get_value('composer_block_key'),
            ':block_key'            => $this->get_value('block_key'),
            ':caption'              => $this->get_value('caption'),
            ':description'          => $this->get_value('description'),
            ':order_at'             => $this->get_value('order_at'),
        );
        //
        $con->query($sql, $sql_params);
    }
}