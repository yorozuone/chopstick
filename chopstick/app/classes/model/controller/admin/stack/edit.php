<?php
namespace app\model\controller\admin\stack;

use \core\db;

class edit extends \app\model\controller\admin\stack\base
{
    //
    // ################################################################################
    //
    // 検証
    //
    // ################################################################################
    //
    // --------------------------------------------------------------------------------
    // 検証（更新）
    // --------------------------------------------------------------------------------
    //
    public function check()
    {
        $this->validate('required', 'stackgroup_id');
        $this->validate('required', 'content');
        //
        return $this->is_valid;
    }
    //
    // ################################################################################
    //
    // データ操作
    //
    // ################################################################################
    //
    // --------------------------------------------------------------------------------
    // 更新
    // --------------------------------------------------------------------------------
    //
    public function update()
    {
        $con = new db();
        //
        $sql = <<< EOT
UPDATE
    cs_stack
SET
    stackgroup_id = :stackgroup_id,
    content = :contnet,
    description = :description,
    publish_status = :publish_status,
    publish_type = :publish_type,
    publish_start = :publish_start,
    publish_end = :publish_end,
    updated_at = NOW()
WHERE
    stack_key = :stack_key;
EOT;
        $sql_params = array
        (
            ':stackgroup_id'    => $this->get_value('stackgroup_id'),
            ':contnet'          => $this->get_value('content'),
            ':description'      => $this->get_value('description'),
            ':publish_status'   => $this->get_value('publish_status'),
            ':publish_type'     => $this->get_value('publish_type'),
            ':publish_start'    => $this->get_value('publish_start')   == '' ? NULL : $this->get_value('publish_start'),
            ':publish_end'      => $this->get_value('publish_end')     == '' ? NULL : $this->get_value('publish_end'),
            //
            ':stack_key'        => $this->get_value('stack_key'),
        );
        //
        $con->query($sql, $sql_params);
        //
        return true;
    }
}