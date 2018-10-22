<?php
namespace app\cspman\model\controller\project_task;

use \core\db;

class edit extends \app\cspman\model\controller\project_task\base
{
    // ################################################################################
    // 検証
    // ################################################################################
    // --------------------------------------------------------------------------------
    // 検証（更新）
    // --------------------------------------------------------------------------------
    public function check()
    {
        $this->validate('required', 'project_task_id');
        $this->validate('required', 'title');
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
    cspman_project_task
SET
    title = :title,
    description = :description,
    updated_at = NOW()
WHERE
    project_task_id = :project_task_id;
EOT;
        $sql_params = array
        (
            ':project_task_id'  => $this->get_value('project_task_id'),
            ':title'            => $this->get_value('title'),
            ':description'      => $this->get_value('description'),
        );
        //
        $con->query($sql, $sql_params);
        //
        return true;
    }
}