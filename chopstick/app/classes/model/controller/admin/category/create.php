<?php
namespace app\model\controller\admin\category;

use \core\db;

class create extends \app\model\controller\admin\category\base
{
    // ################################################################################
    // 検証
    // ################################################################################
    // --------------------------------------------------------------------------------
    // 検証（作成）
    // --------------------------------------------------------------------------------
    public function check()
    {
        $this->validate('required', 'parent_category_id');
        $this->validate('required', 'caption');
        $this->validate('required', 'permanent_name');
        $this->validate('alnum',    'permanent_name', array('-', '_'));
        //
        if ($this->is_create_permanent_name() == false)
        {
            $this->set_error('permanent_name', 'パーマネント名は、ほかのカテゴリで使われています。');
        }
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
INSERT INTO cs_category
(
    parent_category_id,
    caption,
    permanent_name,
    created_at,
    updated_at
)
VALUE
(
    :parent_category_id,
    :caption,
    :permanent_name,
    NOW(),
    NOW()
);
EOT;
        $sql_params = array
        (
            ':parent_category_id'   => $this->get_value('parent_category_id'),
            ':caption'              => $this->get_value('caption'),
            ':permanent_name'       => $this->get_value('permanent_name'),
        );
        //
        $con->query($sql, $sql_params);
    }
    // ################################################################################
    // ヘルパー
    // ################################################################################
    // --------------------------------------------------------------------------------
    // 自分を親とする子カテゴリがないか検証
    // --------------------------------------------------------------------------------
    //
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
    // --------------------------------------------------------------------------------
    // permanent_name が登録可能かどうか検証
    // --------------------------------------------------------------------------------
    public function is_create_permanent_name()
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    COUNT(*) AS CNT
FROM
    cs_category
WHERE
    permanent_name = :permanent_name
EOT;
        $sql_params = array
        (
            ':permanent_name' => $this->get_value('permanent_name'),
        );
        $rs1 = $con->query($sql, $sql_params);
        //
        return ($rs1[0]['CNT'] == 0) ? true : false;
    }
}