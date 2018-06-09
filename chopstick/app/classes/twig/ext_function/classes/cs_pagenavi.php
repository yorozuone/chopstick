<?php
namespace app\twig\ext_function\classes;

use \core\db;
use \core\view;

class cs_pagenavi
{
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public static function render($config = array())
    {
        $config = is_array($config) ? $config : array();
        $config = array_replace_recursive
        (
            array
            (
                'id'                => 'cs_pagenavi',
                'class'             => 'cs_pagenavi',
                'template'          => 'default',
                'parent_page_id'    => 0,
                'depth'             => 0,
            ),
            $config
        );
        $vars = array
        (
            'drec_pagenavi' => self::drec_pagenavi($config['parent_page_id'], $config['depth']),
        );
        $vars = array_merge_recursive($config, $vars);
        //
        $v = new view();
        return $v->render('twig/ext_function/cs_pagenavi/'.$config['template'].'.twig', $vars);
    }
    // --------------------------------------------------------------------------------
    // ツリー構造を取得(再帰)
    // --------------------------------------------------------------------------------
    private static function drec_pagenavi($parent_page_id = 0, $depth = 0)
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    page_id,
    parent_page_id,
    page_title,
    created_at,
    updated_at
FROM
    cs_page
WHERE
    publish_navi = 1 AND
    publish_status = 1 AND
    (
        (publish_type = 1) OR
        (publish_type = 2 AND publish_start <= :current_datetime_1 AND publish_end IS NULL) OR
        (publish_type = 2 AND publish_start <= :current_datetime_2 AND publish_end >= :current_datetime_3) OR
        (publish_type = 2 AND publish_start IS NULL AND publish_end >= :current_datetime_4)
    )
ORDER BY
    order_at ASC
EOT;
        $sql_params = array
        (
            ':current_datetime_1' => date('Y/m/d H:i:s'),
            ':current_datetime_2' => date('Y/m/d H:i:s'),
            ':current_datetime_3' => date('Y/m/d H:i:s'),
            ':current_datetime_4' => date('Y/m/d H:i:s'),
        );
        $rs_src = $con->query($sql, $sql_params);
        $rs_dst = array();
        self::recursion_drec_pagenavi($rs_src, 0, $rs_dst, 1, $depth);
        //
        return $rs_dst;
    }
    // --------------------------------------------------------------------------------
    // ツリー構造を取得(再帰)
    // --------------------------------------------------------------------------------
    private static function recursion_drec_pagenavi($rs_src, $parent_page_id, &$rs_dst, $hierarchy=1, $depth=0)
    {
        if (($depth != 0) and ($hierarchy == $depth + 1))
        {
            return;
        }
        foreach($rs_src as $src)
        {
            if ($src['parent_page_id'] == $parent_page_id)
            {
                $obj = $src;
                $obj['tree_title'] = '└'.str_repeat('─', $hierarchy-1).' '.$src['page_title'];
                $rs_dst[] = $obj;
                self::recursion_drec_pagenavi($rs_src, $src['page_id'], $rs_dst, $hierarchy+1, $depth);
            }
        }
    }
}
