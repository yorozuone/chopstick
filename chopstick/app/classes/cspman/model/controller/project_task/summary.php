<?php
namespace app\cspman\model\controller\project_task;

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
        $this->append('project_id');
    }
    // ################################################################################
    // データ操作
    // ################################################################################
    public function fetch_all()
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    project_task_id,
    title,
    description,
    created_at,
    updated_at
FROM
    cspman_project_task
WHERE
    project_id = :project_id
ORDER BY
    project_task_id
EOT;
        $sql_params = array
        (
            ':project_id' => $this->get_value('project_id'),            
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
    cspman_project
SET
    order_at = :order_at
WHERE
    project_id = :project_id
EOT;
        $i = 0;
        foreach($rs as $v)
        {
            $i++;
            $sql_params = array
            (
                ':order_at' => $i,
                ':project_id'  => $v
            );
            $con->query($sql, $sql_params);
        }
    }
}
