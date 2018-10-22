<?php
namespace app\cscms\model\controller\admin\page;

use \core\db;

class summary extends \core\fieldset
{
    // --------------------------------------------------------------------------------
    // コンストラクタ
    // --------------------------------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public function fetch_all()
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    page_id,
    parent_page_id,
    composer_key,
    page_title,
    permanent_name,
    created_at,
    updated_at
FROM
    cs_page
WHERE
    page_status = 1
ORDER BY
    parent_page_id,
    order_at,
    page_id
EOT;
        $rs_src = $con->query($sql);
        $rs_dst = array();
        self::recursion_fetch_tree($rs_src, 0, $rs_dst, 1);
        return $rs_dst;
    }
    // ----------
    // 表示用ページ一覧取得（再帰）
    // ----------
    private static function recursion_fetch_tree($rs_src, $parent_page_id, &$rs_dst, $hierarchy=1)
    {
        foreach($rs_src as $src)
        {
            if ($src['parent_page_id'] == $parent_page_id)
            {
                $rs_dst[] = $src;
                self::recursion_fetch_tree($rs_src, $src['page_id'], $rs_dst, $hierarchy+1);
            }
        }
    }
}