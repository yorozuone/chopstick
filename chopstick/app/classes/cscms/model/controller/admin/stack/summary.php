<?php
namespace app\cscms\model\controller\admin\stack;

use \core\db;

class summary extends \core\fieldset
{
    // ################################################################################
    // コンストラクタ
    // ################################################################################
    public function __construct()
    {
        parent::__construct();
        //
        $this->append('stackgroup_id',  'スタック・グループid');
    }
    // ################################################################################
    // レコードセット
    // ################################################################################
    public function fetch_all()
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    cs_stack.stack_key,
    cs_stack.stackgroup_id,
    cs_stackgroup.caption as stackgroup_caption,
    cs_stack.description,
    cs_stack.created_at,
    cs_stack.updated_at
FROM
    cs_stack
JOIN
    cs_stackgroup
ON
    cs_stack.stackgroup_id = cs_stackgroup.stackgroup_id
WHERE
    cs_stack.stackgroup_id = :stackgroup_id
ORDER BY
    cs_stack.order_at
EOT;
        $sql_params = array
        (
            ':stackgroup_id' => $this->get_value('stackgroup_id'),
        );
        return $con->query($sql, $sql_params);
    }
    // --------------------------------------------------------------------------------
    // 並び順
    // --------------------------------------------------------------------------------
    public function sort($rs)
    {
        $con = new db();
        //
        $sql = <<< EOT
UPDATE
    cs_stack
SET
    order_at = :order_at
WHERE
    stack_key = :stack_key
EOT;
        $i = 0;
        foreach($rs as $v)
        {
            $i++;
            $sql_params = array
            (
                ':stack_key'    => $v,
                //
                ':order_at'     => $i,
            );
            $con->query($sql, $sql_params);
        }
    }
}