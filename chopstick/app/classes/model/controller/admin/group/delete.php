<?php
namespace app\model\controller\admin\group;

use \core\db;

class delete extends \app\model\controller\admin\group\base
{
    // ################################################################################
    // 検証
    // ################################################################################
    // --------------------------------------------------------------------------------
    // 検証（削除）
    // --------------------------------------------------------------------------------
    public function check()
    {
        return $this->is_valid;
    }
    // ################################################################################
    // データ操作
    // ################################################################################
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function is_delete()
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    COUNT(*) AS CNT
FROM
    cs_group
WHERE
    group_key = :group_key AND
    reserved = 1;
EOT;
        $sql_params = array
        (
            ':group_key' => $this->get_value('group_key'),
        );
        $rs = $con->query($sql, $sql_params);
        //
        if ($rs[0]['CNT'] != 0)
        {
            return false;
        }
        return true;
    }
    // --------------------------------------------------------------------------------
    // 削除
    // --------------------------------------------------------------------------------
    public function delete()
    {
        $con = new db();
        //
        if ($this->is_delete() == false)
        {
            return false;
        }
        //
        $sql_1 = <<< EOT
DELETE
FROM
    cs_group
WHERE
    group_key = :group_key AND
    reserved = 0;
EOT;
        $sql_params_1 = array
        (
            ':group_key' => $this->get_value('group_key'),
        );
        $con->query($sql_1, $sql_params_1);
    //
    $sql_2 = <<< EOT
DELETE
FROM
    cs_group_roll
WHERE
    group_key = :group_key AND
    reserved = 0;
EOT;
        $sql_params_2 = array
        (
            ':group_key' => $this->get_value('group_key'),
        );
        $con->query($sql_2, $sql_params_2);
        //
        return true;
    }
}