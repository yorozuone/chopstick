<?php
namespace app\model\controller\admin\stackgroup;

use \core\db;

class delete extends \app\model\controller\admin\stackgroup\base
{
    //
    // ################################################################################
    //
    // 検証
    //
    // ################################################################################
    //
    // --------------------------------------------------------------------------------
    // 検証（削除）
    // --------------------------------------------------------------------------------
    //
    public function check()
    {
        return $this->is_valid;
    }
    //
    // --------------------------------------------------------------------------------
    // 削除
    // --------------------------------------------------------------------------------
    //
    public function delete()
    {
        $con = new db();
        //
        $sql = <<< EOT
DELETE
FROM
    cs_stackgroup
WHERE
stackgroup_id = :stackgroup_id;
EOT;
        $sql_params = array
        (
            ':stackgroup_id' => $this->get_value('stackgroup_id'),
        );
        //
        $con->query($sql, $sql_params);
    }
}