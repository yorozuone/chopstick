<?php
namespace app\cspman\model\recordset;

use \core\db;

class project_task_comment extends \core\fieldset
{
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public static function fetch_all($project_task_id)
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    project_task_comment_id,
    project_task_id,
    comment,
    created_at,
    updated_at
FROM
    cspman_project_task_comment
WHERE
    project_task_id = :project_task_id
ORDER BY
    project_task_command_id;
EOT;
        $sql_params = array
        (
            ':project_task_id' => $project_task_id,
        );
        return $con->query($sql, $sql_params);
    }
}