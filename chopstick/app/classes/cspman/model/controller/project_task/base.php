<?php
namespace app\cspman\model\controller\project_task;

use \core\db;

class base extends \core\fieldset
{
    // ################################################################################
    // コンストラクタ
    // ################################################################################
    public function __construct()
    {
        parent::__construct();
        //
        $this->append('project_task_id');
        //
        $this->append('project_id');
        $this->append('title',              'タイトル');
        $this->append('description',        '説明');
    }
    // ################################################################################
    // データ操作
    // ################################################################################
    // --------------------------------------------------------------------------------
    // 読込
    // --------------------------------------------------------------------------------
    public function read()
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    project_task_id,
    project_id,
    title,
    description
FROM
    cspman_project_task
WHERE
    project_task_id = :project_task_id;
EOT;
        $sql_params = array
        (
            ':project_task_id' => $this->get_value('project_task_id'),
        );
        $rs = $con->query($sql, $sql_params);
        if (!isset($rs[0]))
        {
            return false;
        }
        $this->set_values($rs[0]);
        return true;
    }
}