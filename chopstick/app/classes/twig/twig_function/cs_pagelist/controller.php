<?php
namespace app\twig\twig_function\cs_pagelist;

use \core\db;
use \core\globalvars;
use \core\view;

class controller extends \app\twig_function
{
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public static function display($config = array())
    {
        $app_cms_page = globalvars::get_value('APP_CMS_PAGE');
        //
        $config = is_array($config) ? $config : array();
        $config = array_replace_recursive
        (
            array
            (
                'id'                => '',
                'class'             => 'cs_pagelist',
                'template'          => 'default',
                'parent_page_id'    => isset($app_cms_page['page_id']) ? $app_cms_page['page_id'] : -1,
                'limit'             => 100,
            ),
            $config
        );
        $vars = array
        (
            'drec_pagelist' => self::drec_pagelist($config['parent_page_id'], $config['limit']),
        );
        $vars = array_merge_recursive($config, $vars);
        //
        return self::render($config['template'], $vars);
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public static function drec_pagelist($parent_page_id, $limit)
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    *
FROM
    cs_page
WHERE
    parent_page_id = :parent_page_id AND
    publish_list = 1 AND
    publish_status = 1 AND
    (
        (publish_type = 1) OR
        (publish_type = 2 AND publish_start <= :current_datetime_1 AND publish_end IS NULL) OR
        (publish_type = 2 AND publish_start <= :current_datetime_2 AND publish_end >= :current_datetime_3) OR
        (publish_type = 2 AND publish_start IS NULL AND publish_end >= :current_datetime_4)
    )
ORDER BY
    order_at ASC
LIMIT :limit
EOT;
        $sql_params = array
        (
            ':parent_page_id' => $parent_page_id,
            ':current_datetime_1' => date('Y/m/d H:i:s'),
            ':current_datetime_2' => date('Y/m/d H:i:s'),
            ':current_datetime_3' => date('Y/m/d H:i:s'),
            ':current_datetime_4' => date('Y/m/d H:i:s'),
            ':limit' => $limit,
        );
        $rs = $con->query($sql, $sql_params);
        return $rs;
    }

}