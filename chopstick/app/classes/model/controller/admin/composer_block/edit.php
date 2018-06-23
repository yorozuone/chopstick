<?php
namespace app\model\controller\admin\composer_block;

use \core\db;

class edit extends \app\model\controller\admin\composer_block\base
{
    // ################################################################################
    // 検証
    // ################################################################################
    // ------------------------------------------------------------
    // 検証（更新）
    // ------------------------------------------------------------
    public function check()
    {
        $this->validate('required', 'caption');
        return $this->is_valid;
    }
    // ################################################################################
    // データ操作
    // ################################################################################
    // --------------------------------------------------------------------------------
    // 更新
    // --------------------------------------------------------------------------------
    public function update()
    {
        $con = new db();
        //
        $sql = <<< EOT
UPDATE
    cs_composer_block
SET
    caption = :caption,
    description = :description,
    updated_at = NOW()
WHERE
    composer_key = :composer_key AND
    composer_block_key = :composer_block_key;
EOT;
        $sql_params = array
        (
            ':composer_key'         => $this->get_value('composer_key'),
            ':composer_block_key'   => $this->get_value('composer_block_key'),
            ':caption'              => $this->get_value('caption'),
            ':description'          => $this->get_value('description'),
        );
        //
        $con->query($sql, $sql_params);
        //
        return true;
    }
}