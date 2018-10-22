<?php
namespace app\cspman\model\controller\project;

use \core\db;

class summary extends \core\fieldset
{
    // ################################################################################
    // コンストラクタ
    // ################################################################################
    public function __construct()
    {
        parent::__construct();
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
    project_id,
    project_name,
    created_at,
    updated_at
FROM
    cspman_project
ORDER BY
    order_at,
    project_id
EOT;
        return $con->query($sql);
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
