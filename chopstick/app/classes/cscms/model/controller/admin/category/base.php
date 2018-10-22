<?php
namespace app\cscms\model\controller\admin\category;

use core\db;

class base extends \core\fieldset
{
    // ################################################################################
    // コンストラクタ
    // ################################################################################
    public function __construct()
    {
        parent::__construct();
        //
        $this->append('category_id',                'カテゴリID');
        //
        $this->append('parent_category_id',         '親カテゴリID', 0);
        $this->append('permanent_name',             'パーマネント名');
        $this->append('caption',                    'カテゴリ名');
        //
        $this->append('parent_category_caption',    '親カテゴリ名');
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
    cs_category.category_id,
    cs_category.parent_category_id,
    t1.caption as parent_category_caption,
    cs_category.caption,
    cs_category.permanent_name
FROM
    cs_category
LEFT JOIN
    cs_category as t1
ON
    cs_category.parent_category_id = t1.category_id
WHERE
    cs_category.category_id = :category_id;
EOT;
        $sql_params = array
        (
            ':category_id' => $this->get_value('category_id'),
        );
        $rs = $con->query($sql, $sql_params);
        //
        if (isset($rs[0]) == false)
        {
            return false;
        }
        $this->set_values($rs[0]);
        return true;
    }
}