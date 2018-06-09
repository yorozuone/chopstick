<?php
namespace app\model\controller\admin\block;

use \core\db;

class delete extends \app\model\controller\admin\block\base
{
    // ################################################################################
    // 検証
    // ################################################################################
    // ------------------------------------------------------------
    // 検証（削除）
    // ------------------------------------------------------------
    public function check()
    {
        return $this->is_valid;
    }
    // ################################################################################
    // データ操作
    // ################################################################################
    // --------------------------------------------------------------------------------
    // データ操作（削除）
    // --------------------------------------------------------------------------------
    public function delete()
    {
        $con = new db();
        //
        $sql = <<< EOT
DELETE
FROM
    cs_block
WHERE
    block_key = :block_key;
EOT;
        $sql_params = array
        (
            ':block_key' => $this->get_value('block_key'),
        );
        //
        $con->query($sql, $sql_params);
        // Block のアンインストール
        $block_class = $this->get_class_path();
        $block = new $block_class();
        $block->remove();
        //
        return true;
    }
}