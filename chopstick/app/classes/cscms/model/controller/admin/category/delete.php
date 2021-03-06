<?php
namespace app\cscms\model\controller\admin\category;

use \core\db;

class delete extends \app\cscms\model\controller\admin\category\base
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
    // 削除
    // --------------------------------------------------------------------------------
    public function delete()
    {
        $con = new db();
        //
        $sql = <<< EOT
DELETE
FROM
    cs_category
WHERE
    category_id = :category_id;
EOT;
        $sql_params = array
        (
            ':category_id' => $this->get_value('category_id'),
        );
        $con->query($sql, $sql_params);
    }
    // ################################################################################
    // ヘルパー
    // ################################################################################
    // --------------------------------------------------------------------------------
    // 自分を親とする子カテゴリがないか検証
    // --------------------------------------------------------------------------------
    public function exists_child_category()
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    COUNT(*) AS CNT
FROM
    cs_category
WHERE
    parent_category_id = :category_id;
EOT;
        $sql_params = array
        (
            ':category_id' => $this->get_value('category_id'),
        );
        $rs = $con->query($sql, $sql_params);
        //
        return ($rs[0]['CNT'] == 0) ? false : true;
    }
}