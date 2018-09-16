<?php
namespace app\model\controller\admin\block;

use \core\db;

class create extends \app\model\controller\admin\block\base
{
    // ################################################################################
    // 検証
    // ################################################################################
    // ------------------------------------------------------------
    // 検証（作成）
    // ------------------------------------------------------------
    public function check()
    {
        return $this->is_valid;
    }
    // ################################################################################
    // データ操作
    // ################################################################################
    // --------------------------------------------------------------------------------
    // データ操作（作成）
    // --------------------------------------------------------------------------------
    public function create()
    {
        if (!$this->read_from_class())
        {
            return false;
        }
        $con = new db();
        //
        $sql_1 = <<< EOT
INSERT INTO cs_block
(
    block_key,
    name,
    description,
    install_version,
    created_at,
    updated_at
)
VALUES
(
    :block_key,
    :name,
    :description,
    :install_version,
    NOW(),
    NOW()
);
EOT;
        $sql_params_1 = array
        (
            ':block_key'        => $this->get_value('block_key'),
            ':name'             => $this->get_value('name'),
            ':description'      => $this->get_value('description'),
            ':install_version'  => $this->get_value('version'),
        );
        $con->query($sql_1, $sql_params_1);
        //
        // Block のインストール
        //
        $block_class = $this->get_class_path();
        $block = new $block_class();
        $block->install();
        //
        return true;
    }
}