<?php
namespace app\model\controller\admin\stackgroup;

use \core\db;

class edit extends \app\model\controller\admin\stackgroup\base
{
    // ################################################################################
    // 検証
    // ################################################################################
    // --------------------------------------------------------------------------------
    // 検証（更新）
    // --------------------------------------------------------------------------------
    public function check()
    {
        $this->validate('required', 'caption');
        //
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
    cs_stackgroup
SET
    caption = :caption,
    updated_at = NOW()
WHERE
    stackgroup_id = :stackgroup_id;
EOT;
        $sql_params = array
        (
            ':stackgroup_id'    => $this->get_value('stackgroup_id'),
            ':caption'          => $this->get_value('caption'),
        );
        //
        $con->query($sql, $sql_params);
    }
}