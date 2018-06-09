<?php
namespace app\model\controller\admin\composer_block;

use \core\db;

class delete extends \app\model\controller\admin\composer_block\base
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
    // 削除
    // --------------------------------------------------------------------------------
    public function delete()
    {
        $con = new db();
        //
        $sql = <<< EOT
DELETE
FROM
    cs_composer_block
WHERE
    composer_key = :composer_key AND
    composer_block_key = :composer_block_key;
EOT;
        $sql_params = array
        (
            ':composer_key' => $this->get_value('composer_key'),
            ':composer_block_key' => $this->get_value('composer_block_key'),
        );
        $con->query($sql, $sql_params);
    }
}