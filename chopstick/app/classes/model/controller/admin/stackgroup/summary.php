<?php
namespace app\model\controller\admin\stackgroup;

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
    stackgroup_id,
    caption,
    created_at,
    updated_at
FROM
    cs_stackgroup
ORDER BY
    order_at,
    stackgroup_id
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
    cs_stackgroup
SET
    order_at = :order_at
WHERE
    stackgroup_id = :stackgroup_id
EOT;
        $i = 0;
        foreach($rs as $v)
        {
            $i++;
            $sql_params = array
            (
                ':order_at' => $i,
                ':stackgroup_id'  => $v
            );
            $con->query($sql, $sql_params);
        }
    }
}
