<?php
namespace app\model\recordset;

use \core\db;

class category
{
    // --------------------------------------------------------------------------------
    // 表示用 category_tree を取得
    // --------------------------------------------------------------------------------
    public static function fetch_tree()
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    category_id,
    parent_category_id,
    caption,
    order_at
FROM
    cs_category
ORDER BY
    order_at
EOT;
        $rs_src = $con->query($sql);
        //
        $rs_dst = array();
        self::recursion_fetch_tree($rs_src, 0, $rs_dst, 1);
        //
        return $rs_dst;
    }
    // ----------
    // 表示用 category_tree を取得（再帰）
    // ----------
    private static function recursion_fetch_tree($rs_src, $parent_category_id, &$rs_dst, $hierarchy=1)
    {
        foreach($rs_src as $src)
        {
            if ($src['parent_category_id'] == $parent_category_id)
            {
                $obj = $src;
                $obj['tree_caption'] = '└'.str_repeat('─', $hierarchy-1).' '.$src['caption'];
                $rs_dst[] = $obj;
                self::recursion_fetch_tree($rs_src, $src['category_id'], $rs_dst, $hierarchy+1);
            }
        }
    }
}