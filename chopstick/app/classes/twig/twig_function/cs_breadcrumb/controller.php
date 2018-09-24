<?php
namespace app\twig\twig_function\cs_breadcrumb;

use \core\db;
use \core\view;

class controller extends \app\twig_function
{
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public static function display($config = array())
    {
        $config = is_array($config) ? $config : array();
        $config = array_replace_recursive
        (
            array
            (
                'id'        => 'cs_breadcrumb',
                'class'     => 'cs_breadcrumb',
                'template'  => 'default',
            ),
            $config
        );
        $vars = array
        (
            'rs_breadcrumb' => self::rs_breadcrumb(\app\model\helper\page::get_current_page_id()),
        );
        $vars = array_merge_recursive($config, $vars);
        //
        return self::render($config['template'], $vars);
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    private static function rs_breadcrumb($page_id)
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    page_id,
    parent_page_id,
    page_title,
    breadcrumb_caption,
    permanent_name
FROM
    cs_page
WHERE
    publish_type != 0;
EOT;
        $rs_src = $con->query($sql);
        self::recursion_rs_breadcrumb($rs_src, $page_id, $rs2);
        return is_array($rs2) ? array_reverse($rs2) : array();
    }
    // ----------
    //
    // ----------
    private static function recursion_rs_breadcrumb($rs_src, $page_id, &$rs_dst)
    {
        foreach($rs_src as $v)
        {
            if ($v['page_id'] == $page_id)
            {
                $rs_dst[] = $v;
                self::recursion_rs_breadcrumb($rs_src, $v['parent_page_id'], $rs_dst);
            }
        }
    }
}
