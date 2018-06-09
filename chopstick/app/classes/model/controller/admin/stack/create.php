<?php
namespace app\model\controller\admin\stack;

use \core\db;

class create extends \app\model\controller\admin\stack\base
{
    //
    // ################################################################################
    //
    // 検証
    //
    // ################################################################################
    //
    // --------------------------------------------------------------------------------
    // 検証（作成）
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
    // 作成
    // --------------------------------------------------------------------------------
    //
    public function create()
    {
        $con = new db();
        //
        $sql = <<< EOT
INSERT INTO cs_stack
(
    stack_key,
    stackgroup_id,
    content,
    description,
    publish_status,
    publish_type,
    publish_start,
    publish_end,
    reserved,
    order_at,
    created_at,
    updated_at
)
VALUE
(
    :stack_key,
    :stackgroup_id,
    :content,
    :description,
    :publish_status,
    :publish_type,
    :publish_start,
    :publish_end,
    :reserved,
    :order_at,
    NOW(),
    NOW()
);
EOT;
        $sql_params = array
        (
            ':stack_key'        => $this->get_value('stack_key'),
            ':stackgroup_id'    => $this->get_value('stackgroup_id'),
            ':content'          => $this->get_value('content'),
            ':description'      => $this->get_value('description'),
            ':publish_status'   => $this->get_value('publish_status'),
            ':publish_type'     => $this->get_value('publish_type'),
            ':publish_start'    => $this->get_value('publish_start') == '' ? NULL : $this->get_value('publish_start'),
            ':publish_end'      => $this->get_value('publish_end')   == '' ? NULL : $this->get_value('publish_end'),
            ':reserved'         => $this->get_value('reserved'),
            ':order_at'         => $this->get_value('order_at'),
        );
        //
        $con->query($sql, $sql_params);
    }
}