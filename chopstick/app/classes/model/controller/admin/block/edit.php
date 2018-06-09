<?php
namespace app\model\controller\admin\block;

use \core\db;

class edit extends \app\model\controller\admin\tblock\base
{
    // ################################################################################
    // 検証
    // ################################################################################
    // ------------------------------------------------------------
    // 検証（更新）
    // ------------------------------------------------------------
    public function check()
    {
        return $this->is_valid;
    }
    // ################################################################################
    // データ操作
    // ################################################################################
    // --------------------------------------------------------------------------------
    // データ操作（更新）
    // --------------------------------------------------------------------------------
    public function update()
    {
        $con = new db();
        //
        $sql = <<< EOT
UPDATE
    cs_block
SET
    install_version = :install_version,
    updated_at = NOW()
WHERE
    block_key = :block_key;
EOT;
        $sql_params = array
        (
            ':block_key'    => $this->get_value('block_key'),
            //
            ':install_version'      => $this->get_value('install_version'),
        );
        //
        $con->query($sql, $sql_params);
    }
}