<?php
namespace app\cspman\model\controller\project_task;

use \core\db;

class create extends \app\cspman\model\controller\project_task\base
{
    // ################################################################################
    // 検証
    // ################################################################################
    // --------------------------------------------------------------------------------
    // 検証（作成）
    // --------------------------------------------------------------------------------
    public function check()
    {
        $this->validate('required', 'title');
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
INSERT INTO cspman_project_task
(
    project_id,
    title,
    description,
    created_at,
    updated_at
)
VALUE
(
    :project_id,
    :title,
    :description,
    NOW(),
    NOW()
);
EOT;
        $sql_params = array
        (
            ':project_id'   => $this->get_value('project_id'),
            ':title'        => $this->get_value('title'),
            ':description'  => $this->get_value('description'),
        );
        $con->query($sql, $sql_params);
    }
}