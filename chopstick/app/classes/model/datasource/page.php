<?php
namespace app\model\datasource;

use \core\db;

class page
{
    // --------------------------------------------------------------------------------
    // 表示用ページ一覧取得
    // --------------------------------------------------------------------------------
    public static function fetch_tree()
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    page_id,
    parent_page_id,
    page_title
FROM
        cs_page
ORDER BY
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
                $obj = $src;
                $obj['tree_title'] = '└'.str_repeat('─', $hierarchy-1).' '.$src['page_title'];
                $rs_dst[] = $obj;
                self::recursion_fetch_tree($rs_src, $src['page_id'], $rs_dst, $hierarchy+1);
            }
        }
    }
}