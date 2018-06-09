<?php
namespace app\twig\ext_function\classes;

use \core\db;
use \core\view;

class cs_news
{
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public static function render($config=array())
    {
        $config = is_array($config) ? $config : array();
        $config = array_replace_recursive
        (
            array
            (
                'id'        => 'cs_news',
                'class'     => 'cs_news',
                'template'  => 'default',
                'limit'     => 10,
            ),
            $config
        );
        $vars = array
        (
            'drec_news' => self::drec_news($config['limit']),
        );
        $vars = array_merge_recursive($config, $vars);
        //
        $v = new view();
        return $v->render('twig/ext_function/cs_news/'.$config['template'].'.twig', $vars);
    }
    // --------------------------------------------------------------------------------
    //
    // --------------------------------------------------------------------------------
    public static function drec_news($limit=10)
    {
        $con = new db();
        //
        $sql = <<< EOT
SELECT
    page_id,
    page_type,
    page_title,
    external_link,
    updated_at
FROM
    cs_page
WHERE
    (
        (publish_type = 1) OR
        (publish_type = 2 AND publish_start <= :current_datetime_1 AND publish_end IS NULL) OR
        (publish_type = 2 AND publish_start <= :current_datetime_2 AND publish_end >= :current_datetime_3) OR
        (publish_type = 2 AND publish_start IS NULL AND publish_end >= :current_datetime_4)
    )
ORDER BY
    updated_at desc
LIMIT :limit
EOT;
        $sql_params = array
        (
            ':current_datetime_1' => date('Y/m/d H:i:s'),
            ':current_datetime_2' => date('Y/m/d H:i:s'),
            ':current_datetime_3' => date('Y/m/d H:i:s'),
            ':current_datetime_4' => date('Y/m/d H:i:s'),
            ':limit' => $limit,
        );
        $rs = $con->query($sql, $sql_params);
        //
        return $rs;
    }

}